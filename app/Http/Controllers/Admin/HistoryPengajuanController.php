<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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

        $surat = $pengajuan->surat;
        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan.');
        }

        return view('admin.history.show', compact('pengajuan', 'surat'));
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

    public function handleApprovalAction(Request $request, $id)
    {
        $user = Auth::user();

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'stage' => 'required|in:bak,dekan',
            'catatan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data input tidak valid.'], 422);
        }

        $action = $request->input('action');
        $stage = $request->input('stage');
        $catatan = $request->input('catatan');

        if ($action === 'reject' && empty($catatan)) {
            return response()->json(['success' => false, 'message' => 'Catatan penolakan wajib diisi.'], 400);
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        $allowedStages = [
            'bak'   => 'pengajuan',
            'dekan' => 'proses'
        ];

        if ($pengajuan->status !== $allowedStages[$stage]) {
            return response()->json([
                'success' => false,
                'message' => "Pengajuan tidak dapat diproses. Status saat ini tidak sesuai dengan tahap $stage."
            ], 403);
        }

        $isSuratAktif = ($pengajuan->tabel === 'surat_aktif');
        $suratAktif = $isSuratAktif ? $pengajuan->suratAktif : null;

        DB::beginTransaction();
        try {
            $newStatus = '';
            $message = '';
            $fullCatatan = '';

            if ($action === 'approve') {
                if ($stage === 'bak') {
                    $newStatus = 'proses';
                    $message = 'Pengajuan disetujui BAK dan dilanjutkan ke Dekan.';
                    $fullCatatan = 'Disetujui oleh BAK';
                } elseif ($stage === 'dekan') {
                    $newStatus = 'diterima';
                    $message = 'Pengajuan disetujui Dekan. Surat selesai diproses.';
                    $fullCatatan = 'Disetujui oleh Dekan';
                }
            } elseif ($action === 'reject') {
                $newStatus = 'ditolak';
                $fullCatatan = "Ditolak oleh $stage: " . $catatan;
                $message = "Pengajuan ditolak pada tahap $stage.";
            }

            $pengajuan->update([
                'status' => $newStatus,
                'catatan' => $fullCatatan,
                'jabatan_id' => $user->penduduk->jabatan->id_jabatan ?? null,
            ]);

            if ($isSuratAktif && $suratAktif) {
                $suratAktif->update([
                    'status' => $newStatus,
                    'catatan' => $fullCatatan,
                ]);
            }

            // NOTE: Jika ada model surat lain (SuratLulus, dll.), tambahkan logic di sini:
            // elseif ($pengajuan->tabel === 'surat_lulus') { ... }


            DB::commit();

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server saat memproses data: ' . $e->getMessage()], 500);
        }
    }
}
