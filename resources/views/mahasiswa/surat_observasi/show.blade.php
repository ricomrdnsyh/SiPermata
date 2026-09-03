@extends('layout.main')
@section('title', 'Surat Permohonan Observasi')
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
    @php
        $daftarMahasiswa = collect($surat->daftar_mahasiswa ?? [])->values();
        $isKelompok = $daftarMahasiswa->count() > 1;
    @endphp

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
                                        Detail Surat Permohonan Observasi
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
                                                <div class="col-md-4">
                                                    <label class="fw-semibold fs-6 mb-2">NIM</label>
                                                    <input type="text" name="nim" class="form-control"
                                                        value="{{ auth()->user()->reference_id }}" disabled />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" name="akademik_id" class="form-control"
                                                        value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}"
                                                        disabled />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" name="semester" class="form-control"
                                                        value="{{ $surat->semester }}" disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i class="fas fa-building text-gray-400 me-2"></i> Detail Observasi</h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tempat Observasi</label>
                                                    <input type="text" name="mitra_id" class="form-control"
                                                        value="{{ $surat->mitra ? $surat->mitra->nama_mitra : '-' }}"
                                                        disabled />
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tanggal Observasi</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tgl_observasi"
                                                            class="form-control ps-12"
                                                            value="{{ $surat->tgl_observasi?->locale('id')->isoFormat('D MMMM YYYY') }}"
                                                            disabled />
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Keperluan Observasi</label>
                                                    <textarea name="keperluan" class="form-control" rows="3" disabled>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                                </div>

                                                @if($surat->catatan)
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">Catatan Verifikator</label>
                                                    <div class="alert alert-dismissible bg-light-danger border border-danger border-dashed d-flex align-items-start w-100 p-4 p-sm-5 mb-0">
                                                        <i class="fas fa-exclamation-circle fs-2hx text-danger me-4"></i>
                                                        <div class="d-flex flex-column pe-0">
                                                            <h5 class="mb-1 text-danger">Catatan dari Verifikator</h5>
                                                            <span>{{ $surat->catatan }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($isKelompok)
                                        <div class="form-group-box">
                                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-6 p-6">
                                                <i class="fas fa-users fs-2x text-primary me-4 mt-1"></i>
                                                <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                                    <div class="mb-3 mb-md-0 fw-semibold">
                                                        <h5 class="text-gray-900 fw-bold mb-1">Pengajuan Kelompok</h5>
                                                        <div class="fs-7 text-gray-700 pe-7">Surat ini diajukan secara berkelompok dengan anggota di bawah ini.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="fv-row mb-0">
                                                <div class="d-none d-md-flex row g-3 mb-3 fw-bold text-muted fs-7 px-2">
                                                    <div class="col-md-3">NIM Anggota</div>
                                                    <div class="col-md-4">Nama Mahasiswa</div>
                                                    <div class="col-md-3">Program Studi</div>
                                                    <div class="col-md-2 text-center">Status</div>
                                                </div>

                                                <div id="anggota-kelompok-container">
                                                    @foreach ($daftarMahasiswa as $index => $mahasiswa)
                                                        <div class="anggota-item bg-body border rounded p-3 mb-3 shadow-sm">
                                                            <div class="row g-3 align-items-center">
                                                                <div class="col-12 col-md-3">
                                                                    <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">NIM Anggota</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm form-control-solid"
                                                                        value="{{ data_get($mahasiswa, 'nim', '-') }}"
                                                                        disabled />
                                                                </div>
                                                                <div class="col-12 col-md-4">
                                                                    <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">Nama Mahasiswa</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm form-control-solid"
                                                                        value="{{ data_get($mahasiswa, 'nama', '-') }}"
                                                                        disabled />
                                                                </div>
                                                                <div class="col-12 col-md-3">
                                                                    <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">Program Studi</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm form-control-solid"
                                                                        value="{{ data_get($mahasiswa, 'prodi', '-') }}"
                                                                        disabled />
                                                                </div>
                                                                <div class="col-12 col-md-2 text-md-center mt-3 mt-md-0">
                                                                    <label class="fw-semibold fs-7 mb-1 d-md-none text-muted w-100">Status</label>
                                                                    <span class="badge {{ data_get($mahasiswa, 'is_ketua') ? 'badge-light-primary' : 'badge-light-success' }} fs-7 px-4 py-2">
                                                                        {{ data_get($mahasiswa, 'is_ketua') ? 'Ketua' : 'Anggota' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        

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
