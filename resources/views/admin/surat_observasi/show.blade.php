@extends('layout.main')
@section('title', 'Detail Surat Permohonan Observasi')
@section('css')
    <style>
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
    @php
        $daftarMahasiswa = collect($surat->daftar_mahasiswa ?? [])->values();
        $isKelompok = $daftarMahasiswa->count() > 1;
    @endphp

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
                                        Detail Surat Permohonan Observasi
                                    </h1>
                                    <div class="text-muted fw-semibold fs-5">Silakan lihat detail pengajuan!</div>
                                </div>
                                <div class="separator border-2 border-dashed mb-10"></div>
<div id="form-container" class="mt-2">
                                    <form id="kt_ecommerce_settings_general_form"
                                        class="form fv-plugins-bootstrap5 fv-plugins-framework">

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-graduation-cap text-gray-400 me-2"></i> Data Akademik Mahasiswa
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12 col-md-4">
                                                    <label class="fw-semibold fs-6 mb-2">Mahasiswa Pengaju</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->nim . ' - ' . ($surat->mahasiswa?->nama ?? '-') }}" disabled />
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <label class="fw-semibold fs-6 mb-2">Tahun Akademik</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->akademik?->tahun_akademik ?? '-' }}" disabled />
                                                </div>

                                                <div class="col-12 col-md-4">
                                                    <label class="fw-semibold fs-6 mb-2">Semester</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->semester }}" disabled />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-box">
                                            <h5 class="mb-5 text-gray-600">
                                                <i class="fas fa-building text-gray-400 me-2"></i> Detail Observasi
                                            </h5>
                                            <div class="row g-5">
                                                <div class="col-12 col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tempat Observasi</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $surat->mitra?->nama_mitra ?? '-' }}" disabled />
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="fw-semibold fs-6 mb-2">Tanggal Observasi</label>
                                                    <div class="position-relative">
                                                        <i class="fas fa-calendar-alt position-absolute top-50 translate-middle-y ms-4 text-gray-500"></i>
                                                        <input type="text" class="form-control form-control-sm ps-12"
                                                            value="{{ $surat->tgl_observasi?->locale('id')->isoFormat('D MMMM YYYY') }}" disabled />
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="fw-semibold fs-6 mb-2">Keperluan Observasi</label>
                                                    <textarea class="form-control form-control-sm" rows="3" disabled>{{ old('keperluan', $surat->keperluan) }}</textarea>
                                                </div>
                                                @if ($surat->catatan)
                                                    <div class="col-12 mt-4">
                                                        <label class="fw-semibold fs-6 mb-2">Catatan Verifikator</label>
                                                        <div class="alert alert-dismissible bg-light-warning border border-warning border-dashed d-flex align-items-start w-100 p-4 p-sm-5 mb-0">
                                                            <i class="fas fa-exclamation-circle fs-2hx text-warning me-4"></i>
                                                            <div class="d-flex flex-column pe-0">
                                                                <h5 class="mb-1 text-warning">Catatan dari Verifikator</h5>
                                                                <span>{{ $surat->catatan }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($isKelompok)
                                            <div class="form-group-box">
                                                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-6 p-6">
                                                    <i class="fas fa-users fs-2x text-primary me-4 mt-1"></i>
                                                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                                                        <div class="mb-3 mb-md-0 fw-semibold">
                                                            <h5 class="text-gray-900 fw-bold mb-1">Pengajuan Kelompok</h5>
                                                            <div class="fs-7 text-gray-700 pe-7">Surat ini diajukan secara berkelompok dengan anggota di bawah ini.</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="table-responsive border rounded bg-body shadow-sm">
                                                    <table class="table table-sm table-row-bordered table-row-gray-200 align-middle gs-0 gy-3 mb-0">
                                                        <thead>
                                                            <tr class="fw-bold text-muted bg-light fs-7">
                                                                <th class="ps-3" style="width: 25%">Peran</th>
                                                                <th style="width: 25%">NIM</th>
                                                                <th style="width: 25%">Nama Mahasiswa</th>
                                                                <th class="pe-4" style="width: 25%">Program Studi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($daftarMahasiswa as $index => $mhs)
                                                                <tr>
                                                                    <td class="ps-3">
                                                                        <span class="badge {{ data_get($mhs, 'is_ketua') ? 'badge-light-primary' : 'badge-light-success' }}">
                                                                            {{ data_get($mhs, 'is_ketua') ? 'Ketua' : 'Anggota' }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="fw-semibold text-gray-800">{{ data_get($mhs, 'nim', '-') }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="fw-semibold text-gray-800">{{ data_get($mhs, 'nama', '-') }}</span>
                                                                    </td>
                                                                    <td class="pe-4">
                                                                        <span class="text-muted">{{ data_get($mhs, 'prodi', '-') }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="d-flex justify-content-center mt-8">
                                            <a href="{{ route('admin.surat-observasi.index') }}" class="btn btn-secondary btn-sm px-6 w-100 w-md-auto">
                                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Surat
                                            </a>
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
