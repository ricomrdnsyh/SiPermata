<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Mitra;
use App\Models\SuratPKL;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Services\SuratPKLGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaSuratPKLController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mahasiswa.surat_pkl.index');
    }

    public function getSuratPKL()
    {
        $user = Auth::user();
        $nim = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = SuratPKL::with([])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
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
                $showBtn = '<a href="' . route('mahasiswa.surat-pkl.show', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-pkl.edit', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';
                }

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['status', 'catatan', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user     = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak');
        }

        $akademik = TahunAkademik::all();
        $mitra    = Mitra::all();
        return view('mahasiswa.surat_pkl.create', compact('akademik', 'mitra'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SuratPKLGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id'         => 'required|exists:mitra,id_mitra',
            'tgl_mulai'        => 'required',
            'tgl_selesai'      => 'required',
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

        $namaTemplate = 'surat_pkl';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template surat ini belum tersedia untuk fakultas Anda.");
        }

        // Generate nomor surat
        $noSurat = SuratPKL::getNextNoSurat($template->id_template);

        $surat = SuratPKL::create([
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'mitra_id'            => $request->mitra_id,
            'tgl_mulai'           => $request->tgl_mulai,
            'tgl_selesai'         => $request->tgl_selesai,
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
            'id_tabel_surat' => $surat->id_surat_pkl,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_pkl',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh mahasiswa',
            'jabatan_id'     => null,
        ]);

        return redirect()->route('mahasiswa.surat-pkl.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratPKL::where('id_surat_pkl', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $akademik = TahunAkademik::all();
        $mitra    = Mitra::all();

        return view('mahasiswa.surat_pkl.show', compact('surat', 'akademik', 'mitra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratPKL::where('id_surat_pkl', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        if ($surat->status !== 'ditolak') {
            return redirect()->route('mahasiswa.surat-pkl.index')->with('failed', 'Hanya pengajuan yang ditolak yang dapat diedit.');
        }

        $akademik = TahunAkademik::all();
        $mitra    = Mitra::all();

        return view('mahasiswa.surat_pkl.edit', compact('surat', 'akademik', 'mitra'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, SuratPKLGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id'         => 'required|exists:mitra,id_mitra',
            'tgl_mulai'        => 'required',
            'tgl_selesai'      => 'required',
        ]);

        $user = Auth::user();

        $surat = SuratPKL::where('id_surat_pkl', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $user->mahasiswa?->nim)->firstOrFail();

        $surat->update([
            'akademik_id'      => $request->akademik_id,
            'mitra_id'         => $request->mitra_id,
            'tgl_mulai'        => $request->tgl_mulai,
            'tgl_selesai'      => $request->tgl_selesai,
            'status'           => 'pengajuan',
            'catatan'          => 'Diajukan ulang oleh mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            $surat->update(['file_generated' => $generatedFilePath]);

            $pengajuan->update([
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh mahasiswa'
            ]);

            return redirect()->route('mahasiswa.surat-pkl.index')
                ->with('success', 'Pengajuan surat berhasil diperbarui! Silakan tunggu proses persetujuan.');
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
