<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../../../">
    <title>Sistem Informasi Pengajuan Surat Terpadu</title>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/unuja.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        html,
        body {
            height: 100%;
        }
    </style>
</head>

<body id="kt_body_custom" class="bg-body d-flex justify-content-center align-items-center min-vh-100">
    <div class="container" style="max-width: 800px;">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-0 w-100 d-flex justify-content-center align-items-center" style="min-height: 50px;">
                    ❌ Verifikasi Dokumen Gagal!
                </h3>
            </div>
            <div class="card-body">
                <p class="lead text-center">Dokumen ini belum ditandatangani secara elektronik oleh pejabat
                    yang berwewenang. Data yang tercantum sesuai dengan data sistem.</p>

                <div class="separator border-gray-200"></div>

                <h5 class="my-6">Detail Surat Permohonan Observasi</h5>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td>Status Verifikasi</td>
                        <td><span class="badge bg-danger">{{ $status_verifikasi }}</span></td>
                    </tr>
                    <tr>
                        <td>Jenis Surat</td>
                        <td>Surat Permohonan Observasi</td>
                    </tr>
                    <tr>
                        <td>Nama Mahasiswa</td>
                        <td>{{ $surat->mahasiswa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>{{ $surat->nim }}</td>
                    </tr>
                    <tr>
                        <td>Fakultas</td>
                        <td>{{ $surat->mahasiswa->fakultas->nama_fakultas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>{{ $surat->mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tahun Akademik</td>
                        <td>{{ $surat->akademik->tahun_akademik ?? '-' }} (Semester {{ $surat->semester }})</td>
                    </tr>
                    <tr>
                        <td>Penandatangan</td>
                        <td>
                            {{ $ttd_dekan->nama_ttd ?? 'Tidak Ditemukan' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Waktu Persetujuan</td>
                        <td>Belum disetujui</td>
                    </tr>
                </table>

                <div class="alert alert-info mt-4">
                    Dokumen ini merupakan hasil cetak dari dokumen elektronik yang ditandatangani menggunakan QR Code.
                </div>
            </div>
            <div class="card-footer text-end">
                <small class="text-muted">Sistem Informasi Pengajuan Surat Terpadu</small>
            </div>
        </div>
    </div>
    <script>
        var hostUrl = "assets/";
    </script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>

</html>
