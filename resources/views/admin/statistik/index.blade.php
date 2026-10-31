@extends('layout.main')
@section('title', 'Statistik Pengajuan Surat')

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

        .animate-fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-250 { animation-delay: 250ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-350 { animation-delay: 350ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-450 { animation-delay: 450ms; }
        .delay-500 { animation-delay: 500ms; }

        .stat-card {
            border-radius: 1rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
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
            box-shadow: 0 0 15px rgba(0, 158, 247, 0.35);
        }

        .icon-glow-success {
            box-shadow: 0 0 15px rgba(80, 205, 137, 0.35);
        }

        .icon-glow-info {
            box-shadow: 0 0 15px rgba(114, 57, 234, 0.35);
        }

        .icon-glow-warning {
            box-shadow: 0 0 15px rgba(255, 199, 0, 0.35);
        }

        .icon-glow-danger {
            box-shadow: 0 0 15px rgba(241, 65, 108, 0.35);
        }

        .chart-card {
            border: 1px dashed rgba(0, 0, 0, 0.12);
            border-radius: 1rem;
            background: #ffffff;
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .chart-card {
            background: #1e1e2d;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .table-row-dashed tr {
            border-bottom: 1px dashed #e4e6ef !important;
        }

        [data-bs-theme="dark"] .table-row-dashed tr {
            border-bottom: 1px dashed rgba(255, 255, 255, 0.08) !important;
        }

        @page {
            size: A4 portrait;
            margin: 8mm 10mm 10mm 10mm;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                box-sizing: border-box !important;
            }

            #kt_app_sidebar,
            #kt_app_sidebar_toggle,
            #kt_app_header,
            #kt_app_footer,
            #kt_app_toolbar,
            .no-print,
            .d-print-none,
            .filter-container,
            #empty_filter_state,
            .card-toolbar,
            .dropdown {
                display: none !important;
            }

            .d-print-block {
                display: block !important;
            }

            .d-print-flex {
                display: flex !important;
            }

            html,
            body {
                background: #ffffff !important;
                color: #181c32 !important;
                font-size: 11px !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
            }

            #kt_app_root,
            #kt_app_page,
            #kt_app_wrapper,
            #kt_app_main,
            #kt_app_content,
            #kt_app_content_container,
            .app-root,
            .app-page,
            .app-wrapper,
            .app-main,
            .app-content,
            .app-container {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                display: block !important;
                position: static !important;
                overflow: visible !important;
                height: auto !important;
                min-height: 0 !important;
            }

            .row:not(.kpi-row-container):not(#barLegendBottom) {
                display: block !important;
                margin: 0 0 10px 0 !important;
                height: auto !important;
                min-height: 0 !important;
            }

            .row:not(.kpi-row-container):not(#barLegendBottom)>[class*="col-"] {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                flex: none !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin: 0 0 10px 0 !important;
                height: auto !important;
                min-height: 0 !important;
            }

            .kpi-row-container {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                gap: 6px !important;
                margin-bottom: 12px !important;
                width: 100% !important;
            }

            .kpi-row-container>div {
                flex: 1 1 0 !important;
                width: 16.666% !important;
                max-width: 16.666% !important;
                padding: 0 !important;
            }

            .stat-card {
                padding: 8px 10px !important;
                border-radius: 6px !important;
                border: 1px dashed #b5b5c3 !important;
                box-shadow: none !important;
                background: #ffffff !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                height: auto !important;
                min-height: 0 !important;
            }

            .stat-card .card-body {
                padding: 0 !important;
            }

            .stat-card .stat-icon {
                width: 28px !important;
                height: 28px !important;
                border-radius: 4px !important;
                margin-right: 6px !important;
            }

            .stat-card .stat-icon i {
                font-size: 0.9rem !important;
            }

            .stat-card .fs-2x {
                font-size: 1.15rem !important;
                line-height: 1.2 !important;
                font-weight: 700 !important;
            }

            .stat-card .fs-7 {
                font-size: 0.72rem !important;
            }

            .mb-8,
            .my-8 {
                margin-bottom: 10px !important;
            }

            .mb-7,
            .my-7 {
                margin-bottom: 8px !important;
            }

            .mb-6,
            .my-6 {
                margin-bottom: 8px !important;
            }

            .mb-5,
            .my-5 {
                margin-bottom: 6px !important;
            }

            .card,
            .chart-card {
                box-shadow: none !important;
                border: 1px dashed #b5b5c3 !important;
                border-radius: 8px !important;
                margin-bottom: 10px !important;
                background: #ffffff !important;
                height: auto !important;
                min-height: 0 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                overflow: hidden !important;
            }

            .h-100 {
                height: auto !important;
                min-height: 0 !important;
                max-height: none !important;
            }

            .card-header {
                padding: 8px 12px 4px 12px !important;
                min-height: 0 !important;
                border-bottom: 1px solid #f0f0f2 !important;
                page-break-after: avoid !important;
                break-after: avoid !important;
            }

            .card-body {
                padding: 8px 12px 10px 12px !important;
                height: auto !important;
                min-height: 0 !important;
            }

            /* SLA Indicators Compact Row in Print */
            .sla-indicators-container {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                gap: 6px !important;
            }

            .sla-indicators-container>div {
                flex: 1 1 0 !important;
                width: 25% !important;
                padding: 6px 8px !important;
                border-radius: 6px !important;
            }

            .sla-indicators-container .stat-icon {
                width: 28px !important;
                height: 28px !important;
            }

            .sla-indicators-container .stat-icon i {
                font-size: 0.9rem !important;
            }

            .sla-indicators-container .fs-7 {
                font-size: 0.72rem !important;
            }

            .sla-indicators-container .fs-8 {
                font-size: 0.62rem !important;
            }

            .sla-indicators-container .badge {
                font-size: 0.75rem !important;
                padding: 3px 6px !important;
            }

            /* Chart canvas wrapper & resizing */
            .chart-canvas-wrapper {
                position: relative !important;
                width: 100% !important;
                max-width: 100% !important;
                height: 220px !important;
                min-height: 0 !important;
                max-height: 220px !important;
                overflow: hidden !important;
                margin: 0 auto !important;
            }

            #surat_chart_container {
                height: 220px !important;
                max-height: 220px !important;
                min-height: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                position: relative !important;
                overflow: hidden !important;
            }

            #prodi_chart_container {
                height: auto !important;
                min-height: 0 !important;
                max-height: 260px !important;
                width: 100% !important;
                max-width: 100% !important;
                position: relative !important;
                overflow: hidden !important;
            }

            canvas {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            #trendChart,
            #suratChart,
            #fakultasChart,
            #prodiChart {
                width: 100% !important;
                max-width: 100% !important;
                height: 220px !important;
                max-height: 220px !important;
                display: block !important;
            }

            #statusDonut {
                max-width: 100% !important;
                max-height: 180px !important;
                width: auto !important;
                height: 180px !important;
                margin: 0 auto !important;
                display: block !important;
            }

            /* Bar Legend in 3-column grid */
            #barLegendBottom {
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 4px !important;
                margin: 6px 0 0 0 !important;
                padding: 6px 0 0 0 !important;
            }

            #barLegendBottom>[class*="col-"] {
                flex: 0 0 calc(33.333% - 4px) !important;
                width: calc(33.333% - 4px) !important;
                max-width: calc(33.333% - 4px) !important;
                display: block !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            #barLegendBottom .p-2 {
                padding: 4px 6px !important;
                font-size: 8px !important;
            }

            /* Status Breakdown Pills in Horizontal Row */
            #status_breakdown_list {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                gap: 4px !important;
                margin-top: 6px !important;
                padding-top: 4px !important;
            }

            #status_breakdown_list>div {
                flex: 1 1 calc(20% - 4px) !important;
                padding: 3px 6px !important;
                font-size: 8px !important;
            }

            #status_breakdown_list .fs-7 {
                font-size: 7.5px !important;
            }

            #status_breakdown_list .badge {
                font-size: 7.5px !important;
                padding: 2px 4px !important;
            }

            /* Top Mitra compact print */
            #top_mitra_container {
                gap: 4px !important;
            }

            #top_mitra_container>div {
                padding: 4px 8px !important;
                margin-bottom: 3px !important;
            }

            #top_mitra_container .fs-6 {
                font-size: 9px !important;
            }

            #top_mitra_container .fs-7 {
                font-size: 8px !important;
            }

            #top_mitra_container .progress {
                height: 4px !important;
            }

            /* Detailed Matrix Table - 100% Fit & No clipping */
            #matrix_table,
            .table {
                width: 100% !important;
                max-width: 100% !important;
                table-layout: fixed !important;
                font-size: 8.5px !important;
                margin-bottom: 0 !important;
            }

            #matrix_table th,
            #matrix_table td,
            .table th,
            .table td {
                min-width: 0 !important;
                width: auto !important;
                padding: 4px 3px !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: normal !important;
                word-break: break-word !important;
            }

            #matrix_table th:nth-child(1),
            #matrix_table td:nth-child(1) {
                width: 32% !important;
                text-align: left !important;
            }

            #matrix_table th:nth-child(2),
            #matrix_table td:nth-child(2) {
                width: 10% !important;
            }

            #matrix_table th:nth-child(3),
            #matrix_table td:nth-child(3),
            #matrix_table th:nth-child(4),
            #matrix_table td:nth-child(4),
            #matrix_table th:nth-child(5),
            #matrix_table td:nth-child(5),
            #matrix_table th:nth-child(6),
            #matrix_table td:nth-child(6),
            #matrix_table th:nth-child(7),
            #matrix_table td:nth-child(7) {
                width: 11.6% !important;
            }

            #matrix_table .badge {
                font-size: 7.5px !important;
                padding: 2px 4px !important;
            }

            #matrix_table .symbol {
                display: none !important;
            }

            #matrix_table td .text-muted {
                font-size: 7px !important;
            }

            .table-responsive {
                overflow: visible !important;
                height: auto !important;
                min-height: 0 !important;
                width: 100% !important;
            }

            thead {
                display: table-header-group !important;
            }

            tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-6">
                <div id="kt_app_content_container" class="app-container container-fluid">

                    <!-- PAGE HEADER (TRACER STYLE) -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-light-primary fw-bold px-3 py-2">
                                    <i class="fas fa-chart-pie text-primary me-1"></i> Analisis Surat
                                </span>
                            </div>
                            <h1 class="text-gray-900 fw-bolder fs-1 mb-1 mt-2">Statistik Pengajuan Surat</h1>
                            <span class="text-gray-600 fw-semibold fs-6">
                                Pantau dan eksplorasi data permohonan surat serta performa layanan administrasi melalui visualisasi data interaktif.
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-3 no-print">
                            <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="printLaporan()">
                                <i class="fas fa-print me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </div>

                    <!-- PRINT HEADER (TRACER STYLE) -->
                    <div class="d-none d-print-block mb-4 p-3 border border-dashed border-gray-400 rounded-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center fs-8 text-gray-700">
                            <div><i class="fas fa-file-alt text-primary me-1"></i><strong>Dokumen:</strong> Laporan Statistik Pengajuan Surat - SiPermata UNUJA</div>
                            <div><i class="fas fa-clock text-gray-500 me-1"></i><strong>Waktu Ekspor:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>
                        </div>
                    </div>

                    <!-- FILTER ANALITIK CARD -->
                    <div class="card shadow-sm border border-dashed border-primary mb-6 filter-container" style="border-radius: 1.25rem;">
                        <div class="card-body p-6">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h5 class="text-primary fw-bolder mb-0 d-flex align-items-center gap-2">
                                    <i class="fas fa-filter text-primary"></i> Filter Analitik
                                </h5>
                                <button type="button" class="btn btn-link btn-color-muted btn-active-color-primary p-0 fs-7 fw-bold" id="btn_reset_filter">
                                    <i class="fas fa-undo-alt me-1"></i> Reset Filter
                                </button>
                            </div>

                            <form id="filterStatistikForm">
                                <div class="row g-4 align-items-end">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label class="form-label fw-bold fs-7 mb-2 text-gray-700">Tahun Akademik:</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            name="id_akademik" id="filter_akademik_id" data-placeholder="Semua Tahun Akademik" data-allow-clear="true">
                                            <option value="all" @if ($currentAkademikId == 'all') selected @endif>Semua Tahun Akademik</option>
                                            @foreach ($tahunAkademikList as $id => $tahun)
                                                <option value="{{ $id }}" @if ($id == $currentAkademikId) selected @endif>
                                                    {{ $tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                                        <label class="form-label fw-bold fs-7 mb-2 text-gray-700">Fakultas:</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            name="fakultas_id" id="filter_fakultas_id" data-placeholder="Semua Fakultas" data-allow-clear="true">
                                            <option value="all" @if ($currentFakultasId == 'all') selected @endif>Semua Fakultas</option>
                                            @foreach ($fakultasList as $f)
                                                <option value="{{ $f->id_fakultas }}" @if ($f->id_fakultas == $currentFakultasId) selected @endif>
                                                    {{ $f->nama_fakultas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <label class="form-label fw-bold fs-7 mb-2 text-gray-700">Program Studi:</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            name="prodi_id" id="filter_prodi_id" data-placeholder="Semua Program Studi" data-allow-clear="true">
                                            <option value="all" @if ($currentProdiId == 'all') selected @endif>Semua Program Studi</option>
                                            @foreach ($prodiList as $p)
                                                <option value="{{ $p->id_prodi }}" data-fakultas="{{ $p->fakultas_id }}"
                                                    @if ($p->id_prodi == $currentProdiId) selected @endif>
                                                    {{ $p->nama_prodi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 d-flex justify-content-xl-end">
                                        <button type="button" class="btn btn-sm btn-primary fw-bold w-100" id="btn_apply_filter" style="height: 38px;">
                                            <span id="btn_apply_text" class="d-inline-flex align-items-center justify-content-center">
                                                <i class="fas fa-filter me-2 fs-7"></i> Terapkan Filter
                                            </span>
                                            <span id="btn_apply_loading" class="d-none align-items-center justify-content-center">
                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                Memproses...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- EMPTY FILTER STATE (IMAGE FROM USER) -->
                    <div id="empty_filter_state"
                        class="card text-center py-20 my-10 rounded-4 border border-dashed border-dark shadow-sm d-flex flex-column justify-content-center align-items-center"
                        style="min-height: 380px; {{ $hasFilter ? 'display: none !important;' : '' }}">
                        <i class="fas fa-chart-pie text-muted mb-6" style="font-size: 5rem;"></i>
                        <h3 class="text-gray-900 fw-bolder fs-1 mb-3">Pilih Filter untuk Menampilkan Statistik</h3>
                        <p class="text-gray-500 fs-5 mb-0">Silakan sesuaikan filter di atas untuk memuat data statistik pengajuan surat.</p>
                    </div>

                    <!-- STATISTICS CONTAINER (SHOWN WHEN FILTER IS APPLIED) -->
                    <div id="statistics_container" style="{{ $hasFilter ? '' : 'display: none;' }}">

                        <!-- ACTIVE FILTER PILLS -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-6">
                            <span class="badge badge-light-primary fw-semibold px-3 py-2" id="pill_tahun">
                                <i class="fas fa-calendar-alt text-primary me-1"></i> <span id="pill_tahun_text">{{ $currentYearLabel }}</span>
                            </span>
                            <span class="badge badge-light-info fw-semibold px-3 py-2" id="pill_fakultas">
                                <i class="fas fa-university text-info me-1"></i> <span id="pill_fakultas_text">{{ $currentFakultasLabel }}</span>
                            </span>
                            <span class="badge badge-light-success fw-semibold px-3 py-2" id="pill_prodi">
                                <i class="fas fa-graduation-cap text-success me-1"></i> <span id="pill_prodi_text">{{ $currentProdiLabel }}</span>
                            </span>
                            <span class="badge badge-light-dark fw-bold px-3 py-2 ms-auto" id="pill_total">
                                <i class="fas fa-layer-group text-dark me-1"></i> <span id="pill_total_text">{{ number_format($globalStats['totalMasuk']) }}</span> Pengajuan Terdata
                            </span>
                        </div>

                        <!-- 6 STATUS KPI CARDS (IDENTICAL DASHBOARD STYLES & COLORS) -->
                        <div class="row g-4 g-xl-5 mb-8 kpi-row-container">
                            <!-- Total Masuk -->
                            <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-200">
                                <div class="card stat-card shadow-sm h-100 border border-dashed border-dark bg-light-secondary"
                                    onclick="window.location.href='{{ route('admin.history-pengajuan.index') }}'">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="stat-icon bg-body shadow-sm me-4">
                                            <i class="fas fa-inbox fs-2x text-dark"></i>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 fw-semibold fs-7 mb-1">Total Masuk</div>
                                            <div class="text-gray-900 fw-bolder fs-2x" id="stat_total_masuk">
                                                {{ number_format($globalStats['totalMasuk']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Menunggu BAK -->
                            <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-250">
                                <div class="card stat-card shadow-sm h-100 border border-dashed border-warning bg-light-warning"
                                    onclick="window.location.href='{{ route('admin.history-pengajuan.index', ['status' => 'pengajuan']) }}'">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="stat-icon bg-body shadow-sm me-4 icon-glow-warning">
                                            <i class="fas fa-file-export fs-2x text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 fw-semibold fs-7 mb-1">Menunggu BAK</div>
                                            <div class="text-gray-900 fw-bolder fs-2x" id="stat_total_pengajuan">
                                                {{ number_format($globalStats['totalPengajuan']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Menunggu Dekan -->
                            <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-300">
                                <div class="card stat-card shadow-sm h-100 border border-dashed border-primary bg-light-primary"
                                    onclick="window.location.href='{{ route('admin.history-pengajuan.index', ['status' => 'proses']) }}'">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="stat-icon bg-body shadow-sm me-4 icon-glow-primary">
                                            <i class="fas fa-user-clock fs-2x text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 fw-semibold fs-7 mb-1">Menunggu Dekan</div>
                                            <div class="text-gray-900 fw-bolder fs-2x" id="stat_total_proses">
                                                {{ number_format($globalStats['totalProses']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Disetujui -->
                            <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-350">
                                <div class="card stat-card shadow-sm h-100 border border-dashed border-success bg-light-success"
                                    onclick="window.location.href='{{ route('admin.history-pengajuan.index', ['status' => 'diterima']) }}'">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="stat-icon bg-body shadow-sm me-4 icon-glow-success">
                                            <i class="fas fa-clipboard-check fs-2x text-success"></i>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 fw-semibold fs-7 mb-1">Disetujui</div>
                                            <div class="text-gray-900 fw-bolder fs-2x" id="stat_total_diterima">
                                                {{ number_format($globalStats['totalDiterima']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selesai -->
                            <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-400">
                                <div class="card stat-card shadow-sm h-100 border border-dashed border-info bg-light-info"
                                    onclick="window.location.href='{{ route('admin.history-pengajuan.index', ['status' => 'selesai']) }}'">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="stat-icon bg-body shadow-sm me-4 icon-glow-info">
                                            <i class="fas fa-download fs-2x text-info"></i>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 fw-semibold fs-7 mb-1">Selesai</div>
                                            <div class="text-gray-900 fw-bolder fs-2x" id="stat_total_selesai">
                                                {{ number_format($globalStats['totalSelesai']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ditolak -->
                            <div class="col-xl-2 col-md-4 col-sm-6 animate-fade-in-up delay-450">
                                <div class="card stat-card shadow-sm h-100 border border-dashed border-danger bg-light-danger"
                                    onclick="window.location.href='{{ route('admin.history-pengajuan.index', ['status' => 'ditolak']) }}'">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="stat-icon bg-body shadow-sm me-4 icon-glow-danger">
                                            <i class="fas fa-times-circle fs-2x text-danger"></i>
                                        </div>
                                        <div>
                                            <div class="text-gray-600 fw-semibold fs-7 mb-1">Ditolak</div>
                                            <div class="text-gray-900 fw-bolder fs-2x" id="stat_total_ditolak">
                                                {{ number_format($globalStats['totalDitolak']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CHARTS ROW 1: TREN BULANAN & PERFORMA LAYANAN (SLA) -->
                        <div class="row g-5 g-xl-6 mb-7">
                            <!-- Tren Pengajuan (Line/Area Chart) -->
                            <div class="col-xl-8">
                                <div class="card chart-card h-100">
                                    <div class="card-header pt-6 pb-4 border-0">
                                        <div class="card-title d-flex flex-column">
                                            <span class="fw-bolder fs-3 text-gray-900">
                                                <i class="fas fa-chart-line text-primary me-2"></i>Tren Pengajuan Surat Mahasiswa
                                            </span>
                                            <span class="text-gray-500 fw-semibold fs-7">Historis permohonan surat dalam 6 bulan terakhir</span>
                                        </div>
                                        <div class="card-toolbar no-print">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Download">
                                                    <i class="fas fa-download fs-5"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end fw-semibold fs-7 shadow-sm border-0 py-2">
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('trendChart', 'jpg', 'Grafik_Tren_Surat'); return false;"><i class="far fa-file-image me-2 text-warning"></i>Download JPG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('trendChart', 'png', 'Grafik_Tren_Surat'); return false;"><i class="far fa-file-image me-2 text-success"></i>Download PNG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('trendChart', 'pdf', 'Grafik_Tren_Surat'); return false;"><i class="far fa-file-pdf me-2 text-danger"></i>Download PDF</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-6">
                                        <div class="chart-canvas-wrapper" style="position: relative; width: 100%; height: 320px;">
                                            <canvas id="trendChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Performa Layanan & SLA -->
                            <div class="col-xl-4">
                                <div class="card chart-card h-100">
                                    <div class="card-header pt-6 pb-4 border-0">
                                        <div class="card-title d-flex flex-column">
                                            <span class="fw-bolder fs-3 text-gray-900">
                                                <i class="fas fa-tachometer-alt text-success me-2"></i>Performa Layanan & SLA
                                            </span>
                                            <span class="text-gray-500 fw-semibold fs-7">Indikator mutu kecepatan verifikasi & validasi surat</span>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-6 d-flex flex-column justify-content-between">
                                        <div class="d-flex flex-column gap-3 sla-indicators-container">
                                            <!-- Rata-rata Verifikasi BAK -->
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light-warning border border-dashed border-warning">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="stat-icon bg-body shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                        <i class="fas fa-user-check text-warning fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-800 fw-bold fs-7">Verifikasi BAK</div>
                                                        <div class="text-gray-500 fs-8">Rata-rata pemrosesan staf</div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-warning fw-bolder fs-6 px-3 py-2" id="sla_bak_time">{{ $serviceSpeedStats['avgBakTime'] ?? '45 Menit' }}</span>
                                            </div>

                                            <!-- Rata-rata TTE Dekan -->
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light-primary border border-dashed border-primary">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="stat-icon bg-body shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                        <i class="fas fa-signature text-primary fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-800 fw-bold fs-7">Validasi & TTE Dekanat</div>
                                                        <div class="text-gray-500 fs-8">Rata-rata persetujuan pimpinan</div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-primary fw-bolder fs-6 px-3 py-2" id="sla_dekan_time">{{ $serviceSpeedStats['avgDekanTime'] ?? '1 Jam' }}</span>
                                            </div>

                                            <!-- Approval Rate -->
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light-success border border-dashed border-success">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="stat-icon bg-body shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                        <i class="fas fa-check-circle text-success fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-800 fw-bold fs-7">Tingkat Kelolosan (Approval)</div>
                                                        <div class="text-gray-500 fs-8">Rasio surat disetujui / terbit</div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-success fw-bolder fs-6 px-3 py-2" id="sla_approval_rate">{{ $serviceSpeedStats['approvalRate'] ?? '0%' }}</span>
                                            </div>

                                            <!-- Kepatuhan SLA -->
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light-info border border-dashed border-info">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="stat-icon bg-body shadow-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                        <i class="fas fa-award text-info fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-800 fw-bold fs-7">Kepatuhan Standar SOP</div>
                                                        <div class="text-gray-500 fs-8">Target selesai &le; 3 hari kerja</div>
                                                    </div>
                                                </div>
                                                <span class="badge badge-info fw-bolder fs-6 px-3 py-2" id="sla_compliance">{{ $serviceSpeedStats['slaCompliance'] ?? '98.5%' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-3 border-top border-gray-200 text-center">
                                            <span class="text-gray-500 fs-8">
                                                <i class="fas fa-info-circle text-primary me-1"></i> Terpantau otomatis berdasarkan log waktu sistem SiPermata.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CHARTS ROW 2: JENIS SURAT & STATUS DONUT -->
                        <div class="row g-5 g-xl-6 mb-7">
                            <div class="col-xl-8">
                                <div class="card chart-card h-100">
                                    <div class="card-header pt-6 pb-4 border-0">
                                        <div class="card-title d-flex flex-column">
                                            <span class="fw-bolder fs-3 text-gray-900">Perbandingan Pengajuan Per Jenis Surat</span>
                                            <span class="text-gray-500 fw-semibold fs-7">Statistik total permohonan surat berdasarkan kriteria filter aktif</span>
                                        </div>
                                        <div class="card-toolbar no-print">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Download Grafik">
                                                    <i class="fas fa-download fs-5"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end fw-semibold fs-7 shadow-sm border-0 py-2">
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('suratChart', 'jpg', 'Grafik_Jenis_Surat'); return false;"><i class="far fa-file-image me-2 text-warning"></i>Download JPG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('suratChart', 'png', 'Grafik_Jenis_Surat'); return false;"><i class="far fa-file-image me-2 text-success"></i>Download PNG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('suratChart', 'pdf', 'Grafik_Jenis_Surat'); return false;"><i class="far fa-file-pdf me-2 text-danger"></i>Download PDF</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-6">
                                        <div id="surat_chart_container" class="chart-canvas-wrapper" style="height: 340px;">
                                            <canvas id="suratChart"></canvas>
                                        </div>
                                        <div id="barLegendBottom" class="row g-2 mt-3 pt-3 border-top border-gray-200"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="card chart-card h-100">
                                    <div class="card-header pt-6 pb-4 border-0">
                                        <div class="card-title d-flex flex-column">
                                            <span class="fw-bolder fs-3 text-gray-900">Komposisi Status</span>
                                            <span class="text-gray-500 fw-semibold fs-7">Distribusi pengajuan berdasarkan status</span>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-6 d-flex flex-column justify-content-between">
                                        <div class="chart-canvas-wrapper" style="height: 250px; position: relative;">
                                            <canvas id="statusDonut"></canvas>
                                        </div>
                                        <div class="d-flex flex-column gap-2 mt-4 pt-3 border-top border-gray-200" id="status_breakdown_list">
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #FFC700;"></span>
                                                    <span class="text-gray-800 fw-bold fs-7">Menunggu BAK</span>
                                                </div>
                                                <span class="badge badge-light-warning fw-bolder" id="donut_val_pengajuan">{{ number_format($globalStats['totalPengajuan']) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #009EF7;"></span>
                                                    <span class="text-gray-800 fw-bold fs-7">Menunggu Dekan</span>
                                                </div>
                                                <span class="badge badge-light-primary fw-bolder" id="donut_val_proses">{{ number_format($globalStats['totalProses']) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #50CD89;"></span>
                                                    <span class="text-gray-800 fw-bold fs-7">Disetujui</span>
                                                </div>
                                                <span class="badge badge-light-success fw-bolder" id="donut_val_diterima">{{ number_format($globalStats['totalDiterima']) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #7239EA;"></span>
                                                    <span class="text-gray-800 fw-bold fs-7">Selesai</span>
                                                </div>
                                                <span class="badge badge-light-info fw-bolder" id="donut_val_selesai">{{ number_format($globalStats['totalSelesai']) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded bg-light">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #F1416C;"></span>
                                                    <span class="text-gray-800 fw-bold fs-7">Ditolak</span>
                                                </div>
                                                <span class="badge badge-light-danger fw-bolder" id="donut_val_ditolak">{{ number_format($globalStats['totalDitolak']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CHARTS ROW 3: FAKULTAS & PRODI -->
                        <div class="row g-5 g-xl-6 mb-7">
                            <div class="col-xl-6">
                                <div class="card chart-card h-100">
                                    <div class="card-header pt-6 pb-4 border-0">
                                        <div class="card-title d-flex flex-column">
                                            <span class="fw-bolder fs-3 text-gray-900">Pengajuan Per Fakultas</span>
                                            <span class="text-gray-500 fw-semibold fs-7">Total permohonan surat berdasarkan fakultas</span>
                                        </div>
                                        <div class="card-toolbar no-print">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Download">
                                                    <i class="fas fa-download fs-5"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end fw-semibold fs-7 shadow-sm border-0 py-2">
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('fakultasChart', 'jpg', 'Grafik_Fakultas'); return false;"><i class="far fa-file-image me-2 text-warning"></i>Download JPG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('fakultasChart', 'png', 'Grafik_Fakultas'); return false;"><i class="far fa-file-image me-2 text-success"></i>Download PNG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('fakultasChart', 'pdf', 'Grafik_Fakultas'); return false;"><i class="far fa-file-pdf me-2 text-danger"></i>Download PDF</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-6">
                                        <div class="chart-canvas-wrapper" style="position: relative; width: 100%; height: 320px;">
                                            <canvas id="fakultasChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="card chart-card h-100">
                                    <div class="card-header pt-6 pb-4 border-0">
                                        <div class="card-title d-flex flex-column">
                                            <span class="fw-bolder fs-3 text-gray-900">Pengajuan Per Program Studi</span>
                                            <span class="text-gray-500 fw-semibold fs-7">Total permohonan surat berdasarkan program studi</span>
                                        </div>
                                        <div class="card-toolbar no-print">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Download">
                                                    <i class="fas fa-download fs-5"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end fw-semibold fs-7 shadow-sm border-0 py-2">
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('prodiChart', 'jpg', 'Grafik_Prodi'); return false;"><i class="far fa-file-image me-2 text-warning"></i>Download JPG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('prodiChart', 'png', 'Grafik_Prodi'); return false;"><i class="far fa-file-image me-2 text-success"></i>Download PNG</a></li>
                                                    <li><a class="dropdown-item px-4 py-2" href="#" onclick="downloadChart('prodiChart', 'pdf', 'Grafik_Prodi'); return false;"><i class="far fa-file-pdf me-2 text-danger"></i>Download PDF</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-6">
                                        <div id="prodi_chart_empty" class="d-none flex-column align-items-center justify-content-center" style="height: 320px;">
                                            <i class="fas fa-folder-open text-gray-400 fs-3x mb-3"></i>
                                            <span class="text-gray-500 fw-bold fs-6">Tidak ada data untuk program studi ini</span>
                                        </div>
                                        <div id="prodi_chart_container" class="chart-canvas-wrapper" style="position: relative; width: 100%; min-height: 320px;">
                                            <canvas id="prodiChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CHARTS ROW 4: TOP MITRA PKL/PENELITIAN -->
                        <div class="row g-5 g-xl-6 mb-7">
                            <!-- Top Mitra PKL & Penelitian -->
                            <div class="col-12">
                                <div class="card chart-card h-100">
                                    <div class="card-header pt-6 pb-4 border-0">
                                        <div class="card-title d-flex flex-column">
                                            <span class="fw-bolder fs-3 text-gray-900">
                                                <i class="fas fa-building text-info me-2"></i>Top Mitra Instansi PKL & Penelitian
                                            </span>
                                            <span class="text-gray-500 fw-semibold fs-7">Instansi / lembaga terpopuler tujuan magang dan riset</span>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-6">
                                        <div id="top_mitra_container" class="d-flex flex-column gap-3">
                                            @forelse ($topMitraData ?? [] as $idx => $m)
                                                <div class="p-3 rounded-3 bg-light border border-dashed border-gray-300">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge {{ $idx === 0 ? 'badge-primary' : ($idx === 1 ? 'badge-info' : 'badge-light-dark') }} fw-bolder px-2 py-1">
                                                                #{{ $idx + 1 }}
                                                            </span>
                                                            <span class="text-gray-900 fw-bold fs-6">{{ $m['nama'] }}</span>
                                                        </div>
                                                        <span class="badge badge-light-primary fw-bolder fs-7">{{ $m['total'] }} Pengajuan</span>
                                                    </div>
                                                    <div class="progress h-6px bg-light-secondary rounded">
                                                        <div class="progress-bar {{ $idx === 0 ? 'bg-primary' : ($idx === 1 ? 'bg-info' : 'bg-success') }}" role="progressbar" style="width: {{ $m['percentage'] }}%" aria-valuenow="{{ $m['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="d-flex flex-column align-items-center justify-content-center py-10 text-muted">
                                                    <i class="fas fa-building fs-3x text-gray-300 mb-3"></i>
                                                    <span class="fw-semibold">Belum ada data pengajuan surat PKL atau penelitian</span>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DETAILED MATRIX TABLE -->
                        <div class="card chart-card mb-8 print-avoid-break">
                            <div class="card-header pt-6 pb-4 border-0">
                                <div class="card-title d-flex flex-column">
                                    <span class="fw-bolder fs-3 text-gray-900">Status Detail Pengajuan Per Jenis Surat</span>
                                    <span class="text-gray-500 fw-semibold fs-7">Rincian komprehensif jumlah surat per tahap status verifikasi</span>
                                </div>
                            </div>
                            <div class="card-body py-2">
                                <div class="table-responsive">
                                    <table class="table align-middle gs-4 gy-4 table-row-dashed" id="matrix_table">
                                        <thead>
                                            <tr class="fw-bold text-gray-700 bg-light rounded">
                                                <th class="ps-4 min-w-250px rounded-start">Jenis Surat</th>
                                                <th class="text-center min-w-90px">Total</th>
                                                <th class="text-center min-w-140px">Menunggu BAK</th>
                                                <th class="text-center min-w-140px">Menunggu Dekan</th>
                                                <th class="text-center min-w-120px">Disetujui</th>
                                                <th class="text-center min-w-110px">Selesai</th>
                                                <th class="text-center min-w-110px rounded-end">Ditolak</th>
                                            </tr>
                                        </thead>
                                        <tbody id="matrix_tbody">
                                            @php
                                                $grandTotal = 0;
                                                $grandPengajuan = 0;
                                                $grandProses = 0;
                                                $grandDiterima = 0;
                                                $grandSelesai = 0;
                                                $grandDitolak = 0;
                                            @endphp
                                            @foreach ($detailedStatus as $surat)
                                                @php
                                                    $grandTotal += $surat['total'];
                                                    $grandPengajuan += $surat['pengajuan'];
                                                    $grandProses += $surat['proses'];
                                                    $grandDiterima += $surat['diterima'];
                                                    $grandSelesai += $surat['selesai'];
                                                    $grandDitolak += $surat['ditolak'];
                                                @endphp
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="symbol symbol-30px">
                                                                <span class="symbol-label bg-light-primary">
                                                                    <i class="far fa-file-alt text-primary fs-6"></i>
                                                                </span>
                                                            </span>
                                                            <div class="d-flex flex-column">
                                                                <span class="text-gray-900 fw-bold fs-6">{{ $surat['label'] }}</span>
                                                                <span class="text-muted fs-8">{{ $currentFakultasLabel }} &bull; {{ $currentProdiLabel }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-dark fw-bolder fs-7 px-3 py-2">{{ number_format($surat['total']) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-warning fw-bolder fs-7 px-3 py-2">{{ number_format($surat['pengajuan']) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-primary fw-bolder fs-7 px-3 py-2">{{ number_format($surat['proses']) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-success fw-bolder fs-7 px-3 py-2">{{ number_format($surat['diterima']) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-info fw-bolder fs-7 px-3 py-2">{{ number_format($surat['selesai']) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light-danger fw-bolder fs-7 px-3 py-2">{{ number_format($surat['ditolak']) }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bolder text-gray-900 bg-light-primary">
                                                <td class="ps-4 fs-6">Total Keseluruhan</td>
                                                <td class="text-center fs-6" id="matrix_footer_total">{{ number_format($grandTotal) }}</td>
                                                <td class="text-center fs-6 text-warning" id="matrix_footer_pengajuan">{{ number_format($grandPengajuan) }}</td>
                                                <td class="text-center fs-6 text-primary" id="matrix_footer_proses">{{ number_format($grandProses) }}</td>
                                                <td class="text-center fs-6 text-success" id="matrix_footer_diterima">{{ number_format($grandDiterima) }}</td>
                                                <td class="text-center fs-6 text-info" id="matrix_footer_selesai">{{ number_format($grandSelesai) }}</td>
                                                <td class="text-center fs-6 text-danger" id="matrix_footer_ditolak">{{ number_format($grandDitolak) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        var suratChartInstance = null;
        var statusDonutInstance = null;
        var fakultasChartInstance = null;
        var prodiChartInstance = null;
        var trendChartInstance = null;

        var chartColors = ['#009EF7', '#50CD89', '#7239EA', '#FFC700', '#F1416C', '#181C32'];
        var fakColors = ['#009EF7', '#50CD89', '#FFC700', '#7239EA', '#F1416C', '#00D4FE'];
        var prodiPalette = [
            '#009EF7', '#50CD89', '#FFC700', '#F1416C', '#7239EA',
            '#00D4FE', '#F64E60', '#FFA800', '#6993FF', '#8950FC',
            '#1BC5BD', '#3699FF', '#E4E6EF', '#20C997', '#FD7E14'
        ];

        // Center total text plugin for Donut
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
                var fontSize = (height / 95).toFixed(2);
                ctx.font = "bold " + fontSize + "em Inter, sans-serif";
                var textX = Math.round((width - ctx.measureText(text).width) / 2);
                var textY = height / 2 + 16;
                ctx.fillText(text, textX, textY);

                ctx.save();
            }
        };

        // Render / Update Chart 1: Jenis Surat
        function renderSuratChart(data) {
            const ctx = document.getElementById('suratChart');
            if (!ctx) return;

            if (suratChartInstance) {
                suratChartInstance.destroy();
            }

            suratChartInstance = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: data.data,
                        backgroundColor: chartColors.slice(0, data.labels.length),
                        borderRadius: 8,
                        barThickness: 32,
                        maxBarThickness: 46
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0,0,0,0.06)'
                            },
                            ticks: {
                                color: '#7E8299',
                                font: { size: 12, weight: '600' },
                                callback: function(value) {
                                    if (value % 1 === 0) return value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            displayColors: false,
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                title: function(items) { return items?.[0]?.label ?? ''; },
                                label: function(context) { return ' ' + context.parsed.y + ' pengajuan'; }
                            }
                        }
                    }
                }
            });

            // Update bottom legend pills
            const barLegendBottom = document.getElementById('barLegendBottom');
            if (barLegendBottom) {
                barLegendBottom.innerHTML = data.labels.map((label, i) => {
                    const color = chartColors[i % chartColors.length];
                    const count = data.data[i] || 0;
                    return `
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                            <div class="d-flex align-items-center gap-2 text-truncate pe-2">
                                <span class="d-inline-block rounded-circle flex-shrink-0" style="width: 12px; height: 12px; background:${color};"></span>
                                <span class="text-gray-700 fw-bold fs-8 text-truncate">${label}</span>
                            </div>
                            <span class="badge badge-light-dark fw-bolder fs-8">${count}</span>
                        </div>
                    </div>`;
                }).join('');
            }
        }

        // Render / Update Chart 2: Status Donut
        function renderStatusDonut(stats) {
            const ctx = document.getElementById('statusDonut');
            if (!ctx) return;

            if (statusDonutInstance) {
                statusDonutInstance.destroy();
            }

            statusDonutInstance = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu BAK', 'Menunggu Dekan', 'Disetujui', 'Selesai', 'Ditolak'],
                    datasets: [{
                        data: [
                            parseInt(stats.totalPengajuan) || 0,
                            parseInt(stats.totalProses) || 0,
                            parseInt(stats.totalDiterima) || 0,
                            parseInt(stats.totalSelesai) || 0,
                            parseInt(stats.totalDitolak) || 0
                        ],
                        backgroundColor: ['#FFC700', '#009EF7', '#50CD89', '#7239EA', '#F1416C'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '55%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.parsed + ' pengajuan';
                                }
                            }
                        }
                    }
                },
                plugins: [statusDonutCenterText]
            });
        }

        // Render / Update Chart 3: Fakultas
        function renderFakultasChart(data) {
            const ctx = document.getElementById('fakultasChart');
            if (!ctx) return;

            if (fakultasChartInstance) {
                fakultasChartInstance.destroy();
            }

            fakultasChartInstance = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: data.data,
                        backgroundColor: data.labels.map((_, i) => fakColors[i % fakColors.length]),
                        borderRadius: 8,
                        barThickness: 34,
                        maxBarThickness: 48
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#7E8299',
                                font: { size: 11, weight: '600' },
                                maxRotation: 20,
                                minRotation: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0,0,0,0.06)'
                            },
                            ticks: {
                                color: '#7E8299',
                                font: { size: 12, weight: '600' },
                                callback: v => v % 1 === 0 ? v : null
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: ctx => ' ' + ctx.parsed.y + ' pengajuan'
                            }
                        }
                    }
                }
            });
        }

        // Render / Update Chart 4: Prodi
        function renderProdiChart(data) {
            const canvas = document.getElementById('prodiChart');
            const emptyNotice = document.getElementById('prodi_chart_empty');
            const container = document.getElementById('prodi_chart_container');
            if (!canvas) return;

            if (prodiChartInstance) {
                prodiChartInstance.destroy();
            }

            if (!data.labels || data.labels.length === 0) {
                if (emptyNotice) {
                    emptyNotice.classList.remove('d-none');
                    emptyNotice.classList.add('d-flex');
                }
                if (container) container.style.display = 'none';
                return;
            }

            if (emptyNotice) {
                emptyNotice.classList.add('d-none');
                emptyNotice.classList.remove('d-flex');
            }
            if (container) {
                container.style.display = 'block';
                container.style.height = Math.max(320, data.labels.length * 36) + 'px';
            }

            prodiChartInstance = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: data.data,
                        backgroundColor: data.labels.map((_, i) => prodiPalette[i % prodiPalette.length]),
                        borderRadius: 6,
                        barThickness: 20,
                        maxBarThickness: 28
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0,0,0,0.06)'
                            },
                            ticks: {
                                color: '#7E8299',
                                font: { size: 12, weight: '600' },
                                callback: v => v % 1 === 0 ? v : null
                            }
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                color: '#7E8299',
                                font: { size: 11, weight: '600' }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: ctx => ' ' + ctx.parsed.x + ' pengajuan'
                            }
                        }
                    }
                }
            });
        }

        // Render / Update Chart: Trend Bulanan (Line/Area)
        function renderTrendChart(data) {
            const ctx = document.getElementById('trendChart');
            if (!ctx) return;

            if (trendChartInstance) {
                trendChartInstance.destroy();
            }

            const context = ctx.getContext('2d');
            const gradient = context.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(0, 158, 247, 0.35)');
            gradient.addColorStop(1, 'rgba(0, 158, 247, 0.00)');

            trendChartInstance = new Chart(context, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: data.data,
                        borderColor: '#009EF7',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#009EF7',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#7E8299',
                                font: { size: 12, weight: '600' }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(0,0,0,0.06)'
                            },
                            ticks: {
                                color: '#7E8299',
                                font: { size: 12, weight: '600' },
                                callback: v => v % 1 === 0 ? v : null
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: ctx => ' ' + ctx.parsed.y + ' pengajuan'
                            }
                        }
                    }
                }
            });
        }

        // Update Top Mitra List HTML
        function updateTopMitraList(mitraData) {
            const $container = $('#top_mitra_container');
            if (!$container.length) return;

            $container.empty();
            if (!mitraData || mitraData.length === 0) {
                $container.html(`
                    <div class="d-flex flex-column align-items-center justify-content-center py-10 text-muted">
                        <i class="fas fa-building fs-3x text-gray-300 mb-3"></i>
                        <span class="fw-semibold">Belum ada data pengajuan surat PKL atau penelitian</span>
                    </div>
                `);
                return;
            }

            $.each(mitraData, function(idx, m) {
                const badgeClass = idx === 0 ? 'badge-primary' : (idx === 1 ? 'badge-info' : 'badge-light-dark');
                const barColor = idx === 0 ? 'bg-primary' : (idx === 1 ? 'bg-info' : 'bg-success');
                $container.append(`
                    <div class="p-3 rounded-3 bg-light border border-dashed border-gray-300">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge ${badgeClass} fw-bolder px-2 py-1">#${idx + 1}</span>
                                <span class="text-gray-900 fw-bold fs-6">${m.nama}</span>
                            </div>
                            <span class="badge badge-light-primary fw-bolder fs-7">${m.total} Pengajuan</span>
                        </div>
                        <div class="progress h-6px bg-light-secondary rounded">
                            <div class="progress-bar ${barColor}" role="progressbar" style="width: ${m.percentage}%" aria-valuenow="${m.percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                `);
            });
        }

        // Update Matrix Table
        function updateMatrixTable(detailedStatus, currentFakultasLabel, currentProdiLabel) {
            const $tbody = $('#matrix_tbody');
            if (!$tbody.length) return;

            $tbody.empty();
            let grandTotal = 0, grandPengajuan = 0, grandProses = 0, grandDiterima = 0, grandSelesai = 0, grandDitolak = 0;

            $.each(detailedStatus, function(i, surat) {
                grandTotal += surat.total;
                grandPengajuan += surat.pengajuan;
                grandProses += surat.proses;
                grandDiterima += surat.diterima;
                grandSelesai += surat.selesai;
                grandDitolak += surat.ditolak;

                $tbody.append(`
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="symbol symbol-30px">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="far fa-file-alt text-primary fs-6"></i>
                                    </span>
                                </span>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">${surat.label}</span>
                                    <span class="text-muted fs-8">${currentFakultasLabel} &bull; ${currentProdiLabel}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light-dark fw-bolder fs-7 px-3 py-2">${Number(surat.total).toLocaleString('id-ID')}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light-warning fw-bolder fs-7 px-3 py-2">${Number(surat.pengajuan).toLocaleString('id-ID')}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light-primary fw-bolder fs-7 px-3 py-2">${Number(surat.proses).toLocaleString('id-ID')}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light-success fw-bolder fs-7 px-3 py-2">${Number(surat.diterima).toLocaleString('id-ID')}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light-info fw-bolder fs-7 px-3 py-2">${Number(surat.selesai).toLocaleString('id-ID')}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-light-danger fw-bolder fs-7 px-3 py-2">${Number(surat.ditolak).toLocaleString('id-ID')}</span>
                        </td>
                    </tr>
                `);
            });

            $('#matrix_footer_total').text(Number(grandTotal).toLocaleString('id-ID'));
            $('#matrix_footer_pengajuan').text(Number(grandPengajuan).toLocaleString('id-ID'));
            $('#matrix_footer_proses').text(Number(grandProses).toLocaleString('id-ID'));
            $('#matrix_footer_diterima').text(Number(grandDiterima).toLocaleString('id-ID'));
            $('#matrix_footer_selesai').text(Number(grandSelesai).toLocaleString('id-ID'));
            $('#matrix_footer_ditolak').text(Number(grandDitolak).toLocaleString('id-ID'));
        }

        // Document Ready
        $(document).ready(function() {
            @if ($hasFilter)
                renderSuratChart(@json($chartData));
                renderStatusDonut(@json($globalStats));
                renderFakultasChart(@json($fakultasChartData));
                renderProdiChart(@json($prodiChartData));
                renderTrendChart(@json($monthlyTrendData));
            @endif

            // Dynamic prodi on fakultas change
            $('#filter_fakultas_id').on('change', function() {
                const fakultasId = $(this).val();
                const $prodiSelect = $('#filter_prodi_id');

                $prodiSelect.prop('disabled', true);

                $.ajax({
                    url: `{{ url('/admin/prodi-by-fakultas') }}/${fakultasId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $prodiSelect.empty();
                        $prodiSelect.append('<option value="all">Semua Program Studi</option>');

                        $.each(data, function(index, prodi) {
                            $prodiSelect.append(`<option value="${prodi.id_prodi}">${prodi.nama_prodi}</option>`);
                        });

                        $prodiSelect.val('all').trigger('change.select2');
                        $prodiSelect.prop('disabled', false);
                    },
                    error: function() {
                        $prodiSelect.prop('disabled', false);
                    }
                });
            });

            // Terapkan Filter Button Click
            $('#btn_apply_filter').on('click', function(e) {
                e.preventDefault();
                var $btn = $(this);

                // Show processing indicator
                $btn.prop('disabled', true);
                $('#btn_apply_text').removeClass('d-inline-flex').addClass('d-none');
                $('#btn_apply_loading').removeClass('d-none').addClass('d-inline-flex');

                var params = {
                    id_akademik: $('#filter_akademik_id').val() || 'all',
                    fakultas_id: $('#filter_fakultas_id').val() || 'all',
                    prodi_id: $('#filter_prodi_id').val() || 'all'
                };

                $.ajax({
                    url: "{{ route('admin.statistik.data') }}",
                    type: "GET",
                    data: params,
                    dataType: "json",
                    success: function(res) {
                        // Hide empty state, show statistics
                        $('#empty_filter_state').addClass('d-none').hide();
                        $('#statistics_container').fadeIn(200);

                        // Update Pills
                        $('#pill_tahun_text').text(res.tahunLabel);
                        $('#pill_fakultas_text').text(res.fakultasLabel);
                        $('#pill_prodi_text').text(res.prodiLabel);
                        $('#pill_total_text').text(Number(res.globalStats.totalMasuk).toLocaleString('id-ID'));

                        // Update Print Header Metadata
                        $('#print_tahun_text').text(res.tahunLabel);
                        $('#print_fakultas_text').text(res.fakultasLabel);
                        $('#print_prodi_text').text(res.prodiLabel);

                        // Update 6 Status KPI Cards
                        const total = res.globalStats.totalMasuk || 0;
                        $('#stat_total_masuk').text(Number(total).toLocaleString('id-ID'));
                        $('#stat_total_pengajuan').text(Number(res.globalStats.totalPengajuan).toLocaleString('id-ID'));
                        $('#stat_total_proses').text(Number(res.globalStats.totalProses).toLocaleString('id-ID'));
                        $('#stat_total_diterima').text(Number(res.globalStats.totalDiterima).toLocaleString('id-ID'));
                        $('#stat_total_selesai').text(Number(res.globalStats.totalSelesai).toLocaleString('id-ID'));
                        $('#stat_total_ditolak').text(Number(res.globalStats.totalDitolak).toLocaleString('id-ID'));

                        // Donut values breakdown
                        $('#donut_val_pengajuan').text(Number(res.globalStats.totalPengajuan).toLocaleString('id-ID'));
                        $('#donut_val_proses').text(Number(res.globalStats.totalProses).toLocaleString('id-ID'));
                        $('#donut_val_diterima').text(Number(res.globalStats.totalDiterima).toLocaleString('id-ID'));
                        $('#donut_val_selesai').text(Number(res.globalStats.totalSelesai).toLocaleString('id-ID'));
                        $('#donut_val_ditolak').text(Number(res.globalStats.totalDitolak).toLocaleString('id-ID'));

                        // Update SLA Stats
                        if (res.serviceSpeedStats) {
                            $('#sla_bak_time').text(res.serviceSpeedStats.avgBakTime);
                            $('#sla_dekan_time').text(res.serviceSpeedStats.avgDekanTime);
                            $('#sla_approval_rate').text(res.serviceSpeedStats.approvalRate);
                            $('#sla_compliance').text(res.serviceSpeedStats.slaCompliance);
                        }

                        // Render Charts
                        renderSuratChart(res.chartData);
                        renderStatusDonut(res.globalStats);
                        renderFakultasChart(res.fakultasChartData);
                        renderProdiChart(res.prodiChartData);
                        if (res.monthlyTrendData) renderTrendChart(res.monthlyTrendData);
                        if (res.topMitraData) updateTopMitraList(res.topMitraData);

                        // Update Table
                        updateMatrixTable(res.detailedStatus, res.fakultasLabel, res.prodiLabel);
                    },
                    error: function() {
                        alert('Gagal mengambil data statistik terbaru.');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $('#btn_apply_loading').removeClass('d-inline-flex').addClass('d-none');
                        $('#btn_apply_text').removeClass('d-none').addClass('d-inline-flex');
                    }
                });
            });

            // Reset Filter Click
            $('#btn_reset_filter').on('click', function(e) {
                e.preventDefault();
                $('#filter_akademik_id').val('all').trigger('change.select2');
                $('#filter_fakultas_id').val('all').trigger('change.select2');
                $('#filter_prodi_id').val('all').trigger('change.select2');

                // Return to clean empty state (matching screenshot)
                $('#statistics_container').hide();
                $('#empty_filter_state').removeClass('d-none').show();
            });
        });

        // Function to download chart as Image or PDF
        function downloadChart(canvasId, format, fileName) {
            const chart = Chart.getChart(canvasId);
            if (!chart) return;

            let originalXTicks = null;
            if (canvasId === 'suratChart') {
                originalXTicks = chart.options.scales.x.ticks.display;
                chart.options.scales.x.ticks.display = true;
                chart.update('none');
            }

            setTimeout(() => {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

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

        // Print Handler
        function printLaporan() {
            var allCharts = [
                trendChartInstance,
                suratChartInstance,
                statusDonutInstance,
                fakultasChartInstance,
                prodiChartInstance
            ];
            allCharts.forEach(function(c) {
                if (c) {
                    try { c.resize(); } catch (e) {}
                }
            });

            setTimeout(function() {
                window.print();
            }, 120);
        }

        // Resize charts on print to fit printable area accurately
        window.addEventListener('beforeprint', function() {
            var allCharts = [
                trendChartInstance,
                suratChartInstance,
                statusDonutInstance,
                fakultasChartInstance,
                prodiChartInstance
            ];
            allCharts.forEach(function(c) {
                if (c) {
                    try { c.resize(); } catch (e) {}
                }
            });
        });
        window.addEventListener('afterprint', function() {
            var allCharts = [
                trendChartInstance,
                suratChartInstance,
                statusDonutInstance,
                fakultasChartInstance,
                prodiChartInstance
            ];
            allCharts.forEach(function(c) {
                if (c) {
                    try { c.resize(); } catch (e) {}
                }
            });
        });
    </script>
@endsection
