{{-- File: resources/views/emails/surat-selesai.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Anda Telah Selesai Diproses! 🎉</title>
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
            font-size: 26px;
            font-weight: 600;
        }

        .content {
            padding: 25px 35px;
            color: #444444;
            line-height: 1.7;
        }

        .highlight-box {
            background-color: #e6f7ff;
            border-left: 5px solid #007bff;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .attachment-info {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
        }

        .footer {
            background-color: #f0f3f6;
            padding: 20px 25px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>

<body>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table class="container" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td class="header">
                            <h1 style="margin: 0;">🎉 Berita Baik! Surat Anda Siap!</h1>
                            <p style="margin-top: 5px; font-size: 16px;">Sistem Informasi Pengajuan Surat Terpadu</p>
                        </td>
                    </tr>

                    <tr>
                        <td class="content">
                            {{-- Mengakses data yang dilewatkan melalui Mailable --}}
                            <p>Halo, <b>{{ $mahasiswa->nama }}</b> (NIM: {{ $mahasiswa->nim }}),</p>

                            <p>Kami sangat senang untuk mengabarkan bahwa proses pengajuan surat Anda, yaitu
                                <b>{{ $namaSurat ?? 'Surat Pengajuan' }}</b>
                                telah <b>SELESAI</b> diproses.
                            </p>

                            <div class="highlight-box">
                                <p style="margin: 0; font-weight: bold; color: #007bff;">Surat ini telah diverifikasi
                                    dan ditandatangani secara elektronik oleh Dekan
                                    {{ $mahasiswa->fakultas->nama_fakultas }}. Surat resmi Anda tersedia
                                    sebagai lampiran pada email ini.</p>
                            </div>

                            <p>Berikut adalah rincian singkat:</p>

                            <table width="100%" cellpadding="5" cellspacing="0" border="0" role="presentation"
                                style="margin: 15px 0; font-size: 14px;">
                                <tr>
                                    <td width="150" style="padding: 10px; border-bottom: 1px dashed #dddddd;">Jenis
                                        Surat</td>
                                    <td style="padding: 10px; border-bottom: 1px dashed #dddddd;">:
                                        <b>{{ $namaSurat ?? 'N/A' }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px;">Tanggal Persetujuan</td>
                                    <td style="padding: 10px;">:
                                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
                                    </td>
                                </tr>
                            </table>

                            <div class="attachment-info">
                                Mohon periksa bagian lampiran (attachment) email ini untuk melihat dokumen!
                                {{ $fileName }}.
                            </div>

                            <p>Silakan gunakan surat tersebut sebagaimana mestinya. Jika ada pertanyaan, jangan ragu
                                untuk menghubungi kami.</p>

                            <p style="margin-top: 30px;">
                                Salam Hormat,<br>
                                <b>BAK {{ $mahasiswa->fakultas->nama_fakultas }}</b><br>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td class="footer">
                            <p style="margin: 0;">&copy; {{ date('Y') }} Universitas Nurul Jadid.</p>
                            <p style="margin: 5px 0 0; font-style: italic;">Ini adalah pemberitahuan otomatis. Harap
                                tidak membalas email ini.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
