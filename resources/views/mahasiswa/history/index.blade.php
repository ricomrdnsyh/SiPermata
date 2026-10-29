@extends('layout.main')
@section('title', 'Riwayat Pengajuan Surat')

@section('css')
    <style>
        .hero-banner {
            position: relative;
            background: linear-gradient(135deg, #071e3d 0%, #0e3b75 50%, #004289 100%);
            border-radius: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 18px rgba(0, 47, 108, 0.12);
        }

        .hero-banner-shape-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            border-radius: inherit;
            pointer-events: none;
            z-index: 1;
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

        .btn-hero-cta .btn-icon-plus {
            color: #004289 !important;
            transition: transform 0.25s ease;
        }

        .btn-hero-cta:hover .btn-icon-plus {
            transform: rotate(90deg);
        }

        .btn.dropdown-toggle::after,
        .btn-hero-cta.dropdown-toggle::after {
            display: none !important;
        }

        .dropdown-menu-custom {
            border-radius: 0.85rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 14px 35px rgba(0, 30, 80, 0.15) !important;
            padding: 0.75rem !important;
            min-width: 320px !important;
            z-index: 1060 !important;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 0.65rem;
            color: #1e293b;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .dropdown-item-custom:hover {
            background-color: #f1f5f9;
            transform: translateX(3px);
            color: #004289;
        }

        .dropdown-item-custom .item-icon {
            width: 36px;
            height: 36px;
            border-radius: 0.55rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .dropdown-item-custom:hover .item-icon {
            transform: scale(1.08);
        }

        .dropdown-item-custom .item-title {
            font-weight: 700;
            font-size: 0.85rem;
            color: #1e293b;
            line-height: 1.25;
            margin-bottom: 2px;
        }

        .dropdown-item-custom:hover .item-title {
            color: #004289;
        }

        .dropdown-item-custom .item-desc {
            font-size: 0.725rem;
            color: #64748b;
            line-height: 1.2;
        }

        .dropdown-menu-header {
            padding: 0.35rem 0.85rem 0.65rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            border-bottom: 1px dashed #e2e8f0;
            margin-bottom: 0.5rem;
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
            user-select: none;
        }

        .filter-pill:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #004289;
        }

        .filter-pill.active {
            background: #004289 !important;
            border-color: #004289 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0, 66, 137, 0.25);
        }

        .filter-pill .badge {
            font-size: 0.725rem;
            padding: 0.25rem 0.55rem;
            border-radius: 0.45rem;
            font-weight: 700;
        }

        .search-control-custom {
            border-radius: 0.55rem !important;
            border: 1px solid #e2e8f0 !important;
            font-size: 0.85rem !important;
            padding-left: 2.75rem !important;
            background: #ffffff;
            transition: all 0.2s ease;
            height: 42px;
        }

        .search-control-custom:focus {
            border-color: #004289 !important;
            box-shadow: 0 0 0 3px rgba(0, 66, 137, 0.15) !important;
        }

        .letter-card {
            border-radius: 0.85rem !important;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .letter-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 47, 108, 0.08);
            border-color: #cbd5e1;
        }

        .letter-stripe {
            height: 4px;
            width: 100%;
        }

        .stripe-diterima {
            background: linear-gradient(90deg, #198754, #20c997);
        }

        .letter-watermark {
            position: absolute;
            right: 15px;
            bottom: 25px;
            font-size: 110px;
            color: #004289;
            opacity: 0.03;
            pointer-events: none;
            user-select: none;
            line-height: 1;
            z-index: 0;
        }

        .stamp-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.7rem;
            padding: 0.3rem 0.65rem;
            border-radius: 0.45rem;
            border: 1.5px solid currentColor;
        }

        .stamp-diterima {
            color: #198754;
            background: rgba(25, 135, 84, 0.08);
            border-color: #198754;
        }

        .timeline-steps {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            margin: 0.5rem 0 0.25rem;
            padding: 0 0.5rem;
        }

        .timeline-steps::before {
            content: '';
            position: absolute;
            top: 13px;
            left: 20px;
            right: 20px;
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
            text-align: center;
            font-size: 0.675rem;
            font-weight: 600;
            color: #94a3b8;
            max-width: 70px;
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
            margin-bottom: 0.35rem;
            font-size: 0.7rem;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .step-item.completed .step-dot {
            background: #198754;
            border-color: #198754;
            color: #ffffff;
        }

        .step-item.completed {
            color: #198754;
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
                width: 65px !important;
                height: 65px !important;
            }

            #no-match-alert .symbol-85px i {
                font-size: 2rem !important;
            }

            #no-match-alert h3 {
                font-size: 1.25rem !important;
            }

            #no-match-alert p {
                font-size: 0.85rem !important;
            }

            #pagination-wrapper {
                flex-direction: column !important;
                gap: 1rem !important;
            }

            #pagination-nav {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .pagination .page-item .page-link {
                min-width: 32px;
                height: 32px;
                font-size: 0.775rem;
                margin: 0 2px;
            }

            .dropdown-menu-custom {
                min-width: calc(100vw - 2.5rem) !important;
                max-width: calc(100vw - 2.5rem) !important;
            }
        }

        [data-bs-theme="dark"] .hero-banner,
        [data-theme="dark"] .hero-banner {
            background: linear-gradient(135deg, #0b1526 0%, #0d274c 50%, #083266 100%) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
        }

        [data-bs-theme="dark"] .btn-hero-cta,
        [data-theme="dark"] .btn-hero-cta {
            background: #1b283f !important;
            color: #5bb2ff !important;
            border: 1px solid rgba(91, 178, 255, 0.3) !important;
        }

        [data-bs-theme="dark"] .btn-hero-cta:hover,
        [data-theme="dark"] .btn-hero-cta:hover {
            background: #233452 !important;
            color: #89c7ff !important;
        }

        [data-bs-theme="dark"] .btn-hero-cta i,
        [data-theme="dark"] .btn-hero-cta i {
            color: #5bb2ff !important;
        }

        [data-bs-theme="dark"] .dropdown-menu,
        [data-theme="dark"] .dropdown-menu {
            background-color: #1e1e2d !important;
            border: 1px solid #363843 !important;
        }

        [data-bs-theme="dark"] .dropdown-menu-custom,
        [data-theme="dark"] .dropdown-menu-custom {
            background-color: #1e1e2d !important;
            border-color: #363843 !important;
            box-shadow: 0 14px 35px rgba(0, 0, 0, 0.5) !important;
        }

        [data-bs-theme="dark"] .dropdown-item,
        [data-theme="dark"] .dropdown-item {
            color: #b5b7c8 !important;
        }

        [data-bs-theme="dark"] .dropdown-item:hover,
        [data-theme="dark"] .dropdown-item:hover {
            background-color: #2b2c36 !important;
            color: #ffffff !important;
        }

        [data-bs-theme="dark"] .dropdown-item-custom,
        [data-theme="dark"] .dropdown-item-custom {
            color: #f5f5f5 !important;
        }

        [data-bs-theme="dark"] .dropdown-item-custom:hover,
        [data-theme="dark"] .dropdown-item-custom:hover {
            background-color: #2b2c36 !important;
            color: #5bb2ff !important;
        }

        [data-bs-theme="dark"] .dropdown-item-custom .item-title,
        [data-theme="dark"] .dropdown-item-custom .item-title {
            color: #f5f5f5 !important;
        }

        [data-bs-theme="dark"] .dropdown-item-custom:hover .item-title,
        [data-theme="dark"] .dropdown-item-custom:hover .item-title {
            color: #5bb2ff !important;
        }

        [data-bs-theme="dark"] .dropdown-item-custom .item-desc,
        [data-theme="dark"] .dropdown-item-custom .item-desc {
            color: #92929f !important;
        }

        [data-bs-theme="dark"] .dropdown-menu-header,
        [data-theme="dark"] .dropdown-menu-header {
            color: #636674 !important;
            border-bottom-color: #363843 !important;
        }

        [data-bs-theme="dark"] .filter-pill,
        [data-theme="dark"] .filter-pill {
            background: #1e1e2d !important;
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

        [data-bs-theme="dark"] .filter-pill .badge.bg-secondary,
        [data-theme="dark"] .filter-pill .badge.bg-secondary {
            background: #2b2c36 !important;
            color: #b5b7c8 !important;
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

        [data-bs-theme="dark"] .stamp-diterima,
        [data-theme="dark"] .stamp-diterima {
            color: #50cd89 !important;
            background: rgba(80, 205, 137, 0.1) !important;
            border-color: #50cd89 !important;
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
                        <div class="hero-banner-shape-wrapper">
                            <i class="fas fa-history hero-banner-shape"></i>
                        </div>

                        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-5 position-relative"
                            style="z-index: 2;">
                            <div>
                                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded mb-3"
                                    style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(4px);">
                                    <i class="fas fa-history text-warning fs-8"></i>
                                    <span class="text-white fw-bold fs-8 text-uppercase ls-1">Arsip Riwayat Pengajuan</span>
                                </div>
                                <h1 class="text-white fw-bolder fs-2x mb-2">Riwayat Pengajuan Surat</h1>
                                <p class="text-white text-opacity-80 fs-7 mb-4" style="max-width: 650px; line-height: 1.5;">
                                    Arsip seluruh permohonan surat mahasiswa yang telah selesai diproses dan disetujui.
                                    Anda dapat meninjau riwayat serta mengunduh berkas surat resmi kapan saja.
                                </p>

                                <div class="d-flex flex-wrap gap-2 pt-1">
                                    <div class="hero-stat-chip" title="Total seluruh riwayat surat selesai">
                                        <i class="fas fa-folder-open text-white opacity-75"></i>
                                        <span>Total Selesai: <strong>{{ $stats['total'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Surat Keterangan Aktif">
                                        <i class="fas fa-file-alt text-info"></i>
                                        <span>Surat Aktif: <strong>{{ $stats['surat_aktif'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Surat Izin Penelitian">
                                        <i class="fas fa-flask text-success"></i>
                                        <span>Penelitian: <strong>{{ $stats['surat_izin_penelitian'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Surat Observasi">
                                        <i class="fas fa-eye text-primary"></i>
                                        <span>Observasi: <strong>{{ $stats['surat_observasi'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Surat Rekomendasi">
                                        <i class="fas fa-award text-warning"></i>
                                        <span>Rekomendasi: <strong>{{ $stats['surat_rekomendasi'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Surat Permohonan PKL">
                                        <i class="fas fa-briefcase text-info"></i>
                                        <span>PKL: <strong>{{ $stats['surat_pkl'] }}</strong></span>
                                    </div>
                                    <div class="hero-stat-chip" title="Surat Keterangan Lulus">
                                        <i class="fas fa-graduation-cap text-danger"></i>
                                        <span>Ket. Lulus: <strong>{{ $stats['surat_keterangan_lulus'] }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center w-100 w-lg-auto">
                                <div class="dropdown w-100 w-lg-auto">
                                    <button class="btn btn-hero-cta w-100 w-lg-auto px-6 py-3 fs-6 d-inline-flex align-items-center justify-content-center gap-2 dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-offset="0,8" aria-expanded="false">
                                        <i class="fas fa-plus fs-5 btn-icon-plus"></i>
                                        <span>Ajukan Surat Baru</span>
                                        <i class="fas fa-chevron-down fs-8 ms-1"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-custom dropdown-menu-end shadow-lg border-0 py-2">
                                        <div class="dropdown-menu-header">
                                            Pilih Jenis Surat
                                        </div>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-aktif.create') }}">
                                            <div class="item-icon bg-light-primary">
                                                <i class="fas fa-file-alt text-primary"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Keterangan Aktif</span>
                                                <span class="item-desc">Kuliah aktif, tunjangan PNS/PPPK, dll.</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-izin-penelitian.create') }}">
                                            <div class="item-icon bg-light-success">
                                                <i class="fas fa-flask text-success"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Izin Penelitian</span>
                                                <span class="item-desc">Riset skripsi, tugas akhir & instansi mitra</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-observasi.create') }}">
                                            <div class="item-icon bg-light-info">
                                                <i class="fas fa-eye text-info"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Observasi</span>
                                                <span class="item-desc">Studi lapangan & observasi mata kuliah</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-rekomendasi.create') }}">
                                            <div class="item-icon bg-light-warning">
                                                <i class="fas fa-award text-warning"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Rekomendasi</span>
                                                <span class="item-desc">Lomba, beasiswa, pertukaran & kegiatan</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-pkl.create') }}">
                                            <div class="item-icon bg-light-primary">
                                                <i class="fas fa-briefcase text-primary"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Permohonan PKL</span>
                                                <span class="item-desc">Praktik Kerja Lapangan / Magang industri</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-keterangan-lulus.create') }}">
                                            <div class="item-icon bg-light-danger">
                                                <i class="fas fa-graduation-cap text-danger"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Keterangan Lulus</span>
                                                <span class="item-desc">Surat keterangan yudisium / kelulusan</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                    </div>
                                </div>
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
                                        <button type="button" class="filter-pill" data-filter="surat_aktif">
                                            <i class="fas fa-file-alt fs-8 text-primary"></i>
                                            <span>Surat Aktif</span>
                                            <span class="badge bg-secondary text-gray-700">{{ $stats['surat_aktif'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="surat_izin_penelitian">
                                            <i class="fas fa-flask fs-8 text-success"></i>
                                            <span>Penelitian</span>
                                            <span class="badge bg-secondary text-gray-700">{{ $stats['surat_izin_penelitian'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="surat_observasi">
                                            <i class="fas fa-eye fs-8 text-info"></i>
                                            <span>Observasi</span>
                                            <span class="badge bg-secondary text-gray-700">{{ $stats['surat_observasi'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="surat_rekomendasi">
                                            <i class="fas fa-award fs-8 text-warning"></i>
                                            <span>Rekomendasi</span>
                                            <span class="badge bg-secondary text-gray-700">{{ $stats['surat_rekomendasi'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="surat_pkl">
                                            <i class="fas fa-briefcase fs-8 text-primary"></i>
                                            <span>PKL</span>
                                            <span class="badge bg-secondary text-gray-700">{{ $stats['surat_pkl'] }}</span>
                                        </button>
                                        <button type="button" class="filter-pill" data-filter="surat_keterangan_lulus">
                                            <i class="fas fa-graduation-cap fs-8 text-danger"></i>
                                            <span>Ket. Lulus</span>
                                            <span class="badge bg-secondary text-gray-700">{{ $stats['surat_keterangan_lulus'] }}</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4">
                                    <div class="position-relative w-100">
                                        <i class="fas fa-search position-absolute top-50 translate-middle-y ms-4 text-gray-400 fs-7"></i>
                                        <input type="text" id="live-search-input"
                                            class="form-control form-control-sm search-control-custom ps-10 pe-10"
                                            placeholder="Cari jenis surat / nomor / keperluan..." autocomplete="off">
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

                    @if ($historyPengajuan->count() > 0)
                        <div class="row g-5 mb-8" id="cards-container">
                            @foreach ($historyPengajuan as $item)
                                @php
                                    $surat = $item->suratData ?? $item->surat;
                                    $tabel = $item->tabel;
                                    $createdAt = \Carbon\Carbon::parse($item->tanggal_pengajuan_asli ?? $item->created_at)->locale('id');

                                    $typeInfo = match ($tabel) {
                                        'surat_aktif' => [
                                            'title'           => 'Surat Keterangan Aktif',
                                            'icon'            => 'fas fa-file-alt text-primary',
                                            'watermark'       => 'fas fa-file-alt',
                                            'badge'           => 'Kategori ' . ($surat?->kategori ?? '-'),
                                            'keperluan_label' => 'Keperluan Surat:',
                                            'keperluan_val'   => $surat?->keperluan ?? '-',
                                            'extra_row_label' => null,
                                            'extra_row_val'   => null,
                                            'extra_row_icon'  => null,
                                        ],
                                        'surat_izin_penelitian' => [
                                            'title'           => 'Surat Izin Penelitian',
                                            'icon'            => 'fas fa-flask text-primary',
                                            'watermark'       => 'fas fa-flask',
                                            'badge'           => $surat?->mitra?->nama_mitra ?: 'Mitra Terdaftar',
                                            'keperluan_label' => 'Judul Penelitian:',
                                            'keperluan_val'   => $surat?->judul_penelitian ?? '-',
                                            'extra_row_label' => 'Periode Penelitian:',
                                            'extra_row_val'   => $surat?->tgl_mulai ? \Carbon\Carbon::parse($surat->tgl_mulai)->locale('id')->isoFormat('D MMM Y') . ' s/d ' . ($surat->tgl_selesai ? \Carbon\Carbon::parse($surat->tgl_selesai)->locale('id')->isoFormat('D MMM Y') : '-') : '-',
                                            'extra_row_icon'  => 'far fa-calendar-check text-primary',
                                        ],
                                        'surat_observasi' => [
                                            'title'           => 'Surat Observasi',
                                            'icon'            => 'fas fa-eye text-primary',
                                            'watermark'       => 'fas fa-eye',
                                            'badge'           => $surat?->mitra?->nama_mitra ?: 'Mitra Observasi',
                                            'keperluan_label' => 'Keperluan Observasi:',
                                            'keperluan_val'   => $surat?->keperluan ?? '-',
                                            'extra_row_label' => 'Waktu Observasi:',
                                            'extra_row_val'   => $surat?->tgl_observasi ? \Carbon\Carbon::parse($surat->tgl_observasi)->locale('id')->isoFormat('D MMMM YYYY') : '-',
                                            'extra_row_icon'  => 'far fa-calendar-alt text-primary',
                                        ],
                                        'surat_rekomendasi' => [
                                            'title'           => 'Surat Rekomendasi',
                                            'icon'            => 'fas fa-award text-primary',
                                            'watermark'       => 'fas fa-award',
                                            'badge'           => Str::limit($surat?->penyelenggara ?: 'Penyelenggara Kegiatan', 32),
                                            'keperluan_label' => 'Keperluan Surat:',
                                            'keperluan_val'   => $surat?->keperluan ?? '-',
                                            'extra_row_label' => 'Penyelenggara:',
                                            'extra_row_val'   => $surat?->penyelenggara ?: '-',
                                            'extra_row_icon'  => 'fas fa-university text-primary',
                                        ],
                                        'surat_pkl' => [
                                            'title'           => 'Surat Permohonan PKL',
                                            'icon'            => 'fas fa-briefcase text-primary',
                                            'watermark'       => 'fas fa-briefcase',
                                            'badge'           => Str::limit($surat?->mitra?->nama_mitra ?: 'Mitra Terdaftar', 26),
                                            'keperluan_label' => 'Tempat PKL / Instansi:',
                                            'keperluan_val'   => $surat?->mitra?->nama_mitra ?: 'Instansi / Perusahaan Mitra',
                                            'extra_row_label' => 'Periode PKL:',
                                            'extra_row_val'   => $surat?->tgl_mulai ? \Carbon\Carbon::parse($surat->tgl_mulai)->locale('id')->isoFormat('D MMM') . ' s/d ' . ($surat->tgl_selesai ? \Carbon\Carbon::parse($surat->tgl_selesai)->locale('id')->isoFormat('D MMM YYYY') : '-') : '-',
                                            'extra_row_icon'  => 'far fa-calendar-check text-primary',
                                        ],
                                        'surat_keterangan_lulus' => [
                                            'title'           => 'Surat Keterangan Lulus',
                                            'icon'            => 'fas fa-graduation-cap text-primary',
                                            'watermark'       => 'fas fa-graduation-cap',
                                            'badge'           => Str::limit($surat?->mahasiswa?->nama ?: 'Mahasiswa Lulusan', 30),
                                            'keperluan_label' => 'Judul Tugas Akhir / Skripsi:',
                                            'keperluan_val'   => $surat?->judul_penelitian ?? '-',
                                            'extra_row_label' => 'Tempat Lahir:',
                                            'extra_row_val'   => $surat?->tempat_lahir ?: '-',
                                            'extra_row_icon'  => 'far fa-id-card text-primary',
                                        ],
                                        default => [
                                            'title'           => $item->nama_surat,
                                            'icon'            => 'fas fa-file-contract text-primary',
                                            'watermark'       => 'fas fa-file-contract',
                                            'badge'           => 'Selesai',
                                            'keperluan_label' => 'Keterangan:',
                                            'keperluan_val'   => $surat?->keperluan ?? '-',
                                            'extra_row_label' => null,
                                            'extra_row_val'   => null,
                                            'extra_row_icon'  => null,
                                        ],
                                    };

                                    $searchMetadata = strtolower(
                                        $typeInfo['title'] .
                                            ' ' .
                                            ($surat?->no_surat ?? '') .
                                            ' ' .
                                            $typeInfo['keperluan_val'] .
                                            ' ' .
                                            $typeInfo['badge'] .
                                            ' ' .
                                            ($typeInfo['extra_row_val'] ?? '') .
                                            ' ' .
                                            ($surat?->akademik?->tahun_akademik ?? ''),
                                    );
                                @endphp

                                <div class="col-12 col-md-6 col-xl-4 letter-card-item" data-filter-type="{{ $tabel }}"
                                    data-search="{{ $searchMetadata }}">
                                    <div class="letter-card">
                                        <div class="letter-stripe stripe-diterima"></div>

                                        <i class="{{ $typeInfo['watermark'] }} letter-watermark"></i>

                                        <div class="card-body p-5 d-flex flex-column h-100">
                                            <div class="d-flex align-items-start justify-content-between flex-wrap flex-sm-nowrap gap-2 mb-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="symbol symbol-40px symbol-circle bg-light-primary d-flex align-items-center justify-content-center flex-shrink-0">
                                                        <i class="{{ $typeInfo['icon'] }} fs-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bolder text-gray-900 fs-6">{{ $typeInfo['title'] }}</div>
                                                        <span class="badge badge-light-primary fw-bold fs-8 mt-1 rounded">
                                                            {{ $typeInfo['badge'] }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex-shrink-0">
                                                    <span class="stamp-badge stamp-diterima" data-bs-toggle="tooltip"
                                                        title="Surat telah disetujui & selesai diterbitkan">
                                                        <i class="fas fa-check-circle fs-9"></i> Selesai
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="kop-ornament"></div>

                                            <div class="d-flex flex-column gap-2 mb-3">
                                                <div
                                                    class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center fs-8 text-muted gap-1">
                                                    <span>No. Surat Resmi:</span>
                                                    <span class="fw-bold text-gray-800 text-break font-monospace">
                                                        {{ $surat?->no_surat ?: 'Nomor belum diterbitkan' }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between fs-8 text-muted">
                                                    <span>Tahun Akademik:</span>
                                                    <span class="fw-bold text-gray-800">
                                                        <i class="fas fa-graduation-cap text-primary me-1 fs-9"></i>
                                                        {{ $surat?->akademik?->tahun_akademik ?? '-' }}
                                                    </span>
                                                </div>
                                                @if (!empty($typeInfo['extra_row_label']))
                                                    <div class="d-flex align-items-center justify-content-between fs-8 text-muted">
                                                        <span>{{ $typeInfo['extra_row_label'] }}</span>
                                                        <span class="fw-bold text-gray-800 text-end text-truncate ms-2"
                                                            style="max-width: 60%;" title="{{ $typeInfo['extra_row_val'] }}">
                                                            <i class="{{ $typeInfo['extra_row_icon'] ?? 'fas fa-info-circle text-primary' }} me-1 fs-9"></i>
                                                            {{ $typeInfo['extra_row_val'] }}
                                                        </span>
                                                    </div>
                                                @endif
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
                                                <div class="text-muted fs-8 fw-semibold mb-1">{{ $typeInfo['keperluan_label'] }}</div>
                                                <div class="keperluan-box">
                                                    <span class="text-dark fw-normal">"{{ $typeInfo['keperluan_val'] }}"</span>
                                                </div>
                                            </div>

                                            @if ($item->catatan)
                                                <div class="alert alert-warning bg-light-warning border-warning border border-dashed p-3 mb-3 d-flex align-items-start gap-2"
                                                    style="border-radius: 0.55rem; font-size: 0.8rem; border-style: dashed !important; border-width: 1.5px !important;">
                                                    <i class="fas fa-info-circle text-warning mt-1"></i>
                                                    <div>
                                                        <div class="fw-bold text-warning">Catatan Petugas:</div>
                                                        <div class="text-gray-800 mt-1">{{ $item->catatan }}</div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="mt-auto pt-2">
                                                <div class="d-flex align-items-center justify-content-between mb-1">
                                                    <span class="text-muted fs-8 fw-bold text-uppercase ls-1">Alur Persetujuan</span>
                                                    <span class="fs-9 fw-semibold text-gray-500">Selesai (4 dari 4)</span>
                                                </div>

                                                <div class="timeline-steps">
                                                    <div class="step-item completed">
                                                        <div class="step-dot" data-bs-toggle="tooltip" title="Pengajuan telah dikirim">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <span>Diajukan</span>
                                                    </div>

                                                    <div class="step-item completed">
                                                        <div class="step-dot" data-bs-toggle="tooltip" title="Disetujui oleh BAK">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <span>Verifikasi BAK</span>
                                                    </div>

                                                    <div class="step-item completed">
                                                        <div class="step-dot" data-bs-toggle="tooltip" title="Telah ditandatangani Dekan">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <span>TTD Dekan</span>
                                                    </div>

                                                    <div class="step-item completed">
                                                        <div class="step-dot" data-bs-toggle="tooltip" title="Surat selesai diterbitkan">
                                                            <i class="fas fa-check-double"></i>
                                                        </div>
                                                        <span>Selesai</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="separator separator-dashed my-4"></div>

                                            <div class="d-flex align-items-center justify-content-between gap-2">
                                                <a href="{{ route('mahasiswa.history.detail', $item->id_history) }}"
                                                    class="btn btn-sm btn-light-primary fw-bolder flex-fill"
                                                    style="border-radius: 0.55rem;">
                                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                                </a>

                                                @if (!empty($surat?->file_generated))
                                                    <a href="{{ route('mahasiswa.surat.view', ['tabel' => $tabel, 'id' => $item->id_tabel_surat]) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-success fw-bolder flex-fill"
                                                        style="border-radius: 0.55rem;">
                                                        <i class="fas fa-download me-1"></i> Unduh Dokumen
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
                                    <h3 class="fw-bolder text-gray-900 mb-2 fs-2">Tidak Ada Riwayat yang Cocok</h3>
                                    <p class="text-muted fs-6 mb-0" style="max-width: 500px; line-height: 1.6;">
                                        Tidak ditemukan data riwayat pengajuan surat yang sesuai dengan kata kunci atau filter jenis surat yang dipilih.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row flex-stack flex-wrap gap-4 pt-2 align-items-center text-center text-sm-start"
                            id="pagination-wrapper">
                            <div class="fs-7 fw-semibold text-gray-700" id="pagination-info">
                                Menampilkan <span id="visible-range" class="fw-bolder text-primary">1 - 6</span> dari total
                                <span id="total-count" class="fw-bolder text-primary">{{ $historyPengajuan->count() }}</span> arsip surat
                            </div>
                            <div id="pagination-nav"></div>
                        </div>
                    @else
                        <div class="card empty-state-card border border-dashed border-gray-400 shadow-sm mb-8"
                            style="border-radius: 0.85rem;">
                            <div class="card-body p-10 p-lg-15 text-center d-flex flex-column align-items-center justify-content-center"
                                style="min-height: 380px;">
                                <div class="mb-5">
                                    <div
                                        class="symbol symbol-100px symbol-circle bg-light-primary mx-auto d-flex align-items-center justify-content-center">
                                        <i class="fas fa-history text-primary fs-2hx"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bolder text-gray-900 mb-2">
                                    Belum Ada Riwayat Pengajuan Surat yang Selesai
                                </h3>
                                <p class="text-muted fs-6 mb-6" style="max-width: 520px; margin: 0 auto; line-height: 1.6;">
                                    Saat ini belum ada permohonan surat Anda yang telah selesai diproses. Setiap surat yang
                                    telah disetujui BAK dan ditandatangani Dekan akan tersimpan di halaman riwayat ini secara otomatis.
                                </p>
                                <div class="dropdown">
                                    <button class="btn btn-primary fw-bolder px-6 py-3 d-inline-flex align-items-center gap-2 dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" data-bs-offset="0,8" aria-expanded="false" style="border-radius: 0.65rem;">
                                        <i class="fas fa-plus"></i>
                                        <span>Buat Pengajuan Baru</span>
                                        <i class="fas fa-chevron-down fs-8 ms-1"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-custom text-start shadow-lg border-0 py-2">
                                        <div class="dropdown-menu-header">
                                            Pilih Jenis Surat
                                        </div>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-aktif.create') }}">
                                            <div class="item-icon bg-light-primary">
                                                <i class="fas fa-file-alt text-primary"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Keterangan Aktif</span>
                                                <span class="item-desc">Kuliah aktif, tunjangan PNS/PPPK, dll.</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-izin-penelitian.create') }}">
                                            <div class="item-icon bg-light-success">
                                                <i class="fas fa-flask text-success"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Izin Penelitian</span>
                                                <span class="item-desc">Riset skripsi, tugas akhir & instansi mitra</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-observasi.create') }}">
                                            <div class="item-icon bg-light-info">
                                                <i class="fas fa-eye text-info"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Observasi</span>
                                                <span class="item-desc">Studi lapangan & observasi mata kuliah</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-rekomendasi.create') }}">
                                            <div class="item-icon bg-light-warning">
                                                <i class="fas fa-award text-warning"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Rekomendasi</span>
                                                <span class="item-desc">Lomba, beasiswa, pertukaran & kegiatan</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-pkl.create') }}">
                                            <div class="item-icon bg-light-primary">
                                                <i class="fas fa-briefcase text-primary"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Permohonan PKL</span>
                                                <span class="item-desc">Praktik Kerja Lapangan / Magang industri</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                        <a class="dropdown-item-custom" href="{{ route('mahasiswa.surat-keterangan-lulus.create') }}">
                                            <div class="item-icon bg-light-danger">
                                                <i class="fas fa-graduation-cap text-danger"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span class="item-title">Surat Keterangan Lulus</span>
                                                <span class="item-desc">Surat keterangan yudisium / kelulusan</span>
                                            </div>
                                            <i class="fas fa-chevron-right fs-9 text-muted ms-1"></i>
                                        </a>
                                    </div>
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
                    const filterType = $card.data('filter-type');
                    const searchData = ($card.data('search') || '').toString().toLowerCase();

                    const typeMatches = (currentFilter === 'all') || (filterType === currentFilter);
                    const searchMatches = !searchQuery || searchData.includes(searchQuery);

                    if (typeMatches && searchMatches) {
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
                        $badge.removeClass('bg-secondary text-gray-700')
                            .addClass('bg-white text-primary');
                    } else {
                        $badge.removeClass('bg-white text-primary')
                            .addClass('bg-secondary text-gray-700');
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
