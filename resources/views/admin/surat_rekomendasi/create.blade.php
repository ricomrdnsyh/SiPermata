@extends('layout.main')
@section('title', 'Surat Rekomendasi')
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm border border-dashed border-dark rounded">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Surat Permohonan Rekomendasi</h1>
                                <div class="text-gray-400 fw-bold fs-5">Mohon untuk mengisi semua data dengan benar.</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                    action="{{ route('admin.surat-rekomendasi.store') }}">
                                    @csrf
                                    <div class="row">

                                                                                <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                <select id="select-nim" class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                                    required>
                                                    <option value="">Pilih Mahasiswa...</option>
                                                    @foreach ($mahasiswa as $mhs)
                                                        <option value="{{ $mhs->nim }}"
                                                            {{ old('nim') == $mhs->nim ? 'selected' : '' }}>
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
                                                <input type="text" class="form-control form-control-sm"
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
                                                    placeholder="Otomatis terisi setelah memilih mahasiswa" value=""
                                                    readonly />
                                            </div>
                                        </div>

                                                                                <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">
                                                    IPK
                                                    <span id="ipk-loading"
                                                        class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                </label>
                                                <input id="field-ipk" type="text"
                                                    class="form-control form-control-sm bg-light"
                                                    placeholder="Otomatis terisi setelah memilih mahasiswa" value=""
                                                    readonly />
                                                <small id="simpt-warning" class="text-warning d-none">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Data IPK/Semester tidak ditemukan di SIMPT.
                                                </small>
                                            </div>
                                        </div>

                                                                                <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tanggal Pelaksanaan</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input id="tgl_pelaksanaan" type="text" name="tgl_pelaksanaan"
                                                        class="form-control form-control-sm"
                                                        placeholder="Pilih tanggal pelaksanaan" autocomplete="off"
                                                        required />
                                                </div>
                                                @error('tgl_pelaksanaan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                                                                <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Keperluan Rekomendasi</label>
                                                <textarea name="keperluan" placeholder="Penerima Beasiswa GenBi, MBKM Santri, dll" class="form-control form-control-sm"
                                                    rows="3" required>{{ old('keperluan') }}</textarea>
                                                @error('keperluan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                                                                <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Penyelenggara</label>
                                                <textarea name="penyelenggara" placeholder="Bank Indonesia, LP3M Universitas Nurul Jadid, dll"
                                                    class="form-control form-control-sm" rows="3" required>{{ old('penyelenggara') }}</textarea>
                                                @error('penyelenggara')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-250px">
                                            <span class="indicator-label">
                                                <i class="fas fa-save me-2"></i> Buat Pengajuan
                                            </span>
                                            <span class="indicator-progress" style="display:none">
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
            const submitBtn = form.querySelector('[data-kt-contacts-type="submit"]');
            const fieldSmt = document.getElementById('field-semester');
            const fieldIpk = document.getElementById('field-ipk');
            const smtSpinner = document.getElementById('semester-loading');
            const ipkSpinner = document.getElementById('ipk-loading');
            const simptWarn = document.getElementById('simpt-warning');

            flatpickr(document.getElementById('tgl_pelaksanaan'), {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                altInputClass: "form-control form-control-sm",
                allowInput: true,
                onReady: function(_, __, instance) {
                    if (instance.altInput) {
                        instance.altInput.required = true;
                        instance.altInput.placeholder = 'Pilih tanggal pelaksanaan';
                    }
                }
            });

            const simptUrl = "{{ route('admin.surat-rekomendasi.simpt', '__NIM__') }}";

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
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.is_valid_krs === false) {
                            Swal.fire({
                                text: "Mahasiswa belum mengisi KRS pada semester ini. Pembuatan surat tidak dapat dilanjutkan.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, mengerti",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
                            }).then(() => {
                                $('#select-nim').val('').trigger('change.select2');
                                fieldSmt.value = '';
                                fieldIpk.value = '';
                                simptWarn.classList.add('d-none');
                            });
                            return;
                        }

                        if (data.semester && data.ipk) {
                            fieldSmt.value = data.semester;
                            fieldIpk.value = data.ipk;
                            simptWarn.classList.add('d-none');
                        } else {
                            fieldSmt.value = '-';
                            fieldIpk.value = '-';
                            simptWarn.classList.remove('d-none');
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

            $('#select-nim').on('change', function() {
                fetchSimpt(this.value);
            });

            form.addEventListener('submit', function() {
                if (!form.checkValidity()) return;
                submitBtn.disabled = true;
                submitBtn.querySelector('.indicator-label').style.display = 'none';
                submitBtn.querySelector('.indicator-progress').style.display = 'inline-block';
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
