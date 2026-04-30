@extends('layout.main')
@section('title', 'Edit Template')
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
                                        <h2>Edit Template</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework"
                                        action="{{ route('admin.template.update', $data->id_template) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Template</label>
                                                    <input type="text" name="nama_template"
                                                        class="form-control form-control-sm mb-3 mb-lg-0"
                                                        value="{{ old('nama_template', $data->nama_template) }}" required />
                                                    @error('nama_template')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">Jenis Surat(contoh_nama_jenis_surat)</label>
                                                    <input type="text" name="jenis_surat"
                                                        class="form-control form-control-sm mb-3 mb-lg-0"
                                                        value="{{ old('jenis_surat', $data->jenis_surat) }}" required />
                                                    <small class="text-muted d-block mt-1">
                                                    @error('jenis_surat')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="fw-semibold fs-6 mb-2">File Template(Doc/Docx)</label>
                                                    @if ($data->file)
                                                        <small class="form-text">File saat ini:
                                                            <a href="{{ route('admin.template.download', $data->id_template) }}"
                                                                target="_blank">Lihat File</a>
                                                        </small>
                                                    @endif
                                                    <input type="file" name="file"
                                                        class="form-control form-control-sm mb-3 mb-lg-0" />
                                                    @error('file')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="fv-row mb-3">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                                    <select class="form-select form-select-sm select2-hidden-accessible w-100"
                                                        data-control="select2" data-placeholder="Pilih Fakultas" name="fakultas_id"
                                                        id="fakultas_id" data-select2-id="select2-data-72-r5i4" tabindex="-1"
                                                        aria-hidden="true" data-kt-initialized="1" required>
                                                        <option value="" data-select2-id="select2-data-74-9zwr">
                                                            Pilih Fakultas...</option>
                                                        @foreach ($fakultas as $f)
                                                            <option value="{{ $f->id_fakultas }}"
                                                                {{ old('fakultas_id', $data->fakultas_id) == $f->id_fakultas ? 'selected' : '' }}>
                                                                {{ $f->nama_fakultas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('fakultas_id')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="fv-row mb-3">
                                                    <label class="fw-semibold fs-6 mb-2">Tanggal SK(Kosongkan jikan bukan surat keterangan lulus)</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">
                                                            <i class="ki-duotone ki-calendar fs-5">
                                                                <span class="path1"></span><span class="path2"></span>
                                                            </i>
                                                        </span>
                                                        <input id="tgl_sk" type="text" name="tgl_sk"
                                                            class="form-control form-control-sm"
                                                            placeholder="Pilih tanggal SK" autocomplete="off"
                                                            value="{{ old('tgl_sk', $data->tgl_sk ? $data->tgl_sk->format('Y-m-d') : '') }}" />
                                                    </div>
                                                    @error('tgl_sk')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="separator mb-6"></div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('admin.template.index') }}" class="btn btn-sm btn-light me-3">
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

            const tglEl = document.getElementById('tgl_sk');
            const tglVal = tglEl.value || null;

            flatpickr(tglEl, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                altInputClass: "form-control form-control-sm",
                allowInput: true,
                defaultDate: tglVal,
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.placeholder = tglEl.placeholder || '';
                    }
                }
            });

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
