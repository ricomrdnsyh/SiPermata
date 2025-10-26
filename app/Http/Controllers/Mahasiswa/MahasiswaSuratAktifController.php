<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Template;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SuratAktifGenerator;
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

        $query = SuratAktif::with([])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'ditolak'])
            ->get();

        return DataTables::of($query)
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
            ->addColumn('tahun_akademik', function ($row) {
                return $row->akademik ? $row->akademik->tahun_akademik : '—';
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
                $showBtn = '<a href="' . route('mahasiswa.surat-aktif.show', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-aktif.edit', $row->id_surat_aktif) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';
                }

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['kategori', 'tahun_akademik', 'status', 'catatan', 'action'])
            ->make(true);
    }

    public function create()
    {
        $user     = Auth::user();
        $akademik = TahunAkademik::all();

        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak');
        }

        return view('mahasiswa.surat_aktif.create', compact('akademik'));
    }

    public function store(Request $request, SuratAktifGenerator $generatorService)
    {
        $request->validate([
            'kategori'            => 'required|in:UMUM,PNS,PPPK',
            'semester'            => 'required',
            'akademik_id'         => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'           => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir' => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'             => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'            => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'          => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'              => 'required_if:kategori,PNS,PPPK|nullable',
        ]);

        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;

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
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'semester'            => $request->semester,
            'kategori'            => $request->kategori,
            'nama_ortu'           => $request->nama_ortu,
            'nip'                 => $request->nip,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'pangkat'             => $request->pangkat,
            'golongan'            => $request->golongan,
            'tmt'                 => $request->tmt,
            'unit_kerja'          => $request->unit_kerja,
            'alamat'              => $request->alamat,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan oleh mahasiswa',
            'file_generated'      => null,
        ]);

        try {
            // GENERATE FILE WORD
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            // UPDATE MODEL DENGAN PATH FILE
            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            $surat->delete();
            return back()->with('failed', 'Gagal memproses template dokumen. Silakan coba lagi atau hubungi admin. Error: ' . $e->getMessage());
        }

        HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_aktif,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_aktif',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh mahasiswa',
            'jabatan_id'     => null,
        ]);

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

        $akademik = TahunAkademik::all();

        return view('mahasiswa.surat_aktif.edit', compact('surat', 'akademik'));
    }

    public function update(Request $request, $id, SuratAktifGenerator $generatorService)
    {
        $request->validate([
            'kategori'            => 'required|in:UMUM,PNS,PPPK',
            'semester'            => 'required',
            'akademik_id'         => 'required|exists:tahun_akademik,id_akademik',
            'nama_ortu'           => 'required_if:kategori,PNS,PPPK|nullable',
            'nip'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'pendidikan_terakhir' => 'required_if:kategori,PNS,PPPK|nullable',
            'pangkat'             => 'required_if:kategori,PNS,PPPK|nullable',
            'golongan'            => 'required_if:kategori,PNS,PPPK|nullable',
            'tmt'                 => 'required_if:kategori,PNS,PPPK|nullable',
            'unit_kerja'          => 'required_if:kategori,PNS,PPPK|nullable',
            'alamat'              => 'required_if:kategori,PNS,PPPK|nullable',
        ]);

        $user = Auth::user();

        $surat = SuratAktif::findOrFail($id);

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $user->mahasiswa?->nim)->firstOrFail();

        if (!$surat) {
            return back()->with('failed', 'Data surat tidak ditemukan.');
        }

        $template = Template::findOrFail($surat->template_id);

        // Update data surat
        $surat->update([
            'akademik_id'         => $request->akademik_id,
            'semester'            => $request->semester,
            'alamat'              => $request->alamat,
            'nama_ortu'           => $request->nama_ortu,
            'nip'                 => $request->nip,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'pangkat'             => $request->pangkat,
            'golongan'            => $request->golongan,
            'tmt'                 => $request->tmt,
            'unit_kerja'          => $request->unit_kerja,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan ulang oleh mahasiswa',
        ]);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template);
            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memproses template dokumen setelah update. Error: ' . $e->getMessage());
        }

        // Update history pengajuan
        $pengajuan->update([
            'status'  => 'pengajuan',
            'catatan' => 'Diajukan ulang oleh mahasiswa'
        ]);

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

        $akademik = TahunAkademik::all();

        return view('mahasiswa.surat_aktif.show', compact('surat', 'akademik'));
    }
}
