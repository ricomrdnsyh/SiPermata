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
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-17">
                        <div class="d-flex flex-column">
                            <div class="mb-13 text-center">
                                <h1 class="fs-2hx fw-bolder mb-5">Edit Surat Keterangan Aktif</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan edit pengajuan Anda untuk melanjutkan
                                    pengajuan!</div>
                            </div>

                            <!-- Form Container -->
                            <div id="form-container" class="mt-2">
                                <!-- Form Umum -->
                                <form id="form-umum" class="form-section active" method="POST"
                                    action="{{ route('bak.surat-aktif.update', $surat->id_surat_aktif) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">

                                    <h3 class="mb-5 text-center">Pengajuan Surat Keterangan Aktif Umum</h3>

                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                        <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                            data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                            data-select2-id="select2-data-72-r5i2" tabindex="-1" aria-hidden="true"
                                            data-kt-initialized="1">
                                            <option value="" data-select2-id="select2-data-74-9zwr">
                                                Pilih Mahasiswa...</option>
                                            @foreach ($mahasiswa as $mhs)
                                                <option value="{{ $mhs->nim }}" {{ $mhs->nim == $surat->nim ? 'selected' : '' }}>
                                                    {{ $mhs->nim }} - {{ $mhs->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('nim')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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
                                                <option value="{{ $item->id_akademik }}"
                                                    {{ $item->id_akademik == $surat->akademik_id ? 'selected' : '' }}>
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
                                        <input type="number" name="semester" class="form-control mb-3 mb-lg-0"
                                            value="{{ $surat->semester }}" />
                                        @error('semester')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                        <textarea name="alamat" class="form-control mb-3 mb-lg-0" rows="3">{{ old('alamat', $surat->alamat) }}</textarea>
                                        @error('alamat')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="text-center mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-100 w-md-50">
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

                                <!-- Form PNS -->
                                <form id="form-pns" class="form-section" method="POST"
                                    action="{{ route('bak.surat-aktif.update', $surat->id_surat_aktif) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">

                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PNS</h3>

                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                        <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                            data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                            data-select2-id="select2-data-72-r5i9" tabindex="-1" aria-hidden="true"
                                            data-kt-initialized="1">
                                            <option value="" data-select2-id="select2-data-74-9zwr">
                                                Pilih Mahasiswa...</option>
                                            @foreach ($mahasiswa as $mhsw)
                                                <option value="{{ $mhsw->nim }}" {{ $mhsw->nim == $surat->nim ? 'selected' : '' }}>
                                                    {{ $mhsw->nim }} - {{ $mhsw->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('nim')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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
                                                        <option value="{{ $akd->id_akademik }}"
                                                            {{ $akd->id_akademik == $surat->akademik_id ? 'selected' : '' }}>
                                                            {{ $akd->tahun_akademik }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('akademik_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="number" name="semester" class="form-control"
                                                    value="{{ $surat->semester }}" required />
                                                @error('semester')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua</label>
                                                <input type="number" name="nip" class="form-control"
                                                    value="{{ $surat->nip }}" required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua</label>
                                                <input type="text" name="nama_ortu" class="form-control"
                                                    value="{{ $surat->nama_ortu }}" required />
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
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control"
                                                    value="{{ $surat->pangkat }}" required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan" class="form-control"
                                                    value="{{ $surat->golongan }}" required />
                                                @error('golongan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>
                                                <input type="date" name="tmt" class="form-control"
                                                    value="{{ $surat->tmt }}" required />
                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja" class="form-control"
                                                    value="{{ $surat->unit_kerja }}" required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Alamat tetap full width -->
                                            <div class="fv-row mb-7 col-12">
                                                <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $surat->alamat) }}</textarea>
                                                @error('alamat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-100 w-md-50">
                                            <span class="indicator-label">Update Pengajuan</span>
                                            <span class="indicator-progress" style="display: none;">
                                                Tunggu sebentar...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>

                                <!-- Form PPPK (sama seperti PNS) -->
                                <form id="form-pppk" class="form-section" method="POST"
                                    action="{{ route('mahasiswa.surat-aktif.update', $surat->id_surat_aktif) }}">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="kategori" value="{{ $surat->kategori }}">

                                    <h3 class="mb-5 text-center">Pengajuan Surat Aktif PPPK</h3>

                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Nama Mahasiswa</label>
                                        <select class="form-select form-select-solid select2-hidden-accessible w-100"
                                            data-control="select2" data-placeholder="Pilih Mahasiswa" name="nim"
                                            data-select2-id="select2-data-72-r5i1" tabindex="-1" aria-hidden="true"
                                            data-kt-initialized="1">
                                            <option value="" data-select2-id="select2-data-74-9zwr">
                                                Pilih Mahasiswa...</option>
                                            @foreach ($mahasiswa as $mhswa)
                                                <option value="{{ $mhswa->nim }}" {{ $mhswa->nim == $surat->nim ? 'selected' : '' }}>
                                                    {{ $mhswa->nim }} - {{ $mhswa->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('nim')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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
                                                        <option value="{{ $akdm->id_akademik }}"
                                                            {{ $akdm->id_akademik == $surat->akademik_id ? 'selected' : '' }}>
                                                            {{ $akdm->tahun_akademik }}</option>
                                                    @endforeach
                                                </select>
                                                @error('akademik_id')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Semester</label>
                                                <input type="number" name="semester" class="form-control"
                                                    value="{{ $surat->semester }}" required />
                                                @error('semester')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">NIP Orang Tua</label>
                                                <input type="number" name="nip" class="form-control"
                                                    value="{{ $surat->nip }}" required />
                                                @error('nip')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Nama Orang Tua</label>
                                                <input type="text" name="nama_ortu" class="form-control"
                                                    value="{{ $surat->nama_ortu }}" required />
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
                                        </div>

                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Pangkat Orang Tua</label>
                                                <input type="text" name="pangkat" class="form-control"
                                                    value="{{ $surat->pangkat }}" required />
                                                @error('pangkat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Golongan</label>
                                                <input type="text" name="golongan" class="form-control"
                                                    value="{{ $surat->golongan }}" required />
                                                @error('golongan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Tahun Mulai Tugas</label>
                                                <input type="date" name="tmt" class="form-control"
                                                    value="{{ $surat->tmt }}" required />
                                                @error('tmt')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="fv-row mb-7">
                                                <label class="required fw-semibold fs-6 mb-2">Unit Kerja</label>
                                                <input type="text" name="unit_kerja" class="form-control"
                                                    value="{{ $surat->unit_kerja }}" required />
                                                @error('unit_kerja')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Alamat tetap full width -->
                                            <div class="fv-row mb-7 col-12">
                                                <label class="required fw-semibold fs-6 mb-2">Alamat</label>
                                                <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $surat->alamat) }}</textarea>
                                                @error('alamat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-8">
                                        <button type="submit" data-kt-contacts-type="submit"
                                            class="btn btn-primary w-100 w-md-50">
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
