@extends('layout.main')

@section('title', 'Surat Permohonan Observasi')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-5">Detail Surat Permohonan Observasi</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan lihat detail pengajuan Anda !</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>

                            <!-- Form Container -->
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">NIM</label>
                                        <input type="text" name="nim" class="form-control form-control-sm mb-3 mb-lg-0"
                                            value="{{ auth()->user()->reference_id }}" disabled />
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                        <input type="text" name="akademik_id" class="form-control form-control-sm mb-3 mb-lg-0"
                                            value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}"
                                            disabled />
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">Semester</label>
                                        <input type="text" name="semester" class="form-control form-control-sm mb-3 mb-lg-0"
                                            value="{{ $surat->semester }}" disabled />
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">Tempat Observasi</label>
                                        <input type="text" name="mitra_id" class="form-control form-control-sm mb-3 mb-lg-0"
                                            value="{{ $surat->mitra ? $surat->mitra->nama_mitra : '-' }}" disabled />
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">Tanggal Observasi</label>
                                        <input type="text" name="tgl_observasi" class="form-control form-control-sm mb-3 mb-lg-0"
                                            value="{{ $surat->tgl_observasi?->locale('id')->isoFormat('D MMMM YYYY') }}"
                                            disabled />
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">Keperluan Observasi</label>
                                        <textarea name="keperluan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">Catatan</label>
                                        <textarea name="catatan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
