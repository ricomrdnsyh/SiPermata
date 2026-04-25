@extends('layout.main')
@section('title', 'Surat Permohonan Observasi')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
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
                                    action="{{ route('mahasiswa.surat-observasi.update', $surat->id_surat_observasi) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                <input type="text" name="nim"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ auth()->user()->reference_id }}" disabled required />
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
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="text" name="semester"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $dataSimpt?->semester ?? '-' }}" disabled />
                                                @if (blank($dataSimpt?->semester))
                                                    <small class="text-warning">Data semester belum ditemukan di SIMPT.</small>
                                                @endif
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
                                                <select class="form-select form-select-sm select2-hidden-accessible w-100"
                                                    data-control="select2" data-placeholder="Pilih Tempat Observasi"
                                                    name="mitra_id" data-select2-id="select2-data-72-r5i4" tabindex="-1"
                                                    aria-hidden="true" data-kt-initialized="1" required>
                                                    <option value="" data-select2-id="select2-data-74-9zwr">
                                                        Pilih Tempat Observasi...</option>
                                                    @foreach ($mitra as $m)
                                                        <option value="{{ $m->id_mitra }}"
                                                            {{ old('mitra_id', $surat->mitra_id) == $m->id_mitra ? 'selected' : '' }}>
                                                            {{ $m->nama_mitra }}
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

                                        @include('mahasiswa.surat_observasi._anggota_kelompok', [
                                            'anggotaKelompok' => old('anggota_kelompok', $surat->anggota_kelompok ?? []),
                                        ])
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
            if (!form) {
                return;
            }

            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            const anggotaContainer = document.getElementById('anggota-kelompok-container');
            const lookupBaseUrl = @json(url('/mahasiswa/surat-observasi/anggota'));
            const lookupDelay = 180;
            let anggotaIndex = anggotaContainer ? anggotaContainer.querySelectorAll('tr').length : 0;
            let lookupRequestCounter = 0;
            const lookupCache = new Map();

            const tglObsEl = document.getElementById('tgl_observasi');
            const tglObsVal = tglObsEl.value || null;

            if (tglObsEl && typeof flatpickr === 'function') {
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
            }

            function buildAnggotaRow(index) {
                return '' +
                    '<tr>' +
                    '    <td>' +
                    '        <input type="text" name="anggota_kelompok[' + index + '][nim]" class="form-control form-control-sm anggota-nim-input" placeholder="NIM mahasiswa" autocomplete="off" />' +
                    '        <div class="invalid-feedback anggota-nim-feedback"></div>' +
                    '    </td>' +
                    '    <td>' +
                    '        <input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-nama-input" placeholder="Nama mahasiswa" readonly disabled />' +
                    '        <input type="hidden" name="anggota_kelompok[' + index + '][nama]" class="anggota-nama-hidden-input" />' +
                    '    </td>' +
                    '    <td>' +
                    '        <input type="text" class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-prodi-input" placeholder="Prodi mahasiswa" readonly disabled />' +
                    '        <input type="hidden" name="anggota_kelompok[' + index + '][prodi]" class="anggota-prodi-hidden-input" />' +
                    '    </td>' +
                    '    <td class="text-center">' +
                    '        <button type="button" class="btn btn-sm btn-danger remove-anggota-kelompok" title="Hapus anggota" aria-label="Hapus anggota"><i class="fas fa-trash-alt"></i></button>' +
                    '    </td>' +
                    '</tr>';
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
                anggotaContainer.querySelectorAll('tr').forEach(function(row) {
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

                    const row = button.closest('tr');
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

                    const row = nimInput.closest('tr');
                    scheduleLookupAnggota(row);
                });

                anggotaContainer.addEventListener('blur', function(event) {
                    const nimInput = event.target.closest('.anggota-nim-input');
                    if (!nimInput) {
                        return;
                    }

                    lookupAnggota(nimInput.closest('tr'));
                }, true);
            }

            function validateAnggotaRows() {
                const rows = Array.from(anggotaContainer.querySelectorAll('tr'));
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
