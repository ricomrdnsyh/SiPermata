@extends('layout.main')
@section('title', 'Surat Izin Penelitian')

@section('css')
    <style>
        .hero-banner {
            position: relative;
            background: linear-gradient(135deg, #071e3d 0%, #0e3b75 50%, #004289 100%);
            border-radius: 0.85rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 18px rgba(0, 47, 108, 0.12);
        }

        .hero-banner-shape {
            position: absolute;
            right: -30px;
            bottom: -40px;
            font-size: 200px;
            color: #ffffff;
            opacity: 0.04;
            pointer-events: none;
            user-select: none;
            line-height: 1;
        }

        .hero-stat-chip {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 0.55rem;
            padding: 0.45rem 0.85rem;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.825rem;
            transition: all 0.2s ease;
        }

        .hero-stat-chip:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px);
        }

        .btn-hero-cta {
            background: #ffffff !important;
            color: #004289 !important;
            border-radius: 0.65rem !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12) !important;
            border: none !important;
            transition: all 0.2s ease !important;
            letter-spacing: 0.3px;
        }

        .btn-hero-cta:hover {
            background: #f8fafc !important;
            color: #002654 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18) !important;
        }

        .btn-hero-cta i {
            color: #004289 !important;
            transition: transform 0.25s ease;
        }

        .btn-hero-cta:hover i {
            transform: rotate(90deg);
        }

        .filter-scroll {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 2px;
            -ms-overflow-style: none;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .filter-scroll::-webkit-scrollbar {
            display: none;
        }

        .filter-pill {
            border-radius: 0.55rem;
            padding: 0.5rem 1rem;
            font-size: 0.825rem;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            flex-shrink: 0;
            user-select: none;
        }

        .filter-pill:hover {
            background: #f8fafc;
            color: #004289;
            border-color: #cbd5e1;
        }

        .filter-pill.active {
            background: #004289 !important;
            color: #ffffff !important;
            border-color: #004289 !important;
            box-shadow: 0 2px 8px rgba(0, 66, 137, 0.2);
        }

        .filter-pill .badge {
            font-size: 0.725rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.4rem;
            transition: all 0.2s ease;
        }

        .filter-pill.active .badge {
            background: rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
        }

        .search-control-custom {
            border-radius: 0.55rem !important;
            height: 40px !important;
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            transition: all 0.2s ease !important;
        }

        .search-control-custom:focus {
            background: #ffffff !important;
            border-color: #004289 !important;
            box-shadow: 0 0 0 3px rgba(0, 66, 137, 0.12) !important;
        }

        .letter-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.85rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .letter-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .letter-card .letter-stripe {
            height: 5px;
            width: 100%;
        }

        .stripe-pengajuan {
            background: linear-gradient(90deg, #ffc107, #fd7e14);
        }

        .stripe-proses {
            background: linear-gradient(90deg, #0dcaf0, #0d6efd);
        }

        .stripe-diterima {
            background: linear-gradient(90deg, #198754, #20c997);
        }

        .stripe-ditolak {
            background: linear-gradient(90deg, #dc3545, #b02a37);
        }

        .letter-watermark {
            position: absolute;
            right: 15px;
            bottom: 30px;
            font-size: 130px;
            opacity: 0.03;
            color: #000;
            pointer-events: none;
            user-select: none;
            line-height: 1;
        }

        .stamp-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 0.55rem;
            border: 1.5px dashed currentColor;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .stamp-pengajuan {
            color: #b45309;
            background: #fffbeb;
            border-color: #f59e0b;
        }

        .stamp-proses {
            color: #0369a1;
            background: #f0f9ff;
            border-color: #0284c7;
        }

        .stamp-diterima {
            color: #15803d;
            background: #f0fdf4;
            border-color: #16a34a;
        }

        .stamp-ditolak {
            color: #b91c1c;
            background: #fef2f2;
            border-color: #dc2626;
        }

        .timeline-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin: 1rem 0 0.5rem;
            padding: 0 0.5rem;
        }

        .timeline-steps::before {
            content: "";
            position: absolute;
            top: 13px;
            left: 24px;
            right: 24px;
            height: 2px;
            background: #e2e8f0;
            z-index: 1;
        }

        .step-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.68rem;
            font-weight: 600;
            color: #94a3b8;
            text-align: center;
        }

        .step-dot {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: #64748b;
            margin-bottom: 0.35rem;
            transition: all 0.2s ease;
        }

        .step-item.completed .step-dot {
            background: #198754;
            border-color: #198754;
            color: #ffffff;
        }

        .step-item.active .step-dot {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
        }

        .step-item.active {
            color: #0d6efd;
            font-weight: 700;
        }

        .step-item.completed {
            color: #198754;
        }

        .step-item.rejected .step-dot {
            background: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.2);
        }

        .step-item.rejected {
            color: #dc3545;
            font-weight: 700;
        }

        .keperluan-box {
            background: #f8fafc;
            border-left: 3px solid #004289;
            padding: 0.65rem 0.85rem;
            border-radius: 0 0.55rem 0.55rem 0;
            font-size: 0.85rem;
            color: #334155;
            line-height: 1.45;
        }

        .kop-ornament {
            border-top: 2px solid #004289;
            border-bottom: 1px solid #004289;
            height: 4px;
            margin: 0.75rem 0;
            opacity: 0.7;
        }

        .letter-card-item {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .pagination .page-item .page-link {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.55rem;
            margin: 0 3px;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .pagination .page-item.active .page-link {
            background-color: #004289 !important;
            border-color: #004289 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0, 66, 137, 0.2);
        }

        .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #f1f5f9;
            color: #004289;
            border-color: #cbd5e1;
        }

        .pagination .page-item.disabled .page-link {
            opacity: 0.45;
            background-color: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
            pointer-events: none;
        }

        @media (max-width: 767.98px) {
            .hero-banner {
                padding: 1.5rem 1.25rem !important;
            }

            .hero-banner h1 {
                font-size: 1.45rem !important;
            }

            .hero-banner-shape {
                font-size: 130px !important;
                right: -10px !important;
                bottom: -15px !important;
            }

            .hero-stat-chip {
                padding: 0.35rem 0.65rem;
                font-size: 0.75rem;
            }

            .filter-pill {
                padding: 0.4rem 0.85rem;
                font-size: 0.775rem;
            }

            .filter-pill .badge {
                font-size: 0.675rem;
                padding: 0.2rem 0.45rem;
            }

            .search-control-custom {
                height: 38px !important;
                font-size: 0.825rem !important;
            }

            .letter-card .card-body {
                padding: 1.15rem !important;
            }

            .timeline-steps {
                padding: 0 0.15rem;
            }

            .timeline-steps::before {
                top: 11px;
                left: 14px;
                right: 14px;
            }

            .step-dot {
                width: 22px;
                height: 22px;
                font-size: 0.6rem;
                margin-bottom: 0.25rem;
            }

            .step-item {
                font-size: 0.6rem;
                max-width: 58px;
                line-height: 1.15;
            }

            .step-item span {
                word-break: break-word;
            }

            #no-match-alert .card,
            .empty-state-card {
                min-height: 290px !important;
                padding: 2.5rem 1.25rem !important;
            }

            #no-match-alert .symbol-85px {
                width: 68px !important;
                height: 68px !important;
            }

            #no-match-alert .symbol-85px i {
                font-size: 2.2rem !important;
            }

            .pagination .page-link {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.775rem !important;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .hero-banner {
                padding: 2rem 1.75rem !important;
            }

            .hero-banner h1 {
                font-size: 1.75rem !important;
            }

            .hero-banner-shape {
                font-size: 170px !important;
                right: 10px !important;
                bottom: -20px !important;
            }

            .letter-card .card-body {
                padding: 1.35rem !important;
            }

            .step-item {
                font-size: 0.65rem;
                max-width: 68px;
            }
        }

        [data-bs-theme="dark"] .border-gray-400,
        [data-theme="dark"] .border-gray-400 {
            border-color: #363843 !important;
        }

        [data-bs-theme="dark"] .filter-pill,
        [data-theme="dark"] .filter-pill {
            background: #26272f !important;
            border-color: #363843 !important;
            color: #b5b7c8 !important;
        }

        [data-bs-theme="dark"] .filter-pill:hover,
        [data-theme="dark"] .filter-pill:hover {
            background: #2b2c36 !important;
            border-color: #4b4c56 !important;
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .filter-pill.active,
        [data-theme="dark"] .filter-pill.active {
            background: #006ae6 !important;
            border-color: #006ae6 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0, 106, 230, 0.35);
        }

        [data-bs-theme="dark"] .filter-pill .badge.bg-white,
        [data-theme="dark"] .filter-pill .badge.bg-white {
            background: #1e1e2d !important;
            color: #76b5ff !important;
        }

        [data-bs-theme="dark"] .filter-pill.active .badge,
        [data-theme="dark"] .filter-pill.active .badge {
            background: rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .search-control-custom,
        [data-theme="dark"] .search-control-custom {
            background: #1b1c22 !important;
            border-color: #363843 !important;
            color: #f5f5f5 !important;
        }

        [data-bs-theme="dark"] .search-control-custom:focus,
        [data-theme="dark"] .search-control-custom:focus {
            background: #1e1e2d !important;
            border-color: #006ae6 !important;
            box-shadow: 0 0 0 3px rgba(0, 106, 230, 0.25) !important;
        }

        [data-bs-theme="dark"] .search-control-custom::placeholder,
        [data-theme="dark"] .search-control-custom::placeholder {
            color: #636674 !important;
        }

        [data-bs-theme="dark"] #btn-clear-search,
        [data-theme="dark"] #btn-clear-search {
            color: #636674 !important;
        }

        [data-bs-theme="dark"] #btn-clear-search:hover,
        [data-theme="dark"] #btn-clear-search:hover {
            color: #f5f5f5 !important;
        }

        [data-bs-theme="dark"] .letter-card,
        [data-theme="dark"] .letter-card {
            background: #1e1e2d !important;
            border-color: #2b2c36 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        }

        [data-bs-theme="dark"] .letter-card:hover,
        [data-theme="dark"] .letter-card:hover {
            border-color: #3b3c49 !important;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.45) !important;
        }

        [data-bs-theme="dark"] .letter-watermark,
        [data-theme="dark"] .letter-watermark {
            color: #ffffff !important;
            opacity: 0.03 !important;
        }

        [data-bs-theme="dark"] .letter-card .symbol.bg-light-primary,
        [data-theme="dark"] .letter-card .symbol.bg-light-primary {
            background: rgba(0, 106, 230, 0.15) !important;
        }

        [data-bs-theme="dark"] .kop-ornament,
        [data-theme="dark"] .kop-ornament {
            border-top: 2px solid #3699ff !important;
            border-bottom: 1px solid #3699ff !important;
            opacity: 0.55 !important;
        }

        [data-bs-theme="dark"] .stamp-pengajuan {
            color: #ffc700 !important;
            background: rgba(255, 199, 0, 0.1) !important;
            border-color: #ffc700 !important;
        }

        [data-bs-theme="dark"] .stamp-proses {
            color: #009ef7 !important;
            background: rgba(0, 158, 247, 0.1) !important;
            border-color: #009ef7 !important;
        }

        [data-bs-theme="dark"] .stamp-diterima {
            color: #50cd89 !important;
            background: rgba(80, 205, 137, 0.1) !important;
            border-color: #50cd89 !important;
        }

        [data-bs-theme="dark"] .stamp-ditolak {
            color: #f1416c !important;
            background: rgba(241, 65, 108, 0.1) !important;
            border-color: #f1416c !important;
        }

        [data-bs-theme="dark"] .keperluan-box,
        [data-theme="dark"] .keperluan-box {
            background: #181924 !important;
            border-left-color: #3699ff !important;
            color: #b5b7c8 !important;
        }

        [data-bs-theme="dark"] .keperluan-box .text-dark,
        [data-theme="dark"] .keperluan-box .text-dark {
            color: #e2e8f0 !important;
        }

        [data-bs-theme="dark"] .alert.bg-light-warning,
        [data-theme="dark"] .alert.bg-light-warning {
            background: rgba(255, 199, 0, 0.08) !important;
            border-color: rgba(255, 199, 0, 0.35) !important;
        }

        [data-bs-theme="dark"] .alert.bg-light-danger,
        [data-theme="dark"] .alert.bg-light-danger {
            background: rgba(241, 65, 108, 0.08) !important;
            border-color: rgba(241, 65, 108, 0.35) !important;
        }

        [data-bs-theme="dark"] .alert .text-gray-800,
        [data-theme="dark"] .alert .text-gray-800 {
            color: #d1d5db !important;
        }

        [data-bs-theme="dark"] .timeline-steps::before,
        [data-theme="dark"] .timeline-steps::before {
            background: #323340 !important;
        }

        [data-bs-theme="dark"] .step-dot,
        [data-theme="dark"] .step-dot {
            background: #181924 !important;
            border-color: #363843 !important;
            color: #636674 !important;
        }

        [data-bs-theme="dark"] .step-item,
        [data-theme="dark"] .step-item {
            color: #636674 !important;
        }

        [data-bs-theme="dark"] .step-item.active .step-dot,
        [data-theme="dark"] .step-item.active .step-dot {
            background: #006ae6 !important;
            border-color: #006ae6 !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(0, 106, 230, 0.25) !important;
        }

        [data-bs-theme="dark"] .step-item.active,
        [data-theme="dark"] .step-item.active {
            color: #3699ff !important;
        }

        [data-bs-theme="dark"] .step-item.completed .step-dot,
        [data-theme="dark"] .step-item.completed .step-dot {
            background: #50cd89 !important;
            border-color: #50cd89 !important;
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .step-item.completed,
        [data-theme="dark"] .step-item.completed {
            color: #50cd89 !important;
        }

        [data-bs-theme="dark"] .step-item.rejected .step-dot,
        [data-theme="dark"] .step-item.rejected .step-dot {
            background: #f1416c !important;
            border-color: #f1416c !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(241, 65, 108, 0.25) !important;
        }

        [data-bs-theme="dark"] .step-item.rejected,
        [data-theme="dark"] .step-item.rejected {
            color: #f1416c !important;
        }

        [data-bs-theme="dark"] .separator.separator-dashed,
        [data-theme="dark"] .separator.separator-dashed {
            border-bottom-color: #2b2c36 !important;
        }

        [data-bs-theme="dark"] .btn-light-primary,
        [data-theme="dark"] .btn-light-primary {
            background: rgba(0, 106, 230, 0.15) !important;
            color: #3699ff !important;
        }

        [data-bs-theme="dark"] .btn-light-primary:hover,
        [data-theme="dark"] .btn-light-primary:hover {
            background: #006ae6 !important;
            color: #ffffff !important;
        }

        #no-match-alert .card {
            background: #ffffff;
        }

        [data-bs-theme="dark"] #no-match-alert .card,
        [data-theme="dark"] #no-match-alert .card,
        [data-bs-theme="dark"] .empty-state-card,
        [data-theme="dark"] .empty-state-card {
            background: #1e1e2d !important;
            border-color: #363843 !important;
        }

        [data-bs-theme="dark"] #no-match-alert .symbol-85px,
        [data-theme="dark"] #no-match-alert .symbol-85px {
            background: rgba(255, 199, 0, 0.12) !important;
        }

        [data-bs-theme="dark"] .empty-state-card .symbol-circle,
        [data-theme="dark"] .empty-state-card .symbol-circle {
            background: rgba(0, 106, 230, 0.15) !important;
        }

        [data-bs-theme="dark"] .pagination .page-link,
        [data-theme="dark"] .pagination .page-link {
            background-color: #1e1e2d !important;
            border-color: #363843 !important;
            color: #b5b7c8 !important;
        }

        [data-bs-theme="dark"] .pagination .page-item.active .page-link,
        [data-theme="dark"] .pagination .page-item.active .page-link {
            background-color: #006ae6 !important;
            border-color: #006ae6 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0, 106, 230, 0.35);
        }

        [data-bs-theme="dark"] .pagination .page-item:not(.active):not(.disabled) .page-link:hover,
        [data-theme="dark"] .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #26272f !important;
            color: #ffffff !important;
            border-color: #4b4c56 !important;
        }

        [data-bs-theme="dark"] .pagination .page-item.disabled .page-link,
        [data-theme="dark"] .pagination .page-item.disabled .page-link {
            background-color: #181924 !important;
            border-color: #2b2c36 !important;
            color: #4b4c56 !important;
            opacity: 0.4;
        }

        [data-bs-theme="dark"] #pagination-info,
        [data-theme="dark"] #pagination-info {
            color: #92929f !important;
        }

        [data-bs-theme="dark"] #pagination-info .text-primary,
        [data-theme="dark"] #pagination-info .text-primary {
            color: #3699ff !important;
        }
    </style>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-5">
                <div id="kt_app_content_container" class="app-container container-fluid">

                    <div class="hero-banner p-6 p-lg-8 mb-6" style="border-radius: 0.85rem;">
                        <i class="fas fa-flask hero-banner-shape"></i>

                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-5 position-relative"
                            style="z-index: 2;">
                            <div>
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded mb-3"
                                    style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);">
                                    <i class="fas fa-certificate text-warning fs-8"></i>
                                    <span class="text-white fw-bold fs-8 text-uppercase ls-1">Portal Surat Mahasiswa</span>
                                </div>
                                <h1 class="text-white fw-bolder fs-2x mb-2">Surat Izin Penelitian</h1>
                                <p class="text-white text-opacity-80 fs-7 mb-4" style="max-width: 650px; line-height: 1.5;">
                                    Layanan permohonan izin penelitian dan pengambilan data skripsi atau tugas akhir ke instansi, lembaga, atau perusahaan mitra.
                                </p>

                                <div class="d-flex flex-wrap gap-2 pt-1">
                                    <div class="hero-stat-chip" title="Total seluruh pengajuan surat Anda">
                                        <i class="fas fa-file-invoice text-white opacity-75"></i>
                                        <span>Total: <strong>{{ $stats['total'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Sedang dalam proses verifikasi BAK & Dekan">
                                        <i class="fas fa-hourglass-half text-warning"></i>
                                        <span>Proses: <strong>{{ $stats['pengajuan'] + $stats['proses'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Surat telah disetujui & selesai">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>Selesai: <strong>{{ $stats['selesai'] ?? $stats['diterima'] }}</strong></span>
                                    </div>
                                    @if ($stats['ditolak'] > 0)
                                        <div class="hero-stat-chip border-danger"
                                            style="background: rgba(220, 53, 69, 0.25);"
                                            title="Pengajuan ditolak / perlu revisi">
                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                            <span>Perlu Revisi: <strong>{{ $stats['ditolak'] }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center w-100 w-lg-auto">
                                <a href="{{ route('mahasiswa.surat-izin-penelitian.create') }}"
                                    class="btn btn-hero-cta w-100 w-lg-auto px-6 py-3 fs-6 d-inline-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-plus fs-5"></i>
                                    <span>Ajukan Surat Baru</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border border-dashed border-gray-400 mb-6" style="border-radius: 0.85rem;">
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-4 align-items-center">
                                <div class="col-12 col-xl-8">
                                    <div class="filter-scroll" id="filter-pills-container">
                                        <button type="button" class="filter-pill active" data-filter="all">
                                            <i class="fas fa-layer-group fs-8"></i>
                                            <span>Semua</span>
                                            <span class="badge bg-white text-primary">{{ $stats['total'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="pengajuan">
                                            <i class="fas fa-clock fs-8 text-warning"></i>
                                            <span>Menunggu BAK</span>
                                            <span class="badge bg-warning text-dark">{{ $stats['pengajuan'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="proses">
                                            <i class="fas fa-file-signature fs-8 text-info"></i>
                                            <span>Menunggu Dekan</span>
                                            <span class="badge bg-info text-white">{{ $stats['proses'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="selesai">
                                            <i class="fas fa-check-circle fs-8 text-success"></i>
                                            <span>Selesai</span>
                                            <span class="badge bg-success text-white">{{ $stats['selesai'] ?? $stats['diterima'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="ditolak">
                                            <i class="fas fa-times-circle fs-8 text-danger"></i>
                                            <span>Ditolak</span>
                                            <span class="badge bg-danger text-white">{{ $stats['ditolak'] }}</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4">
                                    <div class="position-relative w-100">
                                        <i class="fas fa-search position-absolute top-50 translate-middle-y ms-4 text-gray-400 fs-7"></i>
                                        <input type="text" id="live-search-input"
                                            class="form-control form-control-sm search-control-custom ps-10 pe-10"
                                            placeholder="Cari judul / mitra / nomor surat..." autocomplete="off">
                                        <span id="btn-clear-search" role="button"
                                            class="position-absolute top-50 end-0 translate-middle-y me-3 text-gray-400 hover-primary fs-7 d-none"
                                            title="Hapus pencarian">
                                            <i class="fas fa-times-circle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($suratPenelitian->count() > 0)
                        <div class="row g-5 mb-8" id="cards-container">
                            @foreach ($suratPenelitian as $item)
                                @php
                                    $isSelesai = in_array($item->status, ['diterima', 'selesai']);
                                    $filterStatus = $isSelesai ? 'selesai' : $item->status;
                                    $statusClass = match ($item->status) {
                                        'pengajuan' => 'pengajuan',
                                        'proses' => 'proses',
                                        'diterima', 'selesai' => 'diterima',
                                        'ditolak' => 'ditolak',
                                        default => 'pengajuan',
                                    };
                                    $createdAt = \Carbon\Carbon::parse($item->created_at)->locale('id');
                                    $searchMetadata = strtolower(
                                        ($item->judul_penelitian ?? '') .
                                            ' ' .
                                            ($item->no_surat ?? '') .
                                            ' ' .
                                            ($item->mitra?->nama_mitra ?? '') .
                                            ' ' .
                                            ($item->akademik?->tahun_akademik ?? ''),
                                    );
                                @endphp

                                <div class="col-12 col-md-6 col-xl-4 letter-card-item" data-status="{{ $filterStatus }}"
                                    data-search="{{ $searchMetadata }}">
                                    <div class="letter-card">
                                        <div class="letter-stripe stripe-{{ $statusClass }}"></div>

                                        <i class="fas fa-flask letter-watermark"></i>

                                        <div class="card-body p-5 d-flex flex-column h-100">
                                            <div class="d-flex align-items-start justify-content-between flex-wrap flex-sm-nowrap gap-2 mb-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="symbol symbol-40px symbol-circle bg-light-primary d-flex align-items-center justify-content-center flex-shrink-0">
                                                        <i class="fas fa-flask text-primary fs-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bolder text-gray-900 fs-6">Surat Izin Penelitian</div>
                                                        <span class="badge badge-light-primary fw-bold fs-8 mt-1 rounded">
                                                            <i class="fas fa-building text-primary me-1 fs-9"></i>
                                                            {{ $item->mitra?->nama_mitra ?: 'Mitra Terdaftar' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex-shrink-0">
                                                    @if ($item->status === 'pengajuan')
                                                        <span class="stamp-badge stamp-pengajuan" data-bs-toggle="tooltip"
                                                            title="Berkas sedang dalam antrean verifikasi staf BAK">
                                                            <i class="fas fa-clock fs-9"></i> Menunggu BAK
                                                        </span>
                                                    @elseif($item->status === 'proses')
                                                        <span class="stamp-badge stamp-proses" data-bs-toggle="tooltip"
                                                            title="Berkas disetujui BAK dan menunggu tanda tangan Dekan">
                                                            <i class="fas fa-file-signature fs-9"></i> Menunggu Dekan
                                                        </span>
                                                    @elseif(in_array($item->status, ['diterima', 'selesai']))
                                                        <span class="stamp-badge stamp-diterima" data-bs-toggle="tooltip"
                                                            title="Surat telah disetujui dan siap diunduh">
                                                            <i class="fas fa-check-circle fs-9"></i> Selesai
                                                        </span>
                                                    @elseif($item->status === 'ditolak')
                                                        <span class="stamp-badge stamp-ditolak" data-bs-toggle="tooltip"
                                                            title="Pengajuan ditolak. Silakan baca catatan dan lakukan perbaikan">
                                                            <i class="fas fa-times-circle fs-9"></i> Ditolak
                                                        </span>
                                                    @else
                                                        <span class="stamp-badge stamp-pengajuan">{{ ucfirst($item->status) }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="kop-ornament"></div>

                                            <div class="d-flex flex-column gap-2 mb-3">
                                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center fs-8 text-muted gap-1">
                                                    <span>No. Surat Resmi:</span>
                                                    <span class="fw-bold text-gray-800 text-break font-monospace">
                                                        {{ $item->no_surat ?: 'Nomor belum diterbitkan' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between fs-8 text-muted">
                                                    <span>Tahun Akademik:</span>
                                                    <span class="fw-bold text-gray-800">
                                                        <i class="fas fa-graduation-cap text-primary me-1 fs-9"></i>
                                                        {{ $item->akademik?->tahun_akademik ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between fs-8 text-muted">
                                                    <span>Periode Penelitian:</span>
                                                    <span class="fw-bold text-gray-800">
                                                        <i class="far fa-calendar-check text-primary me-1 fs-9"></i>
                                                        {{ $item->tgl_mulai ? \Carbon\Carbon::parse($item->tgl_mulai)->locale('id')->isoFormat('D MMM Y') . ' s/d ' . ($item->tgl_selesai ? \Carbon\Carbon::parse($item->tgl_selesai)->locale('id')->isoFormat('D MMM Y') : '-') : '-' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between fs-8 text-muted">
                                                    <span>Tanggal Pengajuan:</span>
                                                    <span class="fw-bold text-gray-800"
                                                        title="{{ $createdAt->isoFormat('D MMMM YYYY, HH:mm') }} WIB">
                                                        <i class="far fa-calendar-alt text-gray-500 me-1 fs-9"></i>
                                                        {{ $createdAt->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="text-muted fs-8 fw-semibold mb-1">Judul Penelitian:</div>
                                                <div class="keperluan-box">
                                                    <span class="text-dark fw-normal">"{{ $item->judul_penelitian }}"</span>
                                                </div>
                                            </div>

                                            @if ($item->catatan)
                                                <div class="alert {{ $item->status === 'ditolak' ? 'alert-danger bg-light-danger border-danger' : 'alert-warning bg-light-warning border-warning' }} border border-dashed p-3 mb-3 d-flex align-items-start gap-2"
                                                    style="border-radius: 0.55rem; font-size: 0.8rem; border-style: dashed !important; border-width: 1.5px !important;">
                                                    <i class="fas {{ $item->status === 'ditolak' ? 'fa-exclamation-triangle text-danger' : 'fa-info-circle text-warning' }} mt-1"></i>
                                                    <div>
                                                        <div class="fw-bold {{ $item->status === 'ditolak' ? 'text-danger' : 'text-warning' }}">
                                                            {{ $item->status === 'ditolak' ? 'Alasan Penolakan:' : 'Catatan Petugas:' }}
                                                        </div>
                                                        <div class="text-gray-800 mt-1">{{ $item->catatan }}</div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="mt-auto pt-2">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <span class="text-muted fs-8 fw-bold text-uppercase ls-1">Alur Persetujuan</span>
                                                    <span class="fs-9 fw-semibold text-gray-500">
                                                        @if ($item->status === 'pengajuan')
                                                            Langkah 2 dari 4 (BAK)
                                                        @elseif($item->status === 'proses')
                                                            Langkah 3 dari 4 (Dekan)
                                                        @elseif(in_array($item->status, ['diterima', 'selesai']))
                                                            Selesai (4 dari 4)
                                                        @elseif($item->status === 'ditolak')
                                                            Perlu Revisi
                                                        @endif
                                                    </span>
                                                </div>

                                                <div class="timeline-steps">
                                                    <div class="step-item completed">
                                                        <div class="step-dot" data-bs-toggle="tooltip" title="Pengajuan telah dikirim">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <span>Diajukan</span>
                                                    </div>

                                                    @if ($item->status === 'pengajuan')
                                                        <div class="step-item active">
                                                            <div class="step-dot" data-bs-toggle="tooltip" title="Sedang diverifikasi BAK">
                                                                <i class="fas fa-hourglass-half"></i>
                                                            </div>
                                                            <span>Verifikasi BAK</span>
                                                        </div>
                                                    @elseif(in_array($item->status, ['proses', 'diterima', 'selesai']))
                                                        <div class="step-item completed">
                                                            <div class="step-dot" data-bs-toggle="tooltip" title="Disetujui oleh BAK">
                                                                <i class="fas fa-check"></i>
                                                            </div>
                                                            <span>Verifikasi BAK</span>
                                                        </div>
                                                    @elseif($item->status === 'ditolak')
                                                        <div class="step-item rejected">
                                                            <div class="step-dot" data-bs-toggle="tooltip" title="Ditolak pada tahap verifikasi">
                                                                <i class="fas fa-times"></i>
                                                            </div>
                                                            <span>Verifikasi BAK</span>
                                                        </div>
                                                    @endif

                                                    @if ($item->status === 'proses')
                                                        <div class="step-item active">
                                                            <div class="step-dot" data-bs-toggle="tooltip" title="Sedang menunggu tanda tangan Dekan">
                                                                <i class="fas fa-pen-nib"></i>
                                                            </div>
                                                            <span>TTD Dekan</span>
                                                        </div>
                                                    @elseif(in_array($item->status, ['diterima', 'selesai']))
                                                        <div class="step-item completed">
                                                            <div class="step-dot" data-bs-toggle="tooltip" title="Telah ditandatangani Dekan">
                                                                <i class="fas fa-check"></i>
                                                            </div>
                                                            <span>TTD Dekan</span>
                                                        </div>
                                                    @else
                                                        <div class="step-item">
                                                            <div class="step-dot">
                                                                <i class="fas fa-circle fs-9 text-muted opacity-50"></i>
                                                            </div>
                                                            <span>TTD Dekan</span>
                                                        </div>
                                                    @endif

                                                    @if (in_array($item->status, ['diterima', 'selesai']))
                                                        <div class="step-item completed">
                                                            <div class="step-dot" data-bs-toggle="tooltip" title="Surat selesai diterbitkan">
                                                                <i class="fas fa-check-double"></i>
                                                            </div>
                                                            <span>Selesai</span>
                                                        </div>
                                                    @else
                                                        <div class="step-item">
                                                            <div class="step-dot">
                                                                <i class="fas fa-circle fs-9 text-muted opacity-50"></i>
                                                            </div>
                                                            <span>Selesai</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="separator separator-dashed my-4"></div>

                                            <div class="d-flex align-items-center justify-content-between gap-2">
                                                <a href="{{ route('mahasiswa.surat-izin-penelitian.show', $item->id_surat_izin_penelitian) }}"
                                                    class="btn btn-sm btn-light-primary fw-bolder flex-fill"
                                                    style="border-radius: 0.55rem;">
                                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                                </a>

                                                @if (in_array($item->status, ['diterima', 'selesai']) && !empty($item->file_generated))
                                                    <a href="{{ route('mahasiswa.surat.view', ['tabel' => 'surat_izin_penelitian', 'id' => $item->id_surat_izin_penelitian]) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-success fw-bolder flex-fill"
                                                        style="border-radius: 0.55rem;">
                                                        <i class="fas fa-download me-1"></i> Unduh Dokumen
                                                    </a>
                                                @endif

                                                @if ($item->status === 'ditolak')
                                                    <a href="{{ route('mahasiswa.surat-izin-penelitian.edit', $item->id_surat_izin_penelitian) }}"
                                                        class="btn btn-sm btn-warning fw-bolder flex-fill"
                                                        style="border-radius: 0.55rem;">
                                                        <i class="fas fa-edit me-1"></i> Perbaiki Pengajuan
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div id="no-match-alert" class="col-12 d-none">
                                <div class="card border border-dashed border-gray-400 shadow-sm text-center d-flex flex-column align-items-center justify-content-center"
                                    style="border-radius: 0.85rem; min-height: 380px; padding: 4.5rem 2rem;">
                                    <div class="mb-5">
                                        <div class="symbol symbol-85px d-flex align-items-center justify-content-center mx-auto"
                                            style="background: #fff8dd; border-radius: 1.15rem; width: 85px; height: 85px;">
                                            <i class="fas fa-search text-warning" style="font-size: 2.75rem;"></i>
                                        </div>
                                    </div>
                                    <h3 class="fw-bolder text-gray-900 mb-2 fs-2">Tidak Ada Pengajuan yang Cocok</h3>
                                    <p class="text-muted fs-6 mb-0" style="max-width: 500px; line-height: 1.6;">
                                        Tidak ditemukan data pengajuan surat yang sesuai dengan kata kunci atau filter status yang dipilih.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row flex-stack flex-wrap gap-4 pt-2 align-items-center text-center text-sm-start" id="pagination-wrapper">
                            <div class="fs-7 fw-semibold text-gray-700" id="pagination-info">
                                Menampilkan <span id="visible-range" class="fw-bolder text-primary">1 - 6</span> dari
                                total <span id="total-count"
                                    class="fw-bolder text-primary">{{ $suratPenelitian->count() }}</span> pengajuan surat
                            </div>
                            <div id="pagination-nav"></div>
                        </div>
                    @else
                        <div class="card empty-state-card border border-dashed border-gray-400 shadow-sm mb-8" style="border-radius: 0.85rem;">
                            <div class="card-body p-10 p-lg-15 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                                <div class="mb-5">
                                    <div class="symbol symbol-100px symbol-circle bg-light-primary mx-auto d-flex align-items-center justify-content-center">
                                        <i class="fas fa-flask text-primary fs-2hx"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bolder text-gray-900 mb-2">
                                    Belum Ada Pengajuan Surat Izin Penelitian
                                </h3>
                                <p class="text-muted fs-6 mb-6" style="max-width: 500px; margin: 0 auto;">
                                    Anda belum pernah mengajukan Surat Izin Penelitian. Klik tombol di bawah untuk
                                    membuat permohonan surat baru dengan mudah.
                                </p>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="{{ route('mahasiswa.surat-izin-penelitian.create') }}"
                                        class="btn btn-primary fw-bolder px-6" style="border-radius: 0.55rem;">
                                        <i class="fas fa-plus me-2"></i>Buat Pengajuan Baru
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();

            const itemsPerPage = 6;
            let currentPage = 1;
            let currentFilter = 'all';
            let searchQuery = '';
            let matchingCards = [];

            const $cards = $('.letter-card-item');
            const $noMatchAlert = $('#no-match-alert');
            const $paginationWrapper = $('#pagination-wrapper');
            const $paginationNav = $('#pagination-nav');
            const $visibleRange = $('#visible-range');
            const $totalCount = $('#total-count');
            const $searchInput = $('#live-search-input');
            const $clearBtn = $('#btn-clear-search');
            const $filterPills = $('.filter-pill');

            function renderPagination() {
                const totalItems = matchingCards.length;
                const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;

                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;

                $cards.addClass('d-none');

                if (totalItems > 0) {
                    const startIndex = (currentPage - 1) * itemsPerPage;
                    const endIndex = Math.min(startIndex + itemsPerPage, totalItems);

                    for (let i = startIndex; i < endIndex; i++) {
                        $(matchingCards[i]).removeClass('d-none');
                    }

                    $visibleRange.text(`${startIndex + 1} - ${endIndex}`);
                    $totalCount.text(totalItems);

                    $paginationWrapper.removeClass('d-none');
                    $noMatchAlert.addClass('d-none');
                } else {
                    $visibleRange.text('0');
                    $totalCount.text('0');
                    $paginationWrapper.addClass('d-none');
                    $noMatchAlert.removeClass('d-none');
                }

                renderPaginationButtons(totalPages);
            }

            function renderPaginationButtons(totalPages) {
                $paginationNav.empty();

                if (totalPages <= 1) {
                    return;
                }

                let html = '<ul class="pagination pagination-circle mb-0">';

                const prevDisabled = currentPage === 1 ? 'disabled' : '';
                html += `
                    <li class="page-item ${prevDisabled}">
                        <a href="javascript:void(0)" class="page-link prev-page" aria-label="Sebelumnya">
                            <i class="fas fa-chevron-left fs-8"></i>
                        </a>
                    </li>
                `;

                for (let p = 1; p <= totalPages; p++) {
                    const activeClass = p === currentPage ? 'active' : '';
                    html += `
                        <li class="page-item ${activeClass}">
                            <a href="javascript:void(0)" class="page-link page-number" data-page="${p}">${p}</a>
                        </li>
                    `;
                }

                const nextDisabled = currentPage === totalPages ? 'disabled' : '';
                html += `
                    <li class="page-item ${nextDisabled}">
                        <a href="javascript:void(0)" class="page-link next-page" aria-label="Selanjutnya">
                            <i class="fas fa-chevron-right fs-8"></i>
                        </a>
                    </li>
                `;

                html += '</ul>';
                $paginationNav.html(html);
            }

            function filterCards() {
                matchingCards = [];

                $cards.each(function() {
                    const $card = $(this);
                    const status = $card.data('status');
                    const searchData = ($card.data('search') || '').toString().toLowerCase();

                    const statusMatches = (currentFilter === 'all') || (status === currentFilter);
                    const searchMatches = !searchQuery || searchData.includes(searchQuery);

                    if (statusMatches && searchMatches) {
                        matchingCards.push(this);
                    }
                });

                currentPage = 1;
                renderPagination();
            }

            $(document).on('click', '.page-number', function(e) {
                e.preventDefault();
                currentPage = parseInt($(this).data('page'));
                renderPagination();
                scrollToCards();
            });

            $(document).on('click', '.prev-page', function(e) {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    renderPagination();
                    scrollToCards();
                }
            });

            $(document).on('click', '.next-page', function(e) {
                e.preventDefault();
                const totalPages = Math.ceil(matchingCards.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderPagination();
                    scrollToCards();
                }
            });

            function scrollToCards() {
                if ($('#cards-container').length) {
                    $('html, body').animate({
                        scrollTop: $('#cards-container').offset().top - 90
                    }, 200);
                }
            }

            $filterPills.on('click', function(e) {
                e.preventDefault();
                const filter = $(this).data('filter');
                currentFilter = filter;

                $filterPills.removeClass('active');
                $filterPills.each(function() {
                    const $pill = $(this);
                    const $badge = $pill.find('.badge');
                    const pFilter = $pill.data('filter');

                    if (pFilter === filter) {
                        $pill.addClass('active');
                        $badge.removeClass(
                                'bg-secondary bg-warning bg-info bg-success bg-danger text-dark text-white text-gray-700'
                                )
                            .addClass('bg-white text-primary');
                    } else {
                        $badge.removeClass('bg-white text-primary');
                        if (pFilter === 'all') {
                            $badge.addClass('bg-secondary text-gray-700');
                        } else if (pFilter === 'pengajuan') {
                            $badge.addClass('bg-warning text-dark');
                        } else if (pFilter === 'proses') {
                            $badge.addClass('bg-info text-white');
                        } else if (pFilter === 'selesai' || pFilter === 'diterima') {
                            $badge.addClass('bg-success text-white');
                        } else if (pFilter === 'ditolak') {
                            $badge.addClass('bg-danger text-white');
                        }
                    }
                });

                filterCards();
            });

            $searchInput.on('input', function() {
                searchQuery = $(this).val().trim().toLowerCase();
                if (searchQuery.length > 0) {
                    $clearBtn.removeClass('d-none');
                } else {
                    $clearBtn.addClass('d-none');
                }
                filterCards();
            });

            $clearBtn.on('click', function() {
                $searchInput.val('');
                searchQuery = '';
                $clearBtn.addClass('d-none');
                filterCards();
                $searchInput.focus();
            });

            $(document).on('click', '#btn-reset-all', function() {
                $searchInput.val('');
                searchQuery = '';
                $clearBtn.addClass('d-none');
                $('.filter-pill[data-filter="all"]').trigger('click');
            });

            filterCards();
        });
    </script>

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, mengerti",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        </script>
    @endif

    @if ($message = Session::get('failed'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Tutup",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });
        </script>
    @endif
@endsection
