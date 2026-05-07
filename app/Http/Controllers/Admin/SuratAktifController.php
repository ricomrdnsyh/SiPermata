<?php

namespace App\Http\Controllers\Admin;

use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Template;
use App\Models\Mahasiswa;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\SuratAktifGenerator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class SuratAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        return view('admin.surat_aktif.index', compact('listFakultas', 'listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratAktif(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $query = SuratAktif::with(['mahasiswa.prodi', 'mahasiswa.fakultas', 'akademik']);

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
            ->editColumn('kategori', function ($row) {
                if ($row->kategori == 'UMUM') {
                    return '<span>Surat Keterangan Aktif UMUM</span>';
                } elseif ($row->kategori == 'PNS') {
                    return '<span>Surat Keterangan Aktif PNS</span>';
                } elseif ($row->kategori == 'PPPK') {
                    return '<span>Surat Keterangan Aktif PPPK</span>';
                }
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
                    'selesai'  => '<span class="badge bg-primary">Selesai</span>',
                    'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
                    default     => '<span class="badge bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.surat-aktif.show', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.surat-aktif.edit', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'kategori', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $mahasiswa = Mahasiswa::all();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        return view('admin.surat_aktif.create', compact('mahasiswa', 'latestAkademik'));
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
                'message' => 'Data SIMPT tidak ditemukan untuk mahasiswa ini.',
            ]);
        }

        return response()->json([
            'semester' => $dataSimpt->semester,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SuratAktifGenerator $generatorService)
    {
        $request->validate([
            'nim'                       => 'required|exists:mahasiswa,nim',
            'kategori'                  => 'required|in:UMUM,PNS,PPPK',
            'akademik_id'               => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                       => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir'       => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'                   => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'                  => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                       => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'                => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'                    => 'required_if:kategori,PNS,PPPK|nullable',
            'keperluan'                 => 'required',
        ]);

        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas Anda belum ditentukan.');
        }

        // Ambil semester otomatis dari SIM-PT
        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $semester = $dataSimpt?->semester ?? null;

        $kategoriToTemplate = [
            'UMUM'  => 'surat_aktif_umum',
            'PNS'   => 'surat_aktif_pns',
            'PPPK'  => 'surat_aktif_pppk',
        ];

        $namaTemplate = $kategoriToTemplate[$request->kategori];

        // Cari template berdasarkan kategori + fakultas
        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk kategori {$request->kategori} belum tersedia untuk fakultas Anda.");
        }

        // Generate nomor surat
        $noSurat = SuratAktif::getNextNoSurat($template->id_template);

        $surat = SuratAktif::create([
            'template_id'          => $template->id_template,
            'no_surat'             => $noSurat,
            'nim'                  => $mahasiswa->nim,
            'akademik_id'          => $request->akademik_id,
            'semester'             => $semester,
            'kategori'             => $request->kategori,
            'nama_ortu'            => $request->nama_ortu,
            'nip'                  => $request->nip,
            'pendidikan_terakhir'  => $request->pendidikan_terakhir,
            'pangkat'              => $request->pangkat,
            'golongan'             => $request->golongan,
            'tmt'                  => $request->tmt,
            'unit_kerja'           => $request->unit_kerja,
            'alamat'               => $request->alamat,
            'keperluan'            => $request->keperluan,
            'status'               => 'pengajuan',
            'catatan'              => 'Diajukan oleh Admin untuk mahasiswa',
        ]);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            $surat->delete();
            return back()->with('failed', 'Gagal memproses template dokumen. Error: ' . $e->getMessage());
        }

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_aktif,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_aktif',
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

        return redirect()->route('admin.surat-aktif.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratAktif::with('mahasiswa')
            ->where('id_surat_aktif', $id)
            ->firstOrFail();

        return view('admin.surat_aktif.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $surat = SuratAktif::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        return view('admin.surat_aktif.edit', compact('surat', 'mahasiswa', 'latestAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, SuratAktifGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'nim'                   => 'required|exists:mahasiswa,nim',
            'akademik_id'           => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'             => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                   => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir'   => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'               => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'              => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                   => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'            => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'                => 'required_if:kategori,PNS,PPPK|nullable',
            'keperluan'             => 'required',
        ]);

        $surat = SuratAktif::findOrFail($id);

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $request->nim)->firstOrFail();


        $surat->update([
            'nim'                   => $request->nim,
            'akademik_id'           => $request->akademik_id,
            'semester'              => $this->getDataSimpt($request->nim)?->semester ?? $surat->semester,
            'nama_ortu'             => $request->nama_ortu,
            'nip'                   => $request->nip,
            'pendidikan_terakhir'   => $request->pendidikan_terakhir,
            'pangkat'               => $request->pangkat,
            'golongan'              => $request->golongan,
            'tmt'                   => $request->tmt,
            'unit_kerja'            => $request->unit_kerja,
            'alamat'                => $request->alamat,
            'keperluan'             => $request->keperluan,
            'status'                => 'pengajuan',
            'catatan'               => 'Diajukan ulang oleh Admin untuk mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template);

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

            return redirect()->route('admin.surat-aktif.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}

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
