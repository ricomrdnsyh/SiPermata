<?php

namespace App\Http\Controllers\Admin;

use App\Models\SuratPKL;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $suratModels = [
        'Surat Keterangan Aktif'        => SuratAktif::class,
        'Surat Izin Penelitian'         => SuratPenelitian::class,
        'Surat Permohonan Observasi'    => SuratObservasi::class,
        'Surat Rekomendasi'             => SuratRekomendasi::class,
        'Surat Permohonan PKL'          => SuratPKL::class,
    ];

    private $statusMapping = [
        'pengajuan' => ['pengajuan'],
        'proses'    => ['proses'],
        'diterima'  => ['diterima'],
        'selesai'   => ['selesai'],
        'ditolak'   => ['ditolak'],
    ];

    // Warna untuk Chart dan Card
    private $chartColors = [
        'rgba(25, 118, 210, 0.7)',
        'rgba(255, 193, 7, 0.7)',
        'rgba(23, 162, 184, 0.7)',
        'rgba(108, 117, 125, 0.7)',
        'rgba(111, 66, 193, 0.7)',
        'rgba(40, 167, 69, 0.7)',
    ];


    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya untuk Admin.');
        }

        // Tentukan ID Akademik yang aktif
        $defaultAkademikId = $this->getDefaultAkademikId();
        $currentAkademikId = $request->input('id_akademik', $defaultAkademikId);

        // Ambil Daftar Tahun Akademik Global (Semua yang pernah ada pengajuan)
        $tahunAkademikList = $this->getTahunAkademikList();

        $currentYearLabel = TahunAkademik::where('id_akademik', $currentAkademikId)->first()->tahun_akademik ?? 'N/A';

        // Ambil Data Statistik Global (Tanpa filter NIM)
        $globalStats = $this->getGlobalStats($currentAkademikId);
        $detailedStatus = $this->getDetailedStatusData($currentAkademikId);
        $chartData = $this->getChartData($currentAkademikId);

        return view('admin.dashboard.index', [
            'user_name'         => $user->nama,
            'currentAkademikId' => $currentAkademikId,
            'currentYearLabel'  => $currentYearLabel,
            'tahunAkademikList' => $tahunAkademikList,
            'globalStats'       => $globalStats,
            'detailedStatus'    => $detailedStatus,
            'chartData'         => $chartData,
            'chartColors'       => $this->chartColors,
        ]);
    }

    // Helper Functions

    private function getDefaultAkademikId()
    {
        return TahunAkademik::latest('id_akademik')->first()->id_akademik ?? null;
    }

    /**
     * Mengambil daftar Tahun Akademik yang memiliki pengajuan secara GLOBAL
     */
    private function getTahunAkademikList()
    {
        $usedAkademikIds = collect();

        foreach ($this->suratModels as $model) {
            $usedAkademikIds = $usedAkademikIds->merge(
                DB::table((new $model)->getTable())->pluck('akademik_id')->unique()
            );
        }

        return TahunAkademik::whereIn('id_akademik', $usedAkademikIds->unique()->filter())
            ->orderByDesc('tahun_akademik')
            ->pluck('tahun_akademik', 'id_akademik')
            ->toArray();
    }

    /**
     * Mengambil Statistik Global tanpa filter NIM (keseluruhan data)
     */
    private function getGlobalStats($akademikId)
    {
        $totalMasuk     = 0;
        $totalPengajuan = 0;
        $totalProses    = 0;
        $totalDiterima  = 0;
        $totalSelesai   = 0;
        $totalDitolak   = 0;

        foreach ($this->suratModels as $model) {
            // Query tanpa filter NIM
            $baseQuery = $model::where('akademik_id', $akademikId);

            // Menghitung BARIS/RECORD surat.
            $totalMasuk += $baseQuery->count();

            // Menghitung berdasarkan status
            $totalPengajuan += $baseQuery->clone()->whereIn('status', $this->statusMapping['pengajuan'])->count();
            $totalProses    += $baseQuery->clone()->whereIn('status', $this->statusMapping['proses'])->count();
            $totalDiterima  += $baseQuery->clone()->whereIn('status', $this->statusMapping['diterima'])->count();
            $totalSelesai   += $baseQuery->clone()->whereIn('status', $this->statusMapping['selesai'])->count();
            $totalDitolak   += $baseQuery->clone()->whereIn('status', $this->statusMapping['ditolak'])->count();
        }

        return compact('totalMasuk', 'totalPengajuan', 'totalProses', 'totalDiterima', 'totalSelesai', 'totalDitolak');
    }

    /**
     * Mengambil data detail status per jenis surat tanpa filter NIM
     */
    private function getDetailedStatusData($akademikId)
    {
        $data = [];

        foreach ($this->suratModels as $label => $model) {
            // Query tanpa filter NIM
            $baseQuery = $model::where('akademik_id', $akademikId);

            $total = $baseQuery->count();

            $pengajuan  = $baseQuery->clone()->whereIn('status', $this->statusMapping['pengajuan'])->count();
            $proses     = $baseQuery->clone()->whereIn('status', $this->statusMapping['proses'])->count();
            $diterima   = $baseQuery->clone()->whereIn('status', $this->statusMapping['diterima'])->count();
            $selesai    = $baseQuery->clone()->whereIn('status', $this->statusMapping['selesai'])->count();
            $ditolak    = $baseQuery->clone()->whereIn('status', $this->statusMapping['ditolak'])->count();

            $data[] = [
                'label'     => $label,
                'total'     => $total,
                'pengajuan' => $pengajuan,
                'proses'    => $proses,
                'diterima'  => $diterima,
                'selesai'   => $selesai,
                'ditolak'   => $ditolak,
            ];
        }
        return $data;
    }

    /**
     * Mengambil data chart total per jenis surat tanpa filter NIM
     */
    private function getChartData($akademikId)
    {
        $labels = [];
        $dataCounts = [];

        foreach ($this->suratModels as $label => $model) {
            // Query tanpa filter NIM
            $count = $model::where('akademik_id', $akademikId)->count();

            $labels[] = $label;
            $dataCounts[] = $count;
        }

        return [
            'labels' => $labels,
            'data'   => $dataCounts,
        ];
    }
}
