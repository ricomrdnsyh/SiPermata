@extends('layout.main')

@section('title', 'Detail Template')

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
                                        <h2>Detail Template</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Nama Template</label>
                                            <input type="text" name="nama_template" class="form-control mb-3 mb-lg-0"
                                                disabled value="{{ $data->nama_template }}" />
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Jenis Surat</label>
                                            <input type="text" name="jenis_surat" class="form-control mb-3 mb-lg-0"
                                                disabled value="{{ $data->jenis_surat }}" />
                                        </div>
                                        <div class="fv-row mb-7"> <label class="fw-semibold fs-6 mb-2">File Template</label>
                                            @if ($data->file)
                                                @php
                                                    $ext = strtolower(pathinfo($data->file, PATHINFO_EXTENSION));
                                                    $icon = 'fa-file';
                                                    $color = 'text-secondary';
                                                    if (in_array($ext, ['doc', 'docx'])) {
                                                        $icon = 'fa-file-word';
                                                        $color = 'text-primary';
                                                    } elseif ($ext === 'pdf') {
                                                        $icon = 'fa-file-pdf';
                                                        $color = 'text-danger';
                                                    }
                                                @endphp
                                                <div class="input-group"> <span class="input-group-text bg-light"> <i
                                                            class="fas {{ $icon }} {{ $color }}"></i> </span>
                                                    <a href="{{ asset('storage/' . $data->file) }}" target="_blank"
                                                        class="form-control text-decoration-none d-flex align-items-center"
                                                        style="background-color: #f8f9fa; border: 1px solid #ced4da; cursor: pointer;">
                                                        {{ basename($data->file) }} </a>
                                                </div>
                                            @else
                                                <input type="text" class="form-control" value="Tidak ada file" readonly>
                                            @endif
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                            <input type="text" name="fakultas_id" class="form-control mb-3 mb-lg-0"
                                                disabled value="{{ $data->fakultas->nama_fakultas }}" />
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="fw-semibold fs-6 mb-2">Nama Prodi</label>
                                            <input type="text" name="prodi_id" class="form-control mb-3 mb-lg-0" disabled
                                                value="{{ $data->prodi ? $data->prodi->nama_prodi : '-' }}" />
                                        </div>
                                        <div class="separator mb-6"></div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('bak.template.index') }}" class="btn btn-light me-3">
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
