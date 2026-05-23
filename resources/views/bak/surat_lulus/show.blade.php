@extends('layout.main')
@section('title', 'Surat Keterangan Lulus')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Detail Surat Permohonan Keterangan Lulus</h1>
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
                                                    value="{{ $surat->nim . ' - ' . $surat->mahasiswa->nama }}" disabled />
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

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">IPK</label>
                                                <input type="text" name="ipk"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $dataSimpt?->ipk_ketuntasan ? number_format((float) $dataSimpt->ipk_ketuntasan, 2) : '-' }}" disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir"
                                                    class="form-control form-control-sm mb-3 mb-lg-0"
                                                    value="{{ $surat->tempat_lahir }}" disabled />
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tanggal Lahir</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-alt fs-5"></i>
                                                    </span>
                                                    <input type="text" name="tgl_lahir"
                                                        class="form-control form-control-sm mb-3 mb-lg-0"
                                                        value="{{ $surat->tgl_lahir?->locale('id')->isoFormat('D MMMM YYYY') }}"
                                                        disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Judul Penelitian/Tugas Akhir</label>
                                                <textarea name="judul_penelitian" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('judul_penelitian', $surat->judul_penelitian) }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Catatan</label>
                                                <textarea name="catatan" class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
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
