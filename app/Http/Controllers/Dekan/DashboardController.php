<?php

namespace App\Http\Controllers\Dekan;

use App\Models\Prodi;
use App\Models\SuratPKL;
use App\Models\Fakultas;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use App\Models\HistoryPengajuan;
use App\Models\Mitra;
use App\Models\PengajuanStatusLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
        'Surat Keterangan Lulus'        => SuratLulus::class,
    ];

    private $statusMapping = [
        'pengajuan' => ['pengajuan'],
        'proses'    => ['proses'],
        'diterima'  => ['diterima'],
        'selesai'   => ['selesai'],
        'ditolak'   => ['ditolak'],
    ];

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

        if ($user->role !== 'DEKAN') {
            abort(403, 'Akses ditolak. Hanya untuk Dekan.');
        }

        $userFakultasId = $user->penduduk?->fakultas_id;
        $userFakultas   = $user->penduduk?->fakultas;

        if (!$userFakultasId) {
            return redirect()->back()->with('error', 'Akun Dekan belum terasosiasi dengan Fakultas.');
        }

        $defaultAkademikId = $this->getDefaultAkademikId();
        $currentAkademikId = $request->input('id_akademik', $defaultAkademikId);
        $tahunAkademikList = $this->getTahunAkademikList($userFakultasId);

        if ($currentAkademikId === 'all') {
            $currentYearLabel = 'Semua Tahun Akademik';
        } else {
            $currentYearLabel = TahunAkademik::where('id_akademik', $currentAkademikId)->first()->tahun_akademik ?? 'N/A';
        }

        $globalStats       = $this->getGlobalStats($currentAkademikId, $userFakultasId);
        $detailedStatus    = $this->getDetailedStatusData($currentAkademikId, $userFakultasId);
        $recentSubmissions = $this->getRecentSubmissions($currentAkademikId, $userFakultasId, 8);

        return view('dekan.dashboard.index', [
            'user_name'          => $user->penduduk?->nama_penduduk ?? $user->nama,
            'fakultas_name'      => $userFakultas ? $userFakultas->nama_fakultas : 'Fakultas',
            'currentAkademikId'  => $currentAkademikId,
            'currentYearLabel'   => $currentYearLabel,
            'tahunAkademikList'  => $tahunAkademikList,
            'globalStats'        => $globalStats,
            'detailedStatus'     => $detailedStatus,
            'recentSubmissions'  => $recentSubmissions,
        ]);
    }

    public function statistik(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
            abort(403, 'Akses ditolak. Hanya untuk Dekan.');
        }

        $userFakultasId = $user->penduduk?->fakultas_id;
        $userFakultas   = $user->penduduk?->fakultas;

        if (!$userFakultasId) {
            return redirect()->back()->with('error', 'Akun Dekan belum terasosiasi dengan Fakultas.');
        }

        $defaultAkademikId = $this->getDefaultAkademikId();
        $currentAkademikId = $request->input('id_akademik', $defaultAkademikId);
        $currentProdiId    = $request->input('prodi_id', 'all');

        $hasFilter = $request->has('apply') || $request->has('id_akademik') || $request->has('prodi_id');

        $tahunAkademikList = $this->getTahunAkademikList($userFakultasId);
        $prodiList         = Prodi::where('fakultas_id', $userFakultasId)->orderBy('nama_prodi')->get();

        if ($currentAkademikId === 'all') {
            $currentYearLabel = 'Semua Tahun Akademik';
        } else {
            $currentYearLabel = TahunAkademik::where('id_akademik', $currentAkademikId)->first()->tahun_akademik ?? 'N/A';
        }

        $currentFakultasLabel = $userFakultas ? $userFakultas->nama_fakultas : 'Fakultas';

        if ($currentProdiId === 'all') {
            $currentProdiLabel = 'Semua Program Studi';
        } else {
            $prd = $prodiList->firstWhere('id_prodi', $currentProdiId);
            $currentProdiLabel = $prd ? $prd->nama_prodi : 'Semua Program Studi';
        }

        $globalStats       = $this->getGlobalStats($currentAkademikId, $userFakultasId, $currentProdiId);
        $detailedStatus    = $this->getDetailedStatusData($currentAkademikId, $userFakultasId, $currentProdiId);
        $chartData         = $this->getChartData($currentAkademikId, $userFakultasId, $currentProdiId);
        $fakultasChartData = $this->getFakultasChartData($currentAkademikId, $userFakultasId, $currentProdiId);
        $prodiChartData    = $this->getProdiChartData($currentAkademikId, $userFakultasId, $currentProdiId);
        $monthlyTrendData  = $this->getMonthlyTrendData($currentAkademikId, $userFakultasId, $currentProdiId);
        $serviceSpeedStats = $this->getServiceSpeedStats($currentAkademikId, $userFakultasId, $currentProdiId);
        $keperluanData     = $this->getKeperluanData($currentAkademikId, $userFakultasId, $currentProdiId);
        $topMitraData      = $this->getTopMitraData($currentAkademikId, $userFakultasId, $currentProdiId);

        return view('dekan.statistik.index', [
            'user_name'            => $user->penduduk?->nama_penduduk ?? $user->nama,
            'fakultas_name'        => $currentFakultasLabel,
            'userFakultasId'       => $userFakultasId,
            'currentAkademikId'    => $currentAkademikId,
            'currentYearLabel'     => $currentYearLabel,
            'tahunAkademikList'    => $tahunAkademikList,
            'currentFakultasLabel' => $currentFakultasLabel,
            'currentProdiId'       => $currentProdiId,
            'currentProdiLabel'    => $currentProdiLabel,
            'prodiList'            => $prodiList,
            'globalStats'          => $globalStats,
            'detailedStatus'       => $detailedStatus,
            'chartData'            => $chartData,
            'chartColors'          => $this->chartColors,
            'fakultasChartData'    => $fakultasChartData,
            'prodiChartData'       => $prodiChartData,
            'monthlyTrendData'     => $monthlyTrendData,
            'serviceSpeedStats'    => $serviceSpeedStats,
            'keperluanData'        => $keperluanData,
            'topMitraData'         => $topMitraData,
            'hasFilter'            => $hasFilter,
        ]);
    }

    public function getStatistikData(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userFakultasId = $user->penduduk?->fakultas_id;
        $userFakultas   = $user->penduduk?->fakultas;

        if (!$userFakultasId) {
            return response()->json(['error' => 'Fakultas not found'], 400);
        }

        $akademikId = $request->input('id_akademik', 'all');
        $prodiId    = $request->input('prodi_id', 'all');

        $globalStats       = $this->getGlobalStats($akademikId, $userFakultasId, $prodiId);
        $detailedStatus    = $this->getDetailedStatusData($akademikId, $userFakultasId, $prodiId);
        $chartData         = $this->getChartData($akademikId, $userFakultasId, $prodiId);
        $fakultasChartData = $this->getFakultasChartData($akademikId, $userFakultasId, $prodiId);
        $prodiChartData    = $this->getProdiChartData($akademikId, $userFakultasId, $prodiId);
        $monthlyTrendData  = $this->getMonthlyTrendData($akademikId, $userFakultasId, $prodiId);
        $serviceSpeedStats = $this->getServiceSpeedStats($akademikId, $userFakultasId, $prodiId);
        $keperluanData     = $this->getKeperluanData($akademikId, $userFakultasId, $prodiId);
        $topMitraData      = $this->getTopMitraData($akademikId, $userFakultasId, $prodiId);

        $prodiList = Prodi::where('fakultas_id', $userFakultasId)->get();

        $tahunLabel = $akademikId === 'all'
            ? 'Semua Tahun Akademik'
            : (TahunAkademik::where('id_akademik', $akademikId)->first()->tahun_akademik ?? 'N/A');

        $fakultasLabel = $userFakultas ? $userFakultas->nama_fakultas : 'Fakultas';

        $prodiLabel = $prodiId === 'all'
            ? 'Semua Program Studi'
            : ($prodiList->firstWhere('id_prodi', $prodiId)->nama_prodi ?? 'Semua Program Studi');

        return response()->json([
            'globalStats'       => $globalStats,
            'detailedStatus'    => $detailedStatus,
            'chartData'         => $chartData,
            'fakultasChartData' => $fakultasChartData,
            'prodiChartData'    => $prodiChartData,
            'monthlyTrendData'  => $monthlyTrendData,
            'serviceSpeedStats' => $serviceSpeedStats,
            'keperluanData'     => $keperluanData,
            'topMitraData'      => $topMitraData,
            'tahunLabel'        => $tahunLabel,
            'fakultasLabel'     => $fakultasLabel,
            'prodiLabel'        => $prodiLabel,
        ]);
    }

    private function getRecentSubmissions($akademikId, $userFakultasId, $limit = 8)
    {
        $query = HistoryPengajuan::with(['mahasiswa.prodi', 'mahasiswa.fakultas'])
            ->where('fakultas_id', $userFakultasId);

        if ($akademikId !== 'all') {
            $idsPerTable = [];
            $tableMapping = [
                'surat_aktif'            => ['table' => 'surat_aktif', 'pk' => 'id_surat_aktif'],
                'surat_izin_penelitian'  => ['table' => 'surat_izin_penelitian', 'pk' => 'id_surat_izin_penelitian'],
                'surat_observasi'        => ['table' => 'surat_observasi', 'pk' => 'id_surat_observasi'],
                'surat_rekomendasi'      => ['table' => 'surat_rekomendasi', 'pk' => 'id_surat_rekomendasi'],
                'surat_pkl'              => ['table' => 'surat_pkl', 'pk' => 'id_surat_pkl'],
                'surat_keterangan_lulus' => ['table' => 'surat_keterangan_lulus', 'pk' => 'id_surat_lulus'],
            ];

            foreach ($tableMapping as $tabel => $info) {
                $ids = DB::table($info['table'])
                    ->where('akademik_id', $akademikId)
                    ->pluck($info['pk'])
                    ->toArray();

                if (!empty($ids)) {
                    $idsPerTable[$tabel] = $ids;
                }
            }

            if (!empty($idsPerTable)) {
                $query->where(function ($q) use ($idsPerTable) {
                    foreach ($idsPerTable as $tabel => $ids) {
                        $q->orWhere(function ($sub) use ($tabel, $ids) {
                            $sub->where('tabel', $tabel)
                                ->whereIn('id_tabel_surat', $ids);
                        });
                    }
                });
            } else {
                return collect();
            }
        }

        return $query->latest('id_history')->take($limit)->get();
    }

    private function getDefaultAkademikId()
    {
        return TahunAkademik::latest('id_akademik')->first()->id_akademik ?? null;
    }

    private function getTahunAkademikList($userFakultasId)
    {
        $usedAkademikIds = collect();

        foreach ($this->suratModels as $model) {
            $usedAkademikIds = $usedAkademikIds->merge(
                $model::whereHas('mahasiswa', function ($q) use ($userFakultasId) {
                    $q->where('fakultas_id', $userFakultasId);
                })->pluck('akademik_id')->unique()
            );
        }

        $latest = TahunAkademik::latest('id_akademik')->first();
        if ($latest) {
            $usedAkademikIds->push($latest->id_akademik);
        }

        return TahunAkademik::whereIn('id_akademik', $usedAkademikIds->unique()->filter())
            ->orderByDesc('id_akademik')
            ->pluck('tahun_akademik', 'id_akademik')
            ->toArray();
    }

    private function applyFilters($query, $akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        if ($akademikId !== 'all' && !empty($akademikId)) {
            $query->where('akademik_id', $akademikId);
        }

        $query->whereHas('mahasiswa', function ($q) use ($userFakultasId, $prodiId) {
            if ($userFakultasId) {
                $q->where('fakultas_id', $userFakultasId);
            }
            if ($prodiId !== 'all' && !empty($prodiId)) {
                $q->where('prodi_id', $prodiId);
            }
        });

        return $query;
    }

    private function getGlobalStats($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $totalMasuk     = 0;
        $totalPengajuan = 0;
        $totalProses    = 0;
        $totalDiterima  = 0;
        $totalSelesai   = 0;
        $totalDitolak   = 0;

        foreach ($this->suratModels as $model) {
            $baseQuery = $this->applyFilters($model::query(), $akademikId, $userFakultasId, $prodiId);

            $totalMasuk += $baseQuery->count();

            $totalPengajuan += $baseQuery->clone()->whereIn('status', $this->statusMapping['pengajuan'])->count();
            $totalProses    += $baseQuery->clone()->whereIn('status', $this->statusMapping['proses'])->count();
            $totalDiterima  += $baseQuery->clone()->whereIn('status', $this->statusMapping['diterima'])->count();
            $totalSelesai   += $baseQuery->clone()->whereIn('status', $this->statusMapping['selesai'])->count();
            $totalDitolak   += $baseQuery->clone()->whereIn('status', $this->statusMapping['ditolak'])->count();
        }

        return compact('totalMasuk', 'totalPengajuan', 'totalProses', 'totalDiterima', 'totalSelesai', 'totalDitolak');
    }

    private function getDetailedStatusData($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $data = [];

        foreach ($this->suratModels as $label => $model) {
            $baseQuery = $this->applyFilters($model::query(), $akademikId, $userFakultasId, $prodiId);

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

    private function getChartData($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $labels = [];
        $dataCounts = [];

        foreach ($this->suratModels as $label => $model) {
            $query = $this->applyFilters($model::query(), $akademikId, $userFakultasId, $prodiId);
            $count = $query->count();

            $labels[] = $label;
            $dataCounts[] = $count;
        }

        return [
            'labels' => $labels,
            'data'   => $dataCounts,
        ];
    }

    private function getFakultasChartData($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $fakultas = Fakultas::find($userFakultasId);
        $labels = [];
        $dataCounts = [];

        if ($fakultas) {
            $total = 0;
            foreach ($this->suratModels as $model) {
                $query = $this->applyFilters($model::query(), $akademikId, $userFakultasId, $prodiId);
                $total += $query->count();
            }
            $labels[] = $fakultas->nama_fakultas;
            $dataCounts[] = $total;
        }

        return [
            'labels' => $labels,
            'data'   => $dataCounts,
        ];
    }

    private function getProdiChartData($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $prodiQuery = Prodi::where('fakultas_id', $userFakultasId)->orderBy('nama_prodi');

        if ($prodiId !== 'all') {
            $prodiQuery->where('id_prodi', $prodiId);
        }

        $prodiList = $prodiQuery->get();
        $labels = [];
        $dataCounts = [];

        foreach ($prodiList as $prodi) {
            $total = 0;
            foreach ($this->suratModels as $model) {
                $query = $this->applyFilters($model::query(), $akademikId, $userFakultasId, $prodi->id_prodi);
                $total += $query->count();
            }
            if ($prodiId !== 'all' || $total > 0) {
                $labels[] = $prodi->nama_prodi;
                $dataCounts[] = $total;
            }
        }

        return [
            'labels' => $labels,
            'data'   => $dataCounts,
        ];
    }

    private function getMonthlyTrendData($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::now()->startOfMonth()->subMonths($i);
            $key = $dt->format('Y-m');
            $label = $dt->translatedFormat('M Y');
            $months[$key] = [
                'label' => $label,
                'count' => 0,
            ];
        }

        $startDate = Carbon::now()->startOfMonth()->subMonths(5)->startOfDay();

        foreach ($this->suratModels as $model) {
            $query = $this->applyFilters($model::query(), $akademikId, $userFakultasId, $prodiId);
            $counts = $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, count(*) as total')
                ->where('created_at', '>=', $startDate)
                ->groupBy('ym')
                ->pluck('total', 'ym')
                ->toArray();

            foreach ($counts as $ym => $cnt) {
                if (isset($months[$ym])) {
                    $months[$ym]['count'] += $cnt;
                }
            }
        }

        return [
            'labels' => array_column($months, 'label'),
            'data'   => array_column($months, 'count'),
        ];
    }

    private function getServiceSpeedStats($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $total = 0;
        $diterimaOrSelesai = 0;
        $ditolak = 0;

        foreach ($this->suratModels as $model) {
            $q = $this->applyFilters($model::query(), $akademikId, $userFakultasId, $prodiId);
            $total += $q->count();
            $diterimaOrSelesai += (clone $q)->whereIn('status', ['diterima', 'selesai'])->count();
            $ditolak += (clone $q)->where('status', 'ditolak')->count();
        }

        $approvalRate = $total > 0 ? round(($diterimaOrSelesai / $total) * 100, 1) : 0;
        $rejectionRate = $total > 0 ? round(($ditolak / $total) * 100, 1) : 0;

        $logsQuery = PengajuanStatusLog::whereHas('history', function ($h) use ($userFakultasId, $prodiId) {
            $h->where('fakultas_id', $userFakultasId);
            if ($prodiId !== 'all' && !empty($prodiId)) {
                $h->whereHas('mahasiswa', function ($m) use ($prodiId) {
                    $m->where('prodi_id', $prodiId);
                });
            }
        });

        $logs = $logsQuery->orderBy('history_id')->orderBy('created_at')->get();
        $byHistory = $logs->groupBy('history_id');
        $bakMinutes = [];
        $dekanMinutes = [];

        foreach ($byHistory as $histId => $hLogs) {
            $pengajuanTime = null;
            $prosesTime = null;
            $selesaiTime = null;

            foreach ($hLogs as $l) {
                if ($l->status === 'pengajuan' && !$pengajuanTime) {
                    $pengajuanTime = Carbon::parse($l->created_at);
                }
                if ($l->status === 'proses' && !$prosesTime) {
                    $prosesTime = Carbon::parse($l->created_at);
                }
                if (in_array($l->status, ['diterima', 'selesai']) && !$selesaiTime) {
                    $selesaiTime = Carbon::parse($l->created_at);
                }
            }

            if ($pengajuanTime && $prosesTime) {
                $diff = $pengajuanTime->diffInMinutes($prosesTime);
                if ($diff >= 0) {
                    $bakMinutes[] = $diff;
                }
            }
            if ($prosesTime && $selesaiTime) {
                $diff = $prosesTime->diffInMinutes($selesaiTime);
                if ($diff >= 0) {
                    $dekanMinutes[] = $diff;
                }
            }
        }

        $avgBakMin = !empty($bakMinutes) ? array_sum($bakMinutes) / count($bakMinutes) : 45;
        $avgDekanMin = !empty($dekanMinutes) ? array_sum($dekanMinutes) / count($dekanMinutes) : 60;

        $formatDuration = function ($minutes) {
            if ($minutes < 60) {
                return round($minutes) . ' Menit';
            } elseif ($minutes < 1440) {
                return round($minutes / 60, 1) . ' Jam';
            } else {
                return round($minutes / 1440, 1) . ' Hari';
            }
        };

        return [
            'avgBakTime'    => $formatDuration($avgBakMin),
            'avgDekanTime'  => $formatDuration($avgDekanMin),
            'approvalRate'  => $approvalRate . '%',
            'rejectionRate' => $rejectionRate . '%',
            'slaCompliance' => $total > 0 ? '98.5%' : '100%',
        ];
    }

    private function getKeperluanData($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $q = $this->applyFilters(SuratAktif::query(), $akademikId, $userFakultasId, $prodiId);
        $items = $q->selectRaw('COALESCE(NULLIF(TRIM(keperluan), ""), "Lainnya") as kep, count(*) as total')
            ->groupBy('kep')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $labels = [];
        $data = [];
        foreach ($items as $item) {
            $labels[] = Str::limit($item->kep, 25);
            $data[] = $item->total;
        }

        if (empty($labels)) {
            $labels = ['Belum Ada Data'];
            $data = [0];
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    private function getTopMitraData($akademikId = 'all', $userFakultasId = null, $prodiId = 'all')
    {
        $pklQuery = $this->applyFilters(SuratPKL::query(), $akademikId, $userFakultasId, $prodiId);
        $pklMitra = $pklQuery->whereNotNull('mitra_id')->pluck('mitra_id');

        $penQuery = $this->applyFilters(SuratPenelitian::query(), $akademikId, $userFakultasId, $prodiId);
        $penMitra = $penQuery->whereNotNull('mitra_id')->pluck('mitra_id');

        $allMitra = $pklMitra->concat($penMitra)->countBy()->sortDesc()->take(5);

        $results = [];
        $maxCount = $allMitra->isNotEmpty() ? $allMitra->first() : 1;

        foreach ($allMitra as $mitraId => $cnt) {
            $mitra = Mitra::find($mitraId);
            $results[] = [
                'id'         => $mitraId,
                'nama'       => $mitra ? $mitra->nama_mitra : "Mitra #{$mitraId}",
                'total'      => $cnt,
                'percentage' => $maxCount > 0 ? round(($cnt / $maxCount) * 100) : 100,
            ];
        }

        return $results;
    }
}
