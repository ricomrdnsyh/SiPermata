@extends('layout.main')
@section('title', 'Dashboard')

@section('css')
    <style>
        .btn-riwayat-hover {
            transition: all .2s ease;
        }

        .btn-riwayat-hover i {
            transition: all .2s ease;
        }

        .btn-riwayat-hover.btn-light-primary:hover,
        .btn-riwayat-hover.btn-light-warning:hover,
        .btn-riwayat-hover.btn-light-info:hover,
        .btn-riwayat-hover.btn-light-danger:hover,
        .btn-riwayat-hover.btn-light-success:hover,
        .btn-riwayat-hover.btn-light-dark:hover {
            color: #fff !important;
        }

        .btn-riwayat-hover.btn-light-primary:hover i,
        .btn-riwayat-hover.btn-light-warning:hover i,
        .btn-riwayat-hover.btn-light-info:hover i,
        .btn-riwayat-hover.btn-light-danger:hover i,
        .btn-riwayat-hover.btn-light-success:hover i,
        .btn-riwayat-hover.btn-light-dark:hover i {
            color: #fff !important;
        }

        .btn-riwayat-hover.btn-light-primary:hover {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }

        .btn-riwayat-hover.btn-light-warning:hover {
            background-color: var(--bs-warning) !important;
            border-color: var(--bs-warning) !important;
        }

        .btn-riwayat-hover.btn-light-info:hover {
            background-color: var(--bs-info) !important;
            border-color: var(--bs-info) !important;
        }

        .btn-riwayat-hover.btn-light-danger:hover {
            background-color: var(--bs-danger) !important;
            border-color: var(--bs-danger) !important;
        }

        .btn-riwayat-hover.btn-light-success:hover {
            background-color: var(--bs-success) !important;
            border-color: var(--bs-success) !important;
        }

        .btn-riwayat-hover.btn-light-dark:hover {
            background-color: var(--bs-dark) !important;
            border-color: var(--bs-dark) !important;
        }
    </style>
