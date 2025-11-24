<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SiPermata Universitas Nurul Jadid</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .card {
            max-width: 600px;
            margin: 40px auto;
            padding: 24px 20px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 12px;
        }

        .status {
            color: #c0392b;
            font-weight: bold;
            margin-bottom: 12px;
        }

        p {
            margin: 6px 0;
            font-size: 14px;
        }

        strong {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Verifikasi Surat</h2>

        <p class="status">
            {{ $status_verifikasi ?? 'Verifikasi surat gagal.' }}
        </p>

        @if ($surat)
            <p>
                Nomor Surat:
                <strong>{{ $surat->no_surat ?? '-' }}</strong><br>
                Nama Mahasiswa:
                <strong>{{ optional($surat->mahasiswa)->nama ?? '-' }}</strong>
            </p>
        @endif
    </div>
</body>

</html>
