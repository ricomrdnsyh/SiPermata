@extends('layout.main')
@section('title', 'Dashboard')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">

                <div class="card card-flush mb-7">
                    <div class="card-body p-0">
                        <div class="px-8 py-7 rounded" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-6">
                                <div class="d-flex align-items-start gap-4">
                                    <span class="symbol symbol-45px">
                                        <span class="symbol-label bg-white bg-opacity-15">
                                            <i class="fas fa-layer-group text-white fs-3"></i>
                                        </span>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <div class="fs-2 fw-semibold text-white lh-sm">
                                            Halo, <span class="fw-bold">{{ $user_name ?? 'Pengguna' }}</span>
                                        </div>
                                        <div class="text-white text-opacity-75 fw-semibold fs-7 mt-1">
                                            Sistem Pengajuan Surat Mahasiswa Terpadu • Cek status pengajuan atau buat
                                            pengajuan baru
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ asset('panduan.pdf') }}" target="_blank" rel="noopener noreferrer"
                                        class="btn btn-sm btn-light fw-semibold">
                                        <i class="fas fa-book-open me-2"></i>Buku Panduan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning d-flex align-items-center p-5 mb-7">
                    <span class="symbol symbol-40px me-4">
                        <span class="symbol-label bg-light-warning">
                            <i class="fas fa-exclamation-triangle text-warning fs-3"></i>
                        </span>
                    </span>
                    <div class="d-flex flex-column">
                        <span class="fw-bolder fs-6 mb-1">Informasi</span>
                        <span class="fs-7 text-gray-700">
                            Jika Tempat Penelitian, Tempat Observasi, dan Tempat PKL tidak ada pada form pengajuan, silahkan
                            menghubungi BAK Fakultas untuk menambahkan.
                            <span class="d-block mt-1">
                                Informasi lainnya:
                                <a href="https://sipermata.unuja.ac.id/" target="_blank"
                                    class="text-primary fw-bold text-decoration-underline">
                                    https://sipermata.unuja.ac.id
                                </a>
                            </span>
                        </span>
                    </div>
                </div>

                <div class="row g-5 g-xl-8">

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-flush h-100 hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-42px">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="fas fa-file-alt text-primary fs-3"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-semibold text-gray-900">Surat Keterangan Aktif</span>
                                            <span class="text-gray-600 fw-semibold fs-7">Status aktif mahasiswa</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded p-4 mb-5" style="background: rgba(59, 130, 246, 0.08);">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <span class="text-gray-700 fw-semibold">Total Pengajuan</span>
                                        <span class="fs-2 fw-bold text-primary">{{ $surat_aktif['total'] }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <span class="badge badge-light-warning fw-semibold px-3 py-2">Proses:
                                            {{ $surat_aktif['proses'] }}</span>
                                        <span class="badge badge-light-success fw-semibold px-3 py-2">Selesai:
                                            {{ $surat_aktif['selesai'] }}</span>
                                        <span class="badge badge-light-danger fw-semibold px-3 py-2">Ditolak:
                                            {{ $surat_aktif['ditolak'] }}</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-aktif.create') }}"
                                        class="btn btn-sm btn-primary flex-fill">
                                        <i class="fas fa-plus me-2"></i>Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.surat-aktif.index') }}"
                                        class="btn btn-sm btn-light-primary text-primary flex-fill">
                                        <i class="fas fa-history me-2"></i>Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-flush h-100 hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-42px">
                                            <span class="symbol-label bg-light-warning">
                                                <i class="fas fa-flask text-warning fs-3"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-semibold text-gray-900">Surat Izin Penelitian</span>
                                            <span class="text-gray-600 fw-semibold fs-7">Izin penelitian mahasiswa</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded p-4 mb-5" style="background: rgba(245, 158, 11, 0.10);">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <span class="text-gray-700 fw-semibold">Total Pengajuan</span>
                                        <span class="fs-2 fw-bold text-warning">{{ $surat_penelitian['total'] }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <span class="badge badge-light-warning fw-semibold px-3 py-2">Proses:
                                            {{ $surat_penelitian['proses'] }}</span>
                                        <span class="badge badge-light-success fw-semibold px-3 py-2">Selesai:
                                            {{ $surat_penelitian['selesai'] }}</span>
                                        <span class="badge badge-light-danger fw-semibold px-3 py-2">Ditolak:
                                            {{ $surat_penelitian['ditolak'] }}</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-izin-penelitian.create') }}"
                                        class="btn btn-sm btn-warning flex-fill">
                                        <i class="fas fa-plus me-2"></i>Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.surat-izin-penelitian.index') }}"
                                        class="btn btn-sm btn-light-warning text-warning flex-fill">
                                        <i class="fas fa-history me-2"></i>Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-flush h-100 hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-42px">
                                            <span class="symbol-label bg-light-info">
                                                <i class="fas fa-eye text-info fs-3"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-semibold text-gray-900">Surat Permohonan Observasi</span>
                                            <span class="text-gray-600 fw-semibold fs-7">Permohonan observasi</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded p-4 mb-5" style="background: rgba(59, 130, 246, 0.08);">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <span class="text-gray-700 fw-semibold">Total Pengajuan</span>
                                        <span class="fs-2 fw-bold text-info">{{ $surat_observasi['total'] }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <span class="badge badge-light-warning fw-semibold px-3 py-2">Proses:
                                            {{ $surat_observasi['proses'] }}</span>
                                        <span class="badge badge-light-success fw-semibold px-3 py-2">Selesai:
                                            {{ $surat_observasi['selesai'] }}</span>
                                        <span class="badge badge-light-danger fw-semibold px-3 py-2">Ditolak:
                                            {{ $surat_observasi['ditolak'] }}</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-observasi.create') }}"
                                        class="btn btn-sm btn-info flex-fill">
                                        <i class="fas fa-plus me-2"></i>Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.surat-observasi.index') }}"
                                        class="btn btn-sm btn-light-info text-info flex-fill">
                                        <i class="fas fa-history me-2"></i>Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-flush h-100 hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-42px">
                                            <span class="symbol-label bg-light-dark">
                                                <i class="fas fa-thumbs-up text-dark fs-3"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-semibold text-gray-900">Surat Rekomendasi</span>
                                            <span class="text-gray-600 fw-semibold fs-7">Rekomendasi mahasiswa</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded p-4 mb-5" style="background: rgba(17, 24, 39, 0.06);">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <span class="text-gray-700 fw-semibold">Total Pengajuan</span>
                                        <span class="fs-2 fw-bold text-dark">{{ $surat_rekomendasi['total'] }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <span class="badge badge-light-warning fw-semibold px-3 py-2">Proses:
                                            {{ $surat_rekomendasi['proses'] }}</span>
                                        <span class="badge badge-light-success fw-semibold px-3 py-2">Selesai:
                                            {{ $surat_rekomendasi['selesai'] }}</span>
                                        <span class="badge badge-light-danger fw-semibold px-3 py-2">Ditolak:
                                            {{ $surat_rekomendasi['ditolak'] }}</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-rekomendasi.create') }}"
                                        class="btn btn-sm btn-dark flex-fill">
                                        <i class="fas fa-plus me-2"></i>Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.surat-rekomendasi.index') }}"
                                        class="btn btn-sm btn-light-dark text-dark flex-fill">
                                        <i class="fas fa-history me-2"></i>Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-flush h-100 hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-42px">
                                            <span class="symbol-label bg-light-danger">
                                                <i class="fas fa-briefcase text-danger fs-3"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-semibold text-gray-900">Surat Permohonan PKL</span>
                                            <span class="text-gray-600 fw-semibold fs-7">Pengantar untuk PKL</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded p-4 mb-5" style="background: rgba(239, 68, 68, 0.08);">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <span class="text-gray-700 fw-semibold">Total Pengajuan</span>
                                        <span class="fs-2 fw-bold text-danger">{{ $surat_pkl['total'] }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <span class="badge badge-light-warning fw-semibold px-3 py-2">Proses:
                                            {{ $surat_pkl['proses'] }}</span>
                                        <span class="badge badge-light-success fw-semibold px-3 py-2">Selesai:
                                            {{ $surat_pkl['selesai'] }}</span>
                                        <span class="badge badge-light-danger fw-semibold px-3 py-2">Ditolak:
                                            {{ $surat_pkl['ditolak'] }}</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-pkl.create') }}"
                                        class="btn btn-sm btn-danger flex-fill">
                                        <i class="fas fa-plus me-2"></i>Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.surat-pkl.index') }}"
                                        class="btn btn-sm btn-light-danger text-danger flex-fill">
                                        <i class="fas fa-history me-2"></i>Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-flush h-100 hover-elevate-up">
                            <div class="card-body p-6">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-42px">
                                            <span class="symbol-label bg-light-success">
                                                <i class="fas fa-graduation-cap text-success fs-3"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-semibold text-gray-900">Surat Keterangan Lulus</span>
                                            <span class="text-gray-600 fw-semibold fs-7">Ijazah, beasiswa, dll</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded p-4 mb-5" style="background: rgba(34, 197, 94, 0.08);">
                                    <div class="d-flex align-items-end justify-content-between">
                                        <span class="text-gray-700 fw-semibold">Total Pengajuan</span>
                                        <span class="fs-2 fw-bold text-success">{{ $surat_lulus['total'] }}</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <span class="badge badge-light-warning fw-semibold px-3 py-2">Proses:
                                            {{ $surat_lulus['proses'] }}</span>
                                        <span class="badge badge-light-success fw-semibold px-3 py-2">Selesai:
                                            {{ $surat_lulus['selesai'] }}</span>
                                        <span class="badge badge-light-danger fw-semibold px-3 py-2">Ditolak:
                                            {{ $surat_lulus['ditolak'] }}</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-3">
                                    <a href="{{ route('mahasiswa.surat-keterangan-lulus.create') }}"
                                        class="btn btn-sm btn-success flex-fill">
                                        <i class="fas fa-plus me-2"></i>Ajukan
                                    </a>
                                    <a href="{{ route('mahasiswa.surat-keterangan-lulus.index') }}"
                                        class="btn btn-sm btn-light-success text-success flex-fill">
                                        <i class="fas fa-history me-2"></i>Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
