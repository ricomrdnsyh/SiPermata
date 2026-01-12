@extends('layout.main')
@section('title', 'Edit TTD Surat')
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
                                        <h2>Edit TTD Surat</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                        action="{{ route('bak.ttdSurat.update', $ttd->id_ttd) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Template</label>
                                                    <select
                                                        class="form-select form-select-sm select2-hidden-accessible w-100"
                                                        data-control="select2" data-placeholder="Pilih Template"
                                                        name="template_id" id="template_id"
                                                        data-select2-id="select2-data-72-r5i3" tabindex="-1"
                                                        aria-hidden="true" data-kt-initialized="1" required>
                                                        <option value="" data-select2-id="select2-data-74-9zwr">
                                                            Pilih Template...</option>
                                                        @foreach ($template as $tmp)
                                                            <option value="{{ $tmp->id_template }}"
                                                                {{ old('template_id', $ttd->template_id) == $tmp->id_template ? 'selected' : '' }}>
                                                                {{ $tmp->nama_template }} -
                                                                {{ $tmp->fakultas->nama_fakultas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('template_id')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama TTD Surat</label>
                                                    <input type="text" name="nama_ttd"
                                                        class="form-control form-control-sm mb-3 mb-lg-0"
                                                        value="{{ old('nama_ttd', $ttd->nama_ttd) }}" required />
                                                    @error('nama_ttd')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">NIDN/NUPTK</label>
                                                    <input type="text" name="nidn"
                                                        class="form-control form-control-sm mb-3 mb-lg-0"
                                                        value="{{ old('nidn', $ttd->nidn) }}" required />
                                                    @error('nidn')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                                    <select
                                                        class="form-select form-select-sm select2-hidden-accessible w-100"
                                                        data-control="select2" data-placeholder="Pilih Fakultas"
                                                        name="fakultas_id" id="fakultas_id"
                                                        data-select2-id="select2-data-72-r5i4" tabindex="-1"
                                                        aria-hidden="true" data-kt-initialized="1" required>
                                                        <option value="" data-select2-id="select2-data-74-9zwr">
                                                            Pilih Fakultas...</option>
                                                        @foreach ($fakultas as $f)
                                                            <option value="{{ $f->id_fakultas }}"
                                                                {{ old('fakultas_id', $ttd->fakultas_id) == $f->id_fakultas ? 'selected' : '' }}>
                                                                {{ $f->nama_fakultas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('fakultas_id')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">Status</label>
                                                    <div class="d-flex fv-col mt-2">
                                                        <div class="form-check form-check-custom form-check-solid me-8">
                                                            <input class="form-check-input" name="status" type="radio"
                                                                value="aktif" id="aktif"
                                                                {{ old('status', $ttd->status) == 'aktif' ? 'checked' : '' }}
                                                                required />
                                                            <label class="form-check-label" for="aktif">
                                                                <div class="fw-bold text-gray-800">Aktif</div>
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input" name="status" type="radio"
                                                                value="nonaktif" id="nonaktif"
                                                                {{ old('status', $ttd->status) == 'nonaktif' ? 'checked' : '' }}
                                                                required />
                                                            <label class="form-check-label" for="nonaktif">
                                                                <div class="fw-bold text-gray-800">Nonaktif</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @error('status')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator mb-6"></div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('bak.ttdSurat.index') }}" class="btn btn-sm btn-light me-3">
                                                Batal
                                            </a>
                                            <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
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
