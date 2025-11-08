<?php

namespace App\Http\Controllers\Admin;

use App\Models\SuratPKL;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\SuratObservasi;
use Illuminate\Support\Carbon;
use App\Models\SuratPenelitian;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class HistoryPengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.history.index');
    }

    public function getHistory()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $query = HistoryPengajuan::with(['mahasiswa.prodi']);

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
                $showBtn = '<a href="' . route('admin.history-pengajuan.show', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $deleteBtn = '<a href="javascript:void(0)" onclick="confirmDelete(' . $row->id_history . ')" class="btn btn-sm btn-light btn-active-light-danger text-center" data-bs-toggle="tooltip" 
                data-bs-title="Hapus"><i class="fas fa-trash-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $deleteBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'status', 'action'])
            ->make(true);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

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

        return view('admin.history.show', compact('pengajuan', 'surat', 'fileGeneratedPath'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $history = HistoryPengajuan::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        try {
            DB::beginTransaction();

            $history->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat pengajuan dan surat terkait berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus riwayat. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    protected $suratModels = [
        'surat_aktif'           => SuratAktif::class,
        'surat_izin_penelitian' => SuratPenelitian::class,
        'surat_rekomendasi'     => SuratRekomendasi::class,
        'surat_pkl'             => SuratPKL::class,
        'surat_observasi'       => SuratObservasi::class,
        // Tambahkan jenis surat lain di sini
    ];

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
            default:
                return null;
        }
    }

    public function approve($id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        if ($pengajuan->status !== 'pengajuan') {
            return redirect()->back()->with('failed', 'Surat ini sudah diproses.');
        }


        $jenisTabel   = $pengajuan->tabel; // Ambil nilai 'surat_aktif' atau 'surat_izin_penelitian'
        $idSuratUtama = $pengajuan->id_tabel_surat; // Ambil ID surat utama di tabel yang benar

        // A. Cek ketersediaan mapping
        if (!isset($this->suratModels[$jenisTabel])) {
            return response()->json(['success' => false, 'message' => "Jenis surat '{$jenisTabel}' tidak ditemukan dalam daftar mapping."], 400);
        }

        $ModelSurat = $this->suratModels[$jenisTabel];

        $suratUtama = $ModelSurat::find($idSuratUtama);

        if (!$suratUtama) {
            return response()->json(['success' => false, 'message' => "Data surat utama tidak ditemukan."], 404);
        }

        $pengajuan->update([
            'status'     => 'proses',
            'catatan'    => 'Disetujui oleh BAK'
        ]);

        $suratUtama->update([
            'status'  => 'proses',
            'catatan' => 'Disetujui oleh BAK',
        ]);

        $namaSurat = ucwords(str_replace(['_', 'surat'], [' ', ''], $jenisTabel));

        return response()->json(['success' => true, 'message' => "Pengajuan Surat {$namaSurat} berhasil disetujui!"]);
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $request->validate([
            'catatan' => 'required|string|max:500'
        ]);

        $pengajuan = HistoryPengajuan::findOrFail($id);

        if ($pengajuan->status !== 'pengajuan') {
            return redirect()->back()->with('failed', 'Surat ini sudah diproses.');
        }


        $jenisTabel   = $pengajuan->tabel; // Contoh: 'surat_aktif' atau 'surat_izin_penelitian'
        $idSuratUtama = $pengajuan->id_tabel_surat; // ID surat di tabel utama

        // Cek ketersediaan mapping
        if (!isset($this->suratModels[$jenisTabel])) {
            return response()->json(['success' => false, 'message' => "Jenis surat '{$jenisTabel}' tidak ditemukan dalam daftar mapping."], 400);
        }

        $ModelSurat = $this->suratModels[$jenisTabel];

        $suratUtama = $ModelSurat::find($idSuratUtama);

        if (!$suratUtama) {
            return response()->json(['success' => false, 'message' => "Data surat utama tidak ditemukan."], 404);
        }

        $catatanPenolakan = 'Ditolak oleh BAK: ' . $request->catatan;

        $pengajuan->update([
            'status'  => 'ditolak',
            'catatan' => $catatanPenolakan,
        ]);

        $suratUtama->update([
            'status'  => 'ditolak',
            'catatan' => $catatanPenolakan,
        ]);

        $namaSurat = ucwords(str_replace(['_', 'surat'], [' ', ''], $jenisTabel));

        return response()->json(['success' => true, 'message' => "Pengajuan Surat {$namaSurat} berhasil ditolak!"]);
    }

    public function viewGeneratedFile(string $tabel, int $id): Response
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
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

        $filePath = $surat->file_generated;
        $disk = 'local';

        // Cek keberadaan file
        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $fileName = ucfirst(str_replace('_', ' ', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.docx';

        return Storage::download($filePath, $fileName);
    }
}
