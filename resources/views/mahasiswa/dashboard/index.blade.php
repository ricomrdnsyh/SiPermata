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

        .hover-elevate-up {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .hover-elevate-up:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
        }

        :root {
            --primary-soft: #f0f7ff;
            --primary-color: #006ae6;
            --success-soft: #ecfdf3;
            --success-color: #12b76a;
            --warning-soft: #fffcf5;
            --warning-color: #f79009;
            --info-soft: #f0f9ff;
            --info-color: #0ea5e9;
            --border-color: #eaecf0;
            --text-main: #101828;
            --text-muted: #667085;
        }

        [data-bs-theme="dark"] {
            --primary-soft: rgba(0, 106, 230, 0.15);
            --success-soft: rgba(18, 183, 106, 0.15);
            --warning-soft: rgba(247, 144, 9, 0.15);
            --info-soft: rgba(14, 165, 233, 0.15);
            --border-color: var(--bs-border-color);
            --text-main: var(--bs-text-primary);
            --text-muted: var(--bs-text-muted);
        }

        .dash-hero {
            background: linear-gradient(135deg, #006AE6 0%, #004CCC 100%);
            border-radius: 28px;
            padding: 3.5rem 4rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 106, 230, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dash-hero::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .dash-hero-pattern {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.5;
        }

        .hero-illus {
            position: absolute;
            right: 2rem;
            bottom: -2rem;
            height: 110%;
            object-fit: contain;
            opacity: 0.95;
            filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.1));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        .avatar-initial {
            width: 72px;
            height: 72px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(16, 24, 40, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-bs-theme="dark"] .glass-card {
            background: var(--bs-card-bg);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(16, 24, 40, 0.08);
            border-color: #d0d5dd;
        }

        .info-chip {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            gap: 1.25rem;
            height: 100%;
        }

        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .glass-card:hover .icon-box {
            transform: scale(1.05);
        }

        .text-main { color: var(--text-main); }
        .text-muted { color: var(--text-muted); }

        @media (min-width: 768px) {
            .border-md-end { border-right: 1px solid var(--border-color); }
        }

        @media (max-width: 991px) {
            .dash-hero { padding: 2.5rem 1.5rem; }
            .hero-illus { display: none; }
        }
    </style>
</div>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">

                @php
                    $jam = (int) date('G');
                    $sapaan =
                        $jam < 11
                            ? 'Selamat Pagi'
                            : ($jam < 15
                                ? 'Selamat Siang'
                                : ($jam < 18
                                    ? 'Selamat Sore'
                                    : 'Selamat Malam'));
                    $namaUser = $user_name ?? (auth()->user()->name ?? 'Mahasiswa');
                    $inisial = strtoupper(substr($namaUser, 0, 1));
                @endphp

                <div class="dash-hero" style="margin-bottom: -3rem; padding-bottom: 5rem;">
                    <div class="dash-hero-pattern"></div>

                    <svg class="hero-illus d-none d-lg-block" width="380" height="380" viewBox="0 0 380 380"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="190" cy="190" r="140" fill="white" fill-opacity="0.05" />
                        <circle cx="190" cy="190" r="100" fill="white" fill-opacity="0.1" />

                        <g transform="translate(60, 60) rotate(-8)">
                            <rect x="0" y="0" width="140" height="180" rx="12" fill="white" fill-opacity="0.95" style="filter: drop-shadow(0 15px 25px rgba(0,0,0,0.15));" />
                            
                            <rect x="25" y="25" width="90" height="15" rx="4" fill="#006AE6" fill-opacity="0.2" />
                            <circle cx="35" cy="32.5" r="5" fill="#006AE6" />
                            
                            <rect x="25" y="60" width="90" height="6" rx="3" fill="#006AE6" fill-opacity="0.1" />
                            <rect x="25" y="75" width="75" height="6" rx="3" fill="#006AE6" fill-opacity="0.1" />
                            <rect x="25" y="90" width="85" height="6" rx="3" fill="#006AE6" fill-opacity="0.1" />
                            <rect x="25" y="105" width="60" height="6" rx="3" fill="#006AE6" fill-opacity="0.1" />
                            
                            <path d="M70 145 Q85 130 95 150 T115 140" stroke="#006AE6" stroke-width="3" fill="none" stroke-linecap="round" />
                            <circle cx="105" cy="145" r="18" fill="#f79009" fill-opacity="0.2" />
                            <circle cx="105" cy="145" r="12" stroke="#f79009" stroke-width="2" stroke-dasharray="3 3" fill="none" />
                        </g>

                        <g transform="translate(190, 40) rotate(15)">
                            <path d="M0 20 C0 8.95 8.95 0 20 0 L100 0 C111.05 0 120 8.95 120 20 L120 70 C120 81.05 111.05 90 100 90 L20 90 C8.95 90 0 81.05 0 70 Z" fill="white" fill-opacity="0.95" style="filter: drop-shadow(0 10px 20px rgba(0,0,0,0.12));" />
                            <path d="M0 20 L60 60 L120 20" stroke="#006AE6" stroke-width="4" stroke-opacity="0.15" fill="none" stroke-linejoin="round" />
                            <path d="M0 90 L45 50 M120 90 L75 50" stroke="#006AE6" stroke-width="4" stroke-opacity="0.15" fill="none" stroke-linecap="round" />
                            <circle cx="60" cy="50" r="15" fill="#f79009" fill-opacity="0.9" />
                            <path d="M54 50 L58 54 L66 45" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                        </g>

                        <g transform="translate(220, 180) rotate(-10)">
                            <circle cx="50" cy="50" r="45" fill="white" fill-opacity="0.95" style="filter: drop-shadow(0 20px 30px rgba(0,0,0,0.15));" />
                            <circle cx="50" cy="50" r="35" stroke="#12b76a" stroke-width="4" stroke-dasharray="6 4" fill="none" stroke-opacity="0.5" />
                            <path d="M35 50 L45 60 L65 40" stroke="#12b76a" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                        </g>

                        <path d="M40 220 L46 238 L64 244 L46 250 L40 268 L34 250 L16 244 L34 238 Z" fill="white" fill-opacity="0.9" />
                        <path d="M320 80 L324 92 L336 96 L324 100 L320 112 L316 100 L304 96 L316 92 Z" fill="white" fill-opacity="0.7" />
                        <circle cx="80" cy="40" r="6" fill="white" fill-opacity="0.6" />
                        <circle cx="310" cy="280" r="8" fill="white" fill-opacity="0.5" />
                    </svg>

                    <div class="position-relative z-index-1">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-4 mb-6">
                                    <div class="avatar-initial">
                                        {{ $inisial }}
                                    </div>
                                    <div>
                                        <div class="text-white opacity-75 fs-5 mb-1 fw-medium tracking-wide">
                                            {{ $sapaan }},</div>
                                        <h1 class="text-white fw-bolder mb-0 display-6" style="letter-spacing: -0.5px;">
                                            {{ $namaUser }}
                                        </h1>
                                    </div>
                                </div>

                                <p class="text-white opacity-90 fs-5 mb-8"
                                    style="line-height: 1.6; max-width: 600px; font-weight: 300;">
                                    Selamat datang di Sistem Pengajuan Surat Mahasiswa Terpadu (SiPermata). Platform ini memudahkan Anda dalam mengajukan berbagai keperluan administrasi akademik.
                                </p>

                                <div class="d-flex flex-wrap gap-4 align-items-center">
                                    <a href="{{ asset('panduan.pdf') }}" target="_blank"
                                        class="btn btn-light text-primary fw-bolder px-8 py-4 rounded-pill shadow-sm fs-6 hover-elevate-up">
                                        <i class="fas fa-book-open me-2"></i> Unduh Buku Panduan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card glass-card position-relative z-index-2 mx-auto mb-10"
                    style="width: 96%; max-width: 1200px; padding: 0.5rem; box-shadow: 0 16px 32px rgba(0, 106, 230, 0.08);">
                    <div class="row g-0">
                        <div class="col-md-4 border-md-end">
                            <div class="info-chip p-4">
                                <div class="icon-box"
                                    style="background: var(--primary-soft); color: var(--primary-color);">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <div class="text-muted fs-8 fw-bolder text-uppercase tracking-wider mb-1">Nomor Induk Mahasiswa (NIM)</div>
                                    <div class="text-main fs-4 fw-bolder">{{ auth()->user()->reference_id ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 border-md-end">
                            <div class="info-chip p-4">
                                <div class="icon-box"
                                    style="background: var(--warning-soft); color: var(--warning-color);">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="text-muted fs-8 fw-bolder text-uppercase tracking-wider mb-1">Fakultas</div>
                                    <div class="text-main fs-4 fw-bolder text-truncate">
                                        {{ auth()->user()->mahasiswa?->prodi?->fakultas?->nama_fakultas ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-chip p-4">
                                <div class="icon-box" style="background: var(--success-soft); color: var(--success-color);">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <div class="text-muted fs-8 fw-bolder text-uppercase tracking-wider mb-1">Program Studi</div>
                                    <div class="text-main fs-4 fw-bolder text-truncate">
                                        {{ auth()->user()->mahasiswa?->prodi?->nama_prodi ?? '-' }}
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
                                        <div class="symbol-label bg-light-success rounded-3">
                                            <i class="fas fa-flask text-success fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-success fw-bolder fs-4 mb-1">Surat Izin Penelitian</span>
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
                                        <div class="symbol-label bg-light-info rounded-3">
                                            <i class="fas fa-eye text-info fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-info fw-bolder fs-4 mb-1">Permohonan Observasi</span>
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
                                        <div class="symbol-label bg-light-warning rounded-3">
                                            <i class="fas fa-thumbs-up text-warning fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-warning fw-bolder fs-4 mb-1">Surat Rekomendasi</span>
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
                                        <div class="symbol-label bg-light-danger rounded-3">
                                            <i class="fas fa-briefcase text-danger fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-danger fw-bolder fs-4 mb-1">Surat Permohonan PKL</span>
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
                                        <div class="symbol-label bg-light-dark rounded-3">
                                            <i class="fas fa-graduation-cap text-dark fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 text-hover-dark fw-bolder fs-4 mb-1">Surat Keterangan Lulus</span>
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
