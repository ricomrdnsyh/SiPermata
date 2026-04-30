@extends('layout.main')
@section('title', 'Surat Pemohonan PKL')
@section('content')
    @php
        $daftarMahasiswa = collect($surat->daftar_mahasiswa ?? []);
        $isKelompok = $daftarMahasiswa->count() > 1;
    @endphp
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-body p-lg-8">
                        <div class="d-flex flex-column">
                            <div class="mb-6 text-center">
                                <h1 class="fs-2hx fw-bolder mb-3">Detail Surat Pemohonan PKL</h1>
                                <div class="text-gray-400 fw-bold fs-5">Silakan lihat detail pengajuan !</div>
                            </div>
                            <div class="separator border-gray-200 mb-8"></div>
                            <div id="form-container" class="mt-2">
                                <form id="kt_ecommerce_settings_general_form" class="form fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">NIM</label>
                                                <input type="text" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $surat->nim . ' - ' . $surat->mahasiswa->nama }}" disabled />
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                <input type="text" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $surat->akademik ? $surat->akademik->tahun_akademik : '-' }}" disabled />
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tanggal Mulai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fas fa-calendar-alt fs-5"></i></span>
                                                    <input type="text" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $surat->tgl_mulai?->locale('id')->isoFormat('D MMMM YYYY') }}" disabled />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tanggal Selesai</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i class="fas fa-calendar-alt fs-5"></i></span>
                                                    <input type="text" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $surat->tgl_selesai?->locale('id')->isoFormat('D MMMM YYYY') }}" disabled />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Tempat PKL</label>
                                                <input type="text" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $surat->mitra ? $surat->mitra->nama_mitra : '-' }}" disabled />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="fv-row mb-3">
                                                <label class="fw-semibold fs-6 mb-2">Catatan</label>
                                                <textarea class="form-control form-control-sm mb-3 mb-lg-0" rows="3" disabled>{{ old('catatan', $surat->catatan) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                @if ($isKelompok)
                                    <div class="separator border-gray-200 my-6"></div>
                                    <div class="mb-3">
                                        <h4 class="fw-bold mb-1">Daftar Anggota Kelompok</h4>
                                        <div class="text-muted fs-7">Pengajuan ini merupakan pengajuan kelompok dengan {{ $daftarMahasiswa->count() }} mahasiswa.</div>
                                    </div>
                                    @include('partials.surat_pkl_anggota', ['surat' => $surat])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
