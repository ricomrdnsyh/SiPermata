@extends('layout.main')
@section('title', 'Surat Permohonan PKL')
@section('content')
    @php
        $daftarMahasiswa = collect($surat->daftar_mahasiswa ?? [])->values();
        $isKelompok = $daftarMahasiswa->count() > 1;
    @endphp

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm border border-dashed border-dark rounded">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Detail Surat Permohonan PKL</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan lihat detail pengajuan Anda !</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">NIM</label>
                                                <input type="text" name="nim"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ auth()->user()->reference_id }}" disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                <input type="text" name="akademik_id"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}"
                                                    disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input type="text" name="tgl_mulai"
                                                        class="form-control form-control-sm mb-3 mb-lg-0"
                                                        value="{{ $surat->tgl_mulai?->locale('id')->isoFormat('D MMMM YYYY') }}"
                                                        disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input type="text" name="tgl_selesai"
                                                        class="form-control form-control-sm mb-3 mb-lg-0"
                                                        value="{{ $surat->tgl_selesai?->locale('id')->isoFormat('D MMMM YYYY') }}"
                                                        disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tempat PKL</label>
                                                <input type="text" name="mitra_id"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $surat->mitra ? $surat->mitra->nama_mitra : '-' }}"
                                                    disabled />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Catatan</label>
                                                <textarea name="catatan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
                                            </div>
                                        </div>

                                        @if ($isKelompok)
                                            <div class="col-12">
                                                <div class="separator border-gray-200 my-4"></div>
                                                <div class="fv-row mb-3">
                                                    <label class="fw-semibold fs-6 mb-3">Daftar Mahasiswa</label>

                                                    @foreach ($daftarMahasiswa as $index => $mahasiswa)
                                                        <div class="border border-gray-200 rounded p-4 mb-4">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-3">
                                                                <div class="fw-semibold text-gray-900">
                                                                    Mahasiswa {{ $index + 1 }}
                                                                </div>
                                                                <span
                                                                    class="badge {{ data_get($mahasiswa, 'is_ketua') ? 'badge-light-primary' : 'badge-light-success' }}">
                                                                    {{ data_get($mahasiswa, 'is_ketua') ? 'Ketua' : 'Anggota' }}
                                                                </span>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-12 col-md-4">
                                                                    <div class="fv-row mb-3">
                                                                        <label class="fw-semibold fs-6 mb-2">NIM</label>
                                                                        <input type="text"
                                                                            class="form-control form-control-sm mb-3 mb-lg-0"
                                                                            value="{{ data_get($mahasiswa, 'nim', '-') }}"
                                                                            disabled />
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 col-md-4">
                                                                    <div class="fv-row mb-3">
                                                                        <label class="fw-semibold fs-6 mb-2">Nama
                                                                            Mahasiswa</label>
                                                                        <input type="text"
                                                                            class="form-control form-control-sm mb-3 mb-lg-0"
                                                                            value="{{ data_get($mahasiswa, 'nama', '-') }}"
                                                                            disabled />
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 col-md-4">
                                                                    <div class="fv-row mb-3">
                                                                        <label class="fw-semibold fs-6 mb-2">Prodi</label>
                                                                        <input type="text"
                                                                            class="form-control form-control-sm mb-3 mb-lg-0"
                                                                            value="{{ data_get($mahasiswa, 'prodi', '-') }}"
                                                                            disabled />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
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
