@extends('layout.main')
@section('title', 'Surat Permohonan Observasi')
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
                                        Surat Permohonan Observasi
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Mohon untuk perbarui semua data dengan benar.</div>
                                </div>
                                <div class="separator border-2 border-dashed mb-10"></div>
                                <div id="form-container" class="mt-2">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                        action="{{ route('mahasiswa.surat-observasi.update', $surat->id_surat_observasi) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa</h5>
                                            <div class="row g-5">
                                                <div class="col-md-4">
                                                    <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                    <input type="text" name="nim" class="form-control"
                                                        value="{{ auth()->user()->reference_id }}" disabled required />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $latestAkademik?->tahun_akademik }}" disabled />
                                                    <input type="hidden" name="akademik_id"
                                                        value="{{ $latestAkademik?->id_akademik }}">
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" name="semester" class="form-control"
                                                        value="{{ $dataSimpt?->semester ?? '-' }}" disabled />
                                                    @if (blank($dataSimpt?->semester))
                                                        <small class="text-warning mt-1 d-block">Data semester belum ditemukan di SIMPT.</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i class="fas fa-building text-gray-400 me-2"></i> Detail Observasi</h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tempat Observasi</label>
                                                    <select class="form-select w-100" data-control="select2"
                                                        data-placeholder="Pilih Tempat Observasi" name="mitra_id" required>
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
                                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Observasi</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input id="tgl_observasi" type="text" name="tgl_observasi"
                                                            class="form-control ps-12" placeholder="Pilih tanggal observasi"
                                                            autocomplete="off"
                                                            value="{{ old('tgl_observasi', $surat->tgl_observasi ? date('Y-m-d', strtotime($surat->tgl_observasi)) : '') }}"
                                                            required />
                                                    </div>
                                                    @error('tgl_observasi')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Observasi</label>
                                                    <textarea name="keperluan" class="form-control" rows="3" placeholder="Tugas Mata Kuliah, Tugas Akhir, dll" required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        @include('mahasiswa.surat_observasi._anggota_kelompok', [
                                            'anggotaKelompok' => old('anggota_kelompok', $surat->anggota_kelompok ?? []),
                                        ])

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
            if (!form) {
                return;
            }

            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            const anggotaContainer = document.getElementById('anggota-kelompok-container');
            const lookupBaseUrl = @json(url('/mahasiswa/surat-observasi/anggota'));
            const lookupDelay = 180;
            let anggotaIndex = anggotaContainer ? anggotaContainer.querySelectorAll('.anggota-item').length : 0;
            let lookupRequestCounter = 0;
            const lookupCache = new Map();

            const tglObsEl = document.getElementById('tgl_observasi');
            const tglObsVal = tglObsEl.value || null;

            if (tglObsEl && typeof flatpickr === 'function') {
                flatpickr(tglObsEl, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    altInputClass: "form-control ps-12",
                    allowInput: true,
                    disableMobile: "true",
                    defaultDate: tglObsVal,
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.altInput) {
                            instance.altInput.required = true;
                            instance.altInput.placeholder = tglObsEl.placeholder || '';
                        }
                    }
                });
            }

            function buildAnggotaRow(index) {
                return '' +
                    '<div class="anggota-item bg-body border rounded p-3 mb-3 shadow-sm">' +
                    '    <div class="row g-3 align-items-center">' +
                    '        <div class="col-12 col-md-3">' +
                    '            <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">NIM Anggota</label>' +
                    '            <input type="text" name="anggota_kelompok[' + index + '][nim]" class="form-control form-control-sm anggota-nim-input" placeholder="Masukkan NIM..." autocomplete="off" />' +
                    '            <div class="invalid-feedback anggota-nim-feedback"></div>' +
                    '        </div>' +
                    '        <div class="col-12 col-md-4">' +
                    '            <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">Nama Mahasiswa</label>' +
                    '            <input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-nama-input" placeholder="Otomatis terisi..." readonly disabled />' +
                    '            <input type="hidden" name="anggota_kelompok[' + index + '][nama]" class="anggota-nama-hidden-input" />' +
                    '        </div>' +
                    '        <div class="col-12 col-md-4">' +
                    '            <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">Program Studi</label>' +
                    '            <input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-prodi-input" placeholder="Otomatis terisi..." readonly disabled />' +
                    '            <input type="hidden" name="anggota_kelompok[' + index + '][prodi]" class="anggota-prodi-hidden-input" />' +
                    '        </div>' +
                    '        <div class="col-12 col-md-1 text-end text-md-center mt-2 mt-md-0">' +
                    '            <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-anggota-kelompok hover-elevate-up" title="Hapus anggota" aria-label="Hapus anggota">' +
                    '                <i class="fas fa-trash-alt"></i>' +
                    '            </button>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>';
            }

            window.addAnggotaKelompokRow = function() {
                if (!anggotaContainer) {
                    return;
                }

                anggotaContainer.insertAdjacentHTML('beforeend', buildAnggotaRow(anggotaIndex));
                anggotaIndex += 1;
            };

            function fillAnggotaRow(row, data) {
                data = data || {};
                const namaInput = row.querySelector('.anggota-nama-input');
                const prodiInput = row.querySelector('.anggota-prodi-input');
                const namaHiddenInput = row.querySelector('.anggota-nama-hidden-input');
                const prodiHiddenInput = row.querySelector('.anggota-prodi-hidden-input');

                namaInput.value = data.nama || '';
                prodiInput.value = data.prodi || '';
                if (namaHiddenInput) {
                    namaHiddenInput.value = data.nama || '';
                }
                if (prodiHiddenInput) {
                    prodiHiddenInput.value = data.prodi || '';
                }
            }

            function setAnggotaError(row, message) {
                message = message || '';
                const nimInput = row.querySelector('.anggota-nim-input');
                const feedback = row.querySelector('.anggota-nim-feedback');

                nimInput.classList.toggle('is-invalid', Boolean(message));
                feedback.textContent = message;
            }

            function clearLookupTimer(row) {
                if (row && row._lookupTimer) {
                    clearTimeout(row._lookupTimer);
                    row._lookupTimer = null;
                }
            }

            function abortLookupRequest(row) {
                if (row && row._lookupAbortController) {
                    row._lookupAbortController.abort();
                    row._lookupAbortController = null;
                }
            }

            function lookupAnggota(row) {
                const nimInput = row.querySelector('.anggota-nim-input');
                const nim = nimInput.value.trim();
                const requestId = String(++lookupRequestCounter);

                clearLookupTimer(row);
                abortLookupRequest(row);
                row.dataset.lookupRequestId = requestId;
                setAnggotaError(row);

                if (!nim) {
                    fillAnggotaRow(row);
                    delete row.dataset.lookupRequestId;
                    return Promise.resolve();
                }

                if (lookupCache.has(nim)) {
                    fillAnggotaRow(row, lookupCache.get(nim));
                    return Promise.resolve(lookupCache.get(nim));
                }

                const abortController = new AbortController();
                row._lookupAbortController = abortController;

                return fetch(lookupBaseUrl + '/' + encodeURIComponent(nim), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: abortController.signal,
                })
                    .then(function(response) {
                        return response.json().then(function(result) {
                            if (row.dataset.lookupRequestId !== requestId || nimInput.value.trim() !== nim) {
                                return;
                            }

                            if (!response.ok || !result.success) {
                                throw new Error(result.message || ('NIM ' + nim + ' tidak ditemukan pada data mahasiswa.'));
                            }

                            lookupCache.set(nim, result.data);
                            fillAnggotaRow(row, result.data);
                            row._lookupAbortController = null;
                            return result.data;
                        });
                    })
                    .catch(function(error) {
                        if (error.name === 'AbortError') {
                            return;
                        }

                        if (row.dataset.lookupRequestId !== requestId || nimInput.value.trim() !== nim) {
                            return;
                        }

                        fillAnggotaRow(row);
                        setAnggotaError(row, error.message);
                        row._lookupAbortController = null;
                    });
            }

            function scheduleLookupAnggota(row) {
                const nimInput = row.querySelector('.anggota-nim-input');

                clearLookupTimer(row);
                abortLookupRequest(row);
                setAnggotaError(row);

                if (!nimInput.value.trim()) {
                    fillAnggotaRow(row);
                    delete row.dataset.lookupRequestId;
                    return;
                }

                row._lookupTimer = setTimeout(function() {
                    lookupAnggota(row);
                }, lookupDelay);
            }

            if (anggotaContainer) {
                anggotaContainer.querySelectorAll('.anggota-item').forEach(function(row) {
                    const nimInput = row.querySelector('.anggota-nim-input');
                    if (nimInput && nimInput.value.trim()) {
                        lookupAnggota(row);
                    }
                });
            }

            if (anggotaContainer) {
                anggotaContainer.addEventListener('click', function(event) {
                    const button = event.target.closest('.remove-anggota-kelompok');
                    if (!button) {
                        return;
                    }

                    const row = button.closest('.anggota-item');
                    if (row) {
                        clearLookupTimer(row);
                        abortLookupRequest(row);
                        row.remove();
                    }
                });

                anggotaContainer.addEventListener('input', function(event) {
                    const nimInput = event.target.closest('.anggota-nim-input');
                    if (!nimInput) {
                        return;
                    }

                    const row = nimInput.closest('.anggota-item');
                    scheduleLookupAnggota(row);
                });

                anggotaContainer.addEventListener('blur', function(event) {
                    const nimInput = event.target.closest('.anggota-nim-input');
                    if (!nimInput) {
                        return;
                    }

                    lookupAnggota(nimInput.closest('.anggota-item'));
                }, true);
            }

            function validateAnggotaRows() {
                const rows = Array.from(anggotaContainer.querySelectorAll('.anggota-item'));
                const promises = [];

                rows.forEach(function(row) {
                    promises.push(lookupAnggota(row));
                });

                return Promise.all(promises).then(function() {
                    return rows.every(function(row) {
                        const nimInput = row.querySelector('.anggota-nim-input');
                        return !nimInput.classList.contains('is-invalid');
                    });
                });
            }

            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    form.reportValidity();
                    return;
                }

                event.preventDefault();
                validateAnggotaRows().then(function(anggotaValid) {
                    if (!anggotaValid) {
                        return;
                    }

                    submitButton.disabled = true;
                    submitButton.querySelector('.indicator-label').style.display = 'none';
                    submitButton.querySelector('.indicator-progress').style.display = 'inline-block';
                    form.submit();
                });
            });
        });
    </script>

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                text: @json($message),
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
                text: @json($message),
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
