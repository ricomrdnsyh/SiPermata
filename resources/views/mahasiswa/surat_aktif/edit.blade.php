@extends('layout.main')
@section('title', 'Edit Surat Keterangan Aktif')
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

        .category-card-removed {
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
                    <div class="card shadow-sm border border-dashed border-dark rounded">
                        <div class="card-body p-lg-8">
                            <div class="d-flex flex-column">
                                <div class="mb-6 text-center">
                                    <h1 class="fs-2hx fw-bolder mb-5 text-dark">
                                        <i class="fas fa-file-signature fs-2hx text-primary me-2 align-middle"></i>
                                        Edit Surat Keterangan Aktif
                                    </h1>
                                    <div class="text-gray-400 fw-bold fs-5">Silakan edit pengajuan Anda untuk melanjutkan
                                        pengajuan!</div>
                                </div>
                                <div id="form-container" class="mt-2">
                                    <form id="form-umum" class="form-section active" method="POST"
                                        action="{{ route('mahasiswa.surat-aktif.update', $surat->id_surat_aktif) }}">
                                        @csrf
                                        @method('PUT')
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
                                                        value="{{ $dataSimpt?->semester ?? ($surat->semester ?? 1) }}"
                                                        disabled />
                                                    <input type="hidden" name="semester"
                                                        value="{{ $dataSimpt?->semester ?? ($surat->semester ?? 1) }}">
                                                    @error('semester')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Saat Ini</label>
                                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap..." required>{{ old('alamat', $surat->alamat) }}</textarea>
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
                                                        required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-primary w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i>
                                                    Ajukan Ulang</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>

                                    <form id="form-pns" class="form-section" method="POST"
                                        action="{{ route('mahasiswa.surat-aktif.update', $surat->id_surat_aktif) }}">
                                        @csrf
                                        @method('PUT')
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
                                                        value="{{ $dataSimpt?->semester ?? ($surat->semester ?? 1) }}"
                                                        disabled />
                                                    <input type="hidden" name="semester"
                                                        value="{{ $dataSimpt?->semester ?? ($surat->semester ?? 1) }}">
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
                                                        placeholder="Contoh: 198010202005011003"
                                                        value="{{ old('nip', $surat->nip) }}" required />
                                                    @error('nip')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Sesuai
                                                        SK</label>
                                                    <input type="text" name="nama_ortu" class="form-control"
                                                        placeholder="Nama Lengkap Orang Tua"
                                                        value="{{ old('nama_ortu', $surat->nama_ortu) }}" required />
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
                                                        <option value="Tidak sekolah"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Tidak sekolah' ? 'selected' : '' }}>
                                                            Tidak sekolah</option>
                                                        <option value="PAUD"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'PAUD' ? 'selected' : '' }}>
                                                            PAUD</option>
                                                        <option value="TK / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'TK / sederajat' ? 'selected' : '' }}>
                                                            TK / sederajat</option>
                                                        <option value="Putus SD"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Putus SD' ? 'selected' : '' }}>
                                                            Putus SD</option>
                                                        <option value="SD / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SD / sederajat' ? 'selected' : '' }}>
                                                            SD / sederajat</option>
                                                        <option value="SMP / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SMP / sederajat' ? 'selected' : '' }}>
                                                            SMP / sederajat</option>
                                                        <option value="SMA / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SMA / sederajat' ? 'selected' : '' }}>
                                                            SMA / sederajat</option>
                                                        <option value="Paket A"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Paket A' ? 'selected' : '' }}>
                                                            Paket A</option>
                                                        <option value="Paket B"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Paket B' ? 'selected' : '' }}>
                                                            Paket B</option>
                                                        <option value="Paket C"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Paket C' ? 'selected' : '' }}>
                                                            Paket C</option>
                                                        <option value="D1"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D1' ? 'selected' : '' }}>
                                                            D1</option>
                                                        <option value="D2"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D2' ? 'selected' : '' }}>
                                                            D2</option>
                                                        <option value="D3"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>
                                                            D3</option>
                                                        <option value="D4"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D4' ? 'selected' : '' }}>
                                                            D4</option>
                                                        <option value="S1"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>
                                                            S1</option>
                                                        <option value="SP-1"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SP-1' ? 'selected' : '' }}>
                                                            SP-1</option>
                                                        <option value="S2"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>
                                                            S2</option>
                                                        <option value="SP-2"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SP-2' ? 'selected' : '' }}>
                                                            SP-2</option>
                                                        <option value="S3"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>
                                                            S3</option>
                                                        <option value="Non Formal"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Non Formal' ? 'selected' : '' }}>
                                                            Non Formal</option>
                                                        <option value="Informal"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Informal' ? 'selected' : '' }}>
                                                            Informal</option>
                                                        <option value="Pendidikan Profesi"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Pendidikan Profesi' ? 'selected' : '' }}>
                                                            Pendidikan Profesi</option>
                                                        <option value="Lainnya"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Lainnya' ? 'selected' : '' }}>
                                                            Lainnya</option>
                                                    </select>
                                                    @error('pendidikan_terakhir')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" name="pangkat" class="form-control"
                                                        placeholder="Contoh: Pembina Tk. I"
                                                        value="{{ old('pangkat', $surat->pangkat) }}" required />
                                                    @error('pangkat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" name="golongan" class="form-control"
                                                        placeholder="Contoh: IV/b"
                                                        value="{{ old('golongan', $surat->golongan) }}" required />
                                                    @error('golongan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas
                                                        (TMT)</label>
                                                    <div class="position-relative">
                                                        <i
                                                            class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tmt"
                                                            class="form-control ps-12 kt_datepicker_tmt"
                                                            placeholder="Pilih tanggal mulai tugas"
                                                            value="{{ old('tmt', $surat->tmt ? date('Y-m-d', strtotime($surat->tmt)) : '') }}"
                                                            autocomplete="off" required />
                                                    </div>
                                                    @error('tmt')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" name="unit_kerja" class="form-control"
                                                        placeholder="Unit kerja orang tua"
                                                        value="{{ old('unit_kerja', $surat->unit_kerja) }}" required />
                                                    @error('unit_kerja')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai
                                                        SK</label>
                                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap orang tua..."
                                                        required>{{ old('alamat', $surat->alamat) }}</textarea>
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
                                                        required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-info w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i>
                                                    Ajukan
                                                    Ulang</span>
                                                <span class="indicator-progress" style="display: none;">
                                                    Tunggu sebentar... <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>

                                    <form id="form-pppk" class="form-section" method="POST"
                                        action="{{ route('mahasiswa.surat-aktif.update', $surat->id_surat_aktif) }}">
                                        @csrf
                                        @method('PUT')
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
                                                        value="{{ $dataSimpt?->semester ?? ($surat->semester ?? 1) }}"
                                                        disabled />
                                                    <input type="hidden" name="semester"
                                                        value="{{ $dataSimpt?->semester ?? ($surat->semester ?? 1) }}">
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
                                                        placeholder="Contoh: 198010202005011003"
                                                        value="{{ old('nip', $surat->nip) }}" required />
                                                    @error('nip')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Nama Lengkap Sesuai
                                                        SK</label>
                                                    <input type="text" name="nama_ortu" class="form-control"
                                                        placeholder="Nama Lengkap Orang Tua"
                                                        value="{{ old('nama_ortu', $surat->nama_ortu) }}" required />
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
                                                        <option value="Tidak sekolah"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Tidak sekolah' ? 'selected' : '' }}>
                                                            Tidak sekolah</option>
                                                        <option value="PAUD"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'PAUD' ? 'selected' : '' }}>
                                                            PAUD</option>
                                                        <option value="TK / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'TK / sederajat' ? 'selected' : '' }}>
                                                            TK / sederajat</option>
                                                        <option value="Putus SD"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Putus SD' ? 'selected' : '' }}>
                                                            Putus SD</option>
                                                        <option value="SD / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SD / sederajat' ? 'selected' : '' }}>
                                                            SD / sederajat</option>
                                                        <option value="SMP / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SMP / sederajat' ? 'selected' : '' }}>
                                                            SMP / sederajat</option>
                                                        <option value="SMA / sederajat"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SMA / sederajat' ? 'selected' : '' }}>
                                                            SMA / sederajat</option>
                                                        <option value="Paket A"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Paket A' ? 'selected' : '' }}>
                                                            Paket A</option>
                                                        <option value="Paket B"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Paket B' ? 'selected' : '' }}>
                                                            Paket B</option>
                                                        <option value="Paket C"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Paket C' ? 'selected' : '' }}>
                                                            Paket C</option>
                                                        <option value="D1"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D1' ? 'selected' : '' }}>
                                                            D1</option>
                                                        <option value="D2"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D2' ? 'selected' : '' }}>
                                                            D2</option>
                                                        <option value="D3"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>
                                                            D3</option>
                                                        <option value="D4"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'D4' ? 'selected' : '' }}>
                                                            D4</option>
                                                        <option value="S1"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>
                                                            S1</option>
                                                        <option value="SP-1"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SP-1' ? 'selected' : '' }}>
                                                            SP-1</option>
                                                        <option value="S2"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>
                                                            S2</option>
                                                        <option value="SP-2"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'SP-2' ? 'selected' : '' }}>
                                                            SP-2</option>
                                                        <option value="S3"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>
                                                            S3</option>
                                                        <option value="Non Formal"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Non Formal' ? 'selected' : '' }}>
                                                            Non Formal</option>
                                                        <option value="Informal"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Informal' ? 'selected' : '' }}>
                                                            Informal</option>
                                                        <option value="Pendidikan Profesi"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Pendidikan Profesi' ? 'selected' : '' }}>
                                                            Pendidikan Profesi</option>
                                                        <option value="Lainnya"
                                                            {{ old('pendidikan_terakhir', $surat->pendidikan_terakhir) == 'Lainnya' ? 'selected' : '' }}>
                                                            Lainnya</option>
                                                    </select>
                                                    @error('pendidikan_terakhir')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Pangkat</label>
                                                    <input type="text" name="pangkat" class="form-control"
                                                        placeholder="Contoh: Ahli Muda"
                                                        value="{{ old('pangkat', $surat->pangkat) }}" required />
                                                    @error('pangkat')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                    <input type="text" name="golongan" class="form-control"
                                                        placeholder="Contoh: IX"
                                                        value="{{ old('golongan', $surat->golongan) }}" required />
                                                    @error('golongan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas
                                                        (TMT)</label>
                                                    <div class="position-relative">
                                                        <i
                                                            class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" name="tmt"
                                                            class="form-control ps-12 kt_datepicker_tmt"
                                                            placeholder="Pilih tanggal mulai tugas"
                                                            value="{{ old('tmt', $surat->tmt ? date('Y-m-d', strtotime($surat->tmt)) : '') }}"
                                                            autocomplete="off" required />
                                                    </div>
                                                    @error('tmt')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                    <input type="text" name="unit_kerja" class="form-control"
                                                        placeholder="Unit kerja orang tua"
                                                        value="{{ old('unit_kerja', $surat->unit_kerja) }}" required />
                                                    @error('unit_kerja')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua Sesuai
                                                        SK</label>
                                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap orang tua..."
                                                        required>{{ old('alamat', $surat->alamat) }}</textarea>
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
                                                        required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                                    @error('keperluan')
                                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-8">
                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-success w-100 w-md-auto px-10">
                                                <span class="indicator-label"><i class="fas fa-paper-plane me-2"></i>
                                                    Ajukan Ulang</span>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".kt_datepicker_tmt").forEach(function(el) {
                if (typeof flatpickr === "undefined") return;

                flatpickr(el, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d/m/Y",
                    allowInput: true,
                    defaultDate: el.value ? el.value : null
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
