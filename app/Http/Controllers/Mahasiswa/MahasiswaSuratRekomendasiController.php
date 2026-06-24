<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Models\SuratRekomendasi;
use App\Models\TahunAkademik;
use App\Models\Template;
use App\Services\NotifikasiBAKService;
use App\Services\SuratRekomendasiGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaSuratRekomendasiController extends Controller
{
    
    public function index()
    {
        return view('mahasiswa.surat_rekomendasi.index');
    }

    public function getSuratRekomendasi()
    {
        $user = Auth::user();
        $nim  = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = SuratRekomendasi::with(['akademik', 'mahasiswa', 'mahasiswa.prodi'])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—';
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
                $showBtn = '<a href="' . route('mahasiswa.surat-rekomendasi.show', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-rekomendasi.edit', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';
                }

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['status', 'akademik', 'catatan', 'action'])
            ->make(true);
    }

    
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        return view('mahasiswa.surat_rekomendasi.create', compact('latestAkademik', 'dataSimpt'));
    }

    public function store(Request $request, SuratRekomendasiGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'     => 'required|exists:tahun_akademik,id_akademik',
            'keperluan'       => 'required',
            'penyelenggara'   => 'required',
            'tgl_pelaksanaan' => 'required',
        ]);

        $user      = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas Anda belum ditentukan.');
        }

        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $semester  = $dataSimpt?->semester ?? null;
        $ipk       = $dataSimpt?->ipk_ketuntasan ?? null;

        $namaTemplate = 'surat_rekomendasi';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', 'Template surat ini belum tersedia untuk fakultas Anda.');
        }

        $noSurat = SuratRekomendasi::getNextNoSurat($template->id_template, $request->akademik_id);

        $surat = SuratRekomendasi::create([
            'template_id'     => $template->id_template,
            'no_surat'        => $noSurat,
            'nim'             => $mahasiswa->nim,
            'akademik_id'     => $request->akademik_id,
            'keperluan'       => $request->keperluan,
            'penyelenggara'   => $request->penyelenggara,
            'tgl_pelaksanaan' => $request->tgl_pelaksanaan,
            'status'          => 'pengajuan',
            'catatan'         => 'Diajukan oleh mahasiswa',
            'file_generated'  => null,
        ]);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template, $semester, $ipk);
            $surat->update(['file_generated' => $generatedFilePath]);
        } catch (\Exception $e) {
            $surat->delete();
            return back()->with('failed', 'Gagal memproses template dokumen. Error: ' . $e->getMessage());
        }

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_rekomendasi,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_rekomendasi',
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

        NotifikasiBAKService::kirimPengajuanBaru(
            $mahasiswa->nim,
            $pengajuan,
            'Surat Rekomendasi',
            'https://sso.unuja.ac.id'
        );

        return redirect()->route('mahasiswa.surat-rekomendasi.index')
            ->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }

        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        return view('mahasiswa.surat_rekomendasi.show', compact('surat', 'dataSimpt'));
    }

    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }

        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        if ($surat->status !== 'ditolak') {
            return redirect()->route('mahasiswa.surat-rekomendasi.index')
                ->with('failed', 'Hanya pengajuan yang ditolak yang dapat diedit.');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        return view('mahasiswa.surat_rekomendasi.edit', compact('surat', 'latestAkademik', 'dataSimpt'));
    }

    public function update(Request $request, string $id, SuratRekomendasiGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'     => 'required|exists:tahun_akademik,id_akademik',
            'keperluan'       => 'required',
            'penyelenggara'   => 'required',
            'tgl_pelaksanaan' => 'required',
        ]);

        $user = Auth::user();

        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);
        $semester  = $dataSimpt?->semester ?? null;
        $ipk       = $dataSimpt?->ipk_ketuntasan ?? null;

        $surat->update([
            'akademik_id'     => $request->akademik_id,
            'keperluan'       => $request->keperluan,
            'penyelenggara'   => $request->penyelenggara,
            'tgl_pelaksanaan' => $request->tgl_pelaksanaan,
            'status'          => 'pengajuan',
            'catatan'         => 'Diajukan ulang oleh mahasiswa',
        ]);

        try {
            $template          = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template, $semester, $ipk);

            $surat->update(['file_generated' => $generatedFilePath]);

            $pengajuan->update([
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh mahasiswa',
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'Mahasiswa',
                'user_id'    => $user->id,
                'catatan'    => 'Pengajuan ulang dibuat oleh mahasiswa.',
            ]);

            return redirect()->route('mahasiswa.surat-rekomendasi.index')
                ->with('success', 'Pengajuan surat berhasil diperbarui! Silakan tunggu proses persetujuan.');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    private function getDataSimpt(?string $nim): ?object
    {
        if (!$nim) return null;

        try {
            return DB::selectOne('
                SELECT
                    b.id_smt,
                    b.ipk_ketuntasan,
                    (
                        (LEFT(b.id_smt, 4) - LEFT(a.mulai_smt, 4)) * 2
                        + (RIGHT(b.id_smt, 1) - RIGHT(a.mulai_smt, 1))
                        + 1
                        + IF(max_smt.id_smt > b.id_smt, 1, 0)
                    ) AS semester
                FROM dbsimpt.tbmas_mahasiswa_pt a
                LEFT JOIN dbsimpt.tbbak_kuliah_mahasiswa b
                    ON a.id_mahasiswa_pt = b.id_mahasiswa_pt
                    AND b.ipk_ketuntasan IS NOT NULL
                LEFT JOIN (
                    SELECT id_mahasiswa_pt, MAX(id_smt) AS id_smt
                    FROM dbsimpt.tbbak_kuliah_mahasiswa
                    GROUP BY id_mahasiswa_pt
                ) max_smt ON a.id_mahasiswa_pt = max_smt.id_mahasiswa_pt
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
