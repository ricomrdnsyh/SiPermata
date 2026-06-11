@extends('layout.main')
@section('title', 'Surat Keterangan Lulus')
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm border border-dashed border-dark rounded">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Surat Permohonan Keterangan Lulus</h1>
                                <div class="text-gray-400 fw-bold fs-5">Mohon untuk mengisi semua data dengan benar.</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                    action="{{ route('admin.surat-keterangan-lulus.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
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

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">
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
                                                    Data IPK tidak ditemukan di SIMPT.
                                                 </small>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tempat Lahir</label>
                                                <input id="field-tempat-lahir" type="text"
                                                    class="form-control form-control-sm mb-3 mb-lg-0 bg-light"
                                                    placeholder="Otomatis terisi setelah memilih mahasiswa"
                                                    value="{{ old('tempat_lahir') }}" disabled />
                                                <input type="hidden" id="hidden-tempat-lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
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
                                                    <input id="field-tgl-lahir" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi" autocomplete="off"
                                                        value="{{ old('tgl_lahir') }}" disabled />
                                                    <input type="hidden" id="hidden-tgl-lahir" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                                                </div>
                                                @error('tgl_lahir')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Judul Penelitian/Tugas Akhir</label>
                                                <textarea id="field-judul-penelitian" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('judul_penelitian') }}</textarea>
                                                <input type="hidden" id="hidden-judul-penelitian" name="judul_penelitian" value="{{ old('judul_penelitian') }}">
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
</div>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('kt_ecommerce_settings_general_form');
            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            const selectNim = document.getElementById('select-nim');
            const fieldIpk = document.getElementById('field-ipk');
            const ipkSpinner = document.getElementById('ipk-loading');
            const simptWarn = document.getElementById('simpt-warning');

            const simptUrl = "{{ route('admin.surat-keterangan-lulus.simpt', '__NIM__') }}";

            function fetchSimpt(nim) {
                if (!nim) {
                    fieldIpk.value = '';
                    simptWarn.classList.add('d-none');
                    return;
                }

                ipkSpinner.classList.remove('d-none');
                simptWarn.classList.add('d-none');
                fieldIpk.value = '';

                const url = simptUrl.replace('__NIM__', encodeURIComponent(nim));

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.is_eligible === false) {
                            Swal.fire({
                                text: "Mahasiswa dengan NIM ini belum terdaftar di daftar mahasiswa lulusan (Eligible Lulus). Silakan daftarkan terlebih dahulu sebelum membuat pengajuan.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
                            }).then(() => {
                                $('#select-nim').val('').trigger('change.select2');
                                fieldIpk.value = '';
                                simptWarn.classList.add('d-none');
                            });
                            return;
                        }

                        if (data.ipk) {
                            fieldIpk.value = data.ipk;
                            simptWarn.classList.add('d-none');
                        } else {
                            fieldIpk.value = '-';
                            simptWarn.classList.remove('d-none');
                        }
                        
                        if (data.judul_penelitian) {
                            document.getElementById('field-judul-penelitian').value = data.judul_penelitian;
                            document.getElementById('hidden-judul-penelitian').value = data.judul_penelitian;
                        } else {
                            document.getElementById('field-judul-penelitian').value = '';
                            document.getElementById('hidden-judul-penelitian').value = '';
                        }
                        
                        if (data.tempat_lahir) {
                            document.getElementById('field-tempat-lahir').value = data.tempat_lahir;
                            document.getElementById('hidden-tempat-lahir').value = data.tempat_lahir;
                        } else {
                            document.getElementById('field-tempat-lahir').value = '-';
                            document.getElementById('hidden-tempat-lahir').value = '';
                        }
                        
                        if (data.tanggal_lahir) {
                            document.getElementById('field-tgl-lahir').value = data.tanggal_lahir;
                            document.getElementById('hidden-tgl-lahir').value = data.tanggal_lahir;
                        } else {
                            document.getElementById('field-tgl-lahir').value = '-';
                            document.getElementById('hidden-tgl-lahir').value = '';
                        }
                    })
                    .catch(() => {
                        fieldIpk.value = '-';
                        simptWarn.classList.remove('d-none');
                    })
                    .finally(() => {
                        ipkSpinner.classList.add('d-none');
                    });
            }

            $('#select-nim').on('change', function() {
                fetchSimpt(this.value);
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
