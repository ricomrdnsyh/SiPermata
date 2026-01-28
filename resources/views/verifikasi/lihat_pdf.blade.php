<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Verifikasi Surat | SiPermata UNUJA</title>
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

        .kv td:first-child {
            width: 230px;
            color: var(--bs-gray-600);
        }

        .kv td {
            vertical-align: top;
            padding-top: .85rem;
            padding-bottom: .85rem;
        }

        .shadow-soft {
            box-shadow: 0 10px 30px rgba(0, 0, 0, .06) !important;
        }

        .icon-primary {
            color: var(--bs-primary) !important;
        }

        .icon-success {
            color: var(--bs-success) !important;
        }

        .icon-info {
            color: var(--bs-info) !important;
        }

        .icon-warning {
            color: var(--bs-warning) !important;
        }

        .icon-danger {
            color: var(--bs-danger) !important;
        }

        .icon-muted {
            color: var(--bs-gray-600) !important;
        }

        .icon-on-dark {
            color: #ffffff !important;
            opacity: .95;
        }

        .badge i {
            vertical-align: -1px;
        }
    </style>
</head>

<body id="kt_body" class="app-default">
    @php
        $nama = $data['nama'] ?? '-';
        $nim = $data['nim'] ?? '-';
        $fakultas = $data['fakultas'] ?? '-';
        $prodi = $data['prodi'] ?? '-';
        $tahunAkademik = $data['tahun_akademik'] ?? '-';
        $noSurat = $data['no_surat'] ?? '-';
        $tglPengajuan = $data['tgl_pengajuan'] ?? '-';
        $tglPersetujuan = $data['tgl_persetujuan'] ?? '-';
        $penandatangan = $data['penandatangan'] ?? '-';
        $status = $data['status'] ?? 'selesai';
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
                </div>
            </div>

            <div class="app-wrapper flex-column flex-row-fluid">
                <div class="app-main flex-column flex-row-fluid">
                    <div class="app-content flex-column-fluid py-10">
                        <div class="app-container container-xxl">

                            <div
                                class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-7">
                                <div>
                                    <h1 class="text-gray-900 fw-bold mb-2">Verifikator Tanda Tangan Digital</h1>
                                    <div class="text-gray-600">Informasi legalitas surat berdasarkan data sistem.</div>
                                </div>

                                <div class="mt-4 mt-md-0 d-flex gap-2 align-items-center">
                                    <span class="badge badge-light-success fs-7 px-4 py-2">
                                        <i class="fas fa-shield-alt icon-success me-2"></i>
                                        VALID (SISTEM)
                                    </span>

                                    @if (!empty($preview_url))
                                        <a href="{{ $preview_url }}" class="btn btn-sm btn-primary" target="_blank"
                                            rel="noopener noreferrer">
                                            <i class="fas fa-file-alt me-2"></i>Lihat Surat Asli
                                        </a>
                                    @else
                                        <span class="badge badge-light-warning fs-7 px-4 py-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            File belum tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="alert alert-success d-flex align-items-center p-5 mb-8 shadow-soft">
                                <i class="fas fa-check-circle icon-success fs-2 me-4"></i>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-6">Data legalitas surat resmi.</span>
                                    <span class="text-gray-700">
                                        Surat ini telah diverifikasi dan ditandatangani secara elektronik oleh
                                        Direktur/Dekan
                                        Fakultas.
                                    </span>
                                </div>
                            </div>

                            <div class="row g-6 g-xl-9">

                                <div class="col-12 col-xl-6">
                                    <div class="card shadow-soft border-0">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h3 class="fw-bold m-0">
                                                    <i class="fas fa-user-circle icon-primary me-2"></i>
                                                    Identitas Mahasiswa
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="card-body pt-0">
                                            <div class="d-flex align-items-center bg-light-primary rounded-3 p-5 mb-6">
                                                <div class="symbol symbol-50px symbol-circle me-4">
                                                    <div class="symbol-label bg-primary text-white fw-bold">
                                                        {{ mb_substr($nama, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-gray-900 fs-5">{{ $nama }}</div>
                                                    <div class="text-gray-600">
                                                        NIM : <span class="fw-semibold">{{ $nim }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-row-dashed align-middle gs-0 gy-3 kv mb-0">
                                                    <tr>
                                                        <td class="fw-semibold">
                                                            <i class="fas fa-university icon-muted me-2"></i> Fakultas
                                                        </td>
                                                        <td class="text-gray-900 fw-bold">{{ $fakultas }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">
                                                            <i class="fas fa-graduation-cap icon-muted me-2"></i>
                                                            Program Studi
                                                        </td>
                                                        <td class="text-gray-900 fw-bold">{{ $prodi }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">
                                                            <i class="fas fa-calendar-alt icon-muted me-2"></i> Tahun
                                                            Akademik
                                                        </td>
                                                        <td class="text-gray-900 fw-bold">{{ $tahunAkademik }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-6">
                                    <div class="card shadow-soft border-0">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h3 class="fw-bold m-0">
                                                    <i class="fas fa-file-alt icon-info me-2"></i>
                                                    Informasi Surat
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="card-body pt-0">
                                            <div
                                                class="d-flex align-items-center justify-content-between bg-light rounded-3 p-5 mb-6">
                                                <div>
                                                    <div class="text-gray-600 fs-8">
                                                        <i class="fas fa-hashtag icon-muted me-2"></i> Nomor Surat
                                                    </div>
                                                    <div class="fw-bold text-gray-900 fs-5" id="noSuratText">
                                                        {{ $noSurat }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-row-dashed align-middle gs-0 gy-3 kv mb-0">
                                                    <tr>
                                                        <td class="fw-semibold"><i
                                                                class="fas fa-tag icon-muted me-2"></i>Jenis Surat</td>
                                                        <td class="text-gray-900 fw-bold">
                                                            {{ $data['jenis_surat'] ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">
                                                            <i class="fas fa-tasks icon-muted me-2"></i>Status Pengajuan
                                                        </td>
                                                        <td class="text-gray-900 fw-bold">
                                                            <span class="badge badge-light-success px-4 py-2">
                                                                <i class="fas fa-check-circle icon-success me-2"></i>
                                                                SELESAI
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">
                                                            <i class="fas fa-calendar-plus icon-primary me-2"></i>
                                                            Tanggal Pengajuan
                                                        </td>
                                                        <td class="text-gray-900 fw-bold">{{ $tglPengajuan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">
                                                            <i class="fas fa-calendar-check icon-success me-2"></i>
                                                            Tanggal Persetujuan
                                                        </td>
                                                        <td class="text-gray-900 fw-bold">{{ $tglPersetujuan }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card shadow-soft border-0">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h3 class="fw-bold m-0">
                                                    <i class="fas fa-pen-nib icon-warning me-2"></i>
                                                    Penandatangan
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="card-body pt-0">
                                            <div class="d-flex flex-column flex-md-row gap-6 align-items-md-center">
                                                <div class="symbol symbol-70px symbol-circle">
                                                    <div
                                                        class="symbol-label bg-light-success text-success fw-bold fs-2">
                                                        {{ mb_substr($penandatangan, 0, 1) }}
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-gray-900 fs-4">
                                                        <i
                                                            class="fas fa-signature icon-success me-2"></i>{{ $penandatangan }}
                                                    </div>
                                                    <div class="text-gray-600">
                                                        <i class="fas fa-briefcase icon-muted me-2"></i>Direktur/Dekan
                                                        Fakultas {{ $fakultas }}
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <span class="badge badge-light-primary px-4 py-2">
                                                        <i class="fas fa-lock icon-primary me-2"></i>
                                                        TTD Elektronik
                                                    </span>
                                                    <span class="badge badge-light-info px-4 py-2">
                                                        <i class="fas fa-database icon-info me-2"></i>
                                                        Data Sistem
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="separator my-6"></div>

                                            <div class="text-gray-600 fs-8">
                                                <i class="fas fa-exclamation-triangle icon-warning me-2"></i>
                                                Catatan: Halaman ini menampilkan ringkasan legalitas surat resmi.
                                            </div>
                                        </div>
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

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>

</html>
