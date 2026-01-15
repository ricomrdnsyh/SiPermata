<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Verifikasi Gagal | SiPermata UNUJA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="shortcut icon" href="{{ asset('assets/media/logos/unuja.png') }}" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background: #f5f8fa;
        }

        .shadow-soft {
            box-shadow: 0 10px 30px rgba(0, 0, 0, .06) !important;
        }

        .icon-danger {
            color: var(--bs-danger) !important;
        }

        .icon-muted {
            color: var(--bs-gray-600) !important;
        }

        .icon-primary {
            color: var(--bs-primary) !important;
        }

        .icon-info {
            color: var(--bs-info) !important;
        }

        .badge i {
            vertical-align: -1px;
        }
    </style>
</head>

<body id="kt_body" class="app-default">
    @php
        $message = $status_verifikasi ?? 'Verifikasi gagal.';
        $noSurat = data_get($surat, 'no_surat') ?? '-';
    @endphp

    <div class="d-flex flex-column flex-root">
        <div class="app-page flex-column flex-column-fluid">

            <div id="kt_app_header" class="app-header shadow-sm" style="background: #0e345c;">
                <div class="app-container container-xxl d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3 py-3">
                        <img src="{{ asset('assets/media/logos/unuja.png') }}" alt="UNUJA"
                            style="width:44px;height:44px;">
                        <div class="text-white">
                            <div class="fw-bold fs-5">SiPermata</div>
                            <div class="opacity-75 fs-8">Sistem Informasi Pengajuan Surat Mahasiswa Terpadu</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 py-3">
                        <a href="{{ url('/') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-home icon-muted me-2"></i> Beranda
                        </a>
                    </div>
                </div>
            </div>

            <div class="app-wrapper flex-column flex-row-fluid">
                <div class="app-main flex-column flex-row-fluid">
                    <div class="app-content flex-column-fluid py-10">
                        <div class="app-container container-xxl">

                            <div
                                class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-7">
                                <div>
                                    <h1 class="text-gray-900 fw-bold mb-2">Verifikasi Surat</h1>
                                    <div class="text-gray-600">Pemeriksaan legalitas tanda tangan digital</div>
                                </div>

                                <div class="mt-4 mt-md-0">
                                    <span class="badge badge-light-danger fs-7 px-4 py-2">
                                        <i class="fas fa-times-circle icon-danger me-2"></i>
                                        GAGAL
                                    </span>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-9 col-xl-8">

                                    <div class="card shadow-soft border-0">
                                        <div class="card-body p-10">

                                            <div class="d-flex flex-column align-items-center text-center mb-8">
                                                <div class="symbol symbol-90px symbol-circle mb-4">
                                                    <div class="symbol-label bg-light-danger">
                                                        <i class="fas fa-exclamation-triangle icon-danger fs-2"></i>
                                                    </div>
                                                </div>

                                                <div class="fw-bold fs-2 text-gray-900 mb-2">
                                                    Verifikasi Tidak Berhasil
                                                </div>
                                                <div class="text-gray-600 fs-6">
                                                    Kami tidak dapat menampilkan legalitas surat pada tautan ini.
                                                </div>
                                            </div>

                                            <div class="alert alert-danger d-flex align-items-center p-5 mb-7">
                                                <i class="fas fa-info-circle fs-2 me-4 icon-danger"></i>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold fs-6">Keterangan</span>
                                                    <span class="text-gray-800">{{ $message }}</span>
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center bg-light rounded-3 p-5 mb-7">
                                                <div>
                                                    <div class="text-gray-600 fs-8">
                                                        <i class="fas fa-hashtag icon-muted me-2"></i> Nomor Surat (jika
                                                        tersedia)
                                                    </div>
                                                    <div class="fw-bold text-gray-900 fs-5">{{ $noSurat }}</div>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <button onclick="window.location.reload()"
                                                        class="btn btn-sm btn-light-primary">
                                                        <i class="fas fa-sync-alt icon-primary me-2"></i> Coba Lagi
                                                    </button>
                                                    <a href="{{ url('/') }}" class="btn btn-sm btn-light">
                                                        <i class="fas fa-arrow-left icon-muted me-2"></i> Kembali
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="separator my-6"></div>

                                            <div class="text-gray-600 fs-8">
                                                <i class="fas fa-question-circle icon-info me-2"></i>
                                                Jika Anda merasa ini kesalahan, silakan hubungi administrator atau
                                                bagian akademik terkait dan sertakan nomor surat / tautan verifikasi.
                                            </div>

                                        </div>
                                    </div>

                                    <div class="text-center text-gray-500 fs-8 mt-10">
                                        &copy; {{ date('Y') }} PDSI Universitas Nurul Jadid
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>

</html>
