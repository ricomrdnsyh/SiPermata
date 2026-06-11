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
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm border border-dashed border-dark rounded">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-5">Pilih Kategori Surat Keterangan Aktif</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan pilih kategori Anda untuk melanjutkan
                                    pengisian formulir.</div>
                            </div>
                            <div class="nav-group nav-group-outline mx-auto mb-15" data-kt-buttons="true">
                                <a href="javascript:void(0)"
                                    class="btn btn-sm btn-color-gray-400 btn-active btn-active-primary px-6 py-3 me-2 active"
                                    data-category="umum">Umum</a>
                                <a href="javascript:void(0)"
                                    class="btn btn-sm btn-color-gray-400 btn-active btn-active-primary px-6 py-3 me-2"
                                    data-category="pns">PNS</a>
                                <a href="javascript:void(0)"
                                    class="btn btn-sm btn-color-gray-400 btn-active btn-active-primary px-6 py-3"
                                    data-category="pppk">PPPK</a>
                            </div>
                            <div id="form-container" class="mt-2">
                                <form id="form-umum" class="form-section active" method="POST"
                                    action="{{ route('bak.surat-aktif.store') }}">
                                    @csrf
                                    <input type="hidden" name="kategori" value="UMUM">
                                    <h3 class="mb-5 text-center">Pengajuan Surat Keterangan Aktif Umum</h3>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                <select class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                                   
                                                   >
                                                    <option value="">
                                                        Pilih Mahasiswa...</option>
                                                    @foreach ($mahasiswa as $mhs)
                                                        <option value="{{ $mhs->nim }}">
                                                            {{ $mhs->nim }} - {{ $mhs->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('nim')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="fw-semibold fs-6 mb-2">
                                            Semester
                                            <span id="semester-loading-umum"
                                                class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                        </label>
                                        <input id="field-semester-umum" type="text"
                                            class="form-control form-control-sm bg-light mb-3 mb-lg-0"
                                            placeholder="Otomatis terisi setelah memilih mahasiswa" value=""
                                            disabled />
                                        <input type="hidden" name="semester" id="hidden-semester-umum" value="">
                                        <small id="simpt-warning-umum" class="text-warning d-none">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Data semester tidak ditemukan di SIMPT.
                                        </small>
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                        <textarea name="alamat" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required></textarea>
                                        @error('alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                        <textarea name="keperluan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required></textarea>
                                        @error('keperluan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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
                                <form id="form-pns" class="form-section" method="POST"
                                    action="{{ route('bak.surat-aktif.store') }}">
                                    @csrf
                                    <input type="hidden" name="kategori" value="PNS">
                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PNS</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                <select class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                                   
                                                    required>
                                                    <option value="">
                                                        Pilih Mahasiswa...</option>
                                                    @foreach ($mahasiswa as $mhsw)
                                                        <option value="{{ $mhsw->nim }}">
                                                            {{ $mhsw->nim }} - {{ $mhsw->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('nim')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
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
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">
                                                    Semester
                                                    <span id="semester-loading-pns"
                                                        class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                </label>
                                                <input id="field-semester-pns" type="text"
                                                    class="form-control form-control-sm bg-light"
                                                    placeholder="Otomatis terisi setelah memilih mahasiswa" value=""
                                                    disabled />
                                                <input type="hidden" name="semester" id="hidden-semester-pns" value="">
                                                <small id="simpt-warning-pns" class="text-warning d-none">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Data semester tidak ditemukan di SIMPT.
                                                </small>
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua Sesuai
                                                    SK</label>
                                                <input type="number" name="nip" class="form-control form-control-sm"
                                                    required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Orang Tua Sesuai
                                                    SK</label>
                                                <input type="text" name="nama_ortu"
                                                    class="form-control form-control-sm" required />
                                                @error('nama_ortu')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir Orang
                                                    Tua</label>
                                                <select class="form-select form-select-sm" data-control="select2"
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
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control form-control-sm"
                                                    required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan"
                                                    class="form-control form-control-sm" required />
                                                @error('golongan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>

                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>

                                                    <input type="text" name="tmt"
                                                        class="form-control form-control-sm form-control kt_datepicker_tmt"
                                                        placeholder="Pilih tanggal" value="{{ old('tmt') }}"
                                                        autocomplete="off" required />
                                                </div>

                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja"
                                                    class="form-control form-control-sm" required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="fv-row mb-3">
                                            <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua</label>
                                            <textarea name="alamat" class="form-control form-control-sm" rows="3" required></textarea>
                                            @error('alamat')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                            <textarea name="keperluan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required></textarea>
                                            @error('keperluan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-sm btn-primary w-250px">
                                            <span class="indicator-label">Buat Pengajuan</span>
                                            <span class="indicator-progress" style="display: none;">
                                                Tunggu sebentar...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                                <form id="form-pppk" class="form-section" method="POST"
                                    action="{{ route('bak.surat-aktif.store') }}">
                                    @csrf
                                    <input type="hidden" name="kategori" value="PPPK">
                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PPPK</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                <select class="form-select form-select-sm w-100"
                                                    data-control="select2" data-placeholder="Pilih Mahasiswa"
                                                    name="nim"
                                                    required>
                                                    <option value="">
                                                        Pilih Mahasiswa...</option>
                                                    @foreach ($mahasiswa as $mhswa)
                                                        <option value="{{ $mhswa->nim }}">
                                                            {{ $mhswa->nim }} - {{ $mhswa->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('nim')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
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
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">
                                                    Semester
                                                    <span id="semester-loading-pppk"
                                                        class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                </label>
                                                <input id="field-semester-pppk" type="text"
                                                    class="form-control form-control-sm bg-light"
                                                    placeholder="Otomatis terisi setelah memilih mahasiswa" value=""
                                                    disabled />
                                                <input type="hidden" name="semester" id="hidden-semester-pppk" value="">
                                                <small id="simpt-warning-pppk" class="text-warning d-none">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Data semester tidak ditemukan di SIMPT.
                                                </small>
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua Sesuai
                                                    SK</label>
                                                <input type="number" name="nip" class="form-control form-control-sm"
                                                    required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Orang Tua Sesuai
                                                    SK</label>
                                                <input type="text" name="nama_ortu"
                                                    class="form-control form-control-sm" required />
                                                @error('nama_ortu')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir Orang
                                                    Tua</label>
                                                <select class="form-select form-select-sm" data-control="select2"
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
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control form-control-sm"
                                                    required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan"
                                                    class="form-control form-control-sm" required />
                                                @error('golongan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>

                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input type="text" name="tmt"
                                                        class="form-control form-control-sm form-control kt_datepicker_tmt"
                                                        placeholder="Pilih tanggal" value="{{ old('tmt') }}"
                                                        autocomplete="off" required />
                                                </div>

                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja"
                                                    class="form-control form-control-sm" required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="fv-row mb-3">
                                            <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua</label>
                                            <textarea name="alamat" class="form-control form-control-sm" rows="3" required></textarea>
                                            @error('alamat')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                            <textarea name="keperluan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required></textarea>
                                            @error('keperluan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-sm btn-primary w-250px">
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
</div>
@endsection
@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            if (typeof flatpickr !== "undefined") {
                document.querySelectorAll(".kt_datepicker_tmt").forEach(function(el) {
                    flatpickr(el, {
                        dateFormat: "Y-m-d",
                        allowInput: true
                    });

                    flatpickr(el, {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d/m/Y",
                        allowInput: true
                    });
                });
            }

            const simptUrl = "{{ route('bak.surat-aktif.simpt', '__NIM__') }}";

            function fetchSimpt(nim, suffix) {
                const field = document.getElementById('field-semester-' + suffix);
                const hidden = document.getElementById('hidden-semester-' + suffix);
                const spinner = document.getElementById('semester-loading-' + suffix);
                const warning = document.getElementById('simpt-warning-' + suffix);

                if (!field || !spinner || !warning || !hidden) return;

                if (!nim) {
                    field.value = '';
                    hidden.value = '';
                    warning.classList.add('d-none');
                    return;
                }

                spinner.classList.remove('d-none');
                warning.classList.add('d-none');
                field.value = '';
                hidden.value = '';

                fetch(simptUrl.replace('__NIM__', encodeURIComponent(nim)), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.semester) {
                            field.value = data.semester;
                            hidden.value = data.semester;
                            warning.classList.add('d-none');
                        } else {
                            field.value = '-';
                            hidden.value = '';
                            warning.classList.remove('d-none');
                        }
                    })
                    .catch(() => {
                        field.value = '-';
                        hidden.value = '';
                        warning.classList.remove('d-none');
                    })
                    .finally(() => {
                        spinner.classList.add('d-none');
                    });
            }

            const formMap = {
                'form-umum': 'umum',
                'form-pns': 'pns',
                'form-pppk': 'pppk'
            };

            Object.entries(formMap).forEach(([formId, suffix]) => {
                const form = document.getElementById(formId);
                if (!form) return;
                const select = form.querySelector('select[name="nim"]');
                if (!select) return;

                if (window.jQuery && jQuery.fn.select2) {
                    jQuery(select)
                        .off('.simptAktifBak')
                        .on('change.simptAktifBak select2:select.simptAktifBak', function() {
                            fetchSimpt(this.value, suffix);
                        });
                } else {
                    select.addEventListener('change', function() {
                        fetchSimpt(this.value, suffix);
                    });
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
