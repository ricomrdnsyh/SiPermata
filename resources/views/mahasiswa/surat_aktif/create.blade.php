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
                                    <div class="text-muted fw-semibold fs-5">Silakan pilih kategori Anda untuk melanjutkan
                                        pengisian formulir.</div>
                                </div>

                                <div class="row g-6 mb-12" id="category-selector">
                                    <div class="col-md-4">
                                        <label
                                            class="card category-card border border-dashed border-2 border-gray-300 active h-100"
                                            data-category="umum" data-active-class="active">
                                            <div
                                                class="card-body p-6 text-center d-flex flex-column justify-content-center">
                                                <i class="fas fa-user-graduate fs-3x text-primary mb-4 d-block"></i>
                                                <span class="fs-4 fw-bold text-gray-800 d-block mb-1">Kategori Umum</span>
                                                <span class="fs-7 text-muted fw-semibold">Untuk mahasiswa reguler secara
                                                    umum</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="card category-card border border-dashed border-2 border-gray-300 h-100"
                                            data-category="pns" data-active-class="active-pns">
                                            <div
                                                class="card-body p-6 text-center d-flex flex-column justify-content-center">
                                                <i class="fas fa-user-tie fs-3x text-info mb-4 d-block"></i>
                                                <span class="fs-4 fw-bold text-gray-800 d-block mb-1">Kategori PNS</span>
                                                <span class="fs-7 text-muted fw-semibold">Khusus untuk mahasiswa anak
                                                    PNS</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label
                                            class="card category-card border border-dashed border-2 border-gray-300 h-100"
                                            data-category="pppk" data-active-class="active-pppk">
                                            <div
                                                class="card-body p-6 text-center d-flex flex-column justify-content-center">
                                                <i class="fas fa-user-clock fs-3x text-success mb-4 d-block"></i>
                                                <span class="fs-4 fw-bold text-gray-800 d-block mb-1">Kategori PPPK</span>
                                                <span class="fs-7 text-muted fw-semibold">Khusus untuk mahasiswa anak
                                                    PPPK</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="separator border-2 border-dashed mb-10"></div>

                                <div id="form-container">

                                    <form id="form-umum" class="form-section active" method="POST"
                                        action="{{ route('mahasiswa.surat-aktif.store') }}">
                                        @csrf
                                        <input type="hidden" name="kategori" value="UMUM">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-primary me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Formulir Kategori Umum</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik
                                                Mahasiswa</h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                    <input type="text" name="nim" class="form-control"
                                                        value="{{ auth()->user()->reference_id }}" disabled required />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $latestAkademik?->tahun_akademik }}" disabled />
                                                    <input type="hidden" name="akademik_id"
                                                        value="{{ $latestAkademik?->id_akademik }}">
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataSimpt?->semester ?? 'Tidak tersedia' }}" disabled />
                                                    <input type="hidden" name="semester"
                                                        value="{{ $dataSimpt?->semester }}">
                                                    @error('semester')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Saat Ini</label>
                                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap..." required></textarea>
                                                    @error('alamat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat
                                                Keterangan</h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea name="keperluan" class="form-control" rows="3" placeholder="Jelaskan keperluan pembuatan surat..."
                                                        required></textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-primary w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i> Buat
                                                    Pengajuan Umum</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>

                                    <form id="form-pns" class="form-section" method="POST"
                                        action="{{ route('mahasiswa.surat-aktif.store') }}">
                                        @csrf
                                        <input type="hidden" name="kategori" value="PNS">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-info me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Formulir Kategori PNS</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik
                                                Mahasiswa</h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                    <input type="text" name="nim" class="form-control"
                                                        value="{{ auth()->user()->reference_id }}" disabled required />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $latestAkademik?->tahun_akademik }}" disabled />
                                                    <input type="hidden" name="akademik_id"
                                                        value="{{ $latestAkademik?->id_akademik }}">
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataSimpt?->semester ?? 'Tidak tersedia' }}"
                                                        disabled />
                                                    <input type="hidden" name="semester"
                                                        value="{{ $dataSimpt?->semester }}">
                                                    @error('semester')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-user-tie text-gray-400 me-2"></i> Data Orang Tua (PNS)
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIP Sesuai SK</label>
                                                    <input type="number" name="nip" class="form-control"
                                                        placeholder="Contoh: 198010202005011003" required />
                                                    @error('nip')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Sesuai
                                                        SK</label>
                                                    <input type="text" name="nama_ortu" class="form-control"
                                                        placeholder="Nama Lengkap Orang Tua" required />
                                                    @error('nama_ortu')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pendidikan
                                                        Terakhir</label>
                                                    <select class="form-select" data-control="select2"
                                                        data-placeholder="Pilih Pendidikan Terakhir"
                                                        name="pendidikan_terakhir" required>
                                                        <option value=""></option>
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
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" name="pangkat" class="form-control"
                                                        placeholder="Contoh: Pembina Tk. I" required />
                                                    @error('pangkat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" name="golongan" class="form-control"
                                                        placeholder="Contoh: IV/b" required />
                                                    @error('golongan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas
                                                        (TMT)</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tmt"
                                                        class="form-control ps-12 kt_datepicker_tmt"
                                                        placeholder="Pilih tanggal mulai tugas"
                                                        value="{{ old('tmt') }}" autocomplete="off" required />
                                                    </div>
                                                    @error('tmt')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" name="unit_kerja" class="form-control"
                                                        placeholder="Unit kerja orang tua" required />
                                                    @error('unit_kerja')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai
                                                        SK</label>
                                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap orang tua..."
                                                        required></textarea>
                                                    @error('alamat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat
                                                Keterangan</h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea name="keperluan" class="form-control" rows="3" placeholder="Jelaskan keperluan pembuatan surat..."
                                                        required></textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-info w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i> Buat
                                                    Pengajuan PNS</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>

                                    <form id="form-pppk" class="form-section" method="POST"
                                        action="{{ route('mahasiswa.surat-aktif.store') }}">
                                        @csrf
                                        <input type="hidden" name="kategori" value="PPPK">
                                        <div class="d-flex align-items-center mb-8">
                                            <span class="bullet bullet-vertical h-30px bg-success me-4"></span>
                                            <h3 class="fs-3 fw-bold text-gray-900 m-0">Formulir Kategori PPPK</h3>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik
                                                Mahasiswa</h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                    <input type="text" name="nim" class="form-control"
                                                        value="{{ auth()->user()->reference_id }}" disabled required />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $latestAkademik?->tahun_akademik }}" disabled />
                                                    <input type="hidden" name="akademik_id"
                                                        value="{{ $latestAkademik?->id_akademik }}">
                                                    @error('akademik_id')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $dataSimpt?->semester ?? 'Tidak tersedia' }}"
                                                        disabled />
                                                    <input type="hidden" name="semester"
                                                        value="{{ $dataSimpt?->semester }}">
                                                    @error('semester')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-user-clock text-gray-400 me-2"></i> Data Orang Tua (PPPK)
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">NIP / NI PPPK</label>
                                                    <input type="number" name="nip" class="form-control"
                                                        placeholder="Contoh: 198010202005011003" required />
                                                    @error('nip')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Sesuai
                                                        SK</label>
                                                    <input type="text" name="nama_ortu" class="form-control"
                                                        placeholder="Nama Lengkap Orang Tua" required />
                                                    @error('nama_ortu')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pendidikan
                                                        Terakhir</label>
                                                    <select class="form-select" data-control="select2"
                                                        data-placeholder="Pilih Pendidikan Terakhir"
                                                        name="pendidikan_terakhir" required>
                                                        <option value=""></option>
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
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" name="pangkat" class="form-control"
                                                        placeholder="Contoh: Ahli Muda" required />
                                                    @error('pangkat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" name="golongan" class="form-control"
                                                        placeholder="Contoh: IX" required />
                                                    @error('golongan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas
                                                        (TMT)</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tmt"
                                                        class="form-control ps-12 kt_datepicker_tmt"
                                                        placeholder="Pilih tanggal mulai tugas"
                                                        value="{{ old('tmt') }}" autocomplete="off" required />
                                                    </div>
                                                    @error('tmt')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" name="unit_kerja" class="form-control"
                                                        placeholder="Unit kerja orang tua" required />
                                                    @error('unit_kerja')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai
                                                        SK</label>
                                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap orang tua..."
                                                        required></textarea>
                                                    @error('alamat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600"><i
                                                    class="fas fa-map-marker-alt text-gray-400 me-2"></i> Detail Surat
                                                Keterangan</h5>
                                            <div class="row g-5">
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                                    <textarea name="keperluan" class="form-control" rows="3" placeholder="Jelaskan keperluan pembuatan surat..."
                                                        required></textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-success w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i> Buat
                                                    Pengajuan PPPK</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
