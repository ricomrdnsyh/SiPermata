<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\NotifikasiBAKService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SuratRekomendasiGenerator;

class MahasiswaSuratRekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mahasiswa.surat_rekomendasi.index');
    }

    public function getSuratRekomendasi()
    {
        $user = Auth::user();
        $nim = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = SuratRekomendasi::with([])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—';
            })
            ->addColumn('akademik', function ($row) {
                return $row?->akademik?->tahun_akademik ?? "-";
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
                $showBtn = '<a href="' . route('mahasiswa.surat-rekomendasi.show', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-rekomendasi.edit', $row->id_surat_rekomendasi) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';
                }

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['status', 'akademik', 'catatan', 'action'])
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

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        return view('mahasiswa.surat_rekomendasi.create', compact('latestAkademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SuratRekomendasiGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'keperluan'        => 'required',
            'penyelenggara'    => 'required',
            'tgl_pelaksanaan'  => 'required',
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

        $namaTemplate = 'surat_rekomendasi';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template surat ini belum tersedia untuk fakultas Anda.");
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
            'tgl_pelaksanaan'     => $request->tgl_pelaksanaan,
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

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_rekomendasi,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_rekomendasi',
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

        $namaSurat = "Surat Rekomendasi";

        $urlDetail = 'https://sso.unuja.ac.id';

        NotifikasiBAKService::kirimPengajuanBaru(
            $mahasiswa->nim,
            $pengajuan,
            $namaSurat,
            $urlDetail
        );

        return redirect()->route('mahasiswa.surat-rekomendasi.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
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
        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        return view('mahasiswa.surat_rekomendasi.show', compact('surat'));
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
        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        if ($surat->status !== 'ditolak') {
            return redirect()->route('mahasiswa.surat-rekomendasi.index')->with('failed', 'Hanya pengajuan yang ditolak yang dapat diedit.');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();

        return view('mahasiswa.surat_rekomendasi.edit', compact('surat', 'latestAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, SuratRekomendasiGenerator $generatorService)
    {
        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'keperluan'        => 'required',
            'penyelenggara'    => 'required',
            'tgl_pelaksanaan'  => 'required',
        ]);

        $user = Auth::user();

        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $user->mahasiswa?->nim)->firstOrFail();

        $surat->update([
            'akademik_id'     => $request->akademik_id,
            'keperluan'       => $request->keperluan,
            'penyelenggara'   => $request->penyelenggara,
            'tgl_pelaksanaan' => $request->tgl_pelaksanaan,
            'status'          => 'pengajuan',
            'catatan'         => 'Diajukan ulang oleh mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            $surat->update(['file_generated' => $generatedFilePath]);

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

            return redirect()->route('mahasiswa.surat-rekomendasi.index')
                ->with('success', 'Pengajuan surat berhasil diperbarui! Silakan tunggu proses persetujuan.');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }
}
