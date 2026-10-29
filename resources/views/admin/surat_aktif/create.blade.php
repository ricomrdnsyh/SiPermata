@extends('layout.main')
@section('title', 'Surat Keterangan Aktif')
@section('css')
    <style>
        .form-section {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        .form-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .category-card {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
        }

        .category-card.active {
            border-color: var(--bs-primary) !important;
            background-color: var(--bs-primary-light) !important;
        }

        .category-card.active-pns {
            border-color: var(--bs-info) !important;
            background-color: var(--bs-info-light) !important;
        }

        .category-card.active-pppk {
            border-color: var(--bs-success) !important;
            background-color: var(--bs-success-light) !important;
        }

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
                                        Pengajuan Surat Keterangan Aktif
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Silakan pilih kategori untuk melanjutkan pengisian formulir.</div>
                                </div>

                                <div class="row g-6 mb-12" id="category-selector">
                                    <div class="col-md-4">
                                        <label
                                            class="card category-card border border-dashed border-2 border-gray-300 active h-100"
                                            data-category="umum" data-active-class="active">
                                            <div class="card-body p-6 text-center d-flex flex-column justify-content-center">
                                                <i class="fas fa-user-graduate fs-3x text-primary mb-4 d-block"></i>
                                                <span class="fs-4 fw-bold text-gray-800 d-block mb-1">Kategori Umum</span>
                                                <span class="fs-7 text-muted fw-semibold">Untuk mahasiswa reguler secara umum</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="card category-card border border-dashed border-2 border-gray-300 h-100"
                                            data-category="pns" data-active-class="active-pns">
                                            <div class="card-body p-6 text-center d-flex flex-column justify-content-center">
                                                <i class="fas fa-user-tie fs-3x text-info mb-4 d-block"></i>
                                                <span class="fs-4 fw-bold text-gray-800 d-block mb-1">Kategori PNS</span>
                                                <span class="fs-7 text-muted fw-semibold">Khusus untuk mahasiswa anak PNS</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="card category-card border border-dashed border-2 border-gray-300 h-100"
                                            data-category="pppk" data-active-class="active-pppk">
                                            <div class="card-body p-6 text-center d-flex flex-column justify-content-center">
                                                <i class="fas fa-user-clock fs-3x text-success mb-4 d-block"></i>
                                                <span class="fs-4 fw-bold text-gray-800 d-block mb-1">Kategori PPPK</span>
                                                <span class="fs-7 text-muted fw-semibold">Khusus untuk mahasiswa anak PPPK</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="separator border-2 border-dashed mb-10"></div>

                                <div id="form-container">
                                    <form id="form-umum" class="form-section active" method="POST"
                                        action="{{ route('admin.surat-aktif.store') }}">
                                        @csrf
                                        <input type="hidden" name="kategori" value="UMUM">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-primary me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Formulir Kategori Umum</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                    <select class="form-select form-select-sm w-100" data-control="select2"
                                                        data-placeholder="Pilih Mahasiswa" name="nim" required>
                                                        <option value="">Pilih Mahasiswa...</option>
                                                        @foreach ($mahasiswa as $mhs)
                                                            <option value="{{ $mhs->nim }}" {{ old('nim') == $mhs->nim ? 'selected' : '' }}>
                                                                {{ $mhs->nim }} - {{ $mhs->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nim')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <select name="akademik_id" class="akademik-id-select form-select form-select-sm" data-control="select2" required>
                                                        @foreach (\App\Models\TahunAkademik::orderBy('id_akademik', 'desc')->get() as $akademik)
                                                            <option value="{{ $akademik->id_akademik }}"
                                                                {{ (isset($surat) && $surat->akademik_id == $akademik->id_akademik) || (!isset($surat) && $latestAkademik?->id_akademik == $akademik->id_akademik) ? 'selected' : '' }}>
                                                                {{ $akademik->tahun_akademik }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">
                                                        Semester
                                                        <span id="semester-loading-umum" class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                    </label>
                                                    <input id="field-semester-umum" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi setelah memilih mahasiswa" value="" disabled />
                                                    <input type="hidden" name="semester" id="hidden-semester-umum" value="">
                                                    <small id="simpt-warning-umum" class="text-warning d-none">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Data semester tidak ditemukan di SIMPT.
                                                    </small>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Saat Ini</label>
                                                    <textarea name="alamat" class="form-control form-control-sm" rows="3" placeholder="Masukkan alamat lengkap..." required>{{ old('alamat') }}</textarea>
                                                    @error('alamat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat Keterangan
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea name="keperluan" class="form-control form-control-sm" rows="3" placeholder="Jelaskan keperluan pembuatan surat..." required>{{ old('keperluan') }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit" class="btn btn-primary w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i> Buat Pengajuan Umum</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                    <form id="form-pns" class="form-section" method="POST"
                                        action="{{ route('admin.surat-aktif.store') }}">
                                        @csrf
                                        <input type="hidden" name="kategori" value="PNS">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-info me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Formulir Kategori PNS</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                    <select class="form-select form-select-sm w-100" data-control="select2"
                                                        data-placeholder="Pilih Mahasiswa" name="nim" required>
                                                        <option value="">Pilih Mahasiswa...</option>
                                                        @foreach ($mahasiswa as $mhsw)
                                                            <option value="{{ $mhsw->nim }}" {{ old('nim') == $mhsw->nim ? 'selected' : '' }}>
                                                                {{ $mhsw->nim }} - {{ $mhsw->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nim')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <select name="akademik_id" class="akademik-id-select form-select form-select-sm" data-control="select2" required>
                                                        @foreach (\App\Models\TahunAkademik::orderBy('id_akademik', 'desc')->get() as $akademik)
                                                            <option value="{{ $akademik->id_akademik }}"
                                                                {{ (isset($surat) && $surat->akademik_id == $akademik->id_akademik) || (!isset($surat) && $latestAkademik?->id_akademik == $akademik->id_akademik) ? 'selected' : '' }}>
                                                                {{ $akademik->tahun_akademik }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">
                                                        Semester
                                                        <span id="semester-loading-pns" class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                    </label>
                                                    <input id="field-semester-pns" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi setelah memilih mahasiswa" value="" disabled />
                                                    <input type="hidden" name="semester" id="hidden-semester-pns" value="">
                                                    <small id="simpt-warning-pns" class="text-warning d-none">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Data semester tidak ditemukan di SIMPT.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-user-tie text-gray-400 me-2"></i> Data Orang Tua (PNS)
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIP Sesuai SK</label>
                                                    <input type="number" name="nip" class="form-control form-control-sm"
                                                        placeholder="Contoh: 198010202005011003" value="{{ old('nip') }}" required />
                                                    @error('nip')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Sesuai SK</label>
                                                    <input type="text" name="nama_ortu" class="form-control form-control-sm"
                                                        placeholder="Nama Lengkap Orang Tua" value="{{ old('nama_ortu') }}" required />
                                                    @error('nama_ortu')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir</label>
                                                    <select class="form-select form-select-sm" data-control="select2"
                                                        data-placeholder="Pilih Pendidikan Terakhir" name="pendidikan_terakhir" required>
                                                        <option value=""></option>
                                                        @foreach (['Tidak sekolah', 'PAUD', 'TK / sederajat', 'Putus SD', 'SD / sederajat', 'SMP / sederajat', 'SMA / sederajat', 'Paket A', 'Paket B', 'Paket C', 'D1', 'D2', 'D3', 'D4', 'S1', 'SP-1', 'S2', 'SP-2', 'S3', 'Non Formal', 'Informal', 'Pendidikan Profesi', 'Lainnya'] as $pddk)
                                                            <option value="{{ $pddk }}" {{ old('pendidikan_terakhir') == $pddk ? 'selected' : '' }}>{{ $pddk }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('pendidikan_terakhir')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" name="pangkat" class="form-control form-control-sm"
                                                        placeholder="Contoh: Pembina Tk. I" value="{{ old('pangkat') }}" required />
                                                    @error('pangkat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" name="golongan" class="form-control form-control-sm"
                                                        placeholder="Contoh: IV/b" value="{{ old('golongan') }}" required />
                                                    @error('golongan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas (TMT)</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tmt"
                                                            class="form-control form-control-sm ps-12 kt_datepicker_tmt"
                                                            placeholder="Pilih tanggal mulai tugas" value="{{ old('tmt') }}" autocomplete="off" required />
                                                    </div>
                                                    @error('tmt')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" name="unit_kerja" class="form-control form-control-sm"
                                                        placeholder="Unit kerja orang tua" value="{{ old('unit_kerja') }}" required />
                                                    @error('unit_kerja')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai SK</label>
                                                    <textarea name="alamat" class="form-control form-control-sm" rows="3" placeholder="Masukkan alamat lengkap orang tua..." required>{{ old('alamat') }}</textarea>
                                                    @error('alamat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat Keterangan
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea name="keperluan" class="form-control form-control-sm" rows="3" placeholder="Jelaskan keperluan pembuatan surat..." required>{{ old('keperluan') }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit" class="btn btn-info w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i> Buat Pengajuan PNS</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                    <form id="form-pppk" class="form-section" method="POST"
                                        action="{{ route('admin.surat-aktif.store') }}">
                                        @csrf
                                        <input type="hidden" name="kategori" value="PPPK">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-success me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Formulir Kategori PPPK</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                                    <select class="form-select form-select-sm w-100" data-control="select2"
                                                        data-placeholder="Pilih Mahasiswa" name="nim" required>
                                                        <option value="">Pilih Mahasiswa...</option>
                                                        @foreach ($mahasiswa as $mhsw)
                                                            <option value="{{ $mhsw->nim }}" {{ old('nim') == $mhsw->nim ? 'selected' : '' }}>
                                                                {{ $mhsw->nim }} - {{ $mhsw->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('nim')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <select name="akademik_id" class="akademik-id-select form-select form-select-sm" data-control="select2" required>
                                                        @foreach (\App\Models\TahunAkademik::orderBy('id_akademik', 'desc')->get() as $akademik)
                                                            <option value="{{ $akademik->id_akademik }}"
                                                                {{ (isset($surat) && $surat->akademik_id == $akademik->id_akademik) || (!isset($surat) && $latestAkademik?->id_akademik == $akademik->id_akademik) ? 'selected' : '' }}>
                                                                {{ $akademik->tahun_akademik }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fw-semibold fs-6 mb-2">
                                                        Semester
                                                        <span id="semester-loading-pppk" class="spinner-border spinner-border-sm ms-2 text-primary d-none"></span>
                                                    </label>
                                                    <input id="field-semester-pppk" type="text"
                                                        class="form-control form-control-sm bg-light"
                                                        placeholder="Otomatis terisi setelah memilih mahasiswa" value="" disabled />
                                                    <input type="hidden" name="semester" id="hidden-semester-pppk" value="">
                                                    <small id="simpt-warning-pppk" class="text-warning d-none">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Data semester tidak ditemukan di SIMPT.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-user-clock text-gray-400 me-2"></i> Data Orang Tua (PPPK)
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIP / NI PPPK</label>
                                                    <input type="number" name="nip" class="form-control form-control-sm"
                                                        placeholder="Contoh: 198010202005011003" value="{{ old('nip') }}" required />
                                                    @error('nip')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Sesuai SK</label>
                                                    <input type="text" name="nama_ortu" class="form-control form-control-sm"
                                                        placeholder="Nama Lengkap Orang Tua" value="{{ old('nama_ortu') }}" required />
                                                    @error('nama_ortu')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pendidikan Terakhir</label>
                                                    <select class="form-select form-select-sm" data-control="select2"
                                                        data-placeholder="Pilih Pendidikan Terakhir" name="pendidikan_terakhir" required>
                                                        <option value=""></option>
                                                        @foreach (['Tidak sekolah', 'PAUD', 'TK / sederajat', 'Putus SD', 'SD / sederajat', 'SMP / sederajat', 'SMA / sederajat', 'Paket A', 'Paket B', 'Paket C', 'D1', 'D2', 'D3', 'D4', 'S1', 'SP-1', 'S2', 'SP-2', 'S3', 'Non Formal', 'Informal', 'Pendidikan Profesi', 'Lainnya'] as $pddk)
                                                            <option value="{{ $pddk }}" {{ old('pendidikan_terakhir') == $pddk ? 'selected' : '' }}>{{ $pddk }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('pendidikan_terakhir')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" name="pangkat" class="form-control form-control-sm"
                                                        placeholder="Contoh: Ahli Muda" value="{{ old('pangkat') }}" required />
                                                    @error('pangkat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" name="golongan" class="form-control form-control-sm"
                                                        placeholder="Contoh: IX" value="{{ old('golongan') }}" required />
                                                    @error('golongan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas (TMT)</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tmt"
                                                            class="form-control form-control-sm ps-12 kt_datepicker_tmt"
                                                            placeholder="Pilih tanggal mulai tugas" value="{{ old('tmt') }}" autocomplete="off" required />
                                                    </div>
                                                    @error('tmt')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" name="unit_kerja" class="form-control form-control-sm"
                                                        placeholder="Unit kerja orang tua" value="{{ old('unit_kerja') }}" required />
                                                    @error('unit_kerja')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai SK</label>
                                                    <textarea name="alamat" class="form-control form-control-sm" rows="3" placeholder="Masukkan alamat lengkap orang tua..." required>{{ old('alamat') }}</textarea>
                                                    @error('alamat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat Keterangan
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea name="keperluan" class="form-control form-control-sm" rows="3" placeholder="Jelaskan keperluan pembuatan surat..." required>{{ old('keperluan') }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit" class="btn btn-success w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i> Buat Pengajuan PPPK</span>
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
        document.addEventListener("DOMContentLoaded", function() {
            const categoryButtons = document.querySelectorAll('#category-selector .category-card');
            const formElements = {
                umum: document.getElementById('form-umum'),
                pns: document.getElementById('form-pns'),
                pppk: document.getElementById('form-pppk')
            };

            categoryButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    categoryButtons.forEach(btn => {
                        const activeClass = btn.getAttribute('data-active-class');
                        btn.classList.remove(activeClass);
                    });

                    const activeClass = this.getAttribute('data-active-class');
                    this.classList.add(activeClass);

                    Object.values(formElements).forEach(form => {
                        if (form) form.classList.remove('active');
                    });

                    const category = this.getAttribute('data-category');
                    if (formElements[category]) {
                        formElements[category].classList.add('active');
                    }
                });
            });

            const simptUrl = "{{ route('admin.surat-aktif.simpt', '__NIM__') }}";

            function fetchSimpt(nim, suffix) {
                const field = document.getElementById('field-semester-' + suffix);
                const hidden = document.getElementById('hidden-semester-' + suffix);
                const spinner = document.getElementById('semester-loading-' + suffix);
                const warning = document.getElementById('simpt-warning-' + suffix);

                if (!field || !hidden || !spinner || !warning) return;

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
                                const form = document.getElementById('form-' + suffix);
                                if (form) {
                                    const select = $(form.querySelector('select[name="nim"]'));
                                    if (select.length) {
                                        select.val(null).trigger('change.select2');
                                    }
                                }
                                field.value = '';
                                hidden.value = '';
                                warning.classList.add('d-none');
                            });
                            return;
                        }

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
                        .off('.simptAktif')
                        .on('change.simptAktif select2:select.simptAktif', function() {
                            fetchSimpt(this.value, suffix);
                        });
                } else {
                    select.addEventListener('change', function() {
                        fetchSimpt(this.value, suffix);
                    });
                }
            });

            function attachSpinnerToForm(form) {
                const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
                let isConfirmed = false;

                form.addEventListener('submit', function(event) {
                    if (isConfirmed) return;
                    event.preventDefault();

                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    let confirmBtnClass = 'btn btn-primary';
                    if (submitButton) {
                        if (submitButton.classList.contains('btn-info')) {
                            confirmBtnClass = 'btn btn-info';
                        } else if (submitButton.classList.contains('btn-success')) {
                            confirmBtnClass = 'btn btn-success';
                        }
                    }

                    Swal.fire({
                        title: "Konfirmasi Pengajuan",
                        text: "Apakah Anda yakin ingin membuat pengajuan surat keterangan aktif ini?",
                        icon: "question",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Ya, Buat Pengajuan!",
                        cancelButtonText: "Batal",
                        customClass: {
                            confirmButton: confirmBtnClass,
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
            }

            ['form-umum', 'form-pns', 'form-pppk'].forEach(id => {
                const f = document.getElementById(id);
                if (f) attachSpinnerToForm(f);
            });

            if (typeof flatpickr !== "undefined") {
                document.querySelectorAll(".kt_datepicker_tmt").forEach(function(el) {
                    flatpickr(el, {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d/m/Y",
                        altInputClass: "form-control form-control-sm ps-12",
                        allowInput: true,
                        disableMobile: "true"
                    });
                });
            }
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
