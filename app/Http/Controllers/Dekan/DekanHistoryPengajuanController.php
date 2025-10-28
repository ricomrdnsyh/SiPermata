<?php

namespace App\Http\Controllers\Dekan;

use App\Models\TtdSurat;
use App\Models\Mahasiswa;
use App\Mail\SuratSelesai;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\SuratAktifGenerator;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

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
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'selesai', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
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
                    'selesai'   => '<span class="badge bg-primary">Selesai</span>',
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

    private function getModelClass($tableName)
    {
        switch ($tableName) {
            case 'surat_aktif':
                return SuratAktif::class;
                // case 'surat_lulus':
                //     return SuratLulus::class;
                // 💡 TAMBAHKAN KASUS UNTUK JENIS SURAT LAINNYA
                // case 'surat_cuti':
                //     return SuratCuti::class;
            default:
                return null;
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
            abort(403);
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            abort(403, 'Surat ini bukan milik fakultas Anda.');
        }

        $surat = null;
        $fileGeneratedPath = null;

        $modelClass = $this->getModelClass($pengajuan->tabel);

        if ($modelClass) {
            $surat = $modelClass::find($pengajuan->id_tabel_surat);

            if ($surat) {
                $fileGeneratedPath = $surat->file_generated ?? null;
            }
        }

        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan di tabel sumber.');
        }

        return view('dekan.history.detail', compact('pengajuan', 'surat', 'fileGeneratedPath'));
    }

    public function approve($id, SuratAktifGenerator $generatorService)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        $suratAktif = $pengajuan->suratAktif;

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        // Tambahkan Pengecekan Ketersediaan File
        if (empty($suratAktif->file_generated)) {
            return response()->json(['success' => false, 'message' => 'File surat belum tersedia untuk ditandatangani.'], 400);
        }

        // Dapatkan ID Fakultas dan ID Template
        $fakultasId = $pengajuan->fakultas_id; // Diambil dari HistoryPengajuan
        $templateId = $suratAktif->template_id; // Diambil dari SuratAktif

        // 1. Ambil data TTD dari tabel ttd_surat
        // Cari TTD yang aktif, sesuai fakultas, dan sesuai template.
        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        if (!$ttdDekan) {
            return response()->json(['success' => false, 'message' => 'Data penanda tangan (TTD Dekan) untuk fakultas dan template ini tidak ditemukan atau tidak aktif.'], 404);
        }

        // Data yang akan digunakan
        $namaDekan = $ttdDekan->nama_ttd;
        // Asumsi jabatan diambil dari data user Dekan saat ini (Auth::user()->penduduk->jabatan->nama_jabatan)
        // atau jika Anda menyimpan jabatan di tabel ttd_surat, gunakan kolom tersebut (misal: $ttdDekan->jabatan)
        $jabatanDekan = $user->penduduk->jabatan->nama_jabatan ?? 'Dekan'; // Gunakan data jabatan dari user yang approve

        try {
            DB::beginTransaction();

            // 2. Panggil service untuk menyisipkan TTD QR ke file Word yang sudah ada
            // Menggunakan $namaDekan dari tabel ttd_surat
            $generatedFilePath = $generatorService->insertSignatureWithQR(
                $suratAktif,
                $jabatanDekan, // Jabatan tetap diambil dari user yang login (dekan)
                $namaDekan
            );

            // 3. Update Status SuratAktif
            $suratAktif->update([
                'status' => 'diterima',
                'catatan' => "Disetujui oleh Dekan: {$namaDekan}",
                'file_generated' => $generatedFilePath,
            ]);

            // 4. Update History Pengajuan
            $pengajuan->update([
                'status' => 'diterima',
                'catatan' => 'Disetujui oleh Dekan: ' . $namaDekan,
                'jabatan_id' => $user->penduduk->jabatan->id_jabatan,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil disetujui dan TTD QR berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menambahkan TTD QR pada surat aktif {$suratAktif->id_surat_aktif}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses TTD QR. Error: ' . $e->getMessage()
            ], 500);
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

    public function viewGeneratedFile(string $tabel, int $id): Response
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            abort(403);
        }

        $modelClass = $this->getModelClass($tabel);

        if (!$modelClass) {
            abort(404, 'Jenis surat tidak valid.');
        }

        $surat = $modelClass::find($id);

        if (!$surat || empty($surat->file_generated)) {
            abort(404, 'File surat tidak ditemukan atau belum disetujui/digenerate.');
        }

        if ($surat->mahasiswa->fakultas_id !== $user->penduduk?->fakultas_id) {
            abort(403, 'Anda tidak berhak melihat surat ini.');
        }

        $filePath = $surat->file_generated;
        $disk = 'local';

        // 3. Cek keberadaan file
        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $fileName = 'Surat_' . ucfirst(str_replace('_', ' ', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.docx';

        // 5. Unduh file
        return Storage::download($filePath, $fileName);
    }

    public function sendEmailMahasiswa(Request $request, string $tabel, int $id)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $modelClass = $this->getModelClass($tabel);
        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Jenis surat tidak valid.'], 404);
        }

        // 1. Ambil data Surat dan Mahasiswa
        $surat = $modelClass::find($id);

        // Asumsi model surat memiliki relasi/kolom nim
        $mahasiswa = Mahasiswa::where('nim', $surat->nim)->first();

        if (!$surat || empty($surat->file_generated) || !$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Surat atau data mahasiswa tidak valid.'], 404);
        }

        $filePath = $surat->file_generated;
        $disk = 'local';

        if (!Storage::disk($disk)->exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File surat tidak ditemukan di server.'], 404);
        }

        // 2. Siapkan File Name

        $pengajuanHistory = HistoryPengajuan::where('tabel', $tabel)
            ->where('id_tabel_surat', $id)
            ->first();

        $namaSurat = $pengajuanHistory->nama_surat;

        $fileName = ucfirst(str_replace('_', ' ', $tabel)) . '_' . $surat->nim . '.docx';
        try {
            // Mulai Transaksi Database
            DB::beginTransaction();

            // 3. Kirim Email
            Mail::to($mahasiswa->email)->send(new SuratSelesai($mahasiswa, $surat, $filePath, $fileName, $namaSurat));

            // --- 4. LOGIKA PENTING: UPDATE STATUS DI DATABASE ---

            // A. Update Status di tabel Surat ($tabel)
            $surat->status = 'selesai';
            $surat->catatan = 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.';
            $surat->save();

            if ($pengajuanHistory) {
                $pengajuanHistory->update([
                    'status' => 'selesai',
                    'catatan' => 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.',
                    'updated_at' => now(),
                ]);
            }

            // Commit Transaksi jika pengiriman email dan update status berhasil
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Surat berhasil dikirim ke email mahasiswa!']);
        } catch (\Exception $e) {
            // Rollback Transaksi jika terjadi kegagalan (email gagal terkirim atau update DB gagal)
            DB::rollBack();

            // Log the error
            Log::error('Gagal mengirim email surat: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email atau memperbarui status. Silakan cek log server.'], 500);
        }
    }
}
