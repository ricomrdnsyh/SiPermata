<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Template;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SuratRekomendasiGenerator;

class SuratRekomendasiController extends Controller
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

        return view('admin.surat_rekomendasi.index', compact('listFakultas', 'listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratRekomendasi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $query = SuratRekomendasi::with(['mahasiswa.prodi', 'akademik']);

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
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—';
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pengajuan' => '<span class="badge bg-warning">Menunggu BAK</span>',
                    'proses'    => '<span class="badge bg-info">Menunggu Dekan</span>',
                    'diterima'  => '<span class="badge bg-success">Disetujui</span>',
                    'selesai'   => '<span class="badge bg-primary">Selesai</span>',
                    'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
                    default     => '<span class="badge bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.surat-rekomendasi.show', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.surat-rekomendasi.edit', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
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

        $dataSimpt = $this->getDataSimpt($nim);

        if (!$dataSimpt) {
            return response()->json([
                'semester' => null,
                'ipk'      => null,
                'message'  => 'Data SIMPT tidak ditemukan untuk mahasiswa ini.',
            ]);
        }

        return response()->json([
            'semester' => $dataSimpt->semester,
            'ipk'      => $dataSimpt->ipk_ketuntasan
                ? number_format((float) $dataSimpt->ipk_ketuntasan, 2)
                : null,
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $mahasiswa = Mahasiswa::select('nim', 'nama')->orderBy('nama', 'asc')->get();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        return view('admin.surat_rekomendasi.create', compact('mahasiswa', 'latestAkademik'));
    }

    public function store(Request $request, SuratRekomendasiGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'nim'              => 'required|exists:mahasiswa,nim',
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'keperluan'        => 'required',
            'penyelenggara'    => 'required',
            'tgl_pelaksanaan'  => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas mahasiswa belum ditentukan.');
        }

        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $semester  = $dataSimpt?->semester ?? null;
        $ipk       = $dataSimpt?->ipk_ketuntasan ?? null;

        $namaTemplate = 'surat_rekomendasi';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk {$namaTemplate} belum tersedia untuk fakultas mahasiswa ini.");
        }

        $noSurat = SuratRekomendasi::getNextNoSurat($template->id_template);

        $surat = SuratRekomendasi::create([
            'template_id'     => $template->id_template,
            'no_surat'        => $noSurat,
            'nim'             => $mahasiswa->nim,
            'akademik_id'     => $request->akademik_id,
            'keperluan'       => $request->keperluan,
            'penyelenggara'   => $request->penyelenggara,
            'tgl_pelaksanaan' => $request->tgl_pelaksanaan,
            'status'          => 'pengajuan',
            'catatan'         => 'Diajukan oleh Admin untuk mahasiswa',
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

        return redirect()->route('admin.surat-rekomendasi.index')
            ->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratRekomendasi::with('mahasiswa')
            ->where('id_surat_rekomendasi', $id)
            ->firstOrFail();

        $dataSimpt = $this->getDataSimpt($surat->nim);

        return view('admin.surat_rekomendasi.show', compact('surat', 'dataSimpt'));
    }

    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratRekomendasi::with('mahasiswa')
            ->where('id_surat_rekomendasi', $id)
            ->firstOrFail();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mahasiswa = Mahasiswa::select('nim', 'nama')->orderBy('nama', 'asc')->get();

        return view('admin.surat_rekomendasi.edit', compact('surat', 'latestAkademik', 'mahasiswa'));
    }

    public function update(Request $request, string $id, SuratRekomendasiGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'nim'              => 'required|exists:mahasiswa,nim',
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'keperluan'        => 'required',
            'penyelenggara'    => 'required',
            'tgl_pelaksanaan'  => 'required',
        ]);

        $surat = SuratRekomendasi::findOrFail($id);

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $surat->nim)->firstOrFail();

        $dataSimpt = $this->getDataSimpt($request->nim);
        $semester  = $dataSimpt?->semester ?? null;
        $ipk       = $dataSimpt?->ipk_ketuntasan ?? null;

        $surat->update([
            'nim'             => $request->nim,
            'akademik_id'     => $request->akademik_id,
            'keperluan'       => $request->keperluan,
            'penyelenggara'   => $request->penyelenggara,
            'tgl_pelaksanaan' => $request->tgl_pelaksanaan,
            'status'          => 'pengajuan',
            'catatan'         => 'Diajukan ulang oleh Admin untuk mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template, $semester, $ipk);

            $surat->update(['file_generated' => $generatedFilePath]);

            $pengajuan->update([
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh Admin untuk mahasiswa',
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'Admin',
                'user_id'    => $user->id,
                'catatan'    => 'Diajukan ulang oleh Admin untuk mahasiswa',
            ]);

            return redirect()->route('admin.surat-rekomendasi.index')
                ->with('success', 'Data surat berhasil diperbarui!');
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
