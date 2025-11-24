<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SiPermata Universitas Nurul Jadid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/unuja.png') }}" type="image/x-icon" />

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Poppins, sans-serif;
        }

        .page {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .header {
            background-color: #1e5086;
            /* biru */
            color: #ffffff;
            padding: 20px 16px;
            text-align: center;
        }

        .header-title {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
        }

        .header-subtitle {
            margin: 8px 0 0 0;
            font-size: 20px;
        }

        .pdf-container {
            flex: 1;
            min-height: 0;
        }

        .pdf-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <p class="header-title">
                Sistem Informasi Pengajuan Surat Mahasiswa Terpadu
            </p>
            <p class="header-subtitle">
                Universitas Nurul Jadid
            </p>
        </div>

        <div class="pdf-container">
            <iframe src="{{ $pdf_url }}" title="Surat PDF"></iframe>
        </div>
    </div>
</body>

</html>
