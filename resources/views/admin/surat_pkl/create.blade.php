@extends('layout.main')
@section('title', 'Surat Permohonan PKL')
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
    @php
        $mahasiswaOptions = $mahasiswa
            ->map(fn($mhs) => ['nim' => $mhs->nim, 'nama' => $mhs->nama, 'prodi' => $mhs->prodi?->nama_prodi ?? '-'])
            ->values();
    @endphp
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
                                        Surat Permohonan PKL
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Mohon untuk mengisi semua data dengan benar.</div>
                                </div>
                                <div class="separator border-2 border-dashed mb-10"></div>
                                <div id="form-container" class="mt-2">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                        action="{{ route('admin.surat-pkl.store') }}">
                                        @csrf

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Mahasiswa Pengaju</label>
                                                    <select class="form-select form-select-sm w-100" data-control="select2"
                                                        data-placeholder="Pilih Mahasiswa" name="nim"
                                                        id="mahasiswa_pengaju_select" required>
                                                        <option value="">Pilih Mahasiswa...</option>
                                                        @foreach ($mahasiswa as $mhs)
                                                            <option value="{{ $mhs->nim }}"
                                                                data-nama="{{ $mhs->nama }}"
                                                                data-prodi="{{ $mhs->prodi?->nama_prodi ?? '-' }}"
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
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-building text-gray-400 me-2"></i> Detail PKL
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Tempat PKL</label>
                                                    <select class="form-select form-select-sm w-100" data-control="select2"
                                                        data-placeholder="Pilih Tempat PKL" name="mitra_id" required>
                                                        <option value="">Pilih Tempat PKL...</option>
                                                        @foreach ($mitra as $m)
                                                            <option value="{{ $m->id_mitra }}"
                                                                {{ old('mitra_id') == $m->id_mitra ? 'selected' : '' }}>
                                                                {{ $m->nama_mitra }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('mitra_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input id="tgl_mulai" type="text" name="tgl_mulai"
                                                            class="form-control form-control-sm ps-12"
                                                            placeholder="Pilih tanggal mulai" autocomplete="off"
                                                            value="{{ old('tgl_mulai') }}" required />
                                                    </div>
                                                    @error('tgl_mulai')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input id="tgl_selesai" type="text" name="tgl_selesai"
                                                            class="form-control form-control-sm ps-12"
                                                            placeholder="Pilih tanggal selesai" autocomplete="off"
                                                            value="{{ old('tgl_selesai') }}" required />
                                                    </div>
                                                    @error('tgl_selesai')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-users text-gray-400 me-2"></i> Anggota Kelompok (Opsional)
                                            </h5>
                                            <div class="row g-5">
                                                @include('admin.surat_pkl._anggota_kelompok', [
                                                    'anggotaKelompok' => old('anggota_kelompok', []),
                                                    'mahasiswa' => $mahasiswa,
                                                ])
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

            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            const mulaiEl = document.getElementById('tgl_mulai');
            const selesaiEl = document.getElementById('tgl_selesai');
            const mulaiVal = mulaiEl ? (mulaiEl.value || null) : null;
            const selesaiVal = selesaiEl ? (selesaiEl.value || null) : null;
            const pengajuSelect = document.getElementById('mahasiswa_pengaju_select');
            const anggotaContainer = document.getElementById('anggota-kelompok-container');
            const mahasiswaOptions = @json($mahasiswaOptions);
            const simptUrl = "{{ route('admin.surat-pkl.simpt', '__NIM__') }}";
            let anggotaIndex = anggotaContainer ? anggotaContainer.querySelectorAll('tr').length : 0;
            let isConfirmed = false;

            let fpMulai, fpSelesai;
            if (typeof flatpickr !== "undefined") {
                fpMulai = flatpickr(mulaiEl, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    altInputClass: "form-control form-control-sm ps-12",
                    allowInput: true,
                    defaultDate: mulaiVal,
                    disableMobile: "true",
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.altInput) {
                            instance.altInput.required = true;
                            instance.altInput.placeholder = mulaiEl.placeholder || '';
                        }
                    },
                    onChange: function(selectedDates, dateStr) {
                        if (fpSelesai) fpSelesai.set("minDate", dateStr);
                    }
                });

                fpSelesai = flatpickr(selesaiEl, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    altInputClass: "form-control form-control-sm ps-12",
                    allowInput: true,
                    defaultDate: selesaiVal,
                    disableMobile: "true",
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.altInput) {
                            instance.altInput.required = true;
                            instance.altInput.placeholder = selesaiEl.placeholder || '';
                        }
                    },
                    onChange: function(selectedDates, dateStr) {
                        if (fpMulai) fpMulai.set("maxDate", dateStr);
                    }
                });
            }

            function validateSimpt(nim) {
                if (!nim) return;

                fetch(simptUrl.replace('__NIM__', encodeURIComponent(nim)), {
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
                                if (pengajuSelect) {
                                    $(pengajuSelect).val(null).trigger('change.select2');
                                }
                            });
                        }
                    });
            }

            function initSelect2(element) {
                if (!element || !window.jQuery || !jQuery.fn.select2) return;
                const $element = jQuery(element);
                if ($element.data('select2')) {
                    $element.select2('destroy');
                }

                $element.select2({
                    width: '100%',
                    placeholder: element.dataset.placeholder || 'Pilih mahasiswa',
                });

                if ($element.hasClass('anggota-nim-select')) {
                    $element.off('.anggotaSync');
                    $element.on('change.anggotaSync select2:select.anggotaSync select2:clear.anggotaSync', function() {
                        syncAnggotaRow(element.closest('tr'));
                    });
                }
            }

            function buildMahasiswaOptions(selectedNim) {
                let html = '<option value="">Pilih mahasiswa...</option>';
                mahasiswaOptions.forEach(function(item) {
                    const selected = selectedNim === item.nim ? ' selected' : '';
                    html += '<option value="' + item.nim + '" data-nama="' + item.nama + '" data-prodi="' + item.prodi + '"' + selected + '>' + item.nim + ' - ' + item.nama + '</option>';
                });
                return html;
            }

            function buildAnggotaRow(index) {
                return '' +
                    '<tr>' +
                    '    <td class="ps-3">' +
                    '        <select name="anggota_kelompok[' + index + '][nim]" class="form-select form-select-sm anggota-nim-select" data-control="select2" data-placeholder="Pilih mahasiswa">' +
                    buildMahasiswaOptions('') +
                    '        </select>' +
                    '        <div class="invalid-feedback anggota-nim-feedback"></div>' +
                    '    </td>' +
                    '    <td>' +
                    '        <input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-nama-input" placeholder="Nama mahasiswa" disabled />' +
                    '        <input type="hidden" name="anggota_kelompok[' + index + '][nama]" class="anggota-nama-hidden-input" />' +
                    '    </td>' +
                    '    <td>' +
                    '        <input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-prodi-input" placeholder="Prodi mahasiswa" disabled />' +
                    '        <input type="hidden" name="anggota_kelompok[' + index + '][prodi]" class="anggota-prodi-hidden-input" />' +
                    '    </td>' +
                    '    <td class="pe-4 text-center">' +
                    '        <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-anggota-kelompok hover-elevate-up" title="Hapus anggota" aria-label="Hapus anggota"><i class="fas fa-trash-alt"></i></button>' +
                    '    </td>' +
                    '</tr>';
            }

            function fillAnggotaRow(row, data) {
                data = data || {};
                row.querySelector('.anggota-nama-input').value = data.nama || '';
                row.querySelector('.anggota-prodi-input').value = data.prodi || '';
                row.querySelector('.anggota-nama-hidden-input').value = data.nama || '';
                row.querySelector('.anggota-prodi-hidden-input').value = data.prodi || '';
            }

            function setAnggotaError(row, message) {
                const select = row.querySelector('.anggota-nim-select');
                const feedback = row.querySelector('.anggota-nim-feedback');
                const hasError = Boolean(message);
                select.classList.toggle('is-invalid', hasError);
                feedback.textContent = message || '';
            }

            function syncAnggotaRow(row) {
                const select = row.querySelector('.anggota-nim-select');
                const option = select.options[select.selectedIndex];
                setAnggotaError(row);

                if (!option || !option.value) {
                    fillAnggotaRow(row);
                    return;
                }

                fillAnggotaRow(row, {
                    nama: option.dataset.nama || '',
                    prodi: option.dataset.prodi || '',
                });
            }

            function validateAnggotaRows() {
                if (!anggotaContainer) return true;
                const ketuaNim = pengajuSelect ? pengajuSelect.value : '';
                const selected = new Set();
                let valid = true;

                anggotaContainer.querySelectorAll('tr').forEach(function(row) {
                    const select = row.querySelector('.anggota-nim-select');
                    const nim = select.value;
                    syncAnggotaRow(row);

                    if (!nim) return;

                    if (ketuaNim && nim === ketuaNim) {
                        setAnggotaError(row, 'Mahasiswa anggota tidak boleh sama dengan ketua pengaju.');
                        valid = false;
                        return;
                    }

                    if (selected.has(nim)) {
                        setAnggotaError(row, 'Mahasiswa anggota tidak boleh duplikat.');
                        valid = false;
                        return;
                    }

                    selected.add(nim);
                });

                return valid;
            }

            window.addAnggotaKelompokAdminRow = window.addAnggotaKelompokBakRow = function() {
                if (!anggotaContainer) return;
                anggotaContainer.insertAdjacentHTML('beforeend', buildAnggotaRow(anggotaIndex));
                const row = anggotaContainer.lastElementChild;
                initSelect2(row.querySelector('.anggota-nim-select'));
                anggotaIndex += 1;
            };

            if (anggotaContainer) {
                anggotaContainer.addEventListener('click', function(event) {
                    const btn = event.target.closest('.remove-anggota-kelompok');
                    if (!btn) return;
                    btn.closest('tr').remove();
                    validateAnggotaRows();
                });

                anggotaContainer.querySelectorAll('tr').forEach(function(row) {
                    initSelect2(row.querySelector('.anggota-nim-select'));
                    syncAnggotaRow(row);
                });
            }

            if (pengajuSelect) {
                if (window.jQuery) {
                    $(pengajuSelect).on('change select2:select', function() {
                        validateSimpt(this.value);
                        validateAnggotaRows();
                    });
                } else {
                    pengajuSelect.addEventListener('change', function() {
                        validateSimpt(this.value);
                        validateAnggotaRows();
                    });
                }
            }

            form.addEventListener('submit', function(event) {
                if (isConfirmed) return;
                event.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                if (!validateAnggotaRows()) {
                    Swal.fire({
                        text: "Terdapat data anggota kelompok yang belum valid atau duplikat.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, perbaiki",
                        customClass: { confirmButton: "btn btn-danger" }
                    });
                    return;
                }

                Swal.fire({
                    title: "Konfirmasi Pengajuan",
                    text: "Apakah Anda yakin ingin membuat pengajuan surat permohonan PKL ini?",
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

                        if (submitButton) {
                            submitButton.disabled = true;
                            const label = submitButton.querySelector('.indicator-label');
                            const progress = submitButton.querySelector('.indicator-progress');
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
