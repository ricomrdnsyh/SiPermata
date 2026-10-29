@extends('layout.main')
@section('title', 'Edit Surat Keterangan Lulus')
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
                                        Edit Surat Permohonan Keterangan Lulus
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Mohon untuk perbarui semua data dengan benar.</div>
                                </div>
                                <div class="separator border-2 border-dashed mb-10"></div>
                                <div id="form-container" class="mt-2">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                        action="{{ route('admin.surat-keterangan-lulus.update', $surat->id_surat_lulus) }}">
                                        @csrf
                                        @method('PUT')

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
                                                                {{ old('nim', $surat->nim) == $mhs->nim ? 'selected' : '' }}>
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
                                                                {{ (old('akademik_id', $surat->akademik_id) == $akademik->id_akademik) ? 'selected' : '' }}>
                                                                {{ $akademik->tahun_akademik }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12">
                                                    <label class="required fw-semibold fs-6 mb-2">
                                                        IPK
                                                        <span id="ipk-loading" class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                    </label>
                                                    <input id="field-ipk" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi setelah memilih mahasiswa"
                                                        value="{{ $dataSimpt?->ipk_ketuntasan ? number_format((float) $dataSimpt->ipk_ketuntasan, 2) : '' }}" readonly />
                                                    <small id="simpt-warning" class="text-warning d-none">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Data IPK tidak ditemukan di SIMPT.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-user text-gray-400 me-2"></i> Data Pribadi Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tempat Lahir</label>
                                                    <input id="field-tempat-lahir" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi setelah memilih mahasiswa"
                                                        value="{{ old('tempat_lahir', $surat->tempat_lahir) }}" disabled />
                                                    <input type="hidden" id="hidden-tempat-lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $surat->tempat_lahir) }}">
                                                    @error('tempat_lahir')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tanggal Lahir</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input id="field-tgl-lahir" type="text"
                                                            class="form-control form-control-sm bg-light ps-12"
                                                            placeholder="Otomatis terisi" autocomplete="off"
                                                            value="{{ old('tgl_lahir', $surat->tgl_lahir ? $surat->tgl_lahir->format('Y-m-d') : '') }}" disabled />
                                                        <input type="hidden" id="hidden-tgl-lahir" name="tgl_lahir" value="{{ old('tgl_lahir', $surat->tgl_lahir ? $surat->tgl_lahir->format('Y-m-d') : '') }}">
                                                    </div>
                                                    @error('tgl_lahir')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-book text-gray-400 me-2"></i> Data Tugas Akhir
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Judul Penelitian/Tugas Akhir</label>
                                                    <textarea id="field-judul-penelitian" class="form-control form-control-sm bg-light" rows="3" disabled>{{ old('judul_penelitian', $surat->judul_penelitian) }}</textarea>
                                                    <input type="hidden" id="hidden-judul-penelitian" name="judul_penelitian" value="{{ old('judul_penelitian', $surat->judul_penelitian) }}">
                                                    @error('judul_penelitian')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center align-items-center gap-3 mt-8">
                                            <a href="{{ route('admin.surat-keterangan-lulus.index') }}" class="btn btn-secondary d-inline-flex align-items-center justify-content-center px-6">Batal</a>
                                            <button type="submit" data-kt-contacts-type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-save me-2"></i> Simpan Perubahan</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
            const selectNim = document.getElementById('select-nim');
            const fieldIpk = document.getElementById('field-ipk');
            const ipkSpinner = document.getElementById('ipk-loading');
            const simptWarn = document.getElementById('simpt-warning');
            const simptUrl = "{{ route('admin.surat-keterangan-lulus.simpt', '__NIM__') }}";
            let isConfirmed = false;

            function fetchSimpt(nim) {
                if (!nim) {
                    fieldIpk.value = '';
                    simptWarn.classList.add('d-none');
                    return;
                }

                ipkSpinner.classList.remove('d-none');
                simptWarn.classList.add('d-none');

                const url = simptUrl.replace('__NIM__', encodeURIComponent(nim));

                fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(data => {
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
                        }
                        
                        if (data.tempat_lahir) {
                            document.getElementById('field-tempat-lahir').value = data.tempat_lahir;
                            document.getElementById('hidden-tempat-lahir').value = data.tempat_lahir;
                        }
                        
                        if (data.tanggal_lahir) {
                            document.getElementById('field-tgl-lahir').value = data.tanggal_lahir;
                            document.getElementById('hidden-tgl-lahir').value = data.tanggal_lahir;
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

            if (selectNim) {
                $('#select-nim').on('change select2:select', function() {
                    fetchSimpt(this.value);
                });
            }

            form.addEventListener('submit', function(event) {
                if (isConfirmed) return;
                event.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                Swal.fire({
                    title: "Konfirmasi Perubahan",
                    text: "Apakah Anda yakin ingin menyimpan perubahan pada surat keterangan lulus ini?",
                    icon: "question",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Simpan Perubahan!",
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
