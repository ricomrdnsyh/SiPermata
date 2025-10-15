@extends('layout.main')

@section('title', 'Surat Keterangan Aktif')

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
                                <h1 class="fs-2hx fw-bolder mb-5">Pilih Kategori Surat Keterangan Aktif</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan pilih kategori Anda untuk melanjutkan
                                    pengisian formulir.</div>
                            </div>

                            <!-- Nav Group -->
                            <div class="nav-group nav-group-outline mx-auto mb-15" data-kt-buttons="true">
                                <a href="javascript:void(0)"
                                    class="btn btn-color-gray-400 btn-active btn-active-primary px-6 py-3 me-2 active"
                                    data-category="umum">Umum</a>
                                <a href="javascript:void(0)"
                                    class="btn btn-color-gray-400 btn-active btn-active-primary px-6 py-3 me-2"
                                    data-category="pns">PNS</a>
                                <a href="javascript:void(0)"
                                    class="btn btn-color-gray-400 btn-active btn-active-primary px-6 py-3"
                                    data-category="pppk">PPPK</a>
                            </div>

                            <!-- Form Container -->
                            <div id="form-container" class="mt-2">
                                <!-- Form Umum -->
                                <form id="form-umum" class="form-section active" method="POST"
                                    action="{{ route('mahasiswa.suratAktif.store') }}">
                                    @csrf
                                    <input type="hidden" name="kategori" value="UMUM">

                                    <h3 class="mb-5 text-center">Pengajuan Surat Keterangan Aktif Umum</h3>

                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                        <input type="text" name="nim" class="form-control mb-3 mb-lg-0"
                                            value="{{ auth()->user()->reference_id }}" disabled required />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                        <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                            data-control="select2" data-placeholder="Pilih Akademik" name="akademik_id"
                                            data-select2-id="select2-data-72-r5i3" tabindex="-1" aria-hidden="true"
                                            data-kt-initialized="1">
                                            <option value="" data-select2-id="select2-data-74-9zwr">
                                                Pilih Akademik...</option>
                                            @foreach ($akademik as $item)
                                                <option value="{{ $item->id_akademik }}">
                                                    {{ $item->tahun_akademik }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('akademik_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                        <input type="number" name="semester" class="form-control mb-3 mb-lg-0" />
                                        @error('semester')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                        <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="3"></textarea>
                                        @error('alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="text-center mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-100 w-md-50">
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

                                <!-- Form PNS -->
                                <form id="form-pns" class="form-section" method="POST"
                                    action="{{ route('mahasiswa.suratAktif.store') }}">
                                    @csrf
                                    <input type="hidden" name="kategori" value="PNS">

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
                                                <select class="form-select form-select-solid" data-control="select2"
                                                    name="akademik_id" required>
                                                    <option value="">Pilih Akademik...</option>
                                                    @foreach ($akademik as $akd)
                                                        <option value="{{ $akd->id_akademik }}">{{ $akd->tahun_akademik }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('akademik_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="number" name="semester" class="form-control" required />
                                                @error('semester')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua</label>
                                                <input type="number" name="nip" class="form-control" required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua</label>
                                                <input type="text" name="nama_ortu" class="form-control" required />
                                                @error('nama_ortu')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir Orang
                                                    Tua</label>
                                                <select class="form-select form-select-solid" data-control="select2"
                                                    name="pendidikan_terakhir" required>
                                                    <option value="">Pilih Pendidikan Terakhir</option>
                                                    <option value="Tidak sekolah">Tidak sekolah</option>
                                                    <option value="PAUD">PAUD</option>
                                                    <option value="TK / sederajat">TK / sederajat</option>
                                                    <option value="Putus SD">Putus SD</option>
                                                    <option value="SD / sederajat">SD / sederajat</option>
                                                    <option value="SMP / sederajat">SMP / sederajat</option>
                                                    <option value="SMA / sederajat">SMA / sederajat</option>
                                                    <option value="Paket A">Paket A</option>
                                                    <option value="Paket B">Paket B</option>
                                                    <option value="Paket C">Paket C</option>
                                                    <option value="D1">D1</option>
                                                    <option value="D2">D2</option>
                                                    <option value="D3">D3</option>
                                                    <option value="D4">D4</option>
                                                    <option value="S1">S1</option>
                                                    <option value="SP-1">SP-1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="SP-2">SP-2</option>
                                                    <option value="S3">S3</option>
                                                    <option value="Non Formal">Non Formal</option>
                                                    <option value="Informal">Informal</option>
                                                    <option value="Pendidikan Profesi">Pendidikan Profesi</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                                @error('pendidikan_terakhir')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control" required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan" class="form-control" required />
                                                @error('golongan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>
                                                <input type="date" name="tmt" class="form-control" required />
                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja" class="form-control" required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Alamat tetap full width -->
                                            <div class="fv-row mb-7 col-12">
                                                <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="3" required></textarea>
                                                @error('alamat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-100 w-md-50">
                                            <span class="indicator-label">Buat Pengajuan</span>
                                            <span class="indicator-progress" style="display: none;">
                                                Tunggu sebentar...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>

                                <!-- Form PPPK (sama seperti PNS) -->
                                <form id="form-pppk" class="form-section" method="POST"
                                    action="{{ route('mahasiswa.suratAktif.store') }}">
                                    @csrf
                                    <input type="hidden" name="kategori" value="PPPK">

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
                                                <select class="form-select form-select-solid" data-control="select2"
                                                    name="akademik_id" required>
                                                    <option value="">Pilih Akademik...</option>
                                                    @foreach ($akademik as $akdm)
                                                        <option value="{{ $akdm->id_akademik }}">
                                                            {{ $akdm->tahun_akademik }}</option>
                                                    @endforeach
                                                </select>
                                                @error('akademik_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="number" name="semester" class="form-control" required />
                                                @error('semester')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua</label>
                                                <input type="number" name="nip" class="form-control" required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua</label>
                                                <input type="text" name="nama_ortu" class="form-control" required />
                                                @error('nama_ortu')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir Orang
                                                    Tua</label>
                                                <select class="form-select form-select-solid" data-control="select2"
                                                    name="pendidikan_terakhir" required>
                                                    <option value="">Pilih Pendidikan Terakhir</option>
                                                    <option value="Tidak sekolah">Tidak sekolah</option>
                                                    <option value="PAUD">PAUD</option>
                                                    <option value="TK / sederajat">TK / sederajat</option>
                                                    <option value="Putus SD">Putus SD</option>
                                                    <option value="SD / sederajat">SD / sederajat</option>
                                                    <option value="SMP / sederajat">SMP / sederajat</option>
                                                    <option value="SMA / sederajat">SMA / sederajat</option>
                                                    <option value="Paket A">Paket A</option>
                                                    <option value="Paket B">Paket B</option>
                                                    <option value="Paket C">Paket C</option>
                                                    <option value="D1">D1</option>
                                                    <option value="D2">D2</option>
                                                    <option value="D3">D3</option>
                                                    <option value="D4">D4</option>
                                                    <option value="S1">S1</option>
                                                    <option value="SP-1">SP-1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="SP-2">SP-2</option>
                                                    <option value="S3">S3</option>
                                                    <option value="Non Formal">Non Formal</option>
                                                    <option value="Informal">Informal</option>
                                                    <option value="Pendidikan Profesi">Pendidikan Profesi</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                                @error('pendidikan_terakhir')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control" required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan" class="form-control" required />
                                                @error('golongan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>
                                                <input type="date" name="tmt" class="form-control" required />
                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja" class="form-control" required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7 col-12">
                                                <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="3" required></textarea>
                                                @error('alamat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-100 w-md-50">
                                            <span class="indicator-label">Buat Pengajuan</span>
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
@endsection

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // === 1. Switch Form (Umum / PNS / PPPK) ===
            const categoryButtons = document.querySelectorAll('[data-category]');
            const formElements = {
                umum: document.getElementById('form-umum'),
                pns: document.getElementById('form-pns'),
                pppk: document.getElementById('form-pppk')
            };

            categoryButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    categoryButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    Object.values(formElements).forEach(form => {
                        if (form) form.classList.remove('active');
                    });

                    const category = this.getAttribute('data-category');
                    if (formElements[category]) {
                        formElements[category].classList.add('active');
                    }
                });
            });

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

            const allForms = [
                document.getElementById('form-umum'),
                document.getElementById('form-pns'),
                document.getElementById('form-pppk')
            ];

            allForms.forEach(form => {
                if (form) {
                    attachSpinnerToForm(form);
                }
            });
        });

        @if (Session::has('success'))
            Swal.fire({
                text: "{{ Session::get('success') }}",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        @endif

        @if (Session::has('failed'))
            Swal.fire({
                text: "{{ Session::get('failed') }}",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });
        @endif
    </script>
@endsection
