<?php

namespace App\Http\Controllers\Dekan;

use App\Models\SuratPKL;
use App\Models\Mahasiswa;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
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
    // Daftar Model Surat
    private $suratModels = [
        'Surat Keterangan Aktif'        => SuratAktif::class,
        'Surat Izin Penelitian'         => SuratPenelitian::class,
        'Surat Permohonan Observasi'    => SuratObservasi::class,
        'Surat Rekomendasi'             => SuratRekomendasi::class,
        'Surat Permohonan PKL'          => SuratPKL::class,
        'Surat Keterangan Lulus'        => SuratLulus::class,
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

        $userFakultasId = $user->penduduk->fakultas_id;

        $userBAK = $user->penduduk;

        if ($user->role !== 'DEKAN' || !$userFakultasId) {
            return redirect()->back()->with('error', 'Akses ditolak atau akun DEKAN belum terasosiasi dengan Fakultas.');
        }

        // Filter Mahasiswa Fakultas
        $validNimList = Mahasiswa::where('fakultas_id', $userFakultasId)->pluck('nim')->toArray();

        // Tentukan ID Akademik yang aktif
        $defaultAkademikId = $this->getDefaultAkademikId();
        $currentAkademikId = $request->input('id_akademik', $defaultAkademikId);

        // Ambil Daftar Tahun Akademik dan Label
        $tahunAkademikList = $this->getTahunAkademikList($validNimList);

        $currentYearLabel = TahunAkademik::where('id_akademik', $currentAkademikId)->first()->tahun_akademik ?? 'N/A';

        // Ambil Data Statistik
        $globalStats = $this->getGlobalStats($currentAkademikId, $validNimList);
        $detailedStatus = $this->getDetailedStatusData($currentAkademikId, $validNimList);
        $chartData = $this->getChartData($currentAkademikId, $validNimList);

        return view('dekan.dashboard.index', [
            'user_name'         => $userBAK->nama_penduduk,
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

    private function getTahunAkademikList($validNimList)
    {
        $usedAkademikIds = collect();
        if (empty($validNimList)) return [];

        foreach ($this->suratModels as $model) {
            $usedAkademikIds = $usedAkademikIds->merge(
                DB::table((new $model)->getTable())
                    ->whereIn('nim', $validNimList)
                    ->pluck('akademik_id')->unique()
            );
        }

        return TahunAkademik::whereIn('id_akademik', $usedAkademikIds->unique()->filter())
            ->orderByDesc('tahun_akademik')
            ->pluck('tahun_akademik', 'id_akademik')
            ->toArray();
    }

    private function getGlobalStats($akademikId, $validNimList)
    {
        $totalMasuk     = 0;
        $totalPengajuan = 0;
        $totalProses    = 0;
        $totalDiterima  = 0;
        $totalSelesai   = 0;
        $totalDitolak   = 0;

        if (empty($validNimList)) return compact('totalMasuk', 'totalPengajuan', 'totalProses', 'totalDiterima', 'totalSelesai', 'totalDitolak');

        foreach ($this->suratModels as $model) {
            $baseQuery = $model::where('akademik_id', $akademikId)
                ->whereIn('nim', $validNimList);

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

    private function getDetailedStatusData($akademikId, $validNimList)
    {
        $data = [];
        if (empty($validNimList)) return $data;

        foreach ($this->suratModels as $label => $model) {
            $baseQuery = $model::where('akademik_id', $akademikId)
                ->whereIn('nim', $validNimList);

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

    private function getChartData($akademikId, $validNimList)
    {
        $labels = [];
        $dataCounts = [];

        if (empty($validNimList)) return ['labels' => [], 'data' => []];

        foreach ($this->suratModels as $label => $model) {
            $count = $model::where('akademik_id', $akademikId)
                ->whereIn('nim', $validNimList)
                ->count();
            $labels[] = $label;
            $dataCounts[] = $count;
        }

        return [
            'labels' => $labels,
            'data'   => $dataCounts,
        ];
    }
}
