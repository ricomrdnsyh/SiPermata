<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengajuan Surat - Dekan</title>
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

        .status-pill {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .status-approve {
            background-color: #e6f7ff;
            color: #0056b3;
            border: 1px solid #b3e0ff;
        }

        .status-reject {
            background-color: #fdecea;
            color: #c0392b;
            border: 1px solid #f5c6cb;
        }

        .highlight-box {
            background-color: #e6f7ff;
            border-left: 5px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .highlight-box p {
            margin: 0;
        }

        .highlight-box.reject {
            background-color: #fdecea;
            border-left-color: #e74c3c;
        }

        .note-box {
            background-color: #fff8e5;
            border-left: 5px solid #f0ad4e;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
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
        $isApproved = $status === 'disetujui';
        $judulStatus = $isApproved
            ? 'Pengajuan Surat Anda Disetujui Dekan ✅'
            : 'Pengajuan Surat Anda Ditolak Dekan ❌';
        $namaSuratFix = $pengajuan->nama_surat ?? 'Surat Pengajuan';
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table class="container" cellpadding="0" cellspacing="0" border="0" role="presentation">
                    <tr>
                        <td class="header">
                            <h1>{{ $judulStatus }}</h1>
                            <p style="color: white;">Sistem Informasi Pengajuan Surat Mahasiswa Terpadu</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="content">
                            <p>
                                Halo, <b>{{ $mahasiswa->nama }}</b><br>
                                NIM: <b>{{ $mahasiswa->nim }}</b>
                            </p>

                            <p style="margin-top: 10px; margin-bottom: 15px;">
                                @if ($isApproved)
                                    <span class="status-pill status-approve">Disetujui Dekan</span>
                                @else
                                    <span class="status-pill status-reject">Ditolak Dekan</span>
                                @endif
                            </p>

                            <p>
                                Pengajuan surat Anda:
                                <b>{{ $namaSuratFix }}</b>
                                @if ($isApproved)
                                    telah <b>DISETUJUI</b> oleh Dekan
                                    {{ $mahasiswa->fakultas->nama_fakultas ?? '' }}.
                                @else
                                    telah <b>DITOLAK</b> oleh Dekan
                                    {{ $mahasiswa->fakultas->nama_fakultas ?? '' }}.
                                @endif
                            </p>

                            @if ($isApproved)
                                <div class="highlight-box">
                                    <p>
                                        Surat Anda telah diverifikasi dan ditandatangani secara elektronik
                                        oleh Dekan. Dokumen resmi dapat Anda akses melalui
                                        <b>Sistem Informasi Pengajuan Surat Mahasiswa Terpadu</b>
                                        (menu riwayat pengajuan atau melalui pemindaian QR Code pada surat).
                                    </p>
                                </div>
                            @else
                                <div class="highlight-box reject">
                                    <p>
                                        Pengajuan Anda <b>tidak dapat disetujui</b> oleh Dekan.
                                        Silakan perhatikan catatan di bawah ini untuk melakukan perbaikan
                                        atau pengajuan ulang jika diperlukan.
                                    </p>
                                </div>
                            @endif

                            @if (!empty($catatan))
                                <div class="note-box">
                                    <b>Catatan dari Dekan:</b><br>
                                    “{{ $catatan }}”
                                </div>
                            @endif

                            <p>
                                Anda dapat melihat status lengkap dan dokumen (jika tersedia) melalui
                                <b>Sistem Informasi Pengajuan Surat Mahasiswa Terpadu</b>.
                            </p>

                            <p style="margin-top: 25px;">
                                Salam hormat,<br>
                                <b>Dekan {{ $mahasiswa->fakultas->nama_fakultas ?? '' }}</b>
                            </p>
                        </td>
                    </tr>

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
