<?php

namespace App\Http\Controllers\BAK;

use App\Models\Prodi;
use App\Models\Template;
use App\Models\Mahasiswa;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SuratAktifGenerator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class BAKSuratAktifController extends Controller
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

        return view('bak.surat_aktif.index', compact('listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratAktif(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        
        $fakultasId = $user->penduduk?->fakultas_id;

        $query = SuratAktif::whereHas('mahasiswa', function ($q) use ($fakultasId) {
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
                $showBtn = '<a href="' . route('bak.surat-aktif.show', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('bak.surat-aktif.edit', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'kategori', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            return redirect()->route('bak.dashboard')->with('failed', 'Anda belum terhubung ke fakultas manapun.');
        }

        $mahasiswa = Mahasiswa::where('fakultas_id', $fakultasId)->select('nim', 'nama')->orderBy('nama', 'asc')->get();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        return view('bak.surat_aktif.create', compact('mahasiswa', 'latestAkademik'));
    }

    public function getDataMahasiswaSimpt(string $nim)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $dataSimpt = $this->getDataSimpt($nim);

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $isValidKrs = false;
        
        if ($dataSimpt && $latestAkademik) {
            $isValidKrs = ($dataSimpt->id_smt == $latestAkademik->kode_akademik);
        }

        if (!$dataSimpt) {
            return response()->json([
                'semester'     => null,
                'is_valid_krs' => $isValidKrs,
                'message'      => 'Data SIMPT tidak ditemukan untuk mahasiswa ini.',
            ]);
        }

        return response()->json([
            'semester'     => $dataSimpt->semester,
            'is_valid_krs' => $isValidKrs,
        ]);
    }

    
    public function store(Request $request, SuratAktifGenerator $generatorService)
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

        
        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if ($mahasiswa->fakultas_id != $fakultasIdBak) {
            return back()->with('failed', 'Mahasiswa tersebut bukan bagian dari fakultas Anda.');
        }

        
        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $semester = $dataSimpt?->semester ?? null;

        if (blank($semester)) {
            return back()
                ->withInput()
                ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
        }

        $akademik = TahunAkademik::find($request->akademik_id);
        if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
            return back()
                ->withInput()
                ->with('failed', 'Mahasiswa belum mengisi KRS pada semester ini, sehingga tidak dapat dibuatkan surat.');
        }

        $kategoriToTemplate = [
            'UMUM'  => 'surat_aktif_umum',
            'PNS'   => 'surat_aktif_pns',
            'PPPK'  => 'surat_aktif_pppk',
        ];

        $namaTemplate = $kategoriToTemplate[$request->kategori];

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasIdBak)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk kategori {$request->kategori} belum tersedia untuk fakultas Anda.");
        }

        
        $noSurat = SuratAktif::getNextNoSurat($template->id_template, $request->akademik_id);

        $surat = SuratAktif::create([
            'template_id'           => $template->id_template,
            'no_surat'              => $noSurat,
            'nim'                   => $mahasiswa->nim,
            'akademik_id'           => $request->akademik_id,
            'semester'              => $semester,
            'kategori'              => $request->kategori,
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
            'catatan'               => 'Diajukan oleh BAK Fakultas untuk mahasiswa',
            'file_generated'        => null,
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

        return redirect()->route('bak.surat-aktif.index')->with('success', 'Pengajuan surat berhasil dibuat!');
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

        $surat = SuratAktif::with('mahasiswa')
            ->where('id_surat_aktif', $id)
            ->firstOrFail();

        return view('bak.surat_aktif.show', compact('surat'));
    }

    
    public function edit(string $id)
    {
        $userBak = Auth::user();

        if ($userBak->role !== 'BAK') {
            abort(403, 'Akses Ditolak.');
        }

        $fakultasIdBak = $userBak->penduduk?->fakultas_id;

        if (!$fakultasIdBak) {
            return redirect()->route('bak.dashboard')->with('failed', 'Anda tidak terhubung ke fakultas manapun.');
        }

        $surat = SuratAktif::with('mahasiswa')
            ->where('id_surat_aktif', $id)
            ->firstOrFail();

        $mahasiswaFakultasId = $surat->mahasiswa?->fakultas_id;

        if ($mahasiswaFakultasId != $fakultasIdBak) {
            abort(403, 'Surat ini bukan dari fakultas Anda dan tidak dapat diedit.');
        }

        $mahasiswa = Mahasiswa::where('fakultas_id', $fakultasIdBak)
            ->select('nim', 'nama')
            ->orderBy('nama', 'asc')
            ->get();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        return view('bak.surat_aktif.edit', compact('surat', 'mahasiswa', 'latestAkademik'));
    }

    
    public function update(Request $request, string $id, SuratAktifGenerator $generatorService)
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


        $dataSimpt = $this->getDataSimpt($request->nim);
        $semester = $dataSimpt?->semester ?? null;

        if (blank($semester)) {
            return back()
                ->withInput()
                ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
        }

        $akademik = TahunAkademik::find($request->akademik_id);
        if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
            return back()
                ->withInput()
                ->with('failed', 'Mahasiswa belum mengisi KRS pada semester ini, sehingga tidak dapat dibuatkan surat.');
        }

        $surat->update([
            'nim'                   => $request->nim,
            'akademik_id'           => $request->akademik_id,
            'semester'              => $semester,
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
            'catatan'               => 'Diajukan ulang oleh BAK untuk mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template);

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

            return redirect()->route('bak.surat-aktif.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
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

