<?php

namespace App\Http\Controllers\BAK;

use App\Models\Template;
use App\Models\Mahasiswa;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SuratAktifGenerator;
use Yajra\DataTables\Facades\DataTables;

class BAKSuratAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bak.surat_aktif.index');
    }

    public function getSuratAktif()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        // Ambil fakultas_id dari data penduduk BAK
        $fakultasId = $user->penduduk?->fakultas_id;

        $query = SuratAktif::whereHas('mahasiswa', function ($q) use ($fakultasId) {
            $q->where('fakultas_id', $fakultasId);
        });

        $query = $query->with('mahasiswa');

        return DataTables::of($query)
            ->addColumn('nama_mahasiswa', function ($row) {
                return $row->mahasiswa?->nama ?? $row->nim;
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
                return Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM YYYY') ?? '—';
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pengajuan' => '<span class="badge bg-warning">Menunggu BAK</span>',
                    'proses'    => '<span class="badge bg-info">Menunggu Dekan</span>',
                    'diterima'  => '<span class="badge bg-success">Disetujui</span>',
                    'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
                    default     => '<span class="badge bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('bak.surat-aktif.show', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('bak.surat-aktif.edit', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'kategori', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
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

        $akademik = TahunAkademik::all();

        return view('bak.surat_aktif.create', compact('mahasiswa', 'akademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SuratAktifGenerator $generatorService)
    {
        $userBak = Auth::user();

        // 1. Otorisasi
        if ($userBak->role !== 'BAK') {
            abort(403, 'Akses Ditolak.');
        }

        // 2. Tentukan ID Fakultas BAK yang login
        $fakultasIdBak = $userBak->penduduk?->fakultas_id;

        if (!$fakultasIdBak) {
            return back()->with('failed', 'Data BAK tidak terhubung ke fakultas manapun.');
        }

        // 3. Validasi Input (Tambahkan Validasi NIM)
        $request->validate([
            'nim'                       => 'required|exists:mahasiswa,nim',
            'kategori'                  => 'required|in:UMUM,PNS,PPPK',
            'semester'                  => 'required',
            'akademik_id'               => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                       => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir'       => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'                   => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'                  => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                       => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'                => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'                    => 'required_if:kategori,PNS,PPPK|nullable',
        ]);

        // 4. Identifikasi Mahasiswa Pemohon
        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if ($mahasiswa->fakultas_id != $fakultasIdBak) {
            return back()->with('failed', 'Mahasiswa tersebut bukan bagian dari fakultas Anda.');
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

        // Generate nomor surat
        $noSurat = SuratAktif::getNextNoSurat($template->id_template);

        $surat = SuratAktif::create([
            'template_id'           => $template->id_template,
            'no_surat'              => $noSurat,
            'nim'                   => $mahasiswa->nim,
            'akademik_id'           => $request->akademik_id,
            'semester'              => $request->semester,
            'kategori'              => $request->kategori,
            'nama_ortu'             => $request->nama_ortu,
            'nip'                   => $request->nip,
            'pendidikan_terakhir'   => $request->pendidikan_terakhir,
            'pangkat'               => $request->pangkat,
            'golongan'              => $request->golongan,
            'tmt'                   => $request->tmt,
            'unit_kerja'            => $request->unit_kerja,
            'alamat'                => $request->alamat,
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

        HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_aktif,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_aktif',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh BAK Fakultas untuk mahasiswa',
            'jabatan_id'     => null,
        ]);

        return redirect()->route('bak.surat-aktif.index')->with('success', 'Pengajuan surat berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
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

        $akademik = TahunAkademik::all();

        return view('bak.surat_aktif.edit', compact('surat', 'mahasiswa', 'akademik'));
    }

    /**
     * Update the specified resource in storage.
     */
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
            'semester'              => 'required',
            'akademik_id'           => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'             => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                   => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir'   => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'               => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'              => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                   => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'            => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'                => 'required_if:kategori,PNS,PPPK|nullable',
        ]);

        $surat = SuratAktif::findOrFail($id);

        $surat->update([
            'nim'                   => $request->nim,
            'akademik_id'           => $request->akademik_id,
            'semester'              => $request->semester,
            'nama_ortu'             => $request->nama_ortu,
            'nip'                   => $request->nip,
            'pendidikan_terakhir'   => $request->pendidikan_terakhir,
            'pangkat'               => $request->pangkat,
            'golongan'              => $request->golongan,
            'tmt'                   => $request->tmt,
            'unit_kerja'            => $request->unit_kerja,
            'alamat'                => $request->alamat,
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template);

            $surat->update([
                'file_generated' => $generatedFilePath
            ]);

            return redirect()->route('bak.surat-aktif.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }
}
