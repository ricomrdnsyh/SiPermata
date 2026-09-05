@extends('layout.main')
@section('title', 'Surat Keterangan Lulus')
@section('css')
    <style>
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
                                        Detail Surat Permohonan Keterangan Lulus
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Silakan lihat detail pengajuan Anda !</div>
                                </div>
                                <div class="separator border-2 border-dashed mb-10"></div>
                                <div id="form-container" class="mt-2">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                        
                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa</h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">NIM</label>
                                                    <input type="text" name="nim" class="form-control"
                                                        value="{{ auth()->user()->reference_id }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" name="akademik_id" class="form-control"
                                                        value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">IPK</label>
                                                    <input type="text" name="ipk" class="form-control"
                                                        value="{{ $dataSimpt?->ipk_ketuntasan ?? '-' }}" disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i class="fas fa-user text-gray-400 me-2"></i> Data Pribadi Mahasiswa</h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tempat Lahir</label>
                                                    <input type="text" name="tempat_lahir" class="form-control"
                                                        value="{{ $surat->tempat_lahir }}" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tanggal Lahir</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tgl_lahir" class="form-control ps-12"
                                                            value="{{ $surat->tgl_lahir?->locale('id')->isoFormat('D MMMM YYYY') }}"
                                                            disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i class="fas fa-book text-gray-400 me-2"></i> Data Tugas Akhir</h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Judul Penelitian/Tugas Akhir</label>
                                                    <textarea name="judul_penelitian" class="form-control" rows="3" disabled>{{ old('judul_penelitian', $surat->judul_penelitian) }}</textarea>
                                                </div>

                                                @if ($surat->catatan)
                                                <div class="col-md-12 mt-5">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
