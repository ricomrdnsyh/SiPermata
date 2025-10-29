@extends('layout.main')

@section('title', 'Detail TTD Surat')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="row g-7 ">
                        <div class="col-xl-6 py-3 py-lg-6 mb-5 w-100">
                            <div class="card card-flush h-lg-100" id="kt_contacts_main">
                                <div class="card-header pt-7" id="kt_chat_contacts_header">
                                    <div class="card-title">
                                        <h2>Detail TTD Surat</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Nama Template</label>
                                            <input type="text" name="template_id" class="form-control mb-3 mb-lg-0"
                                                disabled value="{{ $ttd->template->nama_template }}" />
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Nama TTD Surat</label>
                                            <input type="text" name="nama_ttd" class="form-control mb-3 mb-lg-0" disabled
                                                value="{{ $ttd->nama_ttd }}" />
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">NIDN</label>
                                            <input type="text" name="nidn" class="form-control mb-3 mb-lg-0" disabled
                                                value="{{ $ttd->nidn }}" />
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                            <input type="text" name="fakultas_id" class="form-control mb-3 mb-lg-0"
                                                disabled value="{{ $ttd->fakultas->nama_fakultas }}" />
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Status</label>
                                            <input type="text" name="status" class="form-control mb-3 mb-lg-0" disabled
                                                value="{{ ucfirst(strtolower($ttd->status)) }}" />
                                        </div>
                                        <div class="separator mb-6"></div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('bak.ttdSurat.index') }}" class="btn btn-light me-3">
                                                Kembali
                                            </a>
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
