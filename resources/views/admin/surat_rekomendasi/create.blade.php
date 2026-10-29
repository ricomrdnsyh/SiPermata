@extends('layout.main')
@section('title', 'Surat Rekomendasi')
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
                                        Surat Permohonan Rekomendasi
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Mohon untuk mengisi semua data dengan benar.</div>
                                </div>
                                <div class="separator border-2 border-dashed mb-10"></div>
                                <div id="form-container" class="mt-2">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                        action="{{ route('admin.surat-rekomendasi.store') }}">
                                        @csrf

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                    <select id="select-nim" class="form-select form-select-sm w-100"
                                                        data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim" required>
                                                        <option value="">Pilih Mahasiswa...</option>
                                                        @foreach ($mahasiswa as $mhs)
                                                            <option value="{{ $mhs->nim }}"
                                                                {{ old('nim') == $mhs->nim ? 'selected' : '' }}>
                                                                {{ $mhs->nim }} - {{ $mhs->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nim')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <select name="akademik_id" class="akademik-id-select form-select form-select-sm" data-control="select2" required>
                                                        @foreach (\App\Models\TahunAkademik::orderBy('id_akademik', 'desc')->get() as $akademik)
                                                            <option value="{{ $akademik->id_akademik }}"
                                                                {{ (isset($surat) && $surat->akademik_id == $akademik->id_akademik) || (!isset($surat) && $latestAkademik?->id_akademik == $akademik->id_akademik) ? 'selected' : '' }}>
                                                                {{ $akademik->tahun_akademik }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">
                                                        Semester
                                                        <span id="semester-loading" class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                    </label>
                                                    <input id="field-semester" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi setelah memilih mahasiswa" value="" readonly />
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">
                                                        IPK
                                                        <span id="ipk-loading" class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                    </label>
                                                    <input id="field-ipk" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi setelah memilih mahasiswa" value="" readonly />
                                                </div>

                                                <div class="col-12">
                                                    <small id="simpt-warning" class="text-warning d-none">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Data IPK/Semester tidak ditemukan di SIMPT.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-clipboard-list text-gray-400 me-2"></i> Detail Rekomendasi
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Pelaksanaan</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input id="tgl_pelaksanaan" type="text" name="tgl_pelaksanaan"
                                                            class="form-control form-control-sm ps-12"
                                                            placeholder="Pilih tanggal pelaksanaan" autocomplete="off"
                                                            value="{{ old('tgl_pelaksanaan') }}" required />
                                                    </div>
                                                    @error('tgl_pelaksanaan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Rekomendasi</label>
                                                    <textarea name="keperluan" placeholder="Penerima Beasiswa GenBi, MBKM Santri, dll"
                                                        class="form-control form-control-sm" rows="3" required>{{ old('keperluan') }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Penyelenggara</label>
                                                    <textarea name="penyelenggara" placeholder="Bank Indonesia, LP3M Universitas Nurul Jadid, dll"
                                                        class="form-control form-control-sm" rows="3" required>{{ old('penyelenggara') }}</textarea>
                                                    @error('penyelenggara')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-primary w-100 w-md-auto px-10">
                                                <span class="indicator-label">
                                                    <i class="fas fa-paper-plane me-2"></i> Buat Pengajuan
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
            if (!form) return;

            const submitBtn = form.querySelector('[data-kt-contacts-type="submit"]');
            const fieldSmt = document.getElementById('field-semester');
            const fieldIpk = document.getElementById('field-ipk');
            const smtSpinner = document.getElementById('semester-loading');
            const ipkSpinner = document.getElementById('ipk-loading');
            const simptWarn = document.getElementById('simpt-warning');
            const tglPel = document.getElementById('tgl_pelaksanaan');
            const simptUrl = "{{ route('admin.surat-rekomendasi.simpt', '__NIM__') }}";
            let isConfirmed = false;

            if (tglPel && typeof flatpickr !== "undefined") {
                flatpickr(tglPel, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    altInputClass: "form-control form-control-sm ps-12",
                    allowInput: true,
                    disableMobile: "true",
                    onReady: function(_, __, instance) {
                        if (instance.altInput) {
                            instance.altInput.required = true;
                            instance.altInput.placeholder = 'Pilih tanggal pelaksanaan';
                        }
                    }
                });
            }

            function fetchSimpt(nim) {
                if (!nim) {
                    fieldSmt.value = '';
                    fieldIpk.value = '';
                    simptWarn.classList.add('d-none');
                    return;
                }

                smtSpinner.classList.remove('d-none');
                ipkSpinner.classList.remove('d-none');
                simptWarn.classList.add('d-none');
                fieldSmt.value = '';
                fieldIpk.value = '';

                const url = simptUrl.replace('__NIM__', encodeURIComponent(nim));

                fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.is_valid_krs === false) {
                            Swal.fire({
                                text: "Mahasiswa belum mengisi KRS pada semester ini. Pembuatan surat tidak dapat dilanjutkan.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, mengerti",
                                customClass: { confirmButton: "btn btn-danger" }
                            }).then(() => {
                                $('#select-nim').val('').trigger('change.select2');
                                fieldSmt.value = '';
                                fieldIpk.value = '';
                                simptWarn.classList.add('d-none');
                            });
                            return;
                        }

                        if (data.semester) {
                            fieldSmt.value = data.semester;
                        } else {
                            fieldSmt.value = '-';
                        }

                        if (data.ipk) {
                            fieldIpk.value = data.ipk;
                        } else {
                            fieldIpk.value = '-';
                        }

                        if (!data.semester && !data.ipk) {
                            simptWarn.classList.remove('d-none');
                        } else {
                            simptWarn.classList.add('d-none');
                        }
                    })
                    .catch(() => {
                        fieldSmt.value = '-';
                        fieldIpk.value = '-';
                        simptWarn.classList.remove('d-none');
                    })
                    .finally(() => {
                        smtSpinner.classList.add('d-none');
                        ipkSpinner.classList.add('d-none');
                    });
            }

            $('#select-nim').on('change select2:select', function() {
                fetchSimpt(this.value);
            });

            if ($('#select-nim').val()) {
                fetchSimpt($('#select-nim').val());
            }

            form.addEventListener('submit', function(event) {
                if (isConfirmed) return;
                event.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "Konfirmasi Pengajuan",
                    text: "Apakah Anda yakin ingin membuat pengajuan surat rekomendasi ini?",
                    icon: "question",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Buat Pengajuan!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-secondary text-black"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mohon tunggu...',
                            text: 'Permintaan anda sedang diproses',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        if (submitBtn) {
                            submitBtn.disabled = true;
                            const label = submitBtn.querySelector('.indicator-label');
                            const progress = submitBtn.querySelector('.indicator-progress');
                            if (label) label.style.display = 'none';
                            if (progress) progress.style.display = 'inline-block';
                        }

                        isConfirmed = true;
                        form.submit();
                    }
                });
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
                customClass: { confirmButton: "btn btn-primary" }
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
                customClass: { confirmButton: "btn btn-danger" }
            });
        </script>
    @endif
@endsection
