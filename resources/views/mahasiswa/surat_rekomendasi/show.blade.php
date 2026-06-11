@extends('layout.main')
@section('title', 'Surat Rekomendasi')
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm border border-dashed border-dark rounded">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Detail Surat Permohonan Rekomendasi</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan lihat detail pengajuan Anda !</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">NIM</label>
                                                <input type="text" name="nim"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ auth()->user()->reference_id }}" disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                <input type="text" name="akademik_id"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}"
                                                    disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="text" name="semester"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $dataSimpt?->semester ?? '-' }}" disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">IPK</label>
                                                <input type="text" name="ipk"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $dataSimpt?->ipk_ketuntasan ?? '-' }}" disabled />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tanggal Pelaksanaan</label>
                                                <input type="text" name="tgl_pelaksanaan"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $surat->tgl_pelaksanaan?->locale('id')->isoFormat('D MMMM YYYY') }}"
                                                    disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Keperluan Rekomendasi</label>
                                                <textarea name="keperluan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Penyelenggara</label>
                                                <textarea name="penyelenggara" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('penyelenggara', $surat->penyelenggara) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Catatan</label>
                                                <textarea name="catatan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
                                            </div>
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
