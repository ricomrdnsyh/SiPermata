<?php

namespace App\Http\Controllers\BAK;

use App\Models\Prodi;
use App\Models\Template;
use App\Models\Mahasiswa;
use App\Models\SuratLulus;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SuratLulusGenerator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BAKSuratLulusController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasIdUser = $user->penduduk?->fakultas_id;

        $listProdi = collect();
        if ($fakultasIdUser) {
            $listProdi = Prodi::where('fakultas_id', $fakultasIdUser)->get();
        }
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();

        return view('bak.surat_lulus.index', compact('listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratLulus(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        
        $fakultasId = $user->penduduk?->fakultas_id;

        $query = SuratLulus::whereHas('mahasiswa', function ($q) use ($fakultasId) {
            $q->where('fakultas_id', $fakultasId);
        });

        $query = $query->with('mahasiswa');

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
                $showBtn = '<a href="' . route('bak.surat-keterangan-lulus.show', $row->id_surat_lulus) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('bak.surat-keterangan-lulus.edit', $row->id_surat_lulus) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    public function getDataMahasiswaSimpt(string $nim)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $isEligible = $mahasiswa ? $mahasiswa->isEligibleLulus() : false;

        $judulPenelitian = null;
        if ($mahasiswa) {
            $eligibleRecord = \App\Models\MahasiswaEligibleLulus::where('nim', $nim)->orderBy('created_at', 'desc')->first();
            if ($eligibleRecord) {
                $judulPenelitian = $eligibleRecord->judul_penelitian;
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
            'judul_penelitian' => $judulPenelitian,
            'tempat_lahir' => $mahasiswa ? $mahasiswa->tempat_lahir : null,
            'tanggal_lahir' => $mahasiswa && $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d/m/Y') : null,
        ]);
    }

    
    public function create()
    {
        $user     = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403, 'Akses ditolak');
        }

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            return redirect()->route('bak.dashboard')->with('failed', 'Anda belum terhubung ke fakultas manapun.');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mahasiswa = Mahasiswa::where('fakultas_id', $fakultasId)->select('nim', 'nama')->orderBy('nama', 'asc')->get();

        return view('bak.surat_lulus.create', compact('latestAkademik', 'mahasiswa'));
    }

    
    public function store(Request $request, SuratLulusGenerator $generatorService)
    {
        $userBak = Auth::user();

        if ($userBak->role !== 'BAK') {
            abort(403, 'Akses Ditolak.');
        }

        
        $fakultasIdBak = $userBak->penduduk?->fakultas_id;

        if (!$fakultasIdBak) {
            return back()->with('failed', 'Data BAK tidak terhubung ke fakultas manapun.');
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

        if ($mahasiswa->fakultas_id != $fakultasIdBak) {
            return back()->with('failed', 'Mahasiswa tersebut bukan bagian dari fakultas Anda.');
        }

        if (!$mahasiswa->isEligibleLulus()) {
            return back()->with('failed', 'Mahasiswa dengan NIM ini belum terdaftar di daftar mahasiswa lulusan.');
        }

        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $ipk       = $dataSimpt?->ipk_ketuntasan ?? null;

        $namaTemplate = 'surat_keterangan_lulus';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasIdBak)
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
            'catatan'             => 'Diajukan oleh BAK Fakultas untuk mahasiswa',
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
            'catatan'        => 'Diajukan oleh BAK Fakultas untuk mahasiswa',
            'jabatan_id'     => null,
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history,
            'status'     => 'pengajuan',
            'user_role'  => 'BAK',
            'user_id'    => $userBak->id,
            'catatan'    => 'Diajukan oleh BAK Fakultas untuk mahasiswa',
        ]);

        return redirect()->route('bak.surat-keterangan-lulus.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403, 'Akses Ditolak.');
        }

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            abort(403, 'Anda tidak terhubung ke fakultas manapun.');
        }

        $surat = SuratLulus::with('mahasiswa')
            ->where('id_surat_lulus', $id)
            ->firstOrFail();

        $dataSimpt = $this->getDataSimpt($surat->nim);

        return view('bak.surat_lulus.show', compact('surat', 'dataSimpt'));
    }

    
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403, 'Akses Ditolak.');
        }

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            abort(403, 'Anda tidak terhubung ke fakultas manapun.');
        }

        $surat = SuratLulus::with('mahasiswa')
            ->where('id_surat_lulus', $id)
            ->firstOrFail();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mahasiswa = Mahasiswa::where('fakultas_id', $fakultasId)->select('nim', 'nama')->orderBy('nama', 'asc')->get();

        $dataSimpt = $this->getDataSimpt($surat->nim);

        return view('bak.surat_lulus.edit', compact('surat', 'latestAkademik', 'mahasiswa', 'dataSimpt'));
    }

    
    public function update(Request $request, string $id, SuratLulusGenerator $generatorService)
    {
        $userBak = Auth::user();

        if ($userBak->role !== 'BAK') {
            abort(403, 'Akses Ditolak.');
        }

        $fakultasIdBak = $userBak->penduduk?->fakultas_id;

        if (!$fakultasIdBak) {
            return back()->with('failed', 'Data BAK tidak terhubung ke fakultas manapun.');
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

        if ($mahasiswa->fakultas_id != $fakultasIdBak) {
            return back()->with('failed', 'Mahasiswa tersebut bukan bagian dari fakultas Anda.');
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
            'catatan'          => 'Diajukan ulang oleh BAK untuk mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template, $ipk);

            $surat->update([
                'file_generated' => $generatedFilePath
            ]);

            $pengajuan->update([
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh BAK untuk mahasiswa'
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'BAK',
                'user_id'    => $userBak->id,
                'catatan'    => 'Diajukan ulang oleh BAK Fakultas untuk mahasiswa',
            ]);

            return redirect()->route('bak.surat-keterangan-lulus.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    
    public function destroy(string $id)
    {
        
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
