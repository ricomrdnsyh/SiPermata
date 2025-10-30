@extends('layout.main')

@section('title', 'Surat Izin Penelitian')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-17">
                        <div class="d-flex flex-column">
                            <div class="mb-13 text-center">
                                <h1 class="fs-2hx fw-bolder mb-5">Detail Surat Permohonan Izin Penelitian</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan lihat detail pengajuan Anda !</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>

                            <!-- Form Container -->
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form"
                                    class="form fv-plugins-bootstrap5 fv-plugins-framework">
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
                                        <label class="required fw-semibold fs-6 mb-2">Tempat Penelitian</label>
                                        <input type="text" name="akademik_id" class="form-control mb-3 mb-lg-0"
                                            value="{{ $surat->mitra ? $surat->mitra->nama_mitra : '-' }}" disabled />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                        <input type="date" name="tgl_mulai" class="form-control mb-3 mb-lg-0"
                                            value="{{ $surat->tgl_mulai }}" disabled />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                        <input type="date" name="tgl_selesai" class="form-control mb-3 mb-lg-0"
                                            value="{{ $surat->tgl_selesai }}" disabled />
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Judul Penelitian</label>
                                        <textarea name="judul_penelitian" class="form-control mb-3 mb-lg-0" rows="3" disabled>{{ old('judul_penelitian', $surat->judul_penelitian) }}</textarea>
                                    </div>
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Catatan</label>
                                        <textarea name="catatan" class="form-control mb-3 mb-lg-0" rows="3" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
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
