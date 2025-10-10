@extends('layout.main')

@section('title', 'Edit Fakultas')

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
                                        <h2>Edit Fakultas</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                        action="{{ route('admin.fakultas.update', $data->id_fakultas) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                            <input type="text" name="nama_fakultas" class="form-control mb-3 mb-lg-0"
                                                value="{{ $data->nama_fakultas }}" />
                                            @error('nama_fakultas')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Singkatan Fakultas</label>
                                            <input type="text" name="singkatan" class="form-control mb-3 mb-lg-0"
                                                value="{{ $data->singkatan }}" />
                                            @error('singkatan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-7">
                                            <label class="required fw-semibold fs-6 mb-5">Status</label>
                                            <div class="d-flex fv-col">
                                                <div class="form-check form-check-custom form-check-solid mx-4">
                                                    <input class="form-check-input" name="status" type="radio"
                                                        value="aktif" id="aktif"
                                                        {{ $data->status == 'aktif' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="aktif">
                                                        <div class="fw-bold text-gray-800">Aktif</div>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid mx-4">
                                                    <input class="form-check-input" name="status" type="radio"
                                                        value="nonaktif" id="nonaktif"
                                                        {{ $data->status == 'nonaktif' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="nonaktif">
                                                        <div class="fw-bold text-gray-800">Nonaktif</div>
                                                    </label>
                                                </div>
                                            </div>
                                            @error('status')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="separator mb-6"></div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('admin.fakultas.index') }}" class="btn btn-light me-3">
                                                Batal
                                            </a>
                                            <button type="submit" data-kt-contacts-type="submit" class="btn btn-primary">
                                                <span class="indicator-label">
                                                    Update
                                                </span>
                                                <span class="indicator-progress">
                                                    Tunggu sebentar...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
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

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('kt_ecommerce_settings_general_form');
            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    return;
                }
                submitButton.disabled = true;
                submitButton.querySelector('.indicator-label').style.display = 'none';
                submitButton.querySelector('.indicator-progress').style.display = 'inline-block';
            });
        });
    </script>
@endsection
