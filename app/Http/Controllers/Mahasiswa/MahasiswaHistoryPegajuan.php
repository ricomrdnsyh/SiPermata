<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\HistoryPengajuan;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratPKL;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaHistoryPegajuan extends Controller
{
    public function index()
    {
        return view('mahasiswa.history.index');
    }

    public function getHistory()
    {
        $user = Auth::user();
        $nim = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = HistoryPengajuan::with(['mahasiswa', 'mahasiswa.prodi'])->where('nim', $nim)
            ->whereIn('status', ['selesai']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
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
            })
            ->addColumn('nama_surat', function ($row) {
                return $row->nama_surat;
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                $date = \Carbon\Carbon::parse($row->tanggal_pengajuan_asli)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'selesai'  => '<span class="badge text-white bg-primary">Selesai</span>',
                    default    => '<span class="badge text-white bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('mahasiswa.history.detail', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . '</div>';
            })
            ->rawColumns(['status', 'action', 'tanggal_pengajuan'])
            ->make(true);
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }

        $pengajuan = HistoryPengajuan::with('statusLogs')->findOrFail($id);

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

        $jumlahPengajuan = $pengajuan->statusLogs->where('status', 'pengajuan')->count();
        $jumlahDitolak   = $pengajuan->statusLogs->where('status', 'ditolak')->count();
        $jumlahDiterima  = $pengajuan->statusLogs->where('status', 'diterima')->count();

        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        return view('mahasiswa.history.detail', compact(
            'pengajuan',
            'surat',
            'fileGeneratedPath',
            'jumlahPengajuan',
            'jumlahDitolak',
            'jumlahDiterima',
            'dataSimpt'
        ));
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

    public function viewGeneratedFile(string $tabel, int $id): Response
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
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

        
        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $fileName = strtoupper(str_replace(' ', '_', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.pdf';

        return Storage::download($filePath, $fileName);
    }

    private function getDataSimpt(?string $nim): ?object
    {
        if (!$nim) return null;

        try {
            return DB::selectOne('
                SELECT
                    b.id_smt,
                    b.ipk_ketuntasan,
                    (
                        (LEFT(b.id_smt, 4) - LEFT(a.mulai_smt, 4)) * 2
                        + (RIGHT(b.id_smt, 1) - RIGHT(a.mulai_smt, 1))
                        + 1
                        + IF(max_smt.id_smt > b.id_smt, 1, 0)
                    ) AS semester
                FROM dbsimpt.tbmas_mahasiswa_pt a
                LEFT JOIN dbsimpt.tbbak_kuliah_mahasiswa b
                    ON a.id_mahasiswa_pt = b.id_mahasiswa_pt
                    AND b.ipk_ketuntasan IS NOT NULL
                LEFT JOIN (
                    SELECT id_mahasiswa_pt, MAX(id_smt) AS id_smt
                    FROM dbsimpt.tbbak_kuliah_mahasiswa
                    GROUP BY id_mahasiswa_pt
                ) max_smt ON a.id_mahasiswa_pt = max_smt.id_mahasiswa_pt
                WHERE a.nipd = ?
                ORDER BY b.id_smt DESC
                LIMIT 1
            ', [$nim]);
        } catch (Throwable $e) {
            Log::warning("Gagal mengambil data SIMPT untuk NIM: {$nim}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
