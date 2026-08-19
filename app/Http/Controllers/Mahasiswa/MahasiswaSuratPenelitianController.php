<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Mitra;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\SuratPenelitian;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Services\NotifikasiBAKService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SuratPenelitianGenerator;

class MahasiswaSuratPenelitianController extends Controller
{
    
    public function index()
    {
        return view('mahasiswa.surat_penelitian.index');
    }

    public function getSuratPenelitian()
    {
        $user = Auth::user();
        $nim = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = SuratPenelitian::with(['akademik', 'mahasiswa', 'mahasiswa.prodi', 'mitra'])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                $date = \Carbon\Carbon::parse($row->tanggal_pengajuan_asli)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
            })
            ->addColumn('akademik', function ($row) {
                return $row?->akademik?->tahun_akademik ?? "-";
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pengajuan' => '<span class="badge text-white bg-warning">Menunggu BAK</span>',
                    'proses'    => '<span class="badge text-white bg-info">Menunggu Dekan</span>',
                    'diterima'  => '<span class="badge text-white bg-success">Disetujui</span>',
                    'ditolak'   => '<span class="badge text-white bg-danger">Ditolak</span>',
                    default     => '<span class="badge text-white bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('mahasiswa.surat-izin-penelitian.show', $row->id_surat_izin_penelitian) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-izin-penelitian.edit', $row->id_surat_izin_penelitian) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';
                }

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['status', 'akademik', 'catatan', 'action', 'tanggal_pengajuan'])
            ->make(true);
    }

    
    public function create()
    {
        $user     = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra    = Mitra::all();
        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        if ($dataSimpt?->id_smt != $latestAkademik?->kode_akademik) {
            return redirect()->route('mahasiswa.surat-izin-penelitian.index')
                ->with('failed', 'Anda belum mengisi KRS pada semester aktif, sehingga tidak dapat mengajukan surat.');
        }

        return view('mahasiswa.surat_penelitian.create', compact('latestAkademik', 'mitra', 'dataSimpt'));
    }

    
    public function store(Request $request, SuratPenelitianGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id'         => 'required|exists:mitra,id_mitra',
            'tgl_mulai'        => 'required|date|after_or_equal:today',
            'tgl_selesai'      => 'required|date|after_or_equal:tgl_mulai',
            'judul_penelitian' => 'required',
        ], [
            'tgl_mulai.after_or_equal' => 'Tanggal mulai minimal hari ini.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $semester = $dataSimpt?->semester;

        if (blank($semester)) {
            return back()
                ->withInput()
                ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
        }

        $akademik = TahunAkademik::find($request->akademik_id);
        if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
            return back()
                ->withInput()
                ->with('failed', 'Anda belum mengisi KRS pada semester ini, sehingga tidak dapat mengajukan surat.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas Anda belum ditentukan.');
        }

        $namaTemplate = 'surat_izin_penelitian';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template surat ini belum tersedia untuk fakultas Anda.");
        }

        
        $noSurat = SuratPenelitian::getNextNoSurat($template->id_template, $request->akademik_id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        $surat = SuratPenelitian::create([
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'mitra_id'            => $request->mitra_id,
            'tgl_mulai'           => $request->tgl_mulai,
            'tgl_selesai'         => $request->tgl_selesai,
            'judul_penelitian'    => $request->judul_penelitian,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan oleh mahasiswa',
            'file_generated'      => null,
        ]);

        try {
            
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            
            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('failed', 'Gagal memproses template dokumen. Silakan coba lagi atau hubungi admin. Error: ' . $e->getMessage());
        }

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_izin_penelitian,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_izin_penelitian',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh mahasiswa',
            'jabatan_id'     => null,
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history,
            'status'     => 'pengajuan',
            'user_role'  => 'Mahasiswa',
            'user_id'    => $user->id,
            'catatan'    => 'Pengajuan baru dibuat oleh mahasiswa.',
        ]);

        \Illuminate\Support\Facades\DB::commit();

        \Illuminate\Support\Facades\DB::commit();

        $namaSurat = "Surat Izin Penelitian";

        $urlDetail = 'https://sso.unuja.ac.id';

        NotifikasiBAKService::kirimPengajuanBaru(
            $mahasiswa->nim,
            $pengajuan,
            $namaSurat,
            $urlDetail
        );

        return redirect()->route('mahasiswa.surat-izin-penelitian.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratPenelitian::where('id_surat_izin_penelitian', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $mitra    = Mitra::all();

        return view('mahasiswa.surat_penelitian.show', compact('surat', 'mitra'));
    }

    
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratPenelitian::where('id_surat_izin_penelitian', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        if ($surat->status !== 'ditolak') {
            return redirect()->route('mahasiswa.surat-izin-penelitian.index')->with('failed', 'Hanya pengajuan yang ditolak yang dapat diedit.');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra    = Mitra::all();
        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        if ($dataSimpt?->id_smt != $latestAkademik?->kode_akademik) {
            return redirect()->route('mahasiswa.surat-izin-penelitian.index')
                ->with('failed', 'Anda belum mengisi KRS pada semester aktif, sehingga tidak dapat mengajukan surat.');
        }

        return view('mahasiswa.surat_penelitian.edit', compact('surat', 'latestAkademik', 'mitra', 'dataSimpt'));
    }

    
    public function update(Request $request, string $id, SuratPenelitianGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id'         => 'required|exists:mitra,id_mitra',
            'tgl_mulai'        => 'required|date|after_or_equal:today',
            'tgl_selesai'      => 'required|date|after_or_equal:tgl_mulai',
            'judul_penelitian' => 'required',
        ], [
            'tgl_mulai.after_or_equal' => 'Tanggal mulai minimal hari ini.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $user = Auth::user();

        $surat = SuratPenelitian::where('id_surat_izin_penelitian', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $user->mahasiswa?->nim)->firstOrFail();

        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);
        $semester = $dataSimpt?->semester;

        if (blank($semester)) {
            return back()
                ->withInput()
                ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
        }

        $akademik = TahunAkademik::find($request->akademik_id);
        if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
            return back()
                ->withInput()
                ->with('failed', 'Anda belum mengisi KRS pada semester ini, sehingga tidak dapat mengajukan surat.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        $surat->update([
            'akademik_id'      => $request->akademik_id,
            'mitra_id'         => $request->mitra_id,
            'tgl_mulai'        => $request->tgl_mulai,
            'tgl_selesai'      => $request->tgl_selesai,
            'judul_penelitian' => $request->judul_penelitian,
            'status'           => 'pengajuan',
            'catatan'          => 'Diajukan ulang oleh mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            \Illuminate\Support\Facades\DB::beginTransaction();
            $surat->update(['file_generated' => $generatedFilePath]);

            $pengajuan->update([
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh mahasiswa'
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'Mahasiswa',
                'user_id'    => $user->id,
                'catatan'    => 'Pengajuan ulang dibuat oleh mahasiswa.',
            ]);

            \Illuminate\Support\Facades\DB::commit();

                return redirect()->route('mahasiswa.surat-izin-penelitian.index')
                ->with('success', 'Pengajuan surat berhasil diperbarui! Silakan tunggu proses persetujuan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    
    public function destroy(string $id)
    {
        
    }

    private function getDataSimpt(?string $nim): ?object
    {
        if (!$nim) {
            return null;
        }

        try {
            return DB::selectOne(
                '
                                SELECT
                    b.id_smt,
                    
                    IFNULL(
                        b.ipk_ketuntasan,
                        (SELECT tkm.ipk_ketuntasan 
                         FROM dbsimpt.tbbak_kuliah_mahasiswa tkm 
                         WHERE tkm.id_mahasiswa_pt = b.id_mahasiswa_pt 
                           AND tkm.ipk_ketuntasan IS NOT NULL 
                           AND tkm.id_smt < b.id_smt 
                         ORDER BY tkm.id_smt DESC 
                         LIMIT 1)
                    ) AS ipk_ketuntasan,
                    
                    (
                        (LEFT(b.id_smt, 4) - LEFT(a.mulai_smt, 4)) * 2
                        + (RIGHT(b.id_smt, 1) - RIGHT(a.mulai_smt, 1))
                        + 1
                    ) AS semester
                FROM dbsimpt.tbmas_mahasiswa_pt a
                LEFT JOIN dbsimpt.tbbak_kuliah_mahasiswa b 
                    ON a.id_mahasiswa_pt = b.id_mahasiswa_pt
                WHERE a.nipd = ?
                ORDER BY b.id_smt DESC
                LIMIT 1
            ',
                [$nim]
            );
        } catch (Throwable $e) {
            Log::warning("Gagal mengambil data SIMPT untuk NIM: {$nim}", [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}

