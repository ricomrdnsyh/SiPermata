@extends('layout.main')

@section('title', 'Surat Izin Penelitian')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-17">
                        <div class="d-flex flex-column">
                            <div class="mb-13 text-center">
                                <h1 class="fs-2hx fw-bolder mb-5">Surat Permohonan Izin Penelitian</h1>
                                <div class="text-gray-400 fw-bold fs-5">Mohon untuk mengisi semua data dengan benar.</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>

                            <!-- Form Container -->
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                    action="{{ route('mahasiswa.surat-izin-penelitian.store') }}">
                                    @csrf
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                        <input type="text" name="nim" class="form-control mb-3 mb-lg-0"
                                            value="{{ auth()->user()->reference_id }}" disabled required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                        <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                            data-control="select2" data-placeholder="Pilih Akademik" name="akademik_id"
                                            data-select2-id="select2-data-72-r5i3" tabindex="-1" aria-hidden="true"
                                            data-kt-initialized="1">
                                            <option value="" data-select2-id="select2-data-74-9zwr">
                                                Pilih Akademik...</option>
                                            @foreach ($akademik as $item)
                                                <option value="{{ $item->id_akademik }}">
                                                    {{ $item->tahun_akademik }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('akademik_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tempat Penelitian</label>
                                        <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                            data-control="select2" data-placeholder="Pilih Tempat Penelitian"
                                            name="mitra_id" data-select2-id="select2-data-72-r5i4" tabindex="-1"
                                            aria-hidden="true" data-kt-initialized="1">
                                            <option value="" data-select2-id="select2-data-74-9zwr">
                                                Pilih Tempat Penelitian...</option>
                                            @foreach ($mitra as $mitra)
                                                <option value="{{ $mitra->id_mitra }}">
                                                    {{ $mitra->nama_mitra }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mitra_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                        <input type="date" name="tgl_mulai" class="form-control mb-3 mb-lg-0" />
                                        @error('tgl_mulai')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                        <input type="date" name="tgl_selesai" class="form-control mb-3 mb-lg-0" />
                                        @error('tgl_selesai')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Judul Penelitian</label>
                                        <textarea name="judul_penelitian" class="form-control mb-3 mb-lg-0" rows="3"></textarea>
                                        @error('judul_penelitian')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="text-center mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-100 w-md-50">
                                            <span class="indicator-label">
                                                Buat Pengajuan
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
