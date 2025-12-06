@extends('layout.main')

@section('title', 'Dashboard')

@section('content')
    <style>
        .btn-light-primary-hover-white:hover,
        .btn-light-warning-hover-white:hover,
        .btn-light-info-hover-white:hover,
        .btn-light-secondary-hover-white:hover,
        .btn-light-danger-hover-white:hover,
        .btn-light-success-hover-white:hover,
        .btn-light-dark-hover-white:hover {
            color: #ffffff !important;
        }
    </style>

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="container-fluid">
                    <div class="row gx-5 gx-xl-10">
                        <div class="col-12 mb-6">
                            <div class="card border-transparent mb-5" data-bs-theme="light"
                                style="background-color: #1C325E;">
                                <div class="card-body d-flex ps-xl-15 position-relative">
                                    <div class="m-0">
                                        <div class="position-relative fs-2x z-index-2 fw-bold text-white mb-7">
                                            <span class="me-2">
                                                Halo
                                                <span class="position-relative d-inline-block text-danger">
                                                    {{ $user_name ?? 'Pengguna' }}
                                                    <span
                                                        class="position-absolute opacity-50 bottom-0 start-0 border-4 border-danger border-bottom w-100"></span>
                                                </span>
                                            </span>
                                            <br>
                                            Selamat datang di <span
                                                class="position-relative d-inline-block text-danger">Sistem Pengajuan Surat
                                                Mahasiswa Terpadu</span>
                                            <br>
                                            Cek status pengajuanmu atau segera buat pengajuan surat baru!
                                        </div>
                                    </div>
                                    <img src="{{ asset('assets/media/illustrations/sigma-1/17-dark.png') }}"
                                        class="d-none d-md-block position-absolute me-3 bottom-0 end-0 h-200px"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 g-xl-8">

                        <div class="col-xl-4 col-md-6 mb-5">
                            <div
                                class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-primary">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-file-alt fs-2x me-3 text-primary"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Keterangan Aktif</span>
                                            <span class="text-muted fs-7">Pengajuan status aktif mahasiswa.</span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-primary p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-primary">{{ $surat_aktif['total'] }}</div>
                                    </div>

                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_aktif['proses'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_aktif['selesai'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_aktif['ditolak'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-aktif.create') }}"
                                            class="btn btn-sm btn-primary flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-aktif.index') }}"
                                            class="btn btn-sm btn-light-primary text-primary flex-fill btn-light-primary-hover-white">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-5">
                            <div
                                class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-warning">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-flask fs-2x me-3 text-warning"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Izin Penelitian</span>
                                            <span class="text-muted fs-7">Pengajuan surat izin penelitian mahasiswa.</span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-warning p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-warning">{{ $surat_penelitian['total'] }}</div>
                                    </div>

                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_penelitian['proses'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_penelitian['selesai'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_penelitian['ditolak'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-izin-penelitian.create') }}"
                                            class="btn btn-sm btn-warning flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-izin-penelitian.index') }}"
                                            class="btn btn-sm btn-light-warning text-warning flex-fill btn-light-warning-hover-white">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-info">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-eye fs-2x me-3 text-info"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Permohonan Observasi</span>
                                            <span class="text-muted fs-7">Pengajuan surat observasi
                                                mahasiswa.</span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-info p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-info">{{ $surat_observasi['total'] }}</div>
                                    </div>

                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_observasi['proses'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_observasi['selesai'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_observasi['ditolak'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-observasi.create') }}"
                                            class="btn btn-sm btn-info flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-observasi.index') }}"
                                            class="btn btn-sm btn-light-info text-info flex-fill btn-light-info-hover-white">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-dark">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-thumbs-up fs-2x me-3 text-dark"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Rekomendasi</span>
                                            <span class="text-muted fs-7">Pengajuan surat rekomendasi
                                                mahasiswa.</span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-dark p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-dark">{{ $surat_rekomendasi['total'] }}</div>
                                    </div>

                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_rekomendasi['proses'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_rekomendasi['selesai'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_rekomendasi['ditolak'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-rekomendasi.create') }}"
                                            class="btn btn-sm btn-dark flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-rekomendasi.index') }}"
                                            class="btn btn-sm btn-light-dark text-dark flex-fill btn-light-dark-hover-white">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-5">
                            <div
                                class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-danger">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-briefcase fs-2x me-3 text-danger"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Permohonan PKL</span>
                                            <span class="text-muted fs-7">Pengantar untuk keperluan PKL.</span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-danger p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-danger">{{ $surat_pkl['total'] }}</div>
                                    </div>

                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_pkl['proses'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_pkl['selesai'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_pkl['ditolak'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-pkl.create') }}"
                                            class="btn btn-sm btn-danger flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-pkl.index') }}"
                                            class="btn btn-sm btn-light-danger text-danger flex-fill btn-light-danger-hover-white">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-5">
                            <div
                                class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-success">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-graduation-cap fs-2x me-3 text-success"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Keterangan Lulus</span>
                                            <span class="text-muted fs-7">Diperlukan untuk ijazah, beasiswa, dll.</span>
                                        </div>
                                    </div>

                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-success p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-success">{{ $surat_lulus['total'] }}</div>
                                    </div>

                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_lulus['proses'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_lulus['selesai'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_lulus['ditolak'] }}</span>
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-keterangan-lulus.create') }}"
                                            class="btn btn-sm btn-success flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-keterangan-lulus.index') }}"
                                            class="btn btn-sm btn-light-success text-success flex-fill btn-light-success-hover-white">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
