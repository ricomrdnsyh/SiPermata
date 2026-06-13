@extends('layout.main')
@section('title', 'Dashboard')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">

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
                                <div class="d-flex align-items-center fw-bold px-4 py-2" style="background-color: #F8F9FA; color: #64748B; border-radius: 6px; font-size: 0.95rem; height: 38px;">
                                    <i class="fas fa-filter me-2" style="color: #64748B;"></i>Tahun Akademik
                                </div>
                                <div class="w-180px">
                                    <style> .select2-container--bootstrap5 .select2-selection { border-color: #E2E8F0 !important; color: #64748B !important; font-weight: 500 !important; border-radius: 6px !important; } .select2-container--bootstrap5 .select2-selection__rendered { color: #64748B !important; } </style>
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
                        <a href="{{ route('bak.history.index') }}"
                            class="card bg-secondary border border-dashed border-gray-400 shadow-sm hover-elevate-up text-decoration-none h-md-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-40px">
                                        <span class="symbol-label bg-dark shadow-sm">
                                            <i class="fas fa-inbox text-white fs-3"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-4">
                                    <div class="fw-bold fs-2x text-gray-900">{{ number_format($globalStats['totalMasuk']) }}</div>
                                    <div class="fw-semibold text-gray-700 mt-1 fs-7">Total Masuk</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('bak.history.index') }}"
                            class="card bg-light-warning border border-dashed border-gray-400 shadow-sm hover-elevate-up text-decoration-none h-md-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-40px">
                                        <span class="symbol-label bg-warning shadow-sm">
                                            <i class="fas fa-file-export text-white fs-3"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-4">
                                    <div class="fw-bold fs-2x text-gray-900">{{ number_format($globalStats['totalPengajuan']) }}</div>
                                    <div class="fw-semibold text-gray-700 mt-1 fs-7">Menunggu BAK</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('bak.history.index') }}"
                            class="card bg-light-primary border border-dashed border-gray-400 shadow-sm hover-elevate-up text-decoration-none h-md-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-40px">
                                        <span class="symbol-label bg-primary shadow-sm">
                                            <i class="fas fa-user-clock text-white fs-3"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-4">
                                    <div class="fw-bold fs-2x text-gray-900">{{ number_format($globalStats['totalProses']) }}</div>
                                    <div class="fw-semibold text-gray-700 mt-1 fs-7">Menunggu Dekan</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('bak.history.index') }}"
                            class="card bg-light-success border border-dashed border-gray-400 shadow-sm hover-elevate-up text-decoration-none h-md-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-40px">
                                        <span class="symbol-label bg-success shadow-sm">
                                            <i class="fas fa-clipboard-check text-white fs-3"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-4">
                                    <div class="fw-bold fs-2x text-gray-900">{{ number_format($globalStats['totalDiterima']) }}</div>
                                    <div class="fw-semibold text-gray-700 mt-1 fs-7">Disetujui</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('bak.history.index') }}"
                            class="card border border-dashed border-gray-400 shadow-sm hover-elevate-up text-decoration-none h-md-100"
                            style="background-color: rgba(114, 57, 234, 0.12) !important;">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-40px">
                                        <span class="symbol-label bg-info shadow-sm">
                                            <i class="fas fa-download text-white fs-3"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-4">
                                    <div class="fw-bold fs-2x text-gray-900">{{ number_format($globalStats['totalSelesai']) }}</div>
                                    <div class="fw-semibold text-gray-700 mt-1 fs-7">Selesai</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-2 col-md-4">
                        <a href="{{ route('bak.history.index') }}"
                            class="card bg-light-danger border border-dashed border-gray-400 shadow-sm hover-elevate-up text-decoration-none h-md-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-40px">
                                        <span class="symbol-label bg-danger shadow-sm">
                                            <i class="fas fa-times-circle text-white fs-3"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-4">
                                    <div class="fw-bold fs-2x text-gray-900">{{ number_format($globalStats['totalDitolak']) }}</div>
                                    <div class="fw-semibold text-gray-700 mt-1 fs-7">Ditolak</div>
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
                                <div class="card-toolbar">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-bars fs-2"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end fw-semibold fs-7 w-125px py-4">
                                            <li><a class="dropdown-item px-3" href="#" onclick="downloadChart('suratChart', 'jpg', 'Grafik_Jenis_Surat'); return false;">Download JPG</a></li>
                                            <li><a class="dropdown-item px-3" href="#" onclick="downloadChart('suratChart', 'png', 'Grafik_Jenis_Surat'); return false;">Download PNG</a></li>
                                            <li><a class="dropdown-item px-3" href="#" onclick="downloadChart('suratChart', 'pdf', 'Grafik_Jenis_Surat'); return false;">Download PDF</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pt-5">
                                <div class="p-0">
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
                                <div class="p-0">
                                    <div style="height: 260px;">
                                        <canvas id="statusDonut"></canvas>
                                    </div>

                                    <div class="separator separator-dashed my-5"></div>

                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1" style="width: 28px; height: 10px; background: rgba(245, 158, 11, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Menunggu BAK</span>
                                            </div>
                                            <span class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalPengajuan']) }}</span>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1" style="width: 28px; height: 10px; background: rgba(59, 130, 246, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Menunggu Dekan</span>
                                            </div>
                                            <span class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalProses']) }}</span>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1" style="width: 28px; height: 10px; background: rgba(34, 197, 94, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Disetujui</span>
                                            </div>
                                            <span class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalDiterima']) }}</span>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1" style="width: 28px; height: 10px; background: rgba(99, 102, 241, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Selesai</span>
                                            </div>
                                            <span class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalSelesai']) }}</span>
                                        </div>
                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="d-inline-block rounded-1" style="width: 28px; height: 10px; background: rgba(239, 68, 68, 0.95); border: 1px solid rgba(0,0,0,.06);"></span>
                                                <span class="text-gray-700 fw-semibold">Ditolak</span>
                                            </div>
                                            <span class="text-gray-900 fw-semibold">{{ number_format($globalStats['totalDitolak']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-flush mb-8">
                    <div class="card-header pt-7 pb-0 border-0">
                        <div class="card-title d-flex flex-column gap-1">
                            <span class="text-uppercase fw-semibold text-gray-500" style="font-size: 10px; letter-spacing: .08em;">Distribusi</span>
                            <span class="fw-semibold fs-3 text-gray-900">Pengajuan Per Program Studi</span>
                            <span class="text-gray-500 fw-semibold fs-7">Total pengajuan berdasarkan prodi &mdash; TA {{ $currentYearLabel }}</span>
                        </div>
                        <div class="card-toolbar">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bars fs-2"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end fw-semibold fs-7 w-125px py-4">
                                    <li><a class="dropdown-item px-3" href="#" onclick="downloadChart('prodiChart', 'jpg', 'Grafik_Prodi'); return false;">Download JPG</a></li>
                                    <li><a class="dropdown-item px-3" href="#" onclick="downloadChart('prodiChart', 'png', 'Grafik_Prodi'); return false;">Download PNG</a></li>
                                    <li><a class="dropdown-item px-3" href="#" onclick="downloadChart('prodiChart', 'pdf', 'Grafik_Prodi'); return false;">Download PDF</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        <div id="prodi_chart_container" style="position: relative; width: 100%; min-height: 300px;">
                            <canvas id="prodiChart"></canvas>
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
                                                    class="badge badge-light-warning fw-bold px-3 py-2">{{ $surat['pengajuan'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-info fw-bold px-3 py-2">{{ $surat['proses'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-success fw-bold px-3 py-2">{{ $surat['diterima'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-primary fw-bold px-3 py-2">{{ $surat['selesai'] }}</span>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge badge-light-danger fw-bold px-3 py-2">{{ $surat['ditolak'] }}</span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function downloadChart(canvasId, format, fileName) {
            const chart = Chart.getChart(canvasId);
            let originalXTicks = null;
            
            if (canvasId === 'suratChart') {
                originalXTicks = chart.options.scales.x.ticks.display;
                chart.options.scales.x.ticks.display = true;
                chart.update('none'); 
            }
            
            setTimeout(() => {
                const canvas = document.getElementById(canvasId);
                
                if (format === 'png' || format === 'jpg') {
                    const tempCanvas = document.createElement('canvas');
                    tempCanvas.width = canvas.width;
                    tempCanvas.height = canvas.height;
                    const ctx = tempCanvas.getContext('2d');
                    
                    if (format === 'jpg') {
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
                    }
                    
                    ctx.drawImage(canvas, 0, 0);
                    
                    const link = document.createElement('a');
                    link.download = fileName + '.' + format;
                    link.href = tempCanvas.toDataURL(format === 'jpg' ? 'image/jpeg' : 'image/png', 1.0);
                    link.click();
                } else if (format === 'pdf') {
                    const { jsPDF } = window.jspdf;
                    const imgData = canvas.toDataURL('image/png');
                    
                    const pdf = new jsPDF({
                        orientation: 'landscape',
                        unit: 'px',
                        format: [canvas.width + 40, canvas.height + 40]
                    });
                    
                    pdf.setFillColor(255, 255, 255);
                    pdf.rect(0, 0, canvas.width + 40, canvas.height + 40, 'F');
                    
                    pdf.addImage(imgData, 'PNG', 20, 20, canvas.width, canvas.height);
                    pdf.save(fileName + '.pdf');
                }
                
                if (canvasId === 'suratChart') {
                    chart.options.scales.x.ticks.display = originalXTicks;
                    chart.update('none');
                }
            }, 100);
        }
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
        const statusDonutCenterText = {
            id: 'centerText',
            beforeDraw: function(chart) {
                var width = chart.width,
                    height = chart.height,
                    ctx = chart.ctx;

                ctx.restore();
                
                var total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                if (total === 0) return;

                var text = total.toLocaleString('id-ID');
                var isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                
                ctx.textBaseline = "middle";
                
                ctx.fillStyle = isDarkMode ? '#A1A5B7' : '#7E8299';
                var labelFontSize = (height / 350).toFixed(2);
                ctx.font = "600 " + labelFontSize + "em Inter, sans-serif";
                var labelText = "TOTAL";
                var labelTextX = Math.round((width - ctx.measureText(labelText).width) / 2);
                var labelTextY = height / 2 - 12;
                ctx.fillText(labelText, labelTextX, labelTextY);

                ctx.fillStyle = isDarkMode ? '#FFFFFF' : '#181C32';
                var fontSize = (height / 90).toFixed(2);
                ctx.font = "bold " + fontSize + "em Inter, sans-serif";
                var textX = Math.round((width - ctx.measureText(text).width) / 2);
                var textY = height / 2 + 16;
                ctx.fillText(text, textX, textY);
                
                ctx.save();
            }
        };

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
                cutout: '70%',
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
            },
            plugins: [statusDonutCenterText]
        });

        $('#filter_akademik_id').on('change', function() {
            const selectedId = $(this).val();
            window.location.href = `{{ url()->current() }}?id_akademik=${selectedId}`;
        });

        const prodiData = @json($prodiChartData);
        const prodiPalette = [
            '#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6',
            '#06B6D4','#F97316','#EC4899','#84CC16','#14B8A6',
            '#6366F1','#F43F5E','#A3E635','#0EA5E9','#D946EF',
            '#FB923C','#22D3EE','#4ADE80','#FBBF24','#C084FC',
            '#34D399','#60A5FA','#FCD34D','#F472B6','#38BDF8',
            '#A78BFA','#86EFAC','#FCA5A5','#67E8F9','#FDE68A',
            '#2DD4BF','#818CF8','#BEF264','#FDA4AF','#7DD3FC',
            '#E879F9','#6EE7B7','#93C5FD','#FDE047','#F9A8D4'
        ];
        function generateProdiColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                colors.push(prodiPalette[i % prodiPalette.length]);
            }
            return colors;
        }
        const prodiBgColors = generateProdiColors(prodiData.labels.length);
        const prodiBorderColors = prodiBgColors;

        const prodiContainer = document.getElementById('prodi_chart_container');
        prodiContainer.style.height = Math.max(300, prodiData.labels.length * 38) + 'px';

        new Chart(document.getElementById('prodiChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: prodiData.labels,
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: prodiData.data,
                    backgroundColor: prodiBgColors,
                    borderColor: prodiBorderColors,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    barThickness: 22,
                    maxBarThickness: 30
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(136,135,128,0.12)', lineWidth: 1 },
                        ticks: {
                            color: '#7E8299',
                            font: { size: 12, weight: '500' },
                            callback: v => v % 1 === 0 ? v : null
                        }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#7E8299', font: { size: 12, weight: '500' } }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        displayColors: false,
                        callbacks: { label: ctx => ' ' + ctx.parsed.x + ' pengajuan' }
                    }
                }
            }
        });
    </script>
</div>
@endsection
