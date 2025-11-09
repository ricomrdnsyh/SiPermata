@extends('layout.main')

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="container-fluid">
                    <div class="row gx-5 gx-xl-10">
                        <div class="col-12 mb-6">
                            <div class="card border-transparent mb-5" data-bs-theme="light"
                                style="background-color: #1C325E;">
                                <div class="card-body d-flex ps-xl-15 position-relative">
                                    <div class="m-0">
                                        <div class="position-relative fs-2x z-index-2 fw-bold text-white mb-7">
                                            <span class="me-2">
                                                Halo
                                                <span class="position-relative d-inline-block text-danger">
                                                    {{ $user_name ?? 'Pengguna' }}
                                                    <span
                                                        class="position-absolute opacity-50 bottom-0 start-0 border-4 border-danger border-bottom w-100"></span>
                                                </span>
                                            </span>
                                            <br>
                                            Selamat datang di <span
                                                class="position-relative d-inline-block text-danger">Sistem Pengajuan Surat
                                                Terpadu</span>
                                            <br>
                                            Cek status pengajuanmu atau segera buat pengajuan surat baru!
                                        </div>
                                    </div>
                                    <img src="{{ asset('assets/media/illustrations/sigma-1/17-dark.png') }}"
                                        class="d-none d-md-block position-absolute me-3 bottom-0 end-0 h-200px"
                                        alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 g-xl-8">

                        {{-- SURAT KETERANGAN AKTIF --}}
                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-xxl-stretch border-hover-dashed h-100">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-file-alt fs-2x me-3 text-primary"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Keterangan Aktif</span>
                                            <span class="text-muted fs-7">Pengajuan status aktif mahasiswa.</span>
                                        </div>
                                    </div>

                                    {{-- Ringkasan Total --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-primary p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-primary">{{ $surat_aktif['total'] }}</div>
                                    </div>

                                    {{-- Status Detail Ringkas --}}
                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_aktif['proses'] }}</span>
                                            {{-- Proses --}}
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_aktif['selesai'] }}</span>
                                            {{-- Selesai/Diterima --}}
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_aktif['ditolak'] }}</span>
                                            {{-- Ditolak --}}
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-aktif.create') }}"
                                            class="btn btn-sm btn-primary flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-aktif.index') }}"
                                            class="btn btn-sm btn-light-primary text-primary flex-fill">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- SURAT IZIN PENELITIAN --}}
                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-xxl-stretch border-hover-dashed h-100">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-flask fs-2x me-3 text-warning"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Izin Penelitian</span>
                                            <span class="text-muted fs-7">Pengajuan surat izin penelitian mahasiswa.</span>
                                        </div>
                                    </div>

                                    {{-- Ringkasan Total --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-warning p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-warning">{{ $surat_penelitian['total'] }}</div>
                                    </div>

                                    {{-- Status Detail Ringkas --}}
                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_penelitian['proses'] }}</span>
                                            {{-- Proses --}}
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_penelitian['selesai'] }}</span>
                                            {{-- Selesai/Diterima --}}
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_penelitian['ditolak'] }}</span>
                                            {{-- Ditolak --}}
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-izin-penelitian.create') }}"
                                            class="btn btn-sm btn-warning flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-izin-penelitian.index') }}"
                                            class="btn btn-sm btn-light-warning text-warning flex-fill">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- SURAT PERMOHONAN OBSERVASI --}}
                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-xxl-stretch border-hover-dashed h-100">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-eye fs-2x me-3 text-info"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Permohonan Observasi</span>
                                            <span class="text-muted fs-7">Pengajuan surat observasi
                                                mahasiswa.</span>
                                        </div>
                                    </div>

                                    {{-- Ringkasan Total --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-info p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-info">{{ $surat_observasi['total'] }}</div>
                                    </div>

                                    {{-- Status Detail Ringkas --}}
                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_observasi['proses'] }}</span>
                                            {{-- Proses --}}
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_observasi['selesai'] }}</span>
                                            {{-- Selesai/Diterima --}}
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_observasi['ditolak'] }}</span>
                                            {{-- Ditolak --}}
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-observasi.create') }}"
                                            class="btn btn-sm btn-info flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-observasi.index') }}"
                                            class="btn btn-sm btn-light-info text-info flex-fill">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- SURAT REKOMENDASI --}}
                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-xxl-stretch border-hover-dashed h-100">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-thumbs-up fs-2x me-3 text-secondary"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Rekomendasi</span>
                                            <span class="text-muted fs-7">Pengajuan surat rekomendasi
                                                mahasiswa.</span>
                                        </div>
                                    </div>

                                    {{-- Ringkasan Total --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-secondary p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-black">{{ $surat_rekomendasi['total'] }}</div>
                                    </div>

                                    {{-- Status Detail Ringkas --}}
                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_rekomendasi['proses'] }}</span>
                                            {{-- Proses --}}
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_rekomendasi['selesai'] }}</span>
                                            {{-- Selesai/Diterima --}}
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_rekomendasi['ditolak'] }}</span>
                                            {{-- Ditolak --}}
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-rekomendasi.create') }}"
                                            class="btn btn-sm btn-secondary flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-rekomendasi.index') }}"
                                            class="btn btn-sm btn-light-secondary text-black flex-fill">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- SURAT PERMOHONAN PKL --}}
                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-xxl-stretch border-hover-dashed h-100">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-briefcase fs-2x me-3 text-danger"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Permohonan PKL</span>
                                            <span class="text-muted fs-7">Pengantar untuk keperluan PKL.</span>
                                        </div>
                                    </div>

                                    {{-- Ringkasan Total --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-danger p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-danger">{{ $surat_pkl['total'] }}</div>
                                        {{-- Total Status --}}
                                    </div>

                                    {{-- Status Detail Ringkas --}}
                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-warning fw-bolder p-2">{{ $surat_pkl['proses'] }}</span>
                                            {{-- Proses --}}
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-success fw-bolder p-2">{{ $surat_pkl['selesai'] }}</span>
                                            {{-- Selesai/Diterima --}}
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span
                                                class="badge badge-light-danger fw-bolder p-2">{{ $surat_pkl['ditolak'] }}</span>
                                            {{-- Ditolak --}}
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ route('mahasiswa.surat-pkl.create') }}"
                                            class="btn btn-sm btn-danger flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ route('mahasiswa.surat-pkl.index') }}"
                                            class="btn btn-sm btn-light-danger text-danger flex-fill">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- SURAT KETERANGAN LULUS --}}
                        <div class="col-xl-4 col-md-6 mb-5">
                            <div class="card card-xxl-stretch border-hover-dashed h-100">
                                <div class="card-body p-5">

                                    <div class="d-flex align-items-center mb-5">
                                        <i class="fas fa-graduation-cap fs-2x me-3 text-success"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-800">Surat Keterangan Lulus</span>
                                            <span class="text-muted fs-7">Diperlukan untuk ijazah, beasiswa, dll.</span>
                                        </div>
                                    </div>

                                    {{-- Ringkasan Total --}}
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-5 bg-light-success p-3 rounded-3">
                                        <div class="fw-semibold text-gray-700">Total Pengajuan Anda:</div>
                                        <div class="fs-3 fw-bolder text-success">0</div> {{-- Total Status --}}
                                    </div>

                                    {{-- Status Detail Ringkas --}}
                                    <div class="row g-2 mb-7">
                                        <div class="col-4 text-center">
                                            <span class="badge badge-light-warning fw-bolder p-2">0</span>
                                            {{-- Proses --}}
                                            <div class="text-muted fs-8 mt-1">Proses</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span class="badge badge-light-success fw-bolder p-2">0</span>
                                            {{-- Selesai/Diterima --}}
                                            <div class="text-muted fs-8 mt-1">Selesai</div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <span class="badge badge-light-danger fw-bolder p-2">0</span>
                                            {{-- Ditolak --}}
                                            <div class="text-muted fs-8 mt-1">Ditolak</div>
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                        <a href="{{ url('pengajuan/lulus') }}" class="btn btn-sm btn-success flex-fill">
                                            <i class="fas fa-plus me-2"></i> Ajukan Sekarang
                                        </a>
                                        <a href="{{ url('riwayat/lulus') }}"
                                            class="btn btn-sm btn-light-success text-success flex-fill">
                                            <i class="fas fa-history me-2"></i> Riwayat
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- END: STATUS SURAT PER JENIS --}}

                </div>
            </div>
        </div>
    </div>
@endsection
