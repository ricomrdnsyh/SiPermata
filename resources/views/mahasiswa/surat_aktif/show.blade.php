@extends('layout.main')

@section('title', 'Detail Surat Keterangan Aktif')

@section('css')
    <style>
        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-17">
                        <div class="d-flex flex-column">
                            <div class="mb-13 text-center">
                                <h1 class="fs-2hx fw-bolder mb-5">Detail Surat Keterangan Aktif</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan lihat detail pengajuan Anda !</div>
                            </div>

                            <!-- Form Container -->
                            <div id="form-container" class="mt-2">
                                <!-- Form Umum -->
                                <form id="form-umum" class="form-section active">
                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">

                                    <h3 class="mb-5 text-center">Pengajuan Surat Keterangan Aktif Umum</h3>

                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                        <input type="text" name="nim" class="form-control mb-3 mb-lg-0"
                                            value="{{ auth()->user()->reference_id }}" disabled required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                        <input type="text" name="akademik_id" class="form-control mb-3 mb-lg-0"
                                            value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}"
                                            disabled />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                        <input type="text" name="semester" class="form-control mb-3 mb-lg-0"
                                            value="{{ $surat->semester }}" disabled />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                        <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="1" disabled>{{ old('alamat', $surat->alamat) }}</textarea>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Catatan</label>
                                        <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="2" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
                                    </div>
                                </form>

                                <!-- Form PNS -->
                                <form id="form-pns" class="form-section">

                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">

                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PNS</h3>

                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                        <input type="text" name="nim" class="form-control"
                                            value="{{ auth()->user()->reference_id }}" disabled required />
                                    </div>

                                    <div class="row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                <input type="text" name="akademik_id" class="form-control mb-3 mb-lg-0"
                                                    value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}"
                                                    disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="text" name="semester" class="form-control mb-3 mb-lg-0"
                                                    value="{{ $surat->semester }}" disabled />
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua</label>
                                                <input type="text" name="nip" class="form-control"
                                                    value="{{ $surat->nip }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua</label>
                                                <input type="text" name="nama_ortu" class="form-control"
                                                    value="{{ $surat->nama_ortu }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir Orang
                                                    Tua</label>
                                                <input type="text" name="pendidikan_terakhir" class="form-control"
                                                    value="{{ $surat->pendidikan_terakhir }}" disabled />
                                            </div>
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control"
                                                    value="{{ $surat->pangkat }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan" class="form-control"
                                                    value="{{ $surat->golongan }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>
                                                <input type="date" name="tmt" class="form-control"
                                                    value="{{ $surat->tmt }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja" class="form-control"
                                                    value="{{ $surat->unit_kerja }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                                <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="1" disabled>{{ old('alamat', $surat->alamat) }}</textarea>
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Catatan</label>
                                                <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="2" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Form PPPK (sama seperti PNS) -->
                                <form id="form-pppk" class="form-section">

                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">

                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PPPK</h3>

                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                        <input type="text" name="nim" class="form-control"
                                            value="{{ auth()->user()->reference_id }}" disabled required />
                                    </div>

                                    <div class="row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                <input type="text" name="akademik_id"
                                                    class="form-control mb-3 mb-lg-0"
                                                    value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}"
                                                    disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="text" name="semester" class="form-control mb-3 mb-lg-0"
                                                    value="{{ $surat->semester }}" disabled />
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua</label>
                                                <input type="text" name="nip" class="form-control"
                                                    value="{{ $surat->nip }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua</label>
                                                <input type="text" name="nama_ortu" class="form-control"
                                                    value="{{ $surat->nama_ortu }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir Orang
                                                    Tua</label>
                                                <input type="text" name="pendidikan_terakhir" class="form-control"
                                                    value="{{ $surat->pendidikan_terakhir }}" disabled />
                                            </div>
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control"
                                                    value="{{ $surat->pangkat }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan" class="form-control"
                                                    value="{{ $surat->golongan }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>
                                                <input type="date" name="tmt" class="form-control"
                                                    value="{{ $surat->tmt }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja" class="form-control"
                                                    value="{{ $surat->unit_kerja }}" disabled />
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                                <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="1" disabled>{{ old('alamat', $surat->alamat) }}</textarea>
                                            </div>
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Catatan</label>
                                                <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="2" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
                                            </div>
                                        </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            const kategori = "{{ $surat->kategori }}";

            const formMap = {
                'UMUM': 'form-umum',
                'PNS': 'form-pns',
                'PPPK': 'form-pppk'
            };

            const activeFormId = formMap[kategori] || 'form-umum';

            const allForms = [
                document.getElementById('form-umum'),
                document.getElementById('form-pns'),
                document.getElementById('form-pppk')
            ];

            allForms.forEach(form => {
                if (form) form.classList.remove('active');
            });

            const activeForm = document.getElementById(activeFormId);
            if (activeForm) {
                activeForm.classList.add('active');
            }

            function attachSpinnerToForm(form) {
                const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
                if (!submitButton) return;

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
            }

            allForms.forEach(form => {
                if (form) {
                    attachSpinnerToForm(form);
                }
            });
        });
    </script>
@endsection
