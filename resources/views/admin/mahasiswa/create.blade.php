@extends('layout.main')

@section('title', 'Tambah Mahasiswa')

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
                                        <h2>Tambah Mahasiswa</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                        action="{{ route('admin.mahasiswa.store') }}" method="POST">
                                        @csrf
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                            <input type="number" name="nim" class="form-control mb-3 mb-lg-0"
                                                value="{{ old('nim') }}" />
                                            @error('nim')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                            <input type="text" name="nama" class="form-control mb-3 mb-lg-0"
                                                value="{{ old('nama') }}" />
                                            @error('nama')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-7">
                                            <label class="required fw-semibold fs-6 mb-5">Jenis Kelamin</label>
                                            <div class="d-flex fv-col">
                                                <div class="form-check form-check-custom form-check-solid mx-4">
                                                    <input class="form-check-input" name="jenis_kelamin" type="radio"
                                                        value="L" id="L" />
                                                    <label class="form-check-label" for="L">
                                                        <div class="fw-bold text-gray-800">Laki-laki</div>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-custom form-check-solid mx-4">
                                                    <input class="form-check-input" name="jenis_kelamin" type="radio"
                                                        value="P" id="P" Label-->
                                                    <label class="form-check-label" for="P">
                                                        <div class="fw-bold text-gray-800">Perempuan</div>
                                                    </label>
                                                </div>
                                            </div>
                                            @error('jenis_kelamin')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                            <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                                data-control="select2" data-placeholder="Pilih Fakultas" name="fakultas_id"
                                                id="fakultas_id" data-select2-id="select2-data-72-r5i4" tabindex="-1"
                                                aria-hidden="true" data-kt-initialized="1">
                                                <option value="" data-select2-id="select2-data-74-9zwr">
                                                    Pilih Fakultas...</option>
                                                @foreach ($fakultas as $fakultas)
                                                    <option value="{{ $fakultas->id_fakultas }}">
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
                                            <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                                data-control="select2" data-placeholder="Pilih Prodi" id="prodi_id"
                                                name="prodi_id" data-select2-id="select2-data-72-r5i5" tabindex="-1"
                                                aria-hidden="true" data-kt-initialized="2">
                                                <option value="" data-select2-id="select2-data-74-9zwr">
                                                    Pilih Prodi</option>
                                            </select>
                                            @error('prodi_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Email</label>
                                            <input type="email" name="email" class="form-control mb-3 mb-lg-0"
                                                value="{{ old('email') }}" />
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">No Telepon</label>
                                            <input type="number" name="no_hp" class="form-control mb-3 mb-lg-0"
                                                value="{{ old('no_hp') }}" />
                                            @error('no_hp')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="separator mb-6"></div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light me-3">
                                                Batal
                                            </a>
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-primary">
                                                <span class="indicator-label">
                                                    Tambah
                                                </span>
                                                <span class="indicator-progress">
                                                    Tunggu sebentar...
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
            const fakultasDropdown = document.getElementById('fakultas_id');
            const prodiDropdown = $('#prodi_id');

            $('#fakultas_id').on('change', function() {
                const fakultasId = this.value;

                prodiDropdown.empty().trigger('change');
                prodiDropdown.append('<option value="">Pilih Prodi</option>');

                if (fakultasId) {
                    fetch(`/admin/get-prodim/${fakultasId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Data prodi:', data);

                            prodiDropdown.empty().append(
                                '<option value="">-- Pilih Prodi --</option>');

                            data.forEach(prodi => {
                                const option = new Option(prodi.nama_prodi, prodi
                                    .id_prodi,
                                    false, false);
                                prodiDropdown.append(option);
                            });

                            prodiDropdown.trigger('change');
                        })
                        .catch(err => {
                            console.error('Gagal ambil data prodi:', err);
                        });
                }
            });
        });
    </script>
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
