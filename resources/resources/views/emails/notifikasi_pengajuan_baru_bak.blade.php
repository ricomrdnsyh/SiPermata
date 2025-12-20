<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat Baru - BAK</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table {
            border-collapse: collapse;
        }

        td {
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            background-color: #0e345c;
            color: #ffffff;
            padding: 30px 25px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .header p {
            margin-top: 5px;
            font-size: 14px;
        }

        .content {
            padding: 25px 35px;
            color: #444444;
            line-height: 1.7;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-new {
            background-color: #e6f7ff;
            color: #0056b3;
            border: 1px solid #b3e0ff;
        }

        .info-box {
            background-color: #f5f9ff;
            border-left: 5px solid #0e345c;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .info-table td.label {
            width: 35%;
            color: #777777;
        }

        .info-table td.value {
            width: 65%;
            font-weight: 600;
        }

        .cta-box {
            margin: 25px 0 10px 0;
            text-align: left;
        }

        .cta-button {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 999px;
            background-color: #0e345c;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .cta-button:hover {
            opacity: 0.9;
        }

        .footer {
            background-color: #f0f3f6;
            padding: 20px 25px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>

<body>

    @php
        $namaFakultas = $mahasiswa->fakultas->nama_fakultas ?? '';
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table class="container" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    {{-- HEADER --}}
                    <tr>
                        <td class="header">
                            <h1>Pengajuan Surat Baru Mahasiswa</h1>
                            <p style="color: #ffffff;">
                                Sistem Informasi Pengajuan Surat Mahasiswa Terpadu
                            </p>
                        </td>
                    </tr>

                    {{-- CONTENT --}}
                    <tr>
                        <td class="content">
                            <p>
                                Yth. <b>BAK Fakultas {{ $namaFakultas }}</b>,<br>
                                Telah masuk <b>Pengajuan Surat Baru</b> dari mahasiswa berikut:
                            </p>

                            <p style="margin-top: 10px; margin-bottom: 15px;">
                                <span class="badge badge-new">Pengajuan Baru</span>
                            </p>

                            <div class="info-box">
                                <table class="info-table">
                                    <tr>
                                        <td class="label">Nama Mahasiswa</td>
                                        <td class="value">{{ $mahasiswa->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">NIM</td>
                                        <td class="value">{{ $mahasiswa->nim }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Fakultas</td>
                                        <td class="value">{{ $namaFakultas ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Program Studi</td>
                                        <td class="value">{{ $mahasiswa->prodi->nama_prodi ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Jenis Surat</td>
                                        <td class="value">{{ $namaSurat }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">Status Pengajuan</td>
                                        <td class="value">{{ strtoupper($pengajuan->status) }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if ($urlDetail)
                                <div class="cta-box">
                                    <p style="margin-bottom: 10px;">
                                        Silakan memproses pengajuan ini melalui sistem pada tautan berikut:
                                    </p>
                                    <a href="{{ $urlDetail }}" target="_blank" class="cta-button">
                                        Buka Detail Pengajuan
                                    </a>
                                    <p style="margin-top: 8px; font-size: 12px; color: #888;">
                                        Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser
                                        Anda:<br>
                                        <span style="word-break: break-all;">{{ $urlDetail }}</span>
                                    </p>
                                </div>
                            @endif

                            <p style="margin-top: 25px;">
                                Terima kasih atas perhatian dan tindak lanjutnya.
                            </p>

                            <p style="margin-top: 10px;">
                                Salam hormat,<br>
                                <b>SiPermata</b>
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td class="footer">
                            <p>&copy; {{ date('Y') }} PDSI Universitas Nurul Jadid.</p>
                            <p style="margin-top: 5px; font-style: italic;">
                                Ini adalah pemberitahuan otomatis. Harap tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
