<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Template;
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
use App\Services\NotifikasiBAKService;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaSuratAktifController extends Controller
{
    public function index()
    {
        return view('mahasiswa.surat_aktif.index');
    }

    public function getSuratAktif()
    {
        $user = Auth::user();
        $nim = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = SuratAktif::with(['akademik', 'mahasiswa', 'mahasiswa.prodi'])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
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
                $date = \Carbon\Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id');
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
                $showBtn = '<a href="' . route('mahasiswa.surat-aktif.show', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-aktif.edit', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';
                }

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['kategori', 'akademik', 'status', 'catatan', 'action', 'tanggal_pengajuan'])
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

        return view('mahasiswa.surat_aktif.create', compact('latestAkademik', 'dataSimpt'));
    }


    public function store(Request $request, SuratAktifGenerator $generatorService)
    {
        $request->validate([
            'kategori'            => 'required|in:UMUM,PNS,PPPK',
            'akademik_id'         => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'           => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir' => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'             => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'            => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'          => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'              => 'required_if:kategori,PNS,PPPK|nullable',
            'keperluan'           => 'required'
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

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas Anda belum ditentukan.');
        }

        $kategoriToTemplate = [
            'UMUM'  => 'surat_aktif_umum',
            'PNS'   => 'surat_aktif_pns',
            'PPPK'  => 'surat_aktif_pppk',
        ];

        $namaTemplate = $kategoriToTemplate[$request->kategori];

        
        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk kategori {$request->kategori} belum tersedia untuk fakultas Anda.");
        }

        
        $noSurat = SuratAktif::getNextNoSurat($template->id_template, $request->akademik_id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        $surat = SuratAktif::create([
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'semester'            => $semester,
            'kategori'            => $request->kategori,
            'nama_ortu'           => $request->nama_ortu,
            'nip'                 => $request->nip,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'pangkat'             => $request->pangkat,
            'golongan'            => $request->golongan,
            'tmt'                 => $request->tmt,
            'unit_kerja'          => $request->unit_kerja,
            'alamat'              => $request->alamat,
            'keperluan'           => $request->keperluan,
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
            'id_tabel_surat' => $surat->id_surat_aktif,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_aktif',
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

        $namaSurat = "Surat Keterangan Aktif";

        $urlDetail = 'https://sso.unuja.ac.id';

        NotifikasiBAKService::kirimPengajuanBaru(
            $mahasiswa->nim,
            $pengajuan,
            $namaSurat,
            $urlDetail
        );

        return redirect()->route('mahasiswa.surat-aktif.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    public function edit($id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratAktif::where('id_surat_aktif', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        return view('mahasiswa.surat_aktif.edit', compact('surat', 'latestAkademik', 'dataSimpt'));
    }

    public function update(Request $request, $id, SuratAktifGenerator $generatorService)
    {
        $request->validate([
            'kategori'            => 'required|in:UMUM,PNS,PPPK',
            'akademik_id'         => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'           => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir' => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'             => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'            => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'          => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'              => 'required_if:kategori,PNS,PPPK|nullable',
            'keperluan'           => 'required',
        ]);

        $user = Auth::user();

        $surat = SuratAktif::findOrFail($id);

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $user->mahasiswa?->nim)->firstOrFail();

        if (!$surat) {
            return back()->with('failed', 'Data surat tidak ditemukan.');
        }

        
        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);
        $semester = $dataSimpt?->semester;

        if (blank($semester)) {
            return back()
                ->withInput()
                ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
        }

        $template = Template::findOrFail($surat->template_id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        $surat->update([
            'akademik_id'         => $request->akademik_id,
            'semester'            => $semester,
            'alamat'              => $request->alamat,
            'nama_ortu'           => $request->nama_ortu,
            'nip'                 => $request->nip,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'pangkat'             => $request->pangkat,
            'golongan'            => $request->golongan,
            'tmt'                 => $request->tmt,
            'unit_kerja'          => $request->unit_kerja,
            'keperluan'           => $request->keperluan,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan ulang oleh mahasiswa',
        ]);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template);
            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('failed', 'Gagal memproses template dokumen setelah update. Error: ' . $e->getMessage());
        }

        
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

        return redirect()->route('mahasiswa.surat-aktif.index')->with('success', 'Pengajuan berhasil diperbarui! Silakan tunggu proses persetujuan.');
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratAktif::where('id_surat_aktif', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        return view('mahasiswa.surat_aktif.show', compact('surat'));
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
