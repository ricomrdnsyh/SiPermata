<?php

namespace App\Http\Controllers\Admin;

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

class SuratAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.surat_aktif.index');
    }

    public function getSuratAktif()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $query = SuratAktif::with(['mahasiswa.prodi', 'mahasiswa.fakultas', 'akademik']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
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
        $akademik  = TahunAkademik::all();

        return view('admin.surat_aktif.create', compact('mahasiswa', 'akademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SuratAktifGenerator $generatorService)
    {
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
            'semester'             => $request->semester,
            'kategori'             => $request->kategori,
            'nama_ortu'            => $request->nama_ortu,
            'nip'                  => $request->nip,
            'pendidikan_terakhir'  => $request->pendidikan_terakhir,
            'pangkat'              => $request->pangkat,
            'golongan'             => $request->golongan,
            'tmt'                  => $request->tmt,
            'unit_kerja'           => $request->unit_kerja,
            'alamat'               => $request->alamat,
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

        HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_aktif,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_aktif',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh Admin untuk mahasiswa',
            'jabatan_id'     => null,
        ]);

        return redirect()->route('admin.surat-aktif.index')->with('success', 'Pengajuan surat berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = SuratAktif::with(['mahasiswa', 'akademik'])->findOrFail($id);

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
        $akademik = TahunAkademik::all();

        return view('admin.surat_aktif.edit', compact('surat', 'mahasiswa', 'akademik'));
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

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $request->nim)->firstOrFail();


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

            return redirect()->route('admin.surat-aktif.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}
}
