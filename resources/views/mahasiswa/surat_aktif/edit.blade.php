@extends('layout.main')
@section('title', 'Edit Surat Keterangan Aktif')
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
                                <h1 class="fs-2hx fw-bolder mb-5">Edit Surat Keterangan Aktif</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan edit pengajuan Anda untuk melanjutkan
                                    pengajuan!</div>
                            </div>
                            <div id="form-container" class="mt-2">
                                <form id="form-umum" class="form-section active" method="POST"
                                    action="{{ route('mahasiswa.surat-aktif.update', $surat->id_surat_aktif) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">
                                    <h3 class="mb-5 text-center">Pengajuan Surat Keterangan Aktif Umum</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                <input type="text" name="nim"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ auth()->user()->reference_id }}" disabled required />
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
                                        <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                        <input type="text"
                                            class="form-control form-control-sm mb-3 mb-lg-0"
                                            value="{{ $dataSimpt?->semester ?? $surat->semester ?? 'Tidak tersedia' }}" disabled />
                                        <input type="hidden" name="semester" value="{{ $dataSimpt?->semester ?? $surat->semester }}">
                                        @error('semester')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                        <textarea name="alamat" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required>{{ old('alamat', $surat->alamat) }}</textarea>
                                        @error('alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-3">
                                        <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                        <textarea name="keperluan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                        @error('keperluan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-sm btn-primary w-250px">
                                            <span class="indicator-label">
                                                Update Pengajuan
                                            </span>
                                            <span class="indicator-progress">
                                                Tunggu sebentar...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                                <form id="form-pns" class="form-section" method="POST"
                                    action="{{ route('mahasiswa.surat-aktif.update', $surat->id_surat_aktif) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">
                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PNS</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                <input type="text" name="nim" class="form-control form-control-sm"
                                                    value="{{ auth()->user()->reference_id }}" disabled required />
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
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    value="{{ $dataSimpt?->semester ?? $surat->semester ?? 'Tidak tersedia' }}" disabled />
                                                <input type="hidden" name="semester" value="{{ $dataSimpt?->semester ?? $surat->semester }}">
                                                @error('semester')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua Sesuai
                                                    SK</label>
                                                <input type="number" name="nip" class="form-control form-control-sm"
                                                    value="{{ $surat->nip }}" required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua Sesuai
                                                    SK</label>
                                                <input type="text" name="nama_ortu"
                                                    class="form-control form-control-sm" value="{{ $surat->nama_ortu }}"
                                                    required />
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
                                                    <option value="Tidak sekolah"
                                                        {{ $surat->pendidikan_terakhir == 'Tidak sekolah' ? 'selected' : '' }}>
                                                        Tidak sekolah
                                                    </option>
                                                    <option value="PAUD"
                                                        {{ $surat->pendidikan_terakhir == 'PAUD' ? 'selected' : '' }}>
                                                        PAUD</option>
                                                    <option value="TK / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'TK / sederajat' ? 'selected' : '' }}>
                                                        TK / sederajat
                                                    </option>
                                                    <option value="Putus SD"
                                                        {{ $surat->pendidikan_terakhir == 'Putus SD' ? 'selected' : '' }}>
                                                        Putus SD</option>
                                                    <option value="SD / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'SD / sederajat' ? 'selected' : '' }}>
                                                        SD / sederajat
                                                    </option>
                                                    <option value="SMP / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'SMP / sederajat' ? 'selected' : '' }}>
                                                        SMP / sederajat
                                                    </option>
                                                    <option value="SMA / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'SMA / sederajat' ? 'selected' : '' }}>
                                                        SMA / sederajat
                                                    </option>
                                                    <option value="Paket A"
                                                        {{ $surat->pendidikan_terakhir == 'Paket A' ? 'selected' : '' }}>
                                                        Paket A</option>
                                                    <option value="Paket B"
                                                        {{ $surat->pendidikan_terakhir == 'Paket B' ? 'selected' : '' }}>
                                                        Paket B</option>
                                                    <option value="Paket C"
                                                        {{ $surat->pendidikan_terakhir == 'Paket C' ? 'selected' : '' }}>
                                                        Paket C</option>
                                                    <option value="D1"
                                                        {{ $surat->pendidikan_terakhir == 'D1' ? 'selected' : '' }}>D1
                                                    </option>
                                                    <option value="D2"
                                                        {{ $surat->pendidikan_terakhir == 'D2' ? 'selected' : '' }}>D2
                                                    </option>
                                                    <option value="D3"
                                                        {{ $surat->pendidikan_terakhir == 'D3' ? 'selected' : '' }}>D3
                                                    </option>
                                                    <option value="D4"
                                                        {{ $surat->pendidikan_terakhir == 'D4' ? 'selected' : '' }}>D4
                                                    </option>
                                                    <option value="S1"
                                                        {{ $surat->pendidikan_terakhir == 'S1' ? 'selected' : '' }}>S1
                                                    </option>
                                                    <option value="SP-1"
                                                        {{ $surat->pendidikan_terakhir == 'SP-1' ? 'selected' : '' }}>
                                                        SP-1</option>
                                                    <option value="S2"
                                                        {{ $surat->pendidikan_terakhir == 'S2' ? 'selected' : '' }}>S2
                                                    </option>
                                                    <option value="SP-2"
                                                        {{ $surat->pendidikan_terakhir == 'SP-2' ? 'selected' : '' }}>
                                                        SP-2</option>
                                                    <option value="S3"
                                                        {{ $surat->pendidikan_terakhir == 'S3' ? 'selected' : '' }}>S3
                                                    </option>
                                                    <option value="Non Formal"
                                                        {{ $surat->pendidikan_terakhir == 'Non Formal' ? 'selected' : '' }}>
                                                        Non Formal
                                                    </option>
                                                    <option value="Informal"
                                                        {{ $surat->pendidikan_terakhir == 'Informal' ? 'selected' : '' }}>
                                                        Informal</option>
                                                    <option value="Pendidikan Profesi"
                                                        {{ $surat->pendidikan_terakhir == 'Pendidikan Profesi' ? 'selected' : '' }}>
                                                        Pendidikan
                                                        Profesi
                                                    </option>
                                                    <option value="Lainnya"
                                                        {{ $surat->pendidikan_terakhir == 'Lainnya' ? 'selected' : '' }}>
                                                        Lainnya</option>
                                                </select>
                                                @error('pendidikan_terakhir')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control form-control-sm"
                                                    value="{{ $surat->pangkat }}" required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan"
                                                    class="form-control form-control-sm" value="{{ $surat->golongan }}"
                                                    required />
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
                                                        placeholder="Pilih tanggal"
                                                        value="{{ $surat->tmt ? $surat->tmt->format('Y-m-d') : '' }}"
                                                        autocomplete="off" required />
                                                </div>
                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja"
                                                    class="form-control form-control-sm" value="{{ $surat->unit_kerja }}"
                                                    required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="fv-row mb-3">
                                            <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua</label>
                                            <textarea name="alamat" class="form-control form-control-sm" rows="3" required>{{ old('alamat', $surat->alamat) }}</textarea>
                                            @error('alamat')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                            <textarea name="keperluan" class="form-control form-control-sm" rows="3" required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                            @error('keperluan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-sm btn-primary w-250px">
                                            <span class="indicator-label">Update Pengajuan</span>
                                            <span class="indicator-progress" style="display: none;">
                                                Tunggu sebentar...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                                <form id="form-pppk" class="form-section" method="POST"
                                    action="{{ route('mahasiswa.surat-aktif.update', $surat->id_surat_aktif) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">
                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PPPK</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIM</label>
                                                <input type="text" name="nim" class="form-control form-control-sm"
                                                    value="{{ auth()->user()->reference_id }}" disabled required />
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
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    value="{{ $dataSimpt?->semester ?? $surat->semester ?? 'Tidak tersedia' }}" disabled />
                                                <input type="hidden" name="semester" value="{{ $dataSimpt?->semester ?? $surat->semester }}">
                                                @error('semester')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua Sesuai
                                                    SK</label>
                                                <input type="number" name="nip" class="form-control form-control-sm"
                                                    value="{{ $surat->nip }}" required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua Sesuai
                                                    SK</label>
                                                <input type="text" name="nama_ortu"
                                                    class="form-control form-control-sm" value="{{ $surat->nama_ortu }}"
                                                    required />
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
                                                    <option value="Tidak sekolah"
                                                        {{ $surat->pendidikan_terakhir == 'Tidak sekolah' ? 'selected' : '' }}>
                                                        Tidak sekolah
                                                    </option>
                                                    <option value="PAUD"
                                                        {{ $surat->pendidikan_terakhir == 'PAUD' ? 'selected' : '' }}>
                                                        PAUD</option>
                                                    <option value="TK / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'TK / sederajat' ? 'selected' : '' }}>
                                                        TK / sederajat
                                                    </option>
                                                    <option value="Putus SD"
                                                        {{ $surat->pendidikan_terakhir == 'Putus SD' ? 'selected' : '' }}>
                                                        Putus SD</option>
                                                    <option value="SD / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'SD / sederajat' ? 'selected' : '' }}>
                                                        SD / sederajat
                                                    </option>
                                                    <option value="SMP / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'SMP / sederajat' ? 'selected' : '' }}>
                                                        SMP / sederajat
                                                    </option>
                                                    <option value="SMA / sederajat"
                                                        {{ $surat->pendidikan_terakhir == 'SMA / sederajat' ? 'selected' : '' }}>
                                                        SMA / sederajat
                                                    </option>
                                                    <option value="Paket A"
                                                        {{ $surat->pendidikan_terakhir == 'Paket A' ? 'selected' : '' }}>
                                                        Paket A</option>
                                                    <option value="Paket B"
                                                        {{ $surat->pendidikan_terakhir == 'Paket B' ? 'selected' : '' }}>
                                                        Paket B</option>
                                                    <option value="Paket C"
                                                        {{ $surat->pendidikan_terakhir == 'Paket C' ? 'selected' : '' }}>
                                                        Paket C</option>
                                                    <option value="D1"
                                                        {{ $surat->pendidikan_terakhir == 'D1' ? 'selected' : '' }}>D1
                                                    </option>
                                                    <option value="D2"
                                                        {{ $surat->pendidikan_terakhir == 'D2' ? 'selected' : '' }}>D2
                                                    </option>
                                                    <option value="D3"
                                                        {{ $surat->pendidikan_terakhir == 'D3' ? 'selected' : '' }}>D3
                                                    </option>
                                                    <option value="D4"
                                                        {{ $surat->pendidikan_terakhir == 'D4' ? 'selected' : '' }}>D4
                                                    </option>
                                                    <option value="S1"
                                                        {{ $surat->pendidikan_terakhir == 'S1' ? 'selected' : '' }}>S1
                                                    </option>
                                                    <option value="SP-1"
                                                        {{ $surat->pendidikan_terakhir == 'SP-1' ? 'selected' : '' }}>
                                                        SP-1</option>
                                                    <option value="S2"
                                                        {{ $surat->pendidikan_terakhir == 'S2' ? 'selected' : '' }}>S2
                                                    </option>
                                                    <option value="SP-2"
                                                        {{ $surat->pendidikan_terakhir == 'SP-2' ? 'selected' : '' }}>
                                                        SP-2</option>
                                                    <option value="S3"
                                                        {{ $surat->pendidikan_terakhir == 'S3' ? 'selected' : '' }}>S3
                                                    </option>
                                                    <option value="Non Formal"
                                                        {{ $surat->pendidikan_terakhir == 'Non Formal' ? 'selected' : '' }}>
                                                        Non Formal
                                                    </option>
                                                    <option value="Informal"
                                                        {{ $surat->pendidikan_terakhir == 'Informal' ? 'selected' : '' }}>
                                                        Informal</option>
                                                    <option value="Pendidikan Profesi"
                                                        {{ $surat->pendidikan_terakhir == 'Pendidikan Profesi' ? 'selected' : '' }}>
                                                        Pendidikan
                                                        Profesi
                                                    </option>
                                                    <option value="Lainnya"
                                                        {{ $surat->pendidikan_terakhir == 'Lainnya' ? 'selected' : '' }}>
                                                        Lainnya</option>
                                                </select>
                                                @error('pendidikan_terakhir')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control form-control-sm"
                                                    value="{{ $surat->pangkat }}" required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan"
                                                    class="form-control form-control-sm" value="{{ $surat->golongan }}"
                                                    required />
                                                @error('golongan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>
                                                <input type="text" name="tmt"
                                                    class="form-control form-control-sm form-control kt_datepicker_tmt"
                                                    placeholder="Pilih tanggal"
                                                    value="{{ $surat->tmt ? $surat->tmt->format('Y-m-d') : '' }}"
                                                    autocomplete="off" required />
                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="fv-row mb-3">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja"
                                                    class="form-control form-control-sm"
                                                    value="{{ $surat->unit_kerja }}" required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="fv-row mb-3">
                                            <label class="required fw-semibold fs-6 mb-2">Alamat Orang Tua</label>
                                            <textarea name="alamat" class="form-control form-control-sm" rows="3" required>{{ old('alamat', $surat->alamat) }}</textarea>
                                            @error('alamat')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Keperluan Surat</label>
                                            <textarea name="keperluan" class="form-control form-control-sm" rows="3" required>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                            @error('keperluan')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-sm btn-primary w-250px">
                                            <span class="indicator-label">Update Pengajuan</span>
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
