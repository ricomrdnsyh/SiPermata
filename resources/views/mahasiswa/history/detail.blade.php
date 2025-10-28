@extends('layout.main')

@section('title', 'Detail Surat Pengajuan')

@section('css')
    <style>
        .table-row-dashed tr {
            border-bottom: 1px dashed #cccccc !important;
        }

        #users-table thead tr th {
            vertical-align: middle;
            border-bottom: 1px dashed #cccccc !important;
        }
    </style>

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="d-flex flex-column flex-lg-row">
                    <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
                        <div class="card card-flush pt-3 mb-5 mb-xl-10">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2 class="fw-bolder">Detail Surat Pengajuan</h2>
                                </div>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="card-body pt-3 mt-5">
                                <div class="mb-0">
                                    <h5 class="mb-4">Informasi Pengajuan</h5>
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                                            <thead>
                                                <tr
                                                    class="border-bottom border-gray-200 text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                    <th class="min-w-150px">Nama Surat</th>
                                                    <th class="min-w-125px">Tanggal Pengajuan</th>
                                                    <th class="min-w-125px">Status</th>
                                                    <th class="min-w-125px">Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-bold text-gray-800">
                                                <tr>
                                                    <td>{{ $pengajuan->nama_surat }}</td>
                                                    <td>{{ $pengajuan->created_at?->locale('id')->isoFormat('D MMMM YYYY') }}
                                                    </td>
                                                    <td>
                                                        @switch($pengajuan->status)
                                                            @case('pengajuan')
                                                                <span class="badge bg-warning">Menunggu Persetujuan</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge bg-info">Menunggu Dekan</span>
                                                            @break

                                                            @case('diterima')
                                                                <span class="badge bg-success">Disetujui</span>
                                                            @break

                                                            @case('ditolak')
                                                                <span class="badge bg-danger">Ditolak</span>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $pengajuan->catatan ?: '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-15">
                                    <h5>Detail Surat</h5>
                                    <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            @if ($pengajuan->tabel === 'surat_aktif')
                                                @include('bak.history.partials.surat_aktif', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_lulus')
                                                @include('bak.history.partials.surat_lulus', [
                                                    'surat' => $surat,
                                                ])
                                            @else
                                                <p class="text-muted">Detail untuk jenis surat ini belum tersedia.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2 **position-lg-sticky top-0**">
                        <div class="card card-flush mb-0">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Data Mahasiswa</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0 fs-6">
                                @php
                                    $mahasiswa = \App\Models\Mahasiswa::where('nim', $pengajuan->nim)->first();
                                    $fakultas = $mahasiswa?->fakultas_id
                                        ? \App\Models\Fakultas::find($mahasiswa->fakultas_id)
                                        : null;
                                @endphp

                                <div class="mb-7">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60px symbol-circle me-3">
                                            <img alt="Pic" src="{{ asset('assets/media/avatars/profile.png') }}" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fs-4 fw-bolder text-gray-900 text-hover-primary me-2">
                                                {{ $mahasiswa?->nama ?? '-' }}
                                            </span>
                                            <span class="fw-bold text-gray-600 text-hover-primary text-break">
                                                {{ $mahasiswa?->email ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed mb-7"></div>
                                <div class="mb-10">
                                    <table class="table fs-6 fw-bold gs-0 gy-2 gx-2">
                                        <tr>
                                            <td class="text-gray-400">NIM</td>
                                            <td class="text-gray-800">{{ $pengajuan->nim }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-400">Nama</td>
                                            <td class="text-gray-800">{{ $mahasiswa?->nama ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-400">Fakultas</td>
                                            <td class="text-gray-800">
                                                {{ $fakultas?->nama_fakultas ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-400">Prodi</td>
                                            <td class="text-gray-800">
                                                {{ $mahasiswa?->prodi->nama_prodi ?? '-' }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