</div>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">

                <div class="card border-0 mb-7 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            <div class="col-lg-6">
                                <div class="p-8 h-100 d-flex flex-column justify-content-center">
                                    <div class="d-flex align-items-start gap-4">
                                        <div class="symbol symbol-50px flex-shrink-0">
                                            <div class="symbol-label bg-white bg-opacity-20 rounded-circle">
                                                <i class="fas fa-user-graduate text-white fs-2"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="fs-2x fw-bolder text-white lh-sm mb-1">
                                                Halo, {{ $user_name ?? 'Pengguna' }}!
                                            </div>
                                            <div class="text-white text-opacity-75 fw-semibold fs-6 mb-5">
                                                Sistem Pengajuan Surat Mahasiswa Terpadu
                                            </div>
                                            <div>
                                                <a href="{{ asset('panduan.pdf') }}" target="_blank" rel="noopener noreferrer"
                                                    class="btn btn-light bg-white btn-sm fw-bolder px-5 py-3 shadow-sm hover-elevate-up text-primary">
                                                    <i class="fas fa-book-open me-2 text-primary"></i>Unduh Buku Panduan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-lg-6">
                                <div class="p-8 h-100 d-flex flex-column justify-content-center border-start border-white border-opacity-10" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px);">
                                    <div class="d-flex align-items-start gap-4">
                                        <div class="symbol symbol-50px flex-shrink-0">
                                            <div class="symbol-label bg-warning bg-opacity-20 rounded-3 border border-warning border-opacity-50">
                                                <i class="fas fa-exclamation-circle text-warning fs-1"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h3 class="text-white fw-bolder fs-4 mb-2">Informasi Penting</h3>
                                            <div class="text-white text-opacity-75 fs-6 fw-semibold lh-lg mb-3">
                                                Jika Tempat Penelitian, Tempat Observasi, dan Tempat PKL tidak ada pada form pengajuan, silakan menghubungi BAK Fakultas untuk menambahkan.
                                            </div>
                                            <a href="https://sipermata.unuja.ac.id/" target="_blank" class="d-flex align-items-center text-warning text-hover-white fw-bold fs-7 text-decoration-underline">
                                                <i class="fas fa-globe me-2 text-warning"></i>sipermata.unuja.ac.id
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-8">
                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border border-dashed border-gray-400 shadow-sm hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-4">
                                        <div class="symbol-label bg-light-primary rounded-3">
                                            <i class="fas fa-file-alt text-primary fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-primary fw-bolder fs-4 mb-1">Surat Keterangan Aktif</span>
                                        <span class="text-gray-500 fw-semibold fs-7">Status aktif mahasiswa</span>
                                    </div>
                                </div>

                                <div class="border border-dashed border-gray-300 rounded p-4 mb-6">
                                    <div class="d-flex flex-stack mb-3">
                                        <span class="text-gray-600 fw-bold fs-6">Total Pengajuan</span>
                                        <span class="text-primary fw-bolder fs-3">{{ $surat_aktif['total'] }}</span>
                                    </div>
                                    <div class="separator separator-dashed border-gray-300 my-3"></div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-warning rounded py-2 px-1" data-bs-toggle="tooltip" title="Proses">
                                                <i class="fas fa-hourglass-half text-warning fs-8 me-2"></i>
                                                <span class="text-warning fw-bolder fs-7">{{ $surat_aktif['proses'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded py-2 px-1" data-bs-toggle="tooltip" title="Selesai">
                                                <i class="fas fa-check-circle text-success fs-8 me-2"></i>
                                                <span class="text-success fw-bolder fs-7">{{ $surat_aktif['selesai'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-danger rounded py-2 px-1" data-bs-toggle="tooltip" title="Ditolak">
                                                <i class="fas fa-times-circle text-danger fs-8 me-2"></i>
                                                <span class="text-danger fw-bolder fs-7">{{ $surat_aktif['ditolak'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-aktif.create') }}" class="btn btn-primary flex-fill btn-sm fw-bolder">
                                        <i class="fas fa-plus me-1"></i> Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.history.index') }}" class="btn btn-light-primary btn-sm flex-fill fw-bolder btn-riwayat-hover">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border border-dashed border-gray-400 shadow-sm hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-4">
                                        <div class="symbol-label bg-light-primary rounded-3">
                                            <i class="fas fa-flask text-primary fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-primary fw-bolder fs-4 mb-1">Surat Izin Penelitian</span>
                                        <span class="text-gray-500 fw-semibold fs-7">Izin penelitian mahasiswa</span>
                                    </div>
                                </div>

                                <div class="border border-dashed border-gray-300 rounded p-4 mb-6">
                                    <div class="d-flex flex-stack mb-3">
                                        <span class="text-gray-600 fw-bold fs-6">Total Pengajuan</span>
                                        <span class="text-primary fw-bolder fs-3">{{ $surat_penelitian['total'] }}</span>
                                    </div>
                                    <div class="separator separator-dashed border-gray-300 my-3"></div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-warning rounded py-2 px-1" data-bs-toggle="tooltip" title="Proses">
                                                <i class="fas fa-hourglass-half text-warning fs-8 me-2"></i>
                                                <span class="text-warning fw-bolder fs-7">{{ $surat_penelitian['proses'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded py-2 px-1" data-bs-toggle="tooltip" title="Selesai">
                                                <i class="fas fa-check-circle text-success fs-8 me-2"></i>
                                                <span class="text-success fw-bolder fs-7">{{ $surat_penelitian['selesai'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-danger rounded py-2 px-1" data-bs-toggle="tooltip" title="Ditolak">
                                                <i class="fas fa-times-circle text-danger fs-8 me-2"></i>
                                                <span class="text-danger fw-bolder fs-7">{{ $surat_penelitian['ditolak'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-izin-penelitian.create') }}" class="btn btn-primary flex-fill btn-sm fw-bolder">
                                        <i class="fas fa-plus me-1"></i> Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.history.index') }}" class="btn btn-light-primary btn-sm flex-fill fw-bolder btn-riwayat-hover">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border border-dashed border-gray-400 shadow-sm hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-4">
                                        <div class="symbol-label bg-light-primary rounded-3">
                                            <i class="fas fa-eye text-primary fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-primary fw-bolder fs-4 mb-1">Permohonan Observasi</span>
                                        <span class="text-gray-500 fw-semibold fs-7">Permohonan observasi lapangan</span>
                                    </div>
                                </div>

                                <div class="border border-dashed border-gray-300 rounded p-4 mb-6">
                                    <div class="d-flex flex-stack mb-3">
                                        <span class="text-gray-600 fw-bold fs-6">Total Pengajuan</span>
                                        <span class="text-primary fw-bolder fs-3">{{ $surat_observasi['total'] }}</span>
                                    </div>
                                    <div class="separator separator-dashed border-gray-300 my-3"></div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-warning rounded py-2 px-1" data-bs-toggle="tooltip" title="Proses">
                                                <i class="fas fa-hourglass-half text-warning fs-8 me-2"></i>
                                                <span class="text-warning fw-bolder fs-7">{{ $surat_observasi['proses'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded py-2 px-1" data-bs-toggle="tooltip" title="Selesai">
                                                <i class="fas fa-check-circle text-success fs-8 me-2"></i>
                                                <span class="text-success fw-bolder fs-7">{{ $surat_observasi['selesai'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-danger rounded py-2 px-1" data-bs-toggle="tooltip" title="Ditolak">
                                                <i class="fas fa-times-circle text-danger fs-8 me-2"></i>
                                                <span class="text-danger fw-bolder fs-7">{{ $surat_observasi['ditolak'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-observasi.create') }}" class="btn btn-primary flex-fill btn-sm fw-bolder">
                                        <i class="fas fa-plus me-1"></i> Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.history.index') }}" class="btn btn-light-primary btn-sm flex-fill fw-bolder btn-riwayat-hover">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border border-dashed border-gray-400 shadow-sm hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-4">
                                        <div class="symbol-label bg-light-primary rounded-3">
                                            <i class="fas fa-thumbs-up text-primary fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-primary fw-bolder fs-4 mb-1">Surat Rekomendasi</span>
                                        <span class="text-gray-500 fw-semibold fs-7">Rekomendasi mahasiswa</span>
                                    </div>
                                </div>

                                <div class="border border-dashed border-gray-300 rounded p-4 mb-6">
                                    <div class="d-flex flex-stack mb-3">
                                        <span class="text-gray-600 fw-bold fs-6">Total Pengajuan</span>
                                        <span class="text-primary fw-bolder fs-3">{{ $surat_rekomendasi['total'] }}</span>
                                    </div>
                                    <div class="separator separator-dashed border-gray-300 my-3"></div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-warning rounded py-2 px-1" data-bs-toggle="tooltip" title="Proses">
                                                <i class="fas fa-hourglass-half text-warning fs-8 me-2"></i>
                                                <span class="text-warning fw-bolder fs-7">{{ $surat_rekomendasi['proses'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded py-2 px-1" data-bs-toggle="tooltip" title="Selesai">
                                                <i class="fas fa-check-circle text-success fs-8 me-2"></i>
                                                <span class="text-success fw-bolder fs-7">{{ $surat_rekomendasi['selesai'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-danger rounded py-2 px-1" data-bs-toggle="tooltip" title="Ditolak">
                                                <i class="fas fa-times-circle text-danger fs-8 me-2"></i>
                                                <span class="text-danger fw-bolder fs-7">{{ $surat_rekomendasi['ditolak'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-rekomendasi.create') }}" class="btn btn-primary flex-fill btn-sm fw-bolder">
                                        <i class="fas fa-plus me-1"></i> Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.history.index') }}" class="btn btn-light-primary btn-sm flex-fill fw-bolder btn-riwayat-hover">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border border-dashed border-gray-400 shadow-sm hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-4">
                                        <div class="symbol-label bg-light-primary rounded-3">
                                            <i class="fas fa-briefcase text-primary fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-primary fw-bolder fs-4 mb-1">Surat Permohonan PKL</span>
                                        <span class="text-gray-500 fw-semibold fs-7">Pengantar untuk PKL</span>
                                    </div>
                                </div>

                                <div class="border border-dashed border-gray-300 rounded p-4 mb-6">
                                    <div class="d-flex flex-stack mb-3">
                                        <span class="text-gray-600 fw-bold fs-6">Total Pengajuan</span>
                                        <span class="text-primary fw-bolder fs-3">{{ $surat_pkl['total'] }}</span>
                                    </div>
                                    <div class="separator separator-dashed border-gray-300 my-3"></div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-warning rounded py-2 px-1" data-bs-toggle="tooltip" title="Proses">
                                                <i class="fas fa-hourglass-half text-warning fs-8 me-2"></i>
                                                <span class="text-warning fw-bolder fs-7">{{ $surat_pkl['proses'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded py-2 px-1" data-bs-toggle="tooltip" title="Selesai">
                                                <i class="fas fa-check-circle text-success fs-8 me-2"></i>
                                                <span class="text-success fw-bolder fs-7">{{ $surat_pkl['selesai'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-danger rounded py-2 px-1" data-bs-toggle="tooltip" title="Ditolak">
                                                <i class="fas fa-times-circle text-danger fs-8 me-2"></i>
                                                <span class="text-danger fw-bolder fs-7">{{ $surat_pkl['ditolak'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-pkl.create') }}" class="btn btn-primary flex-fill btn-sm fw-bolder">
                                        <i class="fas fa-plus me-1"></i> Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.history.index') }}" class="btn btn-light-primary btn-sm flex-fill fw-bolder btn-riwayat-hover">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card h-100 border border-dashed border-gray-400 shadow-sm hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-4">
                                        <div class="symbol-label bg-light-primary rounded-3">
                                            <i class="fas fa-graduation-cap text-primary fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-primary fw-bolder fs-4 mb-1">Surat Keterangan Lulus</span>
                                        <span class="text-gray-500 fw-semibold fs-7">Ijazah, beasiswa, dll</span>
                                    </div>
                                </div>

                                <div class="border border-dashed border-gray-300 rounded p-4 mb-6">
                                    <div class="d-flex flex-stack mb-3">
                                        <span class="text-gray-600 fw-bold fs-6">Total Pengajuan</span>
                                        <span class="text-primary fw-bolder fs-3">{{ $surat_lulus['total'] }}</span>
                                    </div>
                                    <div class="separator separator-dashed border-gray-300 my-3"></div>
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-warning rounded py-2 px-1" data-bs-toggle="tooltip" title="Proses">
                                                <i class="fas fa-hourglass-half text-warning fs-8 me-2"></i>
                                                <span class="text-warning fw-bolder fs-7">{{ $surat_lulus['proses'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded py-2 px-1" data-bs-toggle="tooltip" title="Selesai">
                                                <i class="fas fa-check-circle text-success fs-8 me-2"></i>
                                                <span class="text-success fw-bolder fs-7">{{ $surat_lulus['selesai'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-danger rounded py-2 px-1" data-bs-toggle="tooltip" title="Ditolak">
                                                <i class="fas fa-times-circle text-danger fs-8 me-2"></i>
                                                <span class="text-danger fw-bolder fs-7">{{ $surat_lulus['ditolak'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-keterangan-lulus.create') }}" class="btn btn-primary flex-fill btn-sm fw-bolder">
                                        <i class="fas fa-plus me-1"></i> Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.history.index') }}" class="btn btn-light-primary btn-sm flex-fill fw-bolder btn-riwayat-hover">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
