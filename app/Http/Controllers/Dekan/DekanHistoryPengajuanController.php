<?php

namespace App\Http\Controllers\Dekan;

use App\Models\Prodi;
use App\Models\SuratPKL;
use App\Models\TtdSurat;
use App\Models\Mahasiswa;
use App\Mail\SuratSelesai;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use Illuminate\Http\Request;
use App\Models\SuratObservasi;
use Illuminate\Support\Carbon;
use App\Models\SuratPenelitian;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use App\Services\SignatureService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class DekanHistoryPengajuanController extends Controller
{
    protected $listSurat = [
        'surat_aktif' => 'Surat Keterangan Aktif',
        'surat_izin_penelitian' => 'Surat Izin Penelitian',
        'surat_observasi' => 'Surat Permohonan Observasi',
        'surat_rekomendasi' => 'Surat Rekomendasi',
        'surat_pkl' => 'Surat Permohonan PKL',
        'surat_keterangan_lulus' => 'Surat Keterangan Lulus',
    ];

    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
            abort(403);
        }

        $fakultasIdUser = $user->penduduk?->fakultas_id;

        $listProdi = collect();
        if ($fakultasIdUser) {
            $listProdi = Prodi::where('fakultas_id', $fakultasIdUser)->get();
        }

        $listNamaSurat = $this->listSurat;

        return view('dekan.history.index', compact('listProdi', 'listNamaSurat'));
    }

    public function historyData(Request $request)
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

        if ($request->filled('prodi_filter')) {
            $prodiId = $request->input('prodi_filter');
            $query->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        if ($request->filled('nama_surat_filter')) {
            $query->where('tabel', $request->input('nama_surat_filter'));
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
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
            ->filterColumn('nama_surat', function ($query, $keyword) {
                $keyword = strtolower($keyword);

                if (str_contains('aktif', $keyword)) {
                    $query->orWhere('tabel', 'surat_aktif');
                }
                if (str_contains('penelitian', $keyword) || str_contains('izin', $keyword)) {
                    $query->orWhere('tabel', 'surat_izin_penelitian');
                }
                if (str_contains('rekomendasi', $keyword)) {
                    $query->orWhere('tabel', 'surat_rekomendasi');
                }
                if (str_contains('pkl', $keyword)) {
                    $query->orWhere('tabel', 'surat_pkl');
                }
                if (str_contains('observasi', $keyword)) {
                    $query->orWhere('tabel', 'surat_observasi');
                }
                if (str_contains('lulus', $keyword)) {
                    $query->orWhere('tabel', 'surat_keterangan_lulus');
                }
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
            case 'surat_izin_penelitian':
                return SuratPenelitian::class;
            case 'surat_rekomendasi':
                return SuratRekomendasi::class;
            case 'surat_pkl':
                return SuratPKL::class;
            case 'surat_observasi':
                return SuratObservasi::class;
            case 'surat_keterangan_lulus':
                return SuratLulus::class;
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

    public function approve($id, SignatureService $signatureService) // Injeksi SignatureService
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya Dekan yang diizinkan.'], 403);
        }

        $pengajuan = HistoryPengajuan::find($id);

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan.'], 404);
        }

        if ($pengajuan->status !== 'proses') {
            return response()->json(['success' => false, 'message' => 'Surat ini sudah diproses atau ditolak sebelumnya.'], 400);
        }

        if (!isset($user->penduduk->fakultas_id) || $pengajuan->fakultas_id !== $user->penduduk->fakultas_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Fakultas tidak cocok.'], 403);
        }

        // Detail Surat Secara Dinamis
        $modelClass = $this->getModelClass($pengajuan->tabel);

        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Jenis surat tidak valid atau tidak terdaftar.'], 400);
        }

        $detailSurat = $modelClass::find($pengajuan->id_tabel_surat);

        if (!$detailSurat) {
            return response()->json(['success' => false, 'message' => 'Detail surat (tabel ' . $pengajuan->tabel . ') tidak ditemukan.'], 404);
        }

        // Pengecekan Ketersediaan File
        if (empty($detailSurat->file_generated)) {
            return response()->json(['success' => false, 'message' => 'File surat belum tersedia untuk ditandatangani.'], 400);
        }

        // Dapatkan Data TTD
        $fakultasId = $pengajuan->fakultas_id;
        $templateId = $detailSurat->template_id;

        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        if (!$ttdDekan) {
            return response()->json(['success' => false, 'message' => 'Data penanda tangan (TTD Dekan) untuk fakultas dan template ini tidak ditemukan atau tidak aktif.'], 404);
        }

        // Data yang akan digunakan
        $namaDekan    = $ttdDekan->nama_ttd;
        $nidn         = $ttdDekan->nidn;
        $jabatanDekan = $user->penduduk?->jabatan?->nama_jabatan ?? 'Dekan';
        $idJabatan    = $user->penduduk?->jabatan?->id_jabatan ?? null;


        // Proses Tanda Tangan dan Update Database
        try {
            DB::beginTransaction();

            // Panggil SignatureService yang universal
            $generatedFilePath = $signatureService->insertSignatureWithQR(
                $detailSurat,
                $jabatanDekan,
                $namaDekan,
                $nidn
            );

            $detailSurat->update([
                'status'  => 'diterima',
                'catatan' => "Disetujui oleh Dekan: {$namaDekan}",
                'file_generated' => $generatedFilePath,
            ]);

            $pengajuan->update([
                'status'     => 'diterima',
                'catatan'    => 'Disetujui oleh Dekan: ' . $namaDekan,
                'jabatan_id' => $idJabatan,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pengajuan berhasil disetujui dan TTD QR berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menambahkan TTD QR pada pengajuan {$pengajuan->id_history}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses TTD QR. Silakan cek log server untuk detail lebih lanjut.'
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'catatan' => 'required|string|max:500'
        ]);

        // Pengecekan Akses
        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya Dekan yang diizinkan.'], 403);
        }

        // Cari History Pengajuan
        $pengajuan = HistoryPengajuan::find($id);

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan.'], 404);
        }

        // Pengecekan status pengajuan
        if ($pengajuan->status !== 'proses') {
            return response()->json(['success' => false, 'message' => 'Surat ini sudah diproses atau ditolak sebelumnya.'], 400);
        }

        // Pengecekan Fakultas
        if (!isset($user->penduduk->fakultas_id) || $pengajuan->fakultas_id !== $user->penduduk->fakultas_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Fakultas tidak cocok.'], 403);
        }


        // Mengakses Detail Surat Secara Dinamis
        $modelClass = $this->getModelClass($pengajuan->tabel);

        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Jenis surat tidak valid atau tidak terdaftar.'], 400);
        }

        $detailSurat = $modelClass::find($pengajuan->id_tabel_surat);

        if (!$detailSurat) {
            return response()->json(['success' => false, 'message' => 'Detail surat (tabel ' . $pengajuan->tabel . ') tidak ditemukan.'], 404);
        }


        // Proses Penolakan (Transaksi Database)
        try {
            DB::beginTransaction();

            $catatan = 'Ditolak oleh Dekan: ' . $validated['catatan'];
            $idJabatan = $user->penduduk?->jabatan?->id_jabatan ?? null;

            // Update status di tabel detail surat
            $detailSurat->update([
                'status'  => 'ditolak',
                'catatan' => $catatan,
            ]);

            // Update status di tabel HistoryPengajuan
            $pengajuan->update([
                'status'     => 'ditolak',
                'catatan'    => $catatan,
                'jabatan_id' => $idJabatan,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menolak pengajuan (ID History: {$id}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses penolakan. Silakan cek log server.'
            ], 500);
        }
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

        // Cek keberadaan file
        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $fileName = ucfirst(str_replace('_', ' ', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.docx';

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

        // Ambil data Surat dan Mahasiswa
        $surat = $modelClass::find($id);

        $mahasiswa = Mahasiswa::where('nim', $surat->nim)->first();

        if (!$surat || empty($surat->file_generated) || !$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Surat atau data mahasiswa tidak valid.'], 404);
        }

        $filePath = $surat->file_generated;
        $disk = 'local';

        if (!Storage::disk($disk)->exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File surat tidak ditemukan di server.'], 404);
        }


        $pengajuanHistory = HistoryPengajuan::where('tabel', $tabel)
            ->where('id_tabel_surat', $id)
            ->first();

        $namaSurat = $pengajuanHistory->nama_surat;

        $fileName = ucfirst(str_replace('_', ' ', $tabel)) . '_' . $surat->nim . '.docx';
        try {
            DB::beginTransaction();

            Mail::to($mahasiswa->email)->send(new SuratSelesai($mahasiswa, $surat, $filePath, $fileName, $namaSurat));

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

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Surat berhasil dikirim ke email mahasiswa!']);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal mengirim email surat: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email atau memperbarui status. Silakan cek log server.'], 500);
        }
    }
}
