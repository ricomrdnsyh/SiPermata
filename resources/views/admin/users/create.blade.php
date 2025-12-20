@extends('layout.main')

@section('title', 'Tambah User')

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
                                        <h2>Tambah User</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                        action="{{ route('admin.users.store') }}" method="POST">
                                        @csrf
                                        <div class="fv-row mb-3">
                                            <label class="required fw-semibold fs-6 mb-2">Role</label>
                                            <select class="form-select form-select-sm select2-hidden-accessible w-100"
                                                data-control="select2" data-placeholder="Pilih Role" name="type"
                                                id="userType" data-select2-id="select2-data-72-r5i3" tabindex="-1"
                                                aria-hidden="true" data-kt-initialized="1">
                                                <option value="">Pilih Role</option>
                                                <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="penduduk" {{ old('type') == 'penduduk' ? 'selected' : '' }}>
                                                    Penduduk</option>
                                                <option value="mahasiswa"
                                                    {{ old('type') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                            </select>
                                            @error('type')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- Form Mahasiswa --}}
                                        <div id="mahasiswaFields" class="fv-row mb-3" style="display:none;">
                                            <div class="mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Mahasiswa</label>
                                                <select
                                                    class="form-select form-select-sm select2-hidden-accessible w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa"
                                                    name="m_reference_id" data-select2-id="select2-data-72-r5i6"
                                                    tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                                    <option value="">Pilih Mahasiswa</option>
                                                    @foreach ($mahasiswa as $m)
                                                        <option value="{{ $m->nim }}">{{ $m->nim }} -
                                                            {{ $m->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @error('m_reference_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Password</label>
                                                <input type="password" name="m_password" class="form-control form-control-sm mb-3 mb-lg-0"
                                                    minlength="6" />
                                                @error('m_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Form Penduduk --}}
                                        <div id="pendudukFields" class="fv-row mb-3" style="display:none;">
                                            <div class="mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Penduduk</label>
                                                <select
                                                    class="form-select form-select-sm select2-hidden-accessible w-100"
                                                    data-control="select2" data-placeholder="Pilih Penduduk"
                                                    name="p_reference_id" data-select2-id="select2-data-72-r5i4"
                                                    tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                                    <option value="">Pilih Penduduk</option>
                                                    @foreach ($penduduk as $p)
                                                        <option value="{{ $p->id_penduduk }}">
                                                            {{ $p->id_penduduk }} - {{ $p->nama_penduduk }}
                                                            @if ($p->jabatan)
                                                                ({{ $p->jabatan->status }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('p_reference_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Password</label>
                                                <input type="password" name="p_password" class="form-control form-control-sm mb-3 mb-lg-0"
                                                    minlength="6" />
                                                @error('p_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Form Admin --}}
                                        <div id="adminFields" class="fv-row mb-3" style="display:none;">
                                            <div class="mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Username</label>
                                                <input type="text" name="identifier" class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ old('identifier') }}" />
                                                @error('identifier')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama</label>
                                                <input type="text" name="nama" class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ old('nama') }}" />
                                                @error('nama')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Password</label>
                                                <input type="password" name="password" class="form-control form-control-sm mb-3 mb-lg-0"
                                                    minlength="6" />
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="separator mb-6"></div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light me-3">
                                                Batal
                                            </a>
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-sm btn-primary">
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
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        </script>
    @endif
    @if ($message = Session::get('failed'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#userType').on('change', function() {
                const value = $(this).val();

                document.getElementById('mahasiswaFields').style.display = 'none';
                document.getElementById('pendudukFields').style.display = 'none';
                document.getElementById('adminFields').style.display = 'none';

                if (value === 'mahasiswa') {
                    document.getElementById('mahasiswaFields').style.display = 'block';
                } else if (value === 'penduduk') {
                    document.getElementById('pendudukFields').style.display = 'block';
                } else if (value === 'admin') {
                    document.getElementById('adminFields').style.display = 'block';
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
