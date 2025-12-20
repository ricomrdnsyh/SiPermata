<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SiPermata Universitas Nurul Jadid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/unuja.png') }}" type="image/x-icon" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        body.bg-grid {
            background-color: #f1f4f8;
            background-image:
                linear-gradient(to right, rgba(206, 206, 206, 0.31) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(206, 206, 206, 0.31) 1px, transparent 1px);
            background-size: 25px 25px;
            position: relative;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15);
            max-width: 520px;
            width: 100%;
            animation: fadeIn 0.7s ease-out;
        }

        .card-glass h2 {
            font-weight: 600;
        }

        .card-glass p {
            color: #4b5563;
            font-size: 0.95rem;
        }

        .status-text {
            font-weight: 600;
            color: #b91c1c;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-grid">
    <header class="text-center py-6 text-white" style="background-color:#1e5086;">
        <img src="{{ asset('assets/media/logos/unuja.png') }}" alt="Logo Universitas Nurul Jadid"
            class="mb-3" style="height:70px;">
        <h1 class="fs-1 fw-bold mb-1 text-white">
            Sistem Informasi Pengajuan Surat Mahasiswa Terpadu
        </h1>
        <p class="fs-3 mb-0">
            Universitas Nurul Jadid
        </p>
    </header>

    <main class="flex-grow-1 d-flex align-items-center justify-content-center px-3 px-md-0">
        <div class="card-glass">
            <h2 class="fs-2 mb-3 text-center">Verifikasi Surat</h2>

            <p class="status-text mb-4 text-center">
                {{ $status_verifikasi ?? 'Verifikasi surat gagal.' }}
            </p>

            @if ($surat)
                <p class="mb-1">
                    Nomor Surat:
                    <strong>{{ $surat->no_surat ?? '-' }}</strong>
                </p>
                <p class="mb-0">
                    Nama Mahasiswa:
                    <strong>{{ optional($surat->mahasiswa)->nama ?? '-' }}</strong>
                </p>
            @endif
        </div>
    </main>

    <script>
        var hostUrl = "{{ asset('assets/') }}";
    </script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>

</html>
