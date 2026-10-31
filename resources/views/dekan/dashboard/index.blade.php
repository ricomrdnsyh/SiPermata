@extends('layout.main')

@section('title', 'Dashboard')

@section('css')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .animate-fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-250 {
            animation-delay: 250ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        .delay-350 {
            animation-delay: 350ms;
        }

        .delay-400 {
            animation-delay: 400ms;
        }

        .delay-450 {
            animation-delay: 450ms;
        }

        .delay-500 {
            animation-delay: 500ms;
        }

        .dashboard-greeting {
            background: linear-gradient(120deg, #1A1A2E 0%, #16213E 50%, #0F3460 100%);
            border-radius: 1.25rem;
            overflow: hidden;
            position: relative;
            box-shadow: 0 15px 35px rgba(15, 52, 96, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dash-hero-pattern {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.5;
            z-index: 0;
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
            z-index: 0;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .select2-glass-simple .select2-container--bootstrap5 .select2-selection--single {
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: none !important;
            height: auto;
            pointer-events: none;
        }

        .select2-glass-simple .select2-container--bootstrap5 .select2-selection--single .select2-selection__rendered {
            color: #ffffff !important;
            font-weight: 600;
            padding-left: 0;
            padding-right: 0 !important;
        }

        .select2-glass-simple .select2-container--bootstrap5 .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        .filter-pill:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
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

        .dashboard-greeting .greeting-content {
            position: relative;
            z-index: 1;
        }

        .stat-card {
            border: none;
            border-radius: 1rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            background: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }

        [data-bs-theme="dark"] .stat-card {
            background: #1e1e2d;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-card .stat-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-icon i {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon i {
            transform: scale(1.1);
        }

        .icon-glow-primary {
            box-shadow: 0 0 15px rgba(0, 158, 247, 0.3);
        }

        .icon-glow-success {
            box-shadow: 0 0 15px rgba(80, 205, 137, 0.3);
        }

        .icon-glow-info {
            box-shadow: 0 0 15px rgba(114, 57, 234, 0.3);
        }

        .icon-glow-warning {
            box-shadow: 0 0 15px rgba(255, 199, 0, 0.3);
        }

        .icon-glow-danger {
            box-shadow: 0 0 15px rgba(241, 65, 108, 0.3);
        }

        .table-row-dashed tr {
            border-bottom: 1px dashed #e4e6ef !important;
            transition: background-color 0.2s ease;
        }

        .table-row-dashed tbody tr:hover {
            background-color: rgba(0, 158, 247, 0.02);
        }

        .table-row-dashed tbody tr:last-child {
            border-bottom: none !important;
        }

        .pintasan-btn {
            transition: all 0.3s ease;
        }

        .pintasan-btn.pintasan-primary:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.08) !important;
            border-color: var(--bs-primary) !important;
            border-style: dashed !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb), 0.15);
        }

        .pintasan-btn.pintasan-primary:hover .pintasan-title {
            color: var(--bs-primary) !important;
        }

        .pintasan-btn.pintasan-info:hover {
            background-color: rgba(var(--bs-info-rgb), 0.08) !important;
            border-color: var(--bs-info) !important;
            border-style: dashed !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(var(--bs-info-rgb), 0.15);
        }

        .pintasan-btn.pintasan-info:hover .pintasan-title {
            color: var(--bs-info) !important;
        }

        .pintasan-btn.pintasan-success:hover {
            background-color: rgba(var(--bs-success-rgb), 0.08) !important;
            border-color: var(--bs-success) !important;
            border-style: dashed !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(var(--bs-success-rgb), 0.15);
        }

        .pintasan-btn.pintasan-success:hover .pintasan-title {
            color: var(--bs-success) !important;
        }

        .pintasan-btn.pintasan-warning:hover {
            background-color: rgba(var(--bs-warning-rgb), 0.08) !important;
            border-color: var(--bs-warning) !important;
            border-style: dashed !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(var(--bs-warning-rgb), 0.15);
        }

        .pintasan-btn.pintasan-warning:hover .pintasan-title {
            color: var(--bs-warning) !important;
        }

        .pintasan-btn.pintasan-danger:hover {
            background-color: rgba(var(--bs-danger-rgb), 0.08) !important;
            border-color: var(--bs-danger) !important;
            border-style: dashed !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(var(--bs-danger-rgb), 0.15);
        }

        .pintasan-btn.pintasan-danger:hover .pintasan-title {
            color: var(--bs-danger) !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-primary:hover {
            background-color: rgba(0, 158, 247, 0.15) !important;
            border-color: rgba(0, 158, 247, 0.4) !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-primary:hover .pintasan-title {
            color: #009ef7 !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-info:hover {
            background-color: rgba(114, 57, 234, 0.15) !important;
            border-color: rgba(114, 57, 234, 0.4) !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-info:hover .pintasan-title {
            color: #7239ea !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-success:hover {
            background-color: rgba(80, 205, 137, 0.15) !important;
            border-color: rgba(80, 205, 137, 0.4) !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-success:hover .pintasan-title {
            color: #50cd89 !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-warning:hover {
            background-color: rgba(255, 199, 0, 0.15) !important;
            border-color: rgba(255, 199, 0, 0.4) !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-warning:hover .pintasan-title {
            color: #ffc700 !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-danger:hover {
            background-color: rgba(241, 65, 108, 0.15) !important;
            border-color: rgba(241, 65, 108, 0.4) !important;
        }

        [data-bs-theme="dark"] .pintasan-btn.pintasan-danger:hover .pintasan-title {
            color: #f1416c !important;
        }
    </style>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">

                    <!-- Hero Greeting Card (Tracer Style) -->
                    <div class="card dashboard-greeting shadow-sm mb-8 animate-fade-in-up delay-100 border-0 p-6 p-lg-10"
                        style="border-radius: 1.25rem;">
                        <div class="dash-hero-pattern"></div>

                        <!-- Hero Vector SVG Illustration Khusus SiPermata (Dokumen Surat Resmi, TTE Sah & QR Code) -->
                        <svg class="hero-illus d-none d-lg-block" width="410" height="380" viewBox="0 0 410 380"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="210" cy="190" r="145" fill="white" fill-opacity="0.04" />
                            <circle cx="210" cy="190" r="100" fill="white" fill-opacity="0.07" />

                            <!-- 1. Amplop Surat Digital di Latar Belakang -->
                            <g transform="translate(40, 105) rotate(-14)">
                                <rect x="0" y="20" width="165" height="115" rx="16" fill="#0f2b48"
                                    fill-opacity="0.85" stroke="rgba(255,255,255,0.22)" stroke-width="2"
                                    style="filter: drop-shadow(0 15px 25px rgba(0,0,0,0.35));" />
                                <path d="M0 20 L82 82 L165 20" fill="none" stroke="rgba(255,255,255,0.25)"
                                    stroke-width="2.5" />
                                <rect x="18" y="-12" width="130" height="75" rx="10" fill="white"
                                    fill-opacity="0.9" />
                                <rect x="32" y="4" width="60" height="6" rx="3" fill="#0056b3"
                                    fill-opacity="0.7" />
                                <rect x="32" y="16" width="85" height="4" rx="2" fill="#0e345c"
                                    fill-opacity="0.25" />
                                <rect x="32" y="26" width="70" height="4" rx="2" fill="#0e345c"
                                    fill-opacity="0.25" />
                            </g>

                            <!-- 2. Dokumen Utama Surat Resmi SiPermata -->
                            <g transform="translate(140, 38) rotate(5)">
                                <rect x="0" y="0" width="175" height="235" rx="18" fill="white"
                                    fill-opacity="0.98" style="filter: drop-shadow(0 22px 35px rgba(0,0,0,0.32));" />

                                <circle cx="34" cy="34" r="14" fill="#0056b3" />
                                <circle cx="34" cy="34" r="10" fill="#f8fafc" />
                                <circle cx="34" cy="34" r="6" fill="#0056b3" />

                                <rect x="56" y="24" width="88" height="8" rx="4" fill="#0e345c" />
                                <rect x="56" y="36" width="68" height="5" rx="2.5" fill="#64748b" />

                                <line x1="20" y1="54" x2="155" y2="54" stroke="#e2e8f0"
                                    stroke-width="2" stroke-linecap="round" />

                                <rect x="42" y="66" width="90" height="7" rx="3.5" fill="#0056b3"
                                    fill-opacity="0.85" />
                                <rect x="56" y="77" width="62" height="4" rx="2" fill="#94a3b8" />

                                <rect x="22" y="93" width="130" height="5" rx="2.5" fill="#0e345c"
                                    fill-opacity="0.2" />
                                <rect x="22" y="104" width="122" height="5" rx="2.5" fill="#0e345c"
                                    fill-opacity="0.2" />
                                <rect x="22" y="115" width="128" height="5" rx="2.5" fill="#0e345c"
                                    fill-opacity="0.2" />
                                <rect x="22" y="126" width="92" height="5" rx="2.5" fill="#0e345c"
                                    fill-opacity="0.2" />

                                <rect x="102" y="150" width="52" height="52" rx="8" fill="#f8fafc"
                                    stroke="#cbd5e1" stroke-width="1.5" />
                                <rect x="108" y="156" width="12" height="12" rx="2" fill="#0056b3" />
                                <rect x="110" y="158" width="8" height="8" rx="1" fill="#f8fafc" />
                                <rect x="112" y="160" width="4" height="4" fill="#0056b3" />

                                <rect x="136" y="156" width="12" height="12" rx="2" fill="#0056b3" />
                                <rect x="138" y="158" width="8" height="8" rx="1" fill="#f8fafc" />
                                <rect x="140" y="160" width="4" height="4" fill="#0056b3" />

                                <rect x="108" y="184" width="12" height="12" rx="2" fill="#0056b3" />
                                <rect x="110" y="186" width="8" height="8" rx="1" fill="#f8fafc" />
                                <rect x="112" y="188" width="4" height="4" fill="#0056b3" />

                                <rect x="126" y="170" width="6" height="6" rx="1" fill="#0056b3" />
                                <rect x="136" y="176" width="5" height="5" rx="1" fill="#0056b3" />
                                <rect x="126" y="184" width="5" height="5" rx="1" fill="#0056b3" />
                                <rect x="144" y="188" width="4" height="4" rx="1" fill="#0056b3" />

                                <circle cx="48" cy="174" r="18" fill="#f59e0b"
                                    style="filter: drop-shadow(0 4px 8px rgba(245, 158, 11, 0.35));" />
                                <circle cx="48" cy="174" r="14" fill="#d97706" />
                                <path d="M42 188 L38 212 L48 206 L58 212 L54 188" fill="#d97706" />
                                <path d="M44 188 L40 208 L48 203 L56 208 L52 188" fill="#f59e0b" />
                                <path d="M48 164 L50 170 L56 171 L51 175 L53 181 L48 177 L43 181 L45 175 L40 171 L46 170 Z"
                                    fill="white" />
                            </g>

                            <!-- 3. Floating TTE Verified Shield Badge -->
                            <g transform="translate(55, 230) rotate(-4)">
                                <rect x="0" y="0" width="140" height="48" rx="24" fill="white"
                                    fill-opacity="0.96" style="filter: drop-shadow(0 12px 24px rgba(0,0,0,0.22));" />
                                <circle cx="24" cy="24" r="15" fill="#10b981" />
                                <path d="M18 24 L22 28 L30 20" stroke="white" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <text x="47" y="21" fill="#0e345c" font-size="11" font-weight="700"
                                    font-family="'Poppins', sans-serif">TTE Resmi Dekan</text>
                                <text x="47" y="34" fill="#10b981" font-size="9" font-weight="600"
                                    font-family="'Poppins', sans-serif">Terverifikasi Sah</text>
                            </g>

                            <!-- 4. Stylus / Fountain Pen -->
                            <g transform="translate(325, 95) rotate(42)">
                                <rect x="0" y="0" width="14" height="115" rx="7" fill="#0056b3"
                                    style="filter: drop-shadow(0 8px 16px rgba(0,0,0,0.3));" />
                                <rect x="0" y="26" width="14" height="10" fill="#f59e0b" />
                                <rect x="2" y="40" width="10" height="52" rx="3" fill="#0e345c" />
                                <path d="M0 115 L7 138 L14 115 Z" fill="#f59e0b" />
                                <line x1="7" y1="115" x2="7" y2="132" stroke="#0e345c"
                                    stroke-width="1.5" />
                                <circle cx="7" cy="125" r="1.5" fill="#0e345c" />
                            </g>

                            <path d="M35 75 L38 87 L50 90 L38 93 L35 105 L32 93 L20 90 L32 87 Z" fill="white"
                                fill-opacity="0.9" />
                            <path d="M360 60 L363 70 L373 73 L363 76 L360 86 L357 76 L347 73 L357 70 Z" fill="white"
                                fill-opacity="0.8" />
                            <path d="M375 250 L377 258 L385 260 L377 262 L375 270 L373 262 L365 260 L373 258 Z"
                                fill="#38bdf8" fill-opacity="0.9" />
                            <circle cx="80" cy="45" r="5" fill="white" fill-opacity="0.5" />
                            <circle cx="340" cy="270" r="6" fill="white" fill-opacity="0.4" />
                        </svg>

                        <div class="position-relative" style="z-index: 1;">
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
                                $namaDekan = $user_name ?? Auth::user()->nama;
                            @endphp

                            <div class="d-flex align-items-center gap-4">
                                <div class="avatar-initial">
                                    {{ strtoupper(substr($namaDekan, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-white opacity-75 fs-5 mb-1 fw-medium tracking-wide">
                                        {{ $sapaan }},</div>
                                    <h1 class="text-white fw-bolder mb-0 display-6" style="letter-spacing: -0.5px;">
                                        {{ $namaDekan }}
                                    </h1>
                                </div>
                            </div>

                            <p class="text-white opacity-90 fs-5 mt-6 mb-6"
                                style="line-height: 1.6; max-width: 600px; font-weight: 300;">
                                Selamat datang di Dashboard Dekan SiPermata. Pantau permohonan surat mahasiswa <strong
                                    class="text-white"> Fakultas {{ $fakultas_name }}</strong>, validasi dokumen, dan
                                terbitkan persetujuan secara cepat dan terintegrasi.
                            </p>

                            <div class="d-flex flex-wrap gap-4 align-items-center position-relative" style="z-index: 10;">
                                <form method="GET" action="{{ route('dekan.dashboard') }}" id="filter_akademik_form">
                                    <div class="d-flex align-items-center text-white bg-white bg-opacity-10 px-5 py-2 rounded-pill border border-white border-opacity-25 select2-glass-simple filter-pill hover-elevate-up shadow-sm"
                                        style="backdrop-filter: blur(10px); cursor: pointer; transition: all 0.3s;"
                                        onclick="if(!event.target.closest('.select2-results')) $('#filter_akademik_id').select2('open');">
                                        <i class="fa-solid fa-filter fs-5 me-3 opacity-75"></i>
                                        <span
                                            class="fw-semibold tracking-wide me-2 opacity-75 d-none d-sm-inline">Filter:</span>
                                        <div style="min-width: 200px; max-width: 300px;">
                                            <select name="id_akademik" id="filter_akademik_id"
                                                class="form-select form-select-sm form-select-transparent text-white"
                                                data-control="select2" data-placeholder="Pilih Tahun Akademik"
                                                data-allow-clear="false">
                                                <option value="all" {{ $currentAkademikId == 'all' ? 'selected' : '' }}>
                                                    Semua
                                                    Tahun Akademik</option>
                                                @foreach ($tahunAkademikList as $id => $tahun)
                                                    <option value="{{ $id }}"
                                                        {{ $id == $currentAkademikId ? 'selected' : '' }}>
                                                        {{ $tahun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <i class="fa-solid fa-chevron-down ms-2 fs-7 opacity-75"></i>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- 6 Card Status (Gaya Stat-Card Tracer) -->
                    <div class="row g-5 g-xl-8 mb-8">
                        <!-- Total Masuk -->
                        <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-200">
                            <div class="card stat-card shadow-sm h-100 border border-dashed border-dark bg-light-secondary"
                                onclick="window.location.href='{{ route('dekan.history.index') }}'">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-body shadow-sm me-4">
                                        <i class="fa-solid fa-inbox fs-2x text-dark"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fw-semibold fs-7 mb-1">Total Masuk</div>
                                        <div class="text-gray-900 fw-bolder fs-2x">
                                            {{ number_format($globalStats['totalMasuk']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Menunggu BAK -->
                        <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-250">
                            <div class="card stat-card shadow-sm h-100 border border-dashed border-warning bg-light-warning"
                                onclick="window.location.href='{{ route('dekan.history.index', ['status' => 'pengajuan']) }}'">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-body shadow-sm me-4 icon-glow-warning">
                                        <i class="fa-solid fa-file-export fs-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fw-semibold fs-7 mb-1">Menunggu BAK</div>
                                        <div class="text-gray-900 fw-bolder fs-2x">
                                            {{ number_format($globalStats['totalPengajuan']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Menunggu Dekan -->
                        <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-300">
                            <div class="card stat-card shadow-sm h-100 border border-dashed border-primary bg-light-primary"
                                onclick="window.location.href='{{ route('dekan.history.index', ['status' => 'proses']) }}'">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-body shadow-sm me-4 icon-glow-primary">
                                        <i class="fa-solid fa-user-clock fs-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fw-semibold fs-7 mb-1">Menunggu Dekan</div>
                                        <div class="text-gray-900 fw-bolder fs-2x">
                                            {{ number_format($globalStats['totalProses']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Disetujui -->
                        <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-350">
                            <div class="card stat-card shadow-sm h-100 border border-dashed border-success bg-light-success"
                                onclick="window.location.href='{{ route('dekan.history.index', ['status' => 'diterima']) }}'">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-body shadow-sm me-4 icon-glow-success">
                                        <i class="fa-solid fa-clipboard-check fs-2x text-success"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fw-semibold fs-7 mb-1">Disetujui</div>
                                        <div class="text-gray-900 fw-bolder fs-2x">
                                            {{ number_format($globalStats['totalDiterima']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-400">
                            <div class="card stat-card shadow-sm h-100 border border-dashed border-info bg-light-info"
                                onclick="window.location.href='{{ route('dekan.history.index', ['status' => 'selesai']) }}'">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-body shadow-sm me-4 icon-glow-info">
                                        <i class="fa-solid fa-download fs-2x text-info"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fw-semibold fs-7 mb-1">Selesai</div>
                                        <div class="text-gray-900 fw-bolder fs-2x">
                                            {{ number_format($globalStats['totalSelesai']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ditolak -->
                        <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-450">
                            <div class="card stat-card shadow-sm h-100 border border-dashed border-danger bg-light-danger"
                                onclick="window.location.href='{{ route('dekan.history.index', ['status' => 'ditolak']) }}'">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-body shadow-sm me-4 icon-glow-danger">
                                        <i class="fa-solid fa-circle-xmark fs-2x text-danger"></i>
                                    </div>
                                    <div>
                                        <div class="text-gray-600 fw-semibold fs-7 mb-1">Ditolak</div>
                                        <div class="text-gray-900 fw-bolder fs-2x">
                                            {{ number_format($globalStats['totalDitolak']) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pintasan Cepat (Tracer Style) -->
                    <div class="row g-5 g-xl-8 mb-8 animate-fade-in-up delay-400">
                        <div class="col-12">
                            <div class="card shadow-sm border border-dashed border-dark" style="border-radius: 1.25rem;">
                                <div class="card-header border-0 pt-6">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-primary bg-opacity-10 me-4 icon-glow-primary p-3 rounded-3"
                                                style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa-solid fa-bolt fs-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <span class="card-label fw-bolder fs-4 text-gray-900">Pintasan Cepat</span>
                                                <span class="text-gray-600 fw-semibold fs-7 d-block mt-1">Akses cepat ke
                                                    fitur utama layanan Dekanat</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pb-6">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <a href="{{ route('dekan.history.index', ['status' => 'proses']) }}"
                                                class="btn btn-outline btn-outline-dashed pintasan-btn pintasan-primary p-7 d-flex align-items-center mb-0 w-100"
                                                style="border-radius: 1rem;">
                                                <i class="fa-solid fa-signature fs-2x me-4 text-primary"></i>
                                                <div class="text-start">
                                                    <span class="d-block fw-bold fs-5 text-gray-800 pintasan-title"
                                                        style="transition: color 0.3s ease;">Validasi & TTE</span>
                                                    <span class="d-block fw-semibold fs-7 text-gray-600 mt-1">
                                                        @if ($globalStats['totalProses'] > 0)
                                                            {{ $globalStats['totalProses'] }} berkas menunggu persetujuan
                                                        @else
                                                            Semua berkas tervalidasi
                                                        @endif
                                                    </span>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-md-6">
                                            <a href="{{ route('dekan.statistik') }}"
                                                class="btn btn-outline btn-outline-dashed pintasan-btn pintasan-info p-7 d-flex align-items-center mb-0 w-100"
                                                style="border-radius: 1rem;">
                                                <i class="fa-solid fa-chart-pie fs-2x me-4 text-info"></i>
                                                <div class="text-start">
                                                    <span class="d-block fw-bold fs-5 text-gray-800 pintasan-title"
                                                        style="transition: color 0.3s ease;">Statistik &amp; Laporan</span>
                                                    <span class="d-block fw-semibold fs-7 text-gray-600 mt-1">Lihat
                                                        analitik permohonan fakultas</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengajuan Terbaru Fakultas -->
                    <div class="card shadow-sm border border-dashed border-dark animate-fade-in-up delay-500"
                        style="border-radius: 1.25rem; animation-delay: 600ms">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div>
                                    <span class="card-label fw-bolder fs-4 text-gray-900">Pengajuan Terbaru
                                        ({{ $fakultas_name }})</span>
                                    <span class="text-gray-600 fw-semibold fs-7 d-block mt-1">Daftar permohonan surat
                                        mahasiswa
                                        fakultas yang terakhir masuk</span>
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <a href="{{ route('dekan.history.index') }}" class="btn btn-sm btn-light-primary">
                                    Lihat Semua
                                    <i class="fa-solid fa-arrow-right ms-2 fs-7"></i>
                                </a>
                            </div>
                        </div>
                        <div class="separator my-3"></div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-4">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="min-w-80px text-center">Aksi</th>
                                            <th class="min-w-150px">Mahasiswa</th>
                                            <th class="min-w-100px">NIM</th>
                                            <th class="min-w-150px d-none d-md-table-cell">Prodi</th>
                                            <th class="min-w-150px d-none d-lg-table-cell">Jenis Surat</th>
                                            <th class="min-w-140px">Tanggal Pengajuan</th>
                                            <th class="min-w-90px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-800">
                                        @forelse ($recentSubmissions as $r)
                                            <tr>
                                                <td class="text-center">
                                                    <a href="{{ route('dekan.history.detail', $r->id_history) }}"
                                                        class="btn btn-sm btn-icon btn-light btn-active-light-info"
                                                        data-bs-toggle="tooltip" title="Lihat Detail Permohonan">
                                                        <i class="fa-solid fa-eye text-primary"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px symbol-circle me-3">
                                                            <span
                                                                class="symbol-label bg-primary bg-opacity-10 text-primary fw-bolder">
                                                                {{ strtoupper(substr($r->mahasiswa?->nama ?? '?', 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div class="text-gray-800 fw-bolder">
                                                            {{ $r->mahasiswa?->nama ?? '-' }}</div>
                                                    </div>
                                                </td>
                                                <td class="text-gray-600">{{ $r->nim }}</td>
                                                <td class="d-none d-md-table-cell text-gray-600">
                                                    {{ $r->mahasiswa?->prodi?->nama_prodi ?? '-' }}
                                                </td>
                                                <td class="d-none d-lg-table-cell text-gray-600">
                                                    <span class="text-truncate d-block"
                                                        style="max-width: 220px;">{{ $r->nama_surat }}</span>
                                                </td>
                                                <td class="text-gray-600">
                                                    {{ \Carbon\Carbon::parse($r->tanggal_pengajuan_asli)->translatedFormat('d F Y, H:i') }}
                                                    WIB
                                                </td>
                                                <td>
                                                    @if ($r->status === 'pengajuan')
                                                        <span class="badge badge-warning">Menunggu BAK</span>
                                                    @elseif ($r->status === 'proses')
                                                        <span class="badge badge-info">Menunggu Dekan</span>
                                                    @elseif ($r->status === 'diterima')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @elseif ($r->status === 'selesai')
                                                        <span class="badge badge-primary">Selesai</span>
                                                    @elseif ($r->status === 'ditolak')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @else
                                                        <span
                                                            class="badge badge-secondary">{{ ucfirst($r->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-gray-500 py-8">
                                                    <i class="fa-solid fa-inbox fs-2x mb-3 d-block text-gray-300"></i>
                                                    <span class="fw-semibold">Belum ada pengajuan surat yang masuk</span>
                                                </td>
                                            </tr>
                                        @endforelse
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
    <script>
        (function() {
            var $filterEl = $('#filter_akademik_id');
            var filterForm = document.getElementById('filter_akademik_form');
            if ($filterEl.length && filterForm) {
                $filterEl.on('change', function() {
                    filterForm.submit();
                });
            }
        })();
    </script>
@endsection
