@extends('layout.main')

@section('title', 'SSO Sinkronisasi')

@section('content')
    <div class="container-fluid py-4 mb-8">

        <div class="card card-flush my-6 border-dashed border-2 rounded-3 border-gray-300 shadow-sm">
            <div
                class="card-body p-8 p-lg-7 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center bg-white">
                <div class="mb-4 mb-md-0">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-light-primary me-3 px-3 py-2">
                            <i class="ki-duotone ki-shield-tick fs-3 me-1"></i> SSO Sinkronisasi
                        </span>
                        @if ($hasAuth && $status === 'active')
                            <span class="badge badge-light-success px-3 py-2">
                                <i class="ki-duotone ki-check fs-3 me-1"></i> Token Aktif
                            </span>
                        @elseif ($hasAuth && $status === 'expired')
                            <span class="badge badge-light-danger px-3 py-2">
                                <i class="ki-duotone ki-information-5 fs-3 me-1"></i> Token Kadaluarsa
                            </span>
                        @else
                            <span class="badge badge-light-warning px-3 py-2">
                                <i class="ki-duotone ki-information-5 fs-3 me-1"></i> Belum Ada Token
                            </span>
                        @endif
                    </div>

                    <h1 class="text-dark fw-bolder fs-2 mb-2">Debug & Sinkronisasi SSO</h1>
                    <div class="text-muted fs-7">
                        Monitor status token, endpoint, dan lakukan refresh token untuk menjaga integrasi
                        <strong>SiPermata</strong> dengan SSO tetap stabil.
                    </div>
                </div>

                <form id="sinkron_sso" action="{{ route('admin.sso.refresh-token') }}" method="POST" class="ms-md-4">
                    @csrf
                    <button type="submit" data-kt-contacts-type="submit"
                        class="btn btn-primary px-6 py-3 d-flex align-items-center">
                        <span class="indicator-label">
                            <i class="fas fa-sync me-2"></i>
                            <span class="fw-bold">Refresh Token Sekarang</span>
                        </span>
                        <span class="indicator-progress">
                            <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            Refresh Token Sekarang...
                        </span>
                    </button>
                    @if ($hasAuth && $createdAt)
                        <div class="text-muted fs-8 mt-2 text-end">
                            Terakhir diperbarui:
                            <span class="fw-semibold">
                                {{ $createdAt->format('d-m-Y H:i') }}
                            </span>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if (!$hasAuth)
            <div class="card mb-6 border-dashed border-2 rounded-3 border-gray-300">
                <div
                    class="card-body d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between py-6 px-5 px-lg-8 bg-white">
                    <div class="d-flex align-items-center mb-4 mb-lg-0">
                        <div class="symbol symbol-45px symbol-circle me-4">
                            <div class="symbol-label bg-light-primary">
                                <i class="fas fa-user-lock"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Belum ada token SSO di cache</h3>
                            <div class="text-muted fs-7">
                                Klik <strong>Generate Token Sekarang</strong> untuk melakukan authorize pertama kali dan
                                mendapatkan <code>X-Token</code> dari SSO.
                            </div>
                        </div>
                    </div>
                    <form id="sinkron_sso" action="{{ route('admin.sso.refresh-token') }}" method="POST">
                        @csrf
                        <button type="submit" data-kt-contacts-type="submit" class="btn btn-primary px-6">
                            <span class="indicator-label">
                                <i class="fas fa-cog"></i>
                                <span class="fw-bold">Generate Token</span>
                            </span>
                            <span class="indicator-progress">
                                <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                                Generate Token...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="row g-6 g-xl-8">

                <div class="col-xl-4 col-md-6">
                    <div class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-success">
                        <div class="card-header border-0 pb-0">
                            <div class="card-title">
                                <h3 class="fw-bold mb-0">Status Token</h3>
                            </div>
                        </div>
                        <div class="card-body pt-4 bg-white">
                            <div class="d-flex align-items-center mb-4">
                                <div class="symbol symbol-50px me-4">
                                    <div class="symbol-label bg-light-success bg-opacity-40">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                                <div>
                                    @if ($status === 'active')
                                        <div class="fs-4 fw-bolder text-success mb-1">
                                            Token Aktif
                                        </div>
                                        <div class="text-muted fs-8">
                                            Integrasi SSO siap digunakan.
                                        </div>
                                    @elseif ($status === 'expired')
                                        <div class="fs-4 fw-bolder text-danger mb-1">
                                            Token KADALUARSA
                                        </div>
                                        <div class="text-muted fs-8">
                                            Lakukan refresh token untuk melanjutkan sinkronisasi.
                                        </div>
                                    @else
                                        <div class="fs-4 fw-bolder text-gray-600 mb-1">
                                            Token tidak tersedia
                                        </div>
                                        <div class="text-muted fs-8">
                                            Silakan generate token baru.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if (!is_null($remainingMinutes))
                                @php
                                    $hours = intdiv($remainingMinutes, 60);
                                    $mins = abs($remainingMinutes % 60);
                                @endphp

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted fs-8">Sisa waktu token</span>
                                        <span class="fs-8 fw-semibold">
                                            @if ($remainingMinutes > 0)
                                                {{ $hours }} jam {{ $mins }} menit
                                            @else
                                                Kadaluarsa {{ abs($remainingMinutes) }} menit lalu
                                            @endif
                                        </span>
                                    </div>
                                    <div class="progress h-7px bg-light-secondary">
                                        @php
                                            $total = 360;
                                            $used = max(0, min($total, $total - max($remainingMinutes, 0)));
                                            $percent = $total > 0 ? ($used / $total) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar @if ($remainingMinutes > 0) bg-success @else bg-danger @endif"
                                            role="progressbar" style="width: {{ $percent }}%;"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="border rounded px-3 py-2 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-circle-info fs-3 text-success me-2"></i>
                                    <span class="fs-8 text-muted">
                                        Token disimpan di cache backend dan tidak pernah ditampilkan penuh di UI
                                        untuk alasan keamanan.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-info">
                        <div class="card-header border-0 pb-0">
                            <div class="card-title">
                                <h3 class="fw-bold mb-0">Header X-Token</h3>
                            </div>
                            <div class="card-toolbar">
                                <span class="badge badge-light-info">Authorization Header</span>
                            </div>
                        </div>
                        <div class="card-body pt-4 bg-white">
                            <div class="mb-3">
                                <div class="text-muted fs-8 mb-1">Token (disingkat)</div>
                                <div class="fs-5 fw-bold text-dark">
                                    {{ $maskedToken ?? '-' }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-light-info copy-btn"
                                    data-copy="{{ $fullToken }}">
                                    <i class="fas fa-copy"></i>
                                    Salin X-Token
                                </button>
                            </div>

                            <div class="border rounded p-3 bg-light">
                                <code class="fs-8 d-block text-wrap">
                                    X-Token: {{ $fullToken ?? '-' }}<br>
                                    Accept: application/json<br>
                                    Content-Type: application/json<br>
                                    User-Agent: browser client
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-12">
                    <div class="card card-flush h-100 shadow-sm hover-elevate-up border-top border-3 border-primary">
                        <div class="card-header border-0 pb-0">
                            <div class="card-title">
                                <h3 class="fw-bold mb-0">Waktu & Endpoint</h3>
                            </div>
                            <div class="card-toolbar">
                                <span class="badge badge-light-primary">Informasi Teknis</span>
                            </div>
                        </div>
                        <div class="card-body pt-4 bg-white">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="bullet bullet-dot bg-primary me-2"></span>
                                    <span class="text-muted fs-8">Waktu dibuat</span>
                                </div>
                                <div class="fw-bold fs-6">
                                    {{ $createdAt ? $createdAt->format('d-m-Y H:i') : '-' }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="bullet bullet-dot bg-danger me-2"></span>
                                    <span class="text-muted fs-8">Berlaku sampai</span>
                                </div>
                                <div class="fw-bold fs-6">
                                    {{ $expiredAt ? $expiredAt->format('d-m-Y H:i') : '-' }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="bullet bullet-dot bg-success me-2"></span>
                                    <span class="text-muted fs-8">URL Data</span>
                                </div>

                                @if ($dataUrl)
                                    <div class="mb-2">
                                        <a href="{{ $dataUrl }}" target="_blank"
                                            class="fw-semibold fs-7 text-primary text-decoration-underline">
                                            {{ $shortUrl }}
                                        </a>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-light-primary copy-btn"
                                        data-copy="{{ $dataUrl }}">
                                        <i class="fas fa-copy"></i>
                                        Salin URL Data
                                    </button>
                                @else
                                    <div class="fw-semibold fs-7 text-muted">-</div>
                                @endif
                            </div>

                            <div class="border rounded px-3 py-2 bg-light mt-3">
                                <div class="d-flex">
                                    <i class="fas fa-gears"></i>
                                    <div class="fs-8 text-muted">
                                        Endpoint ini digunakan oleh SiPermata ketika memanggil API SSO,
                                        misalnya untuk sinkronisasi :<br>
                                        <code>{"filter":"mahasiswa"}</code><br>
                                        <code>{"filter":"dosen"}</code><br>
                                        dan lain-lain.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        @endif

    </div>
@endsection

@section('js')
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
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
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.copy-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const value = this.getAttribute('data-copy') || '';
                if (!value) return;

                navigator.clipboard.writeText(value).then(() => {
                    Swal.fire({
                        text: 'Berhasil disalin ke clipboard.',
                        icon: 'success',
                        buttonsStyling: false,
                        confirmButtonText: 'Ok',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                }).catch(() => {
                    Swal.fire({
                        text: 'Gagal menyalin ke clipboard.',
                        icon: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Ok',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('sinkron_sso');
            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    return;
                }
                submitButton.disabled = true;
                submitButton.querySelector('.indicator-label').style.display = 'none';
                submitButton.querySelector('.indicator-progress').style.display = 'inline-block';
            });
        });
    </script>
@endsection
