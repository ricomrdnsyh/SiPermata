<?php

namespace App\Http\Controllers\BAK;

use App\Models\Template;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SuratRekomendasiGenerator;

class BAKSuratRekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bak.surat_rekomendasi.index');
    }

    public function getSuratRekomendasi()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        // Ambil fakultas_id dari data penduduk BAK
        $fakultasId = $user->penduduk?->fakultas_id;

        $query = SuratRekomendasi::whereHas('mahasiswa', function ($q) use ($fakultasId) {
            $q->where('fakultas_id', $fakultasId);
        });

        $query = $query->with('mahasiswa');

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
                    'selesai'   => '<span class="badge bg-primary">Selesai</span>',
                    'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
                    default     => '<span class="badge bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('bak.surat-rekomendasi.show', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('bak.surat-rekomendasi.edit', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
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

        $akademik = TahunAkademik::all();
        $mahasiswa = Mahasiswa::where('fakultas_id', $fakultasId)->select('nim', 'nama')->orderBy('nama', 'asc')->get();

        return view('bak.surat_rekomendasi.create', compact('akademik', 'mahasiswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SuratRekomendasiGenerator $generatorService)
    {
        $userBak = Auth::user();

        if ($userBak->role !== 'BAK') {
            abort(403, 'Akses Ditolak.');
        }

        // Tentukan ID Fakultas BAK yang login
        $fakultasIdBak = $userBak->penduduk?->fakultas_id;

        if (!$fakultasIdBak) {
            return back()->with('failed', 'Data BAK tidak terhubung ke fakultas manapun.');
        }

        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'keperluan'        => 'required',
            'penyelenggara'    => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if ($mahasiswa->fakultas_id != $fakultasIdBak) {
            return back()->with('failed', 'Mahasiswa tersebut bukan bagian dari fakultas Anda.');
        }

        $namaTemplate = 'surat_rekomendasi';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasIdBak)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk {$namaTemplate} belum tersedia untuk fakultas Anda.");
        }

        // Generate nomor surat
        $noSurat = SuratRekomendasi::getNextNoSurat($template->id_template);

        $surat = SuratRekomendasi::create([
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'keperluan'           => $request->keperluan,
            'penyelenggara'       => $request->penyelenggara,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan BAK untuk mahasiswa',
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
            'id_tabel_surat' => $surat->id_surat_rekomendasi,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_rekomendasi',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh mahasiswa',
            'jabatan_id'     => null,
        ]);

        return redirect()->route('bak.surat-rekomendasi.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
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

        $surat = SuratRekomendasi::with('mahasiswa')
            ->where('id_surat_rekomendasi', $id)
            ->firstOrFail();

        return view('bak.surat_rekomendasi.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
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

        $surat = SuratRekomendasi::with('mahasiswa')
            ->where('id_surat_rekomendasi', $id)
            ->firstOrFail();

        $akademik = TahunAkademik::all();
        $mahasiswa = Mahasiswa::where('fakultas_id', $fakultasId)->select('nim', 'nama')->orderBy('nama', 'asc')->get();

        return view('bak.surat_rekomendasi.edit', compact('surat', 'akademik', 'mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, SuratRekomendasiGenerator $generatorService)
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
            'keperluan'        => 'required',
            'penyelenggara'    => 'required',
        ]);

        $surat = SuratRekomendasi::findOrFail($id);

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $request->nim)->firstOrFail();

        $surat->update([
            'nim'              => $request->nim,
            'akademik_id'      => $request->akademik_id,
            'keperluan'        => $request->keperluan,
            'penyelenggara'    => $request->penyelenggara,
            'status'           => 'pengajuan',
            'catatan'          => 'Diajukan ulang oleh BAK untuk mahasiswa',
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

            return redirect()->route('bak.surat-rekomendasi.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
