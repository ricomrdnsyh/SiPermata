@extends('layout.main')

@section('title', 'Dashboard')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="container-fluid">
                    <div class="card card-flush mb-8">
                        <div
                            class="card-header py-5 gap-2 gap-md-5 
                            d-flex flex-column flex-md-row 
                            align-items-center justify-content-center 
                            justify-content-md-between align-items-md-center">
                            <div class="card-title order-1 order-md-1">
                                <h2 class="d-flex flex-column flex-md-row align-items-center">
                                    Selamat Datang,
                                    <span class="position-relative ms-0 ms-md-2 text-danger">
                                        {{ $user_name ?? 'Pengguna' }}
                                        <span
                                            class="position-absolute opacity-50 bottom-0 start-0 border-4 border-danger border-bottom w-100"></span>
                                    </span>
                                </h2>
                            </div>
                            <div
                                class="card-toolbar flex-row-fluid 
                                justify-content-center justify-content-md-end gap-5 
                                order-2 order-md-2 mt-3 mt-md-0">
                                <div class="w-200px">
                                    <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="true" data-placeholder="Tahun Akademik" id="filter_akademik_id">
                                        @foreach ($tahunAkademikList as $id => $tahun)
                                            <option value="{{ $id }}"
                                                @if ($id == $currentAkademikId) selected @endif>
                                                {{ $tahun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 g-xl-8 mb-5">

                        <div class="col-xl-2 col-md-4">
                            <div class="card text-white card-xl-stretch h-md-100"
                                style="background: linear-gradient(to right, #434343 0%, #000000 100%);">
                                <div class="card-body p-5">
                                    <i class="fas fa-inbox fs-1 text-white opacity-75 mb-3"></i>
                                    <div class="text-white fw-bolder fs-3 mb-1 mt-5">
                                        {{ number_format($globalStats['totalMasuk']) }}
                                    </div>
                                    <div class="fw-semibold text-white opacity-75 fs-7">TOTAL MASUK</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <div class="card card-xl-stretch h-md-100"
                                style="background: linear-gradient(to right, #f6d365 0%, #fda085 100%);">
                                <div class="card-body p-5">
                                    <i class="fas fa-file-export fs-1 text-dark opacity-75 mb-3"></i>
                                    <div class="text-dark fw-bolder fs-3 mb-1 mt-5">
                                        {{ number_format($globalStats['totalPengajuan']) }}
                                    </div>
                                    <div class="fw-semibold text-dark opacity-75 fs-7">MENUNGGU BAK</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <div class="card text-white card-xl-stretch h-md-100"
                                style="background: linear-gradient(to right, #4c4a6d 0%, #9370db 100%);">
                                <div class="card-body p-5">
                                    <i class="fas fa-user-clock fs-1 text-white opacity-75 mb-3"></i>
                                    <div class="text-white fw-bolder fs-3 mb-1 mt-5">
                                        {{ number_format($globalStats['totalProses']) }}
                                    </div>
                                    <div class="fw-semibold text-white opacity-75 fs-7">MENUNGGU DEKAN</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <div class="card text-white card-xl-stretch h-md-100"
                                style="background: linear-gradient(to right, #1ddf76 0%, #00bc8c 100%);">
                                <div class="card-body p-5">
                                    <i class="fas fa-clipboard-check fs-1 text-white opacity-75 mb-3"></i>
                                    <div class="text-white fw-bolder fs-3 mb-1 mt-5">
                                        {{ number_format($globalStats['totalDiterima']) }}
                                    </div>
                                    <div class="fw-semibold text-white opacity-75 fs-7">DISETUJUI</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <div class="card text-white card-xl-stretch h-md-100"
                                style="background: linear-gradient(to right, #4e73df 0%, #2e59d9 100%);">
                                <div class="card-body p-5">
                                    <i class="fas fa-download fs-1 text-white opacity-75 mb-3"></i>
                                    <div class="text-white fw-bolder fs-3 mb-1 mt-5">
                                        {{ number_format($globalStats['totalSelesai']) }}
                                    </div>
                                    <div class="fw-semibold text-white opacity-75 fs-7">SELESAI</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4">
                            <div class="card text-white card-xl-stretch h-md-100"
                                style="background: linear-gradient(to right, #e74a3b 0%, #cc0000 100%);">
                                <div class="card-body p-5">
                                    <i class="fas fa-times-circle fs-1 text-white opacity-75 mb-3"></i>
                                    <div class="text-white fw-bolder fs-3 mb-1 mt-5">
                                        {{ number_format($globalStats['totalDitolak']) }}
                                    </div>
                                    <div class="fw-semibold text-white opacity-75 fs-7">DITOLAK</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-xl-stretch mb-5">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Perbandingan Pengajuan Per Jenis Surat</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Statistik total pengajuan pada Tahun Akademik
                                    {{ $currentYearLabel }}.</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="surat_chart_container" style="height: 400px;">
                                <canvas id="suratChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card card-xl-stretch mb-5">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Status Detail Pengajuan Per Jenis Surat</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Rincian jumlah pengajuan berdasarkan
                                    status.</span>
                            </h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">
                                            <th class="min-w-150px">Jenis Surat</th>
                                            <th class="min-w-100px text-center">Total Pengajuan</th>
                                            <th class="min-w-100px text-center">Pengajuan (Menunggu BAK)</th>
                                            <th class="min-w-100px text-center">Proses (Menunggu Dekan)</th>
                                            <th class="min-w-100px text-center">Diterima (Disetujui)</th>
                                            <th class="min-w-100px text-center">Selesai</th>
                                            <th class="min-w-100px text-center">Ditolak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detailedStatus as $surat)
                                            <tr>
                                                <td>
                                                    <span
                                                        class="text-dark fw-bolder d-block fs-6">{{ $surat['label'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="text-dark fw-bolder d-block fs-6">{{ $surat['total'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-light-warning fw-bolder">{{ $surat['pengajuan'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-light-info fw-bolder">{{ $surat['proses'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-light-success fw-bolder">{{ $surat['diterima'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-light-primary fw-bolder">{{ $surat['selesai'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-light-danger fw-bolder">{{ $surat['ditolak'] }}</span>
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
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Ambil data dari Controller
        const chartData = @json($chartData);
        const chartColors = @json($chartColors);

        // Konfigurasi Chart
        const ctx = document.getElementById('suratChart').getContext('2d');
        const suratChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: chartData.data,
                    // Menggunakan array warna yang dikirim dari Controller
                    backgroundColor: chartColors.map(color => color.replace('0.7', '1')),
                    borderColor: chartColors, // Warna border
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Filter Tahun Akademik
        $('#filter_akademik_id').on('change', function() {
            const selectedId = $(this).val();
            window.location.href = `{{ url()->current() }}?id_akademik=${selectedId}`;
        });
    </script>
@endsection
