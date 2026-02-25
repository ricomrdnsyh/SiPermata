@extends('layout.main')
@section('title', 'Surat Keterangan Lulus')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Surat Permohonan Keterangan Lulus</h1>
                                <div class="text-gray-400 fw-bold fs-5">Mohon untuk perbarui semua data dengan benar.</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                    action="{{ route('bak.surat-keterangan-lulus.update', $surat->id_surat_lulus) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                <select class="form-select form-select-sm select2-hidden-accessible w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                                    data-select2-id="select2-data-72-r5i2" tabindex="-1" aria-hidden="true"
                                                    data-kt-initialized="1" required>
                                                    <option value="" data-select2-id="select2-data-74-9zwr">
                                                        Pilih Mahasiswa...</option>
                                                    @foreach ($mahasiswa as $mhs)
                                                        <option value="{{ $mhs->nim }}"
                                                            {{ old('nim', $surat->nim) == $mhs->nim ? 'selected' : '' }}>
                                                            {{ $mhs->nim }} - {{ $mhs->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('nim')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                <input type="text" class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $latestAkademik?->tahun_akademik }}" disabled />
                                                <input type="hidden" name="akademik_id"
                                                    value="{{ $latestAkademik?->id_akademik }}">
                                                @error('akademik_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ old('tempat_lahir', $surat->tempat_lahir) }}" required />
                                                @error('tempat_lahir')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tanggal Lahir</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input id="tgl_lahir" type="text" name="tgl_lahir"
                                                        class="form-control form-control-sm"
                                                        placeholder="Pilih tanggal lahir" autocomplete="off"
                                                        value="{{ old('tgl_lahir', $surat->tgl_lahir ? $surat->tgl_lahir->format('Y-m-d') : '') }}"
                                                        required />
                                                </div>
                                                @error('tgl_lahir')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Judul Penelitian/Tugas
                                                    Akhir</label>
                                                <textarea name="judul_penelitian" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required>{{ old('judul_penelitian', $surat->judul_penelitian) }}</textarea>
                                                @error('judul_penelitian')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-sm btn-primary w-250px">
                                            <span class="indicator-label">
                                                Update Pengajuan
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
            const tglEl = document.getElementById('tgl_lahir');
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
                        instance.altInput.required = true;
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
@endsection
