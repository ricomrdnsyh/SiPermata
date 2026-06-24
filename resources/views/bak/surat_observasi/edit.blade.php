@extends('layout.main')
@section('title', 'Surat Permohonan Observasi')
@section('content')
    @php
        $mahasiswaOptions = $mahasiswa
            ->map(function ($mhs) {
                return [
                    'nim' => $mhs->nim,
                    'nama' => $mhs->nama,
                    'prodi' => $mhs->prodi?->nama_prodi ?? '-',
                ];
            })
            ->values();
    @endphp

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm border border-dashed border-dark rounded">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Surat Permohonan Observasi</h1>
                                <div class="text-gray-400 fw-bold fs-5">Mohon untuk perbarui semua data dengan benar.</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                    action="{{ route('bak.surat-observasi.update', $surat->id_surat_observasi) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Mahasiswa Pengaju</label>
                                                <select class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                                    id="mahasiswa_pengaju_select"
                                                    required>
                                                    <option value="">
                                                        Pilih Mahasiswa...</option>
                                                    @foreach ($mahasiswa as $mhs)
                                                        <option value="{{ $mhs->nim }}"
                                                            data-nama="{{ $mhs->nama }}"
                                                            data-prodi="{{ $mhs->prodi?->nama_prodi ?? '-' }}"
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
                                                <label class="fw-semibold fs-6 mb-2">
                                                    Semester
                                                    <span id="semester-loading"
                                                        class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                </label>
                                                <input id="field-semester" type="text"
                                                    class="form-control form-control-sm bg-light"
                                                    placeholder="Otomatis terisi setelah memilih mahasiswa"
                                                    value="{{ old('nim', $surat->nim) ? ($surat->semester ?? '') : '' }}"
                                                    readonly />
                                                <small id="simpt-warning" class="text-warning d-none">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Data semester tidak ditemukan di SIMPT.
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tanggal Observasi</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input id="tgl_observasi" type="text" name="tgl_observasi"
                                                        class="form-control form-control-sm"
                                                        placeholder="Pilih tanggal observasi" autocomplete="off"
                                                        value="{{ old('tgl_observasi', $surat->tgl_observasi ? $surat->tgl_observasi->format('Y-m-d') : '') }}"
                                                        required />
                                                </div>
                                                @error('tgl_observasi')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tempat Observasi</label>
                                                <select class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Tempat Observasi"
                                                    name="mitra_id"
                                                    required>
                                                    <option value="">
                                                        Pilih Tempat Observasi...</option>
                                                    @foreach ($mitra as $mitra)
                                                        <option value="{{ $mitra->id_mitra }}"
                                                            {{ old('mitra_id', $surat->mitra_id) == $mitra->id_mitra ? 'selected' : '' }}>
                                                            {{ $mitra->nama_mitra }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('mitra_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Keperluan Observasi</label>
                                                <textarea name="keperluan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                                @error('keperluan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        @include('bak.surat_observasi._anggota_kelompok', [
                                            'anggotaKelompok' => old('anggota_kelompok', $surat->anggota_kelompok ?? []),
                                            'mahasiswa' => $mahasiswa,
                                        ])
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-250px">
                                            <span class="indicator-label">
                                                <i class="fas fa-save me-2"></i> Update Pengajuan
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
            if (!form) {
                return;
            }
            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            const tglObsEl = document.getElementById('tgl_observasi');
            const tglObsVal = tglObsEl.value || null;
            const pengajuSelect = document.getElementById('mahasiswa_pengaju_select');
            const fieldSemester = document.getElementById('field-semester');
            const semesterSpinner = document.getElementById('semester-loading');
            const simptWarning = document.getElementById('simpt-warning');
            const anggotaContainer = document.getElementById('anggota-kelompok-container');
            const mahasiswaOptions = @json($mahasiswaOptions);
            const simptUrl = "{{ route('bak.surat-observasi.simpt', '__NIM__') }}";
            let anggotaIndex = anggotaContainer ? anggotaContainer.querySelectorAll('tr').length : 0;

            flatpickr(tglObsEl, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                altInputClass: "form-control form-control-sm",
                allowInput: true,
                defaultDate: tglObsVal,
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.required = true;
                        instance.altInput.placeholder = tglObsEl.placeholder || '';
                    }
                }
            });

            function initSelect2(element) {
                if (!element || !window.jQuery || !jQuery.fn.select2) {
                    return;
                }

                const $element = jQuery(element);
                if ($element.data('select2')) {
                    $element.select2('destroy');
                }

                $element.select2({
                    width: '100%',
                    placeholder: element.dataset.placeholder || 'Pilih mahasiswa',
                });

                if ($element.hasClass('anggota-nim-select')) {
                    $element.off('.anggotaBakSync');
                    $element.on('change.anggotaBakSync select2:select.anggotaBakSync select2:clear.anggotaBakSync',
                        function() {
                            syncAnggotaRow(element.closest('tr'));
                        });
                }
            }

            function fetchSimpt(nim) {
                if (!fieldSemester || !semesterSpinner || !simptWarning) {
                    return;
                }

                if (!nim) {
                    fieldSemester.value = '';
                    simptWarning.classList.add('d-none');
                    return;
                }

                semesterSpinner.classList.remove('d-none');
                simptWarning.classList.add('d-none');
                fieldSemester.value = '';

                fetch(simptUrl.replace('__NIM__', encodeURIComponent(nim)), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.semester) {
                            fieldSemester.value = data.semester;
                            simptWarning.classList.add('d-none');
                        } else {
                            fieldSemester.value = '-';
                            simptWarning.classList.remove('d-none');
                        }
                    })
                    .catch(() => {
                        fieldSemester.value = '-';
                        simptWarning.classList.remove('d-none');
                    })
                    .finally(() => {
                        semesterSpinner.classList.add('d-none');
                    });
            }

            function buildMahasiswaOptions(selectedNim) {
                let html = '<option value="">Pilih mahasiswa...</option>';

                mahasiswaOptions.forEach(function(item) {
                    const selected = selectedNim === item.nim ? ' selected' : '';
                    html += '<option value="' + item.nim + '" data-nama="' + item.nama + '" data-prodi="' + item.prodi +
                        '"' + selected + '>' + item.nim + ' - ' + item.nama + '</option>';
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
                if (!anggotaContainer) {
                    return true;
                }

                const ketuaNim = pengajuSelect ? pengajuSelect.value : '';
                const selected = new Set();
                let valid = true;

                anggotaContainer.querySelectorAll('tr').forEach(function(row) {
                    const select = row.querySelector('.anggota-nim-select');
                    const nim = select.value;

                    syncAnggotaRow(row);

                    if (!nim) {
                        return;
                    }

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

            window.addAnggotaKelompokBakRow = function() {
                if (!anggotaContainer) {
                    return;
                }

                anggotaContainer.insertAdjacentHTML('beforeend', buildAnggotaRow(anggotaIndex));
                const row = anggotaContainer.lastElementChild;
                anggotaIndex += 1;
                initSelect2(row.querySelector('.anggota-nim-select'));
                syncAnggotaRow(row);
            };

            if (pengajuSelect) {
                initSelect2(pengajuSelect);
                fetchSimpt(pengajuSelect.value);
                if (window.jQuery) {
                    jQuery(pengajuSelect)
                        .off('.simptObservasiBak')
                        .on('change.simptObservasiBak select2:select.simptObservasiBak', function() {
                            fetchSimpt(this.value);
                        });
                } else {
                    pengajuSelect.addEventListener('change', function() {
                        fetchSimpt(this.value);
                    });
                }
            }

            if (anggotaContainer) {
                anggotaContainer.querySelectorAll('.anggota-nim-select').forEach(function(select) {
                    initSelect2(select);
                    syncAnggotaRow(select.closest('tr'));
                });

                anggotaContainer.addEventListener('change', function(event) {
                    const select = event.target.closest('.anggota-nim-select');
                    if (!select) {
                        return;
                    }

                    syncAnggotaRow(select.closest('tr'));
                });

                anggotaContainer.addEventListener('click', function(event) {
                    const button = event.target.closest('.remove-anggota-kelompok');
                    if (!button) {
                        return;
                    }

                    const row = button.closest('tr');
                    if (row) {
                        row.remove();
                    }
                });
            }

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    return;
                }
                if (!validateAnggotaRows()) {
                    event.preventDefault();
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
