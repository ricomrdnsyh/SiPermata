@extends('layout.main')
@section('title', 'Surat Izin Penelitian')
@section('css')
    <style>
        .form-group-box {
            background-color: var(--bs-gray-100);
            border: 1px dashed var(--bs-gray-300);
            border-radius: 0.75rem;
            padding: 1.75rem 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        @media (max-width: 767.98px) {
            .form-group-box {
                padding: 1.25rem 1rem;
            }
        }

        .form-group-box:hover {
            border-color: var(--bs-gray-400);
            background-color: var(--bs-gray-200);
        }

        html[data-theme="dark"] .form-group-box,
        body[data-theme="dark"] .form-group-box,
        [data-bs-theme="dark"] .form-group-box {
            background-color: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
        }

        html[data-theme="dark"] .form-group-box:hover,
        body[data-theme="dark"] .form-group-box:hover,
        [data-bs-theme="dark"] .form-group-box:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
        }
    </style>
@endsection
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="card shadow-sm border border-dashed border-dark rounded-4">
                        <div class="card-body p-lg-12">
                            <div class="d-flex flex-column">
                                <div class="mb-10 text-center">
                                    <h1 class="fs-2hx fw-bolder mb-3 text-dark">
                                        <i class="fas fa-file-signature fs-2hx text-primary me-2 align-middle"></i>
                                        Surat Permohonan Izin Penelitian
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Mohon untuk perbarui semua data dengan benar.</div>
                                </div>
                                <div class="separator border-2 border-dashed mb-10"></div>
                                <div id="form-container" class="mt-2">
                                    <form id="kt_ecommerce_settings_general_form" method="POST"
                                        action="{{ route('mahasiswa.surat-izin-penelitian.update', $surat->id_surat_izin_penelitian) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik
                                                Mahasiswa</h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                    <input type="text" name="nim" class="form-control"
                                                        value="{{ auth()->user()->reference_id }}" disabled required />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $latestAkademik?->tahun_akademik }}" disabled />
                                                    <input type="hidden" name="akademik_id"
                                                        value="{{ $latestAkademik?->id_akademik }}">
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-building text-gray-400 me-2"></i> Detail Penelitian</h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Tempat Penelitian</label>
                                                    <select class="form-select w-100" data-control="select2"
                                                        data-placeholder="Pilih Tempat Penelitian" name="mitra_id" required>
                                                        <option value=""></option>
                                                        @foreach ($mitra as $m)
                                                            <option value="{{ $m->id_mitra }}"
                                                                {{ old('mitra_id', $surat->mitra_id) == $m->id_mitra ? 'selected' : '' }}>
                                                                {{ $m->nama_mitra }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('mitra_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input id="tgl_mulai" type="text" name="tgl_mulai"
                                                            class="form-control ps-12" placeholder="Pilih tanggal mulai"
                                                            autocomplete="off" value="{{ old('tgl_mulai', $surat->tgl_mulai ? $surat->tgl_mulai->format('Y-m-d') : '') }}" required />
                                                    </div>
                                                    @error('tgl_mulai')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input id="tgl_selesai" type="text" name="tgl_selesai"
                                                            class="form-control ps-12" placeholder="Pilih tanggal selesai"
                                                            autocomplete="off" value="{{ old('tgl_selesai', $surat->tgl_selesai ? $surat->tgl_selesai->format('Y-m-d') : '') }}" required />
                                                    </div>
                                                    @error('tgl_selesai')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Judul Penelitian</label>
                                                    <textarea name="judul_penelitian" class="form-control" rows="3" placeholder="Masukkan judul penelitian..." required>{{ old('judul_penelitian', $surat->judul_penelitian) }}</textarea>
                                                    @error('judul_penelitian')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-primary w-100 w-md-auto px-10">
                                                <span class="indicator-label">
                                                    <i class="fas fa-save me-2"></i> Update Pengajuan
                                                </span>
                                                <span class="indicator-progress" style="display: none;">
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

            const mulaiEl = document.getElementById('tgl_mulai');
            const selesaiEl = document.getElementById('tgl_selesai');

            const mulaiVal = mulaiEl.value || null;
            const selesaiVal = selesaiEl.value || null;

            const fpMulai = flatpickr(mulaiEl, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                altInputClass: "form-control ps-12",
                allowInput: true,
                defaultDate: mulaiVal,
                maxDate: selesaiVal,
                disableMobile: "true",
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.required = true;
                        instance.altInput.placeholder = mulaiEl.placeholder || '';
                    }
                },
                onChange: function(selectedDates, dateStr) {
                    if (fpSelesai) fpSelesai.set("minDate", dateStr || null);
                }
            });

            const fpSelesai = flatpickr(selesaiEl, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                altInputClass: "form-control ps-12",
                allowInput: true,
                defaultDate: selesaiVal,
                minDate: mulaiVal || "today",
                disableMobile: "true",
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.required = true;
                        instance.altInput.placeholder = selesaiEl.placeholder || '';
                    }
                },
                onChange: function(selectedDates, dateStr) {
                    if (fpMulai) fpMulai.set("maxDate", dateStr || null);
                }
            });

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    return;
                }
                submitButton.disabled = true;
                const label = submitButton.querySelector('.indicator-label');
                const progress = submitButton.querySelector('.indicator-progress');
                if (label) label.style.display = 'none';
                if (progress) progress.style.display = 'inline-block';
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
