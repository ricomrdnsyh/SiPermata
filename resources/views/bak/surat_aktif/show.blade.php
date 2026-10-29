@extends('layout.main')
@section('title', 'Detail Surat Keterangan Aktif')
@section('css')
    <style>
        .form-section {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        .form-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group-box {
            background-color: var(--bs-gray-100);
            border: 1px dashed var(--bs-gray-300);
            border-radius: 0.75rem;
            padding: 1.75rem 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        @media (max-width: 767.98px) {
            .form-group-box {
                padding: 1.25rem 1rem;
            }
        }

        .form-group-box:hover {
            border-color: var(--bs-gray-400);
            background-color: var(--bs-gray-200);
        }

        html[data-theme="dark"] .form-group-box,
        body[data-theme="dark"] .form-group-box,
        [data-bs-theme="dark"] .form-group-box {
            background-color: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
        }

        html[data-theme="dark"] .form-group-box:hover,
        body[data-theme="dark"] .form-group-box:hover,
        [data-bs-theme="dark"] .form-group-box:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
        }
</style>
@endsection
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="card shadow-sm border border-dashed border-dark rounded-4">
                        <div class="card-body p-lg-12">
                            <div class="d-flex flex-column">
                                <div class="mb-10 text-center">
                                    <h1 class="fs-2hx fw-bolder mb-3 text-dark">
                                        <i class="fas fa-file-signature fs-2hx text-primary me-2 align-middle"></i>
                                        Detail Surat Keterangan Aktif
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Silakan lihat detail pengajuan!</div>
                                </div>

                                <div class="separator border-2 border-dashed mb-10"></div>
<div id="form-container">
                                    <form id="form-umum" class="form-section active">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-primary me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Kategori Umum</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nim . ' - ' . ($surat->mahasiswa?->nama ?? '-') }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}" disabled />
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->semester }}" disabled />
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Alamat Saat Ini</label>
                                                    <textarea class="form-control form-control-sm" rows="3" disabled>{{ $surat->alamat }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat Keterangan
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea class="form-control form-control-sm" rows="3" disabled>{{ $surat->keperluan }}</textarea>
                                                </div>
                                                @if ($surat->catatan)
                                                    <div class="col-12 mt-4">
                                                        <label class="fw-semibold fs-6 mb-2">Catatan Verifikator</label>
                                                        <div class="alert alert-dismissible bg-light-warning border border-warning border-dashed d-flex align-items-start w-100 p-4 p-sm-5 mb-0">
                                                            <i class="fas fa-exclamation-circle fs-2hx text-warning me-4"></i>
                                                            <div class="d-flex flex-column pe-0">
                                                                <h5 class="mb-1 text-warning">Catatan dari Verifikator</h5>
                                                                <span>{{ $surat->catatan }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                    <form id="form-pns" class="form-section">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-info me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Kategori PNS</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nim . ' - ' . ($surat->mahasiswa?->nama ?? '-') }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}" disabled />
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->semester }}" disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-user-tie text-gray-400 me-2"></i> Data Orang Tua (PNS)
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">NIP Sesuai SK</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nip }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Nama Lengkap Sesuai SK</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nama_ortu }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Pendidikan Terakhir</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->pendidikan_terakhir }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->pangkat }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->golongan }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Mulai Tugas (TMT)</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" class="form-control form-control-sm ps-12"
                                                            value="{{ $surat->tmt ? $surat->tmt->locale('id')->isoFormat('D MMMM YYYY') : '-' }}" disabled />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->unit_kerja }}" disabled />
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai SK</label>
                                                    <textarea class="form-control form-control-sm" rows="3" disabled>{{ $surat->alamat }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat Keterangan
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea class="form-control form-control-sm" rows="3" disabled>{{ $surat->keperluan }}</textarea>
                                                </div>
                                                @if ($surat->catatan)
                                                    <div class="col-12 mt-4">
                                                        <label class="fw-semibold fs-6 mb-2">Catatan Verifikator</label>
                                                        <div class="alert alert-dismissible bg-light-warning border border-warning border-dashed d-flex align-items-start w-100 p-4 p-sm-5 mb-0">
                                                            <i class="fas fa-exclamation-circle fs-2hx text-warning me-4"></i>
                                                            <div class="d-flex flex-column pe-0">
                                                                <h5 class="mb-1 text-warning">Catatan dari Verifikator</h5>
                                                                <span>{{ $surat->catatan }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                    <form id="form-pppk" class="form-section">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-success me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Kategori PPPK</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nim . ' - ' . ($surat->mahasiswa?->nama ?? '-') }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}" disabled />
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->semester }}" disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-user-clock text-gray-400 me-2"></i> Data Orang Tua (PPPK)
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">NIP / NI PPPK</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nip }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Nama Lengkap Sesuai SK</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nama_ortu }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Pendidikan Terakhir</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->pendidikan_terakhir }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->pangkat }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->golongan }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Mulai Tugas (TMT)</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" class="form-control form-control-sm ps-12"
                                                            value="{{ $surat->tmt ? $surat->tmt->locale('id')->isoFormat('D MMMM YYYY') : '-' }}" disabled />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->unit_kerja }}" disabled />
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai SK</label>
                                                    <textarea class="form-control form-control-sm" rows="3" disabled>{{ $surat->alamat }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat Keterangan
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea class="form-control form-control-sm" rows="3" disabled>{{ $surat->keperluan }}</textarea>
                                                </div>
                                                @if ($surat->catatan)
                                                    <div class="col-12 mt-4">
                                                        <label class="fw-semibold fs-6 mb-2">Catatan Verifikator</label>
                                                        <div class="alert alert-dismissible bg-light-warning border border-warning border-dashed d-flex align-items-start w-100 p-4 p-sm-5 mb-0">
                                                            <i class="fas fa-exclamation-circle fs-2hx text-warning me-4"></i>
                                                            <div class="d-flex flex-column pe-0">
                                                                <h5 class="mb-1 text-warning">Catatan dari Verifikator</h5>
                                                                <span>{{ $surat->catatan }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                
                                    <div class="d-flex justify-content-center mt-8">
                                        <a href="{{ route('bak.surat-aktif.index') }}" class="btn btn-secondary btn-sm px-6 w-100 w-md-auto">
                                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Surat
                                        </a>
                                    </div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kategori = "{{ $surat->kategori }}";
            const formMap = {
                'UMUM': 'form-umum',
                'PNS': 'form-pns',
                'PPPK': 'form-pppk'
            };
            const activeFormId = formMap[kategori] || 'form-umum';
            const allForms = [
                document.getElementById('form-umum'),
                document.getElementById('form-pns'),
                document.getElementById('form-pppk')
            ];
            allForms.forEach(form => {
                if (form) form.classList.remove('active');
            });
            const activeForm = document.getElementById(activeFormId);
            if (activeForm) {
                activeForm.classList.add('active');
            }
        });
    </script>
@endsection
