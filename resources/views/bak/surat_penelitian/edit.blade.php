@extends('layout.main')
@section('title', 'Surat Izin Penelitian')
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm border border-dashed border-dark rounded">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-5">Surat Permohonan Izin Penelitian</h1>
                                <div class="text-gray-400 fw-bold fs-5">Mohon untuk perbarui semua data dengan benar.</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
                                    action="{{ route('bak.surat-izin-penelitian.update', $surat->id_surat_izin_penelitian) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                <select class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                                   
                                                    required>
                                                    <option value="">
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

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tempat Penelitian</label>
                                                <select class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Tempat Penelitian"
                                                    name="mitra_id"
                                                    required>
                                                    <option value="">
                                                        Pilih Tempat Penelitian...</option>
                                                    @foreach ($mitra as $item)
                                                        <option value="{{ $item->id_mitra }}"
                                                            {{ old('mitra_id', $surat->mitra_id) == $item->id_mitra ? 'selected' : '' }}>
                                                            {{ $item->nama_mitra }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('mitra_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input id="tgl_mulai" type="text" name="tgl_mulai"
                                                        class="form-control form-control-sm"
                                                        placeholder="Pilih tanggal mulai" autocomplete="off"
                                                        value="{{ old('tgl_mulai', $surat->tgl_mulai ? $surat->tgl_mulai->format('Y-m-d') : '') }}"
                                                        required />
                                                </div>
                                                @error('tgl_mulai')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input id="tgl_selesai" type="text" name="tgl_selesai"
                                                        class="form-control form-control-sm"
                                                        placeholder="Pilih tanggal selesai" autocomplete="off"
                                                        value="{{ old('tgl_selesai', $surat->tgl_selesai ? $surat->tgl_selesai->format('Y-m-d') : '') }}"
                                                        required />
                                                </div>
                                                @error('tgl_selesai')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Judul Penelitian</label>
                                                <textarea name="judul_penelitian" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required>{{ old('judul_penelitian', $surat->judul_penelitian) }}</textarea>
                                                @error('judul_penelitian')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
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
            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    return;
                }
                submitButton.disabled = true;
                submitButton.querySelector('.indicator-label').style.display = 'none';
                submitButton.querySelector('.indicator-progress').style.display = 'inline-block';
            });

            const nimSelect = document.querySelector('select[name="nim"]');
            const simptUrl = "{{ route('bak.surat-penelitian.simpt', '__NIM__') }}";
            
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
                            customClass: {
                                confirmButton: "btn btn-danger"
                            }
                        }).then(() => {
                            $(nimSelect).val(null).trigger('change.select2');
                        });
                    }
                });
            }
            
            if (nimSelect) {
                if (window.jQuery) {
                    $(nimSelect).on('change', function() {
                        validateSimpt(this.value);
                    });
                } else {
                    nimSelect.addEventListener('change', function() {
                        validateSimpt(this.value);
                    });
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mulaiEl = document.getElementById('tgl_mulai');
            const selesaiEl = document.getElementById('tgl_selesai');
            const mulaiVal = mulaiEl.value || null;
            const selesaiVal = selesaiEl.value || null;

            const fpMulai = flatpickr(mulaiEl, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                altInputClass: "form-control form-control-sm",
                allowInput: true,
                defaultDate: mulaiVal,
                maxDate: selesaiVal,
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.required = true;
                        instance.altInput.placeholder = mulaiEl.placeholder || '';
                    }
                },
                onChange: function(selectedDates, dateStr) {
                    fpSelesai.set("minDate", dateStr || null);
                }
            });

            const fpSelesai = flatpickr(selesaiEl, {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                altInputClass: "form-control form-control-sm",
                allowInput: true,
                defaultDate: selesaiVal,
                minDate: mulaiVal,
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.required = true;
                        instance.altInput.placeholder = selesaiEl.placeholder || '';
                    }
                },
                onChange: function(selectedDates, dateStr) {
                    fpMulai.set("maxDate", dateStr || null);
                }
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
