@extends('layout.main')
@section('title', 'Dashboard')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">

                <div class="card card-flush mb-7">
                    <div class="card-header pt-6 pb-4">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center gap-3">
                                <span class="symbol symbol-40px">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="fas fa-chart-line text-primary fs-3"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="fs-3 fw-semibold text-gray-900">
                                        Selamat Datang,
                                        <span class="text-primary fw-bolder">{{ $user_name ?? 'Pengguna' }}</span>
                                    </span>
                                    <span class="text-gray-600 fw-semibold fs-7">
                                        Dashboard pengajuan surat mahasiswa - Tahun Akademik {{ $currentYearLabel }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge badge-light fw-semibold px-4 py-3">
                                    <i class="fas fa-filter me-2 text-gray-600"></i>Tahun Akademik
                                </span>
                                <div class="w-180px">
                                    <select class="form-select form-select-sm" data-control="select2"
                                        data-hide-search="true" data-placeholder="Tahun Akademik" id="filter_akademik_id">
                                        @foreach ($tahunAkademikList as $id => $tahun)
                                            <option value="{{ $id }}"
                                                @if ($id == $currentAkademikId) selected @endif>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 g-xl-6 mb-7">
                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('dekan.history.index') }}"
                            class="card card-flush h-md-100 text-decoration-none hover-elevate-up text-white"
                            style="background: linear-gradient(135deg, #334155 0%, #475569 100%);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-38px">
                                        <span class="symbol-label bg-white bg-opacity-20">
                                            <i class="fas fa-inbox text-white"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-6">
                                    <div class="fw-semibold fs-2x text-white">
                                        {{ number_format($globalStats['totalMasuk']) }}</div>
                                    <div class="fw-semibold text-white text-opacity-75 mt-1">Total Masuk</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('dekan.history.index') }}"
                            class="card card-flush h-md-100 text-decoration-none hover-elevate-up text-white"
                            style="background: linear-gradient(135deg, #f59e0b 0%, #fb923c 100%);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-38px">
                                        <span class="symbol-label bg-white bg-opacity-20">
                                            <i class="fas fa-file-export text-white"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-6">
                                    <div class="fw-semibold fs-2x text-white">
                                        {{ number_format($globalStats['totalPengajuan']) }}</div>
                                    <div class="fw-semibold text-white text-opacity-75 mt-1">Menunggu BAK</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('dekan.history.index') }}"
                            class="card card-flush h-md-100 text-decoration-none hover-elevate-up text-white"
                            style="background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-38px">
                                        <span class="symbol-label bg-white bg-opacity-20">
                                            <i class="fas fa-user-clock text-white"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-6">
                                    <div class="fw-semibold fs-2x text-white">
                                        {{ number_format($globalStats['totalProses']) }}</div>
                                    <div class="fw-semibold text-white text-opacity-75 mt-1">Menunggu Dekan</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('dekan.history.index') }}"
                            class="card card-flush h-md-100 text-decoration-none hover-elevate-up text-white"
                            style="background: linear-gradient(135deg, #22c55e 0%, #4ade80 100%);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-38px">
                                        <span class="symbol-label bg-white bg-opacity-20">
                                            <i class="fas fa-clipboard-check text-white"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-6">
                                    <div class="fw-semibold fs-2x text-white">
                                        {{ number_format($globalStats['totalDiterima']) }}</div>
                                    <div class="fw-semibold text-white text-opacity-75 mt-1">Disetujui</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('dekan.history.index') }}"
                            class="card card-flush h-md-100 text-decoration-none hover-elevate-up text-white"
                            style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-38px">
                                        <span class="symbol-label bg-white bg-opacity-20">
                                            <i class="fas fa-download text-white"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-6">
                                    <div class="fw-semibold fs-2x text-white">
                                        {{ number_format($globalStats['totalSelesai']) }}</div>
                                    <div class="fw-semibold text-white text-opacity-75 mt-1">Selesai</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('dekan.history.index') }}"
                            class="card card-flush h-md-100 text-decoration-none hover-elevate-up text-white"
                            style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-38px">
                                        <span class="symbol-label bg-white bg-opacity-20">
                                            <i class="fas fa-times-circle text-white"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-6">
                                    <div class="fw-semibold fs-2x text-white">
                                        {{ number_format($globalStats['totalDitolak']) }}</div>
                                    <div class="fw-semibold text-white text-opacity-75 mt-1">Ditolak</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row g-5 g-xl-8 mb-8">
                    <div class="col-xl-8">
                        <div class="card card-flush h-100">
                            <div class="card-header pt-7">
                                <div class="card-title d-flex flex-column">
                                    <span class="fw-semibold fs-3 text-gray-900">Perbandingan Pengajuan Per Jenis
                                        Surat</span>
                                    <span class="text-gray-600 fw-semibold fs-7">Statistik total pengajuan pada Tahun
                                        Akademik
                                        {{ $currentYearLabel }}.</span>
                                </div>
                            </div>

                            <div class="card-body pt-5">
                                <div class="bg-white rounded p-5">
                                    <div id="surat_chart_container" style="height: 360px; padding-bottom: 6px;">
                                        <canvas id="suratChart"></canvas>
                                    </div>
                                    <div id="barLegendBottom" class="row g-2 mt-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card card-flush h-100">
                            <div class="card-header pt-7">
                                <div class="card-title d-flex flex-column">
                                    <span class="fw-semibold fs-3 text-gray-900">Komposisi Status Pengajuan</span>
                                    <span class="text-gray-600 fw-semibold fs-7">Distribusi berdasarkan status.</span>
                                </div>
                            </div>

                            <div class="card-body pt-5">
                                <div class="bg-white rounded p-5">
                                    <div style="height: 260px;">
                                        <canvas id="statusDonut"></canvas>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>

                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1"
                                                    style="width: 28px; height: 10px; background: rgba(245, 158, 11, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Menunggu BAK</span>
                                            </div>
                                            <span
                                                class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalPengajuan']) }}</span>
                                        </div>

                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1"
                                                    style="width: 28px; height: 10px; background: rgba(59, 130, 246, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Menunggu Dekan</span>
                                            </div>
                                            <span
                                                class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalProses']) }}</span>
                                        </div>

                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1"
                                                    style="width: 28px; height: 10px; background: rgba(34, 197, 94, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Disetujui</span>
                                            </div>
                                            <span
                                                class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalDiterima']) }}</span>
                                        </div>

                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1"
                                                    style="width: 28px; height: 10px; background: rgba(99, 102, 241, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Selesai</span>
                                            </div>
                                            <span
                                                class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalSelesai']) }}</span>
                                        </div>

                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1"
                                                    style="width: 28px; height: 10px; background: rgba(239, 68, 68, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Ditolak</span>
                                            </div>
                                            <span
                                                class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalDitolak']) }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush mb-5">
                    <div class="card-header pt-7">
                        <div class="card-title d-flex flex-column">
                            <span class="fw-semibold fs-3 text-gray-900">Status Detail Pengajuan Per Jenis Surat</span>
                            <span class="text-gray-600 fw-semibold fs-7">Rincian jumlah pengajuan berdasarkan
                                status.</span>
                        </div>
                    </div>

                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="fw-semibold text-gray-600 bg-light">
                                        <th class="min-w-260px ps-4 rounded-start">Jenis Surat</th>
                                        <th class="min-w-100px text-center">Total</th>
                                        <th class="min-w-140px text-center">Pengajuan (Menunggu BAK)</th>
                                        <th class="min-w-140px text-center">Proses (Menunggu Dekan)</th>
                                        <th class="min-w-140px text-center">Diterima</th>
                                        <th class="min-w-110px text-center">Selesai</th>
                                        <th class="min-w-110px text-center rounded-end">Ditolak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detailedStatus as $surat)
                                        <tr class="border-bottom border-gray-200">
                                            <td class="ps-4">
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-900 fw-bolder">{{ $surat['label'] }}</span>
                                                    <span class="text-gray-600 fw-semibold fs-7">Tahun Akademik
                                                        {{ $currentYearLabel }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center"><span
                                                    class="text-gray-900 fw-semibold">{{ $surat['total'] }}</span></td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-warning fw-semibold px-3 py-2">{{ $surat['pengajuan'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-info fw-semibold px-3 py-2">{{ $surat['proses'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-success fw-semibold px-3 py-2">{{ $surat['diterima'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-primary fw-semibold px-3 py-2">{{ $surat['selesai'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-danger fw-semibold px-3 py-2">{{ $surat['ditolak'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);
        const chartColors = @json($chartColors);
        const solidColors = chartColors.map(c => (typeof c === 'string' ? c.replace('0.7', '1') : c));

        new Chart(document.getElementById('suratChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: chartData.data,
                    backgroundColor: solidColors,
                    borderColor: chartColors,
                    borderWidth: 1,
                    borderRadius: 10,
                    barThickness: 34,
                    maxBarThickness: 44
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [6, 6],
                            color: 'rgba(0,0,0,0.08)'
                        },
                        ticks: {
                            color: '#7E8299',
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            callback: function(value) {
                                if (value % 1 === 0) return value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            title: function(items) {
                                return items?.[0]?.label ?? '';
                            },
                            label: function(context) {
                                return ' ' + context.parsed.y + ' pengajuan';
                            }
                        }
                    }
                }
            }
        });

        const barLegendBottom = document.getElementById('barLegendBottom');
        if (barLegendBottom) {
            barLegendBottom.innerHTML = chartData.labels.map((label, i) => {
                const color = solidColors[i] || 'rgba(0,0,0,0.35)';
                return `
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center gap-2 py-1" style="min-height: 36px;">
                            <span class="d-inline-block rounded-1 flex-shrink-0" style="width: 28px; height: 10px; background:${color}; border: 1px solid rgba(0,0,0,.06);"></span>
                            <div class="min-w-0">
                                <div class="text-gray-700 fw-semibold text-truncate" style="max-width: 100%;">${label}</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        new Chart(document.getElementById('statusDonut').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Menunggu BAK', 'Menunggu Dekan', 'Disetujui', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ (int) $globalStats['totalPengajuan'] }},
                        {{ (int) $globalStats['totalProses'] }},
                        {{ (int) $globalStats['totalDiterima'] }},
                        {{ (int) $globalStats['totalSelesai'] }},
                        {{ (int) $globalStats['totalDitolak'] }}
                    ],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.95)',
                        'rgba(59, 130, 246, 0.95)',
                        'rgba(34, 197, 94, 0.95)',
                        'rgba(99, 102, 241, 0.95)',
                        'rgba(239, 68, 68, 0.95)'
                    ],
                    borderColor: 'rgba(255,255,255,0.85)',
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed;
                            }
                        }
                    }
                }
            }
        });

        $('#filter_akademik_id').on('change', function() {
            const selectedId = $(this).val();
            window.location.href = `{{ url()->current() }}?id_akademik=${selectedId}`;
        });
    </script>
@endsection
