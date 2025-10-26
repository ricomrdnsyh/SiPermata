<?php

namespace App\Http\Controllers\Dekan;

use App\Models\Mahasiswa;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\SuratAktifDiterima;
use App\Models\HistoryPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\SuratAktifGenerator;
use Yajra\DataTables\Facades\DataTables;

class DekanHistoryPengajuanController extends Controller
{
    public function index()
    {
        return view('dekan.history.index');
    }

    public function historyData()
    {
        $user = Auth::user();

        // Pastikan user adalah DEKAN
        if ($user->role !== 'DEKAN') {
            abort(403);
        }

        // Ambil fakultas_id dari data penduduk BAK
        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            return DataTables::of(HistoryPengajuan::whereRaw('1=0'))->make(true);
        }

        // Ambil semua pengajuan di fakultas ini yang statusnya 'pengajuan'
        $query = HistoryPengajuan::with([])
            ->where('fakultas_id', $fakultasId)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->addColumn('nama_mahasiswa', function ($row) {
                $mahasiswa = Mahasiswa::where('nim', $row->nim)->first();
                return $mahasiswa?->nama ?? $row->nim;
            })
            ->addColumn('prodi', function ($row) {
                $mahasiswa = Mahasiswa::where('nim', $row->nim)->first();
                return $mahasiswa?->prodi?->nama_prodi ?? $row->nim;
            })
            ->addColumn('nama_surat', function ($row) {
                return $row->nama_surat;
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                return Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM YYYY') ?? '—';
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
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('dekan.history.detail', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . '</div>';
            })
            ->rawColumns(['catatan', 'status', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            abort(403);
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        // Pastikan ini surat di fakultas BAK yang login
        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            abort(403, 'Surat ini bukan milik fakultas Anda.');
        }

        $surat = $pengajuan->surat;
        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan.');
        }

        return view('dekan.history.detail', compact('pengajuan', 'surat'));
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        try {
            $pengajuan = HistoryPengajuan::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // SOLUSI: Tangkap kegagalan findOrFail dan kembalikan JSON 404
            return response()->json(['error' => 'Pengajuan tidak ditemukan.'], 404);
        }

        // ... (Logika validasi fakultas dan surat) ...
        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            return response()->json(['error' => 'Surat ini bukan milik fakultas Anda.'], 403);
        }

        $surat = SuratAktif::find($pengajuan->id_tabel_surat);
        if (!$surat) {
            return response()->json(['error' => 'Data surat terkait tidak ditemukan.'], 404);
        }

        try {
            DB::beginTransaction();

            // 3. Update Status Surat
            // Ubah status ke 'disetujui' (atau 'selesai' jika itu final status Anda)
            $surat->update([
                'status' => 'diterima',
            ]);

            // 4. Update Status History
            $pengajuan->update([
                'status' => 'disetujui',
                'catatan' => 'Disetujui oleh Dekan',
                'jabatan_id' => $user->penduduk->jabatan_id ?? null,
            ]);

            // 5. Kirim email dengan melampirkan file yang sudah ada
            $this->sendEmail($surat, $pengajuan);

            DB::commit();

            return response()->json(['success' => 'Surat berhasil disetujui dan dikirim ke email mahasiswa!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat ACC surat oleh Dekan ID ' . $surat->id_surat_aktif . ': ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses persetujuan atau mengirim email. Detail: ' . $e->getMessage()], 500);
        }
    }

    // Metode sendEmail tetap sama
    private function sendEmail(SuratAktif $surat, HistoryPengajuan $pengajuan)
    {
        // Asumsi: SuratAktif memiliki relasi ke Mahasiswa, dan Mahasiswa memiliki relasi ke User (dengan email)
        $mahasiswa = $surat->mahasiswa;
        $mahasiswaEmail = $mahasiswa?->email;

        if ($mahasiswaEmail) {
            // Pastikan SuratAktifDiterima (Mail Class) dapat melampirkan file yang ada di $surat->file_generated
            Mail::to($mahasiswaEmail)->send(new SuratAktifDiterima($surat));
        } else {
            Log::warning('Email mahasiswa tidak ditemukan untuk NIM: ' . $pengajuan->nim);
        }
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $request->validate([
            'catatan' => 'required'
        ]);

        $pengajuan = HistoryPengajuan::findOrFail($id);

        $suratAktif = $pengajuan->suratAktif;

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        if ($pengajuan->status !== 'proses') {
            return redirect()->back()->with('failed', 'Surat ini sudah diterima.');
        }

        $pengajuan->update([
            'status'     => 'ditolak',
            'catatan'    => 'Ditolak oleh Dekan: ' . $request->catatan,
            'jabatan_id' => $user->penduduk->jabatan->id_jabatan
        ]);

        $suratAktif->update([
            'status'     => 'ditolak',
            'catatan'    => 'Ditolak oleh Dekan: ' . $request->catatan,
        ]);

        return response()->json(['success' => true, 'message' => 'Pengajuan berhasil ditolak!']);
    }
}
