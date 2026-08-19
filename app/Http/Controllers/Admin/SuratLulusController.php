<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\HistoryPengajuan;
use App\Models\Mahasiswa;
use App\Models\MahasiswaEligibleLulus;
use App\Models\PengajuanStatusLog;
use App\Models\Prodi;
use App\Models\SuratLulus;
use App\Models\TahunAkademik;
use App\Models\Template;
use App\Services\SuratLulusGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class SuratLulusController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $listFakultas = Fakultas::all();
        $listProdi = Prodi::all();
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();

        return view('admin.surat_lulus.index', compact('listFakultas', 'listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratLulus(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $query = SuratLulus::with(['mahasiswa.prodi', 'akademik']);

        if ($request->filled('fakultas_filter')) {
            $fakultasId = $request->input('fakultas_filter');
            $query->whereHas('mahasiswa', function ($q) use ($fakultasId) {
                $q->where('fakultas_id', $fakultasId);
            });
        }

        if ($request->filled('prodi_filter')) {
            $prodiId = $request->input('prodi_filter');
            $query->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
        }

        if ($request->filled('tahun_akademik_filter')) {
            $akademikId = $request->input('tahun_akademik_filter');
            $query->where('akademik_id', $akademikId);
        }

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->filterColumn('nama_mahasiswa', function ($query, $keyword) {
                $query->whereHas('mahasiswa', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('prodi', function ($query, $keyword) {
                $query->whereHas('mahasiswa.prodi', function ($q) use ($keyword) {
                    $q->where('nama_prodi', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('nama_mahasiswa', function ($row) {
                return $row->mahasiswa?->nama ?? $row->nim;
            })
            ->addColumn('prodi', function ($row) {
                return $row->mahasiswa?->prodi?->nama_prodi ?? $row->nim;
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                $date = \Carbon\Carbon::parse($row->tanggal_pengajuan_asli)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pengajuan' => '<span class="badge text-white bg-warning">Menunggu BAK</span>',
                    'proses'    => '<span class="badge text-white bg-info">Menunggu Dekan</span>',
                    'diterima'  => '<span class="badge text-white bg-success">Disetujui</span>',
                    'selesai'   => '<span class="badge text-white bg-primary">Selesai</span>',
                    'ditolak'   => '<span class="badge text-white bg-danger">Ditolak</span>',
                    default     => '<span class="badge text-white bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.surat-keterangan-lulus.show', $row->id_surat_lulus) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip"
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.surat-keterangan-lulus.edit', $row->id_surat_lulus) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip"
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    public function getDataMahasiswaSimpt(string $nim)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $isEligible = $mahasiswa ? $mahasiswa->isEligibleLulus() : false;
        $isAlreadyApplied = SuratLulus::where('nim', $nim)->exists();

        $judulPenelitian = null;
        if ($mahasiswa) {
            $eligibleRecord = MahasiswaEligibleLulus::with('akademik')->where('nim', $nim)->orderBy('created_at', 'desc')->first();
            if ($eligibleRecord) {
                $judulPenelitian = $eligibleRecord->judul_penelitian;
                $akademik = $eligibleRecord->akademik;
            }
        }

        $dataSimpt = $this->getDataSimpt($nim);

        if (!$dataSimpt) {
            return response()->json([
                'ipk'              => null,
                'is_eligible'      => $isEligible,
                'judul_penelitian' => $judulPenelitian,
                'message'          => 'Data SIMPT tidak ditemukan untuk mahasiswa ini.',
            ]);
        }

        return response()->json([
            'ipk'         => $dataSimpt->ipk_ketuntasan
                ? number_format((float) $dataSimpt->ipk_ketuntasan, 2)
                : null,
            'is_eligible' => $isEligible,
            'is_already_applied' => $isAlreadyApplied,
            'judul_penelitian' => $judulPenelitian,
            'tempat_lahir' => $mahasiswa ? $mahasiswa->tempat_lahir : null,
            'tanggal_lahir' => $mahasiswa && $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d/m/Y') : null,
            'akademik_id' => isset($akademik) && $akademik ? $akademik->id_akademik : null,
            'tahun_akademik' => isset($akademik) && $akademik ? $akademik->tahun_akademik : null,
        ]);
    }


    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $mahasiswa = Mahasiswa::all();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        return view('admin.surat_lulus.create', compact('mahasiswa', 'latestAkademik'));
    }


    public function store(Request $request, SuratLulusGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'tempat_lahir'     => 'required',
            'tgl_lahir'        => 'required',
            'judul_penelitian' => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        if (!$mahasiswa->isEligibleLulus()) {
            return back()->with('failed', 'Mahasiswa dengan NIM ini belum terdaftar di daftar mahasiswa lulusan.');
        }

        $existingSurat = SuratLulus::where('nim', $mahasiswa->nim)->exists();
        if ($existingSurat) {
            return back()->with('failed', 'Mahasiswa ini sudah memiliki Surat Keterangan Lulus. Jika ditolak, silakan gunakan fitur edit.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas Anda belum ditentukan.');
        }

        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $ipk       = $dataSimpt?->ipk_ketuntasan ?? null;

        $namaTemplate = 'surat_keterangan_lulus';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk {$namaTemplate} belum tersedia untuk fakultas Anda.");
        }


        $noSurat = SuratLulus::getNextNoSurat($template->id_template, $request->akademik_id);

        $surat = SuratLulus::create([
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'tempat_lahir'        => $request->tempat_lahir,
            'tgl_lahir'           => $request->tgl_lahir,
            'judul_penelitian'    => $request->judul_penelitian,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan oleh Admin untuk mahasiswa',
            'file_generated'      => null,
        ]);

        try {

            $generatedFilePath = $generatorService->generateWord($surat, $template, $ipk);


            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            $surat->delete();
            return back()->with('failed', 'Gagal memproses template dokumen. Silakan coba lagi atau hubungi admin. Error: ' . $e->getMessage());
        }

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_lulus,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_keterangan_lulus',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh Admin untuk mahasiswa',
            'jabatan_id'     => null,
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history,
            'status'     => 'pengajuan',
            'user_role'  => 'Admin',
            'user_id'    => $user->id,
            'catatan'    => 'Diajukan oleh Admin untuk mahasiswa',
        ]);

        return redirect()->route('admin.surat-keterangan-lulus.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }


    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratLulus::with('mahasiswa')
            ->where('id_surat_lulus', $id)
            ->firstOrFail();

        $dataSimpt = $this->getDataSimpt($surat->nim);

        return view('admin.surat_lulus.show', compact('surat', 'dataSimpt'));
    }


    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratLulus::with('mahasiswa')
            ->where('id_surat_lulus', $id)
            ->firstOrFail();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mahasiswa = Mahasiswa::all();

        $dataSimpt = $this->getDataSimpt($surat->nim);

        return view('admin.surat_lulus.edit', compact('surat', 'latestAkademik', 'mahasiswa', 'dataSimpt'));
    }


    public function update(Request $request, string $id, SuratLulusGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'tempat_lahir'     => 'required',
            'tgl_lahir'        => 'required',
            'judul_penelitian' => 'required',
        ]);

        $surat = SuratLulus::findOrFail($id);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        if (!$mahasiswa->isEligibleLulus()) {
            return back()->with('failed', 'Mahasiswa dengan NIM ini belum terdaftar di daftar mahasiswa lulusan.');
        }

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $request->nim)->firstOrFail();

        $dataSimpt = $this->getDataSimpt($request->nim);
        $ipk       = $dataSimpt?->ipk_ketuntasan ?? null;

        $surat->update([
            'nim'              => $request->nim,
            'akademik_id'      => $request->akademik_id,
            'tempat_lahir'     => $request->tempat_lahir,
            'tgl_lahir'        => $request->tgl_lahir,
            'judul_penelitian' => $request->judul_penelitian,
            'status'           => 'pengajuan',
            'catatan'          => 'Diajukan ulang oleh Admin untuk mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template, $ipk);

            $surat->update([
                'file_generated' => $generatedFilePath
            ]);

            $pengajuan->update([
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh Admin untuk mahasiswa'
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'Admin',
                'user_id'    => $user->id,
                'catatan'    => 'Diajukan ulang oleh Admin untuk mahasiswa',
            ]);

            return redirect()->route('admin.surat-keterangan-lulus.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }


    public function destroy(string $id) {}

    private function getDataSimpt(?string $nim): ?object
    {
        if (!$nim) return null;

        try {
            return DB::selectOne('
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
            ', [$nim]);
        } catch (Throwable $e) {
            Log::warning("Gagal mengambil data SIMPT untuk NIM: {$nim}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
