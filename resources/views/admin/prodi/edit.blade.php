@extends('layout.main')

@section('title', 'Edit Prodi')

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
                                        <h2>Edit Prodi</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                        action="{{ route('admin.prodi.update', $prodi->id_prodi) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                            <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                                data-control="select2" data-placeholder="Pilih Fakultas" name="fakultas_id"
                                                id="fakltas_id" data-select2-id="select2-data-72-r5i4" tabindex="-1"
                                                aria-hidden="true" data-kt-initialized="1">
                                                <option value="" data-select2-id="select2-data-74-9zwr">
                                                    Pilih Fakultas...</option>
                                                @foreach ($fakultas as $fakultas)
                                                    <option value="{{ $fakultas->id_fakultas }}"
                                                        {{ $fakultas->id_fakultas == $prodi->fakultas_id ? 'selected' : '' }}>
                                                        {{ $fakultas->nama_fakultas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('fakultas_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Nama Prodi</label>
                                            <input type="text" name="nama_prodi" class="form-control mb-3 mb-lg-0"
                                                value="{{ $prodi->nama_prodi }}" />
                                            @error('nama_prodi')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Singkatan Prodi</label>
                                            <input type="text" name="singkatan" class="form-control mb-3 mb-lg-0"
                                                value="{{ $prodi->singkatan }}" />
                                            @error('singkatan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Gelar</label>
                                            <input type="text" name="gelar" class="form-control mb-3 mb-lg-0"
                                                value="{{ $prodi->gelar }}" />
                                            @error('gelar')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-7">
                                            <label class="required fw-semibold fs-6 mb-5">Status</label>
                                            <div class="d-flex fv-col">
                                                <div class="form-check form-check-custom form-check-solid mx-4">
                                                    <input class="form-check-input" name="status" type="radio"
                                                        value="aktif" id="aktif"
                                                        {{ $prodi->status == 'aktif' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="aktif">
                                                        <div class="fw-bold text-gray-800">Aktif</div>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid mx-4">
                                                    <input class="form-check-input" name="status" type="radio"
                                                        value="nonaktif" id="nonaktif"
                                                        {{ $prodi->status == 'nonaktif' ? 'checked' : '' }} />
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
                // Hanya aktifkan spinner jika form valid (opsional)
                if (!form.checkValidity()) {
                    return;
                }

                // Nonaktifkan tombol dan tampilkan spinner
                submitButton.disabled = true;
                submitButton.querySelector('.indicator-label').style.display = 'none';
                submitButton.querySelector('.indicator-progress').style.display = 'inline-block';
            });
        });
    </script>
@endsection
