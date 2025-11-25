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

        .summary-pill {
            padding: 0.65rem 1.2rem;
            border-radius: 999px;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="d-flex flex-column flex-lg-row">

                    <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
                        <div class="card card-flush pt-3 mb-5 mb-xl-10">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2 class="fw-bolder mb-0">Detail Surat Pengajuan</h2>
                                </div>
                            </div>

                            <div class="separator my-2"></div>

                            <div class="card-body pt-5">
                                @isset($jumlahPengajuan)
                                    <div class="mb-10">
                                        <h5 class="mb-4">Ringkasan Riwayat</h5>
                                        <div class="d-flex flex-wrap gap-3">
                                            <span class="badge bg-light-primary text-primary summary-pill">
                                                Diajukan: {{ $jumlahPengajuan }}x
                                            </span>
                                            <span class="badge bg-light-danger text-danger summary-pill">
                                                Ditolak: {{ $jumlahDitolak ?? 0 }}x
                                            </span>
                                            <span class="badge bg-light-success text-success summary-pill">
                                                Disetujui Dekan: {{ $jumlahDiterima ?? 0 }}x
                                            </span>
                                        </div>
                                    </div>
                                @endisset

                                <div class="mb-10">
                                    <h5 class="mb-4">Informasi Pengajuan</h5>
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                                            <thead>
                                                <tr
                                                    class="border-bottom border-gray-200 text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                    <th class="min-w-150px">Nama Surat</th>
                                                    <th class="min-w-150px">Tanggal Pengajuan</th>
                                                    <th class="min-w-125px">Status</th>
                                                    <th class="min-w-200px">Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-bold text-gray-800">
                                                <tr>
                                                    <td>{{ $pengajuan->nama_surat }}</td>
                                                    <td>
                                                        {{ $pengajuan->created_at?->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') }}
                                                    </td>
                                                    <td>
                                                        @switch($pengajuan->status)
                                                            @case('selesai')
                                                                <span class="badge bg-primary">Selesai</span>
                                                            @break

                                                            @case('diterima')
                                                                <span class="badge bg-success">Disetujui Dekan</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge bg-info">Diproses</span>
                                                            @break

                                                            @case('pengajuan')
                                                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                                                            @break

                                                            @case('ditolak')
                                                                <span class="badge bg-danger">Ditolak</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-secondary">
                                                                    {{ ucfirst($pengajuan->status) }}
                                                                </span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $pengajuan->catatan ?: '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mb-10">
                                    <h5 class="mb-4">Detail Surat</h5>
                                    <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            @if ($pengajuan->tabel === 'surat_aktif')
                                                @include('mahasiswa.history.partials.surat_aktif', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_izin_penelitian')
                                                @include('mahasiswa.history.partials.surat_penelitian', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_rekomendasi')
                                                @include('mahasiswa.history.partials.surat_rekomendasi', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_pkl')
                                                @include('mahasiswa.history.partials.surat_pkl', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_observasi')
                                                @include('mahasiswa.history.partials.surat_observasi', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_keterangan_lulus')
                                                @include('mahasiswa.history.partials.surat_lulus', [
                                                    'surat' => $surat,
                                                ])
                                            @else
                                                <p class="text-muted mb-0">
                                                    Detail untuk jenis surat ini belum tersedia.
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-5 border-top border-gray-200 d-flex justify-content-end">
                                    @if ($pengajuan->status === 'selesai')
                                        @if (!empty($fileGeneratedPath) && $pengajuan->id_tabel_surat)
                                            <a href="{{ route('mahasiswa.surat.view', [
                                                'tabel' => $pengajuan->tabel,
                                                'id' => $pengajuan->id_tabel_surat,
                                            ]) }}"
                                                class="btn btn-light-primary" target="_blank">
                                                <i class="fas fa-cloud-download-alt"></i> Lihat Surat
                                            </a>
                                        @endif
                                    @else
                                        <button class="btn btn-sm btn-light">
                                            Surat belum selesai diproses
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card card-flush mb-6">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2 class="mt-8 mb-0">Riwayat Perubahan Status</h2>
                                </div>
                            </div>

                            <div class="card-body pt-5">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0">
                                        <thead>
                                            <tr
                                                class="border-bottom border-gray-200 text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-150px">Waktu</th>
                                                <th class="min-w-100px">Status</th>
                                                <th class="min-w-200px">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-bold text-gray-800">
                                            @forelse($pengajuan->statusLogs ?? [] as $log)
                                                <tr>
                                                    <td>
                                                        {{ $log->created_at?->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—' }}
                                                    </td>
                                                    <td>
                                                        @switch($log->status)
                                                            @case('pengajuan')
                                                                <span class="badge bg-warning">Diajukan</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge bg-info">Diproses</span>
                                                            @break

                                                            @case('diterima')
                                                                <span class="badge bg-success">Disetujui Dekan</span>
                                                            @break

                                                            @case('selesai')
                                                                <span class="badge bg-primary">Selesai</span>
                                                            @break

                                                            @case('ditolak')
                                                                <span class="badge bg-danger">Ditolak</span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-secondary">
                                                                    {{ ucfirst($log->status) }}
                                                                </span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $log->catatan ?: '-' }}</td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-muted text-center">
                                                            Belum ada riwayat status.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div
                            class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2 position-lg-sticky top-0">
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
                                                <span class="fs-4 fw-bolder text-gray-900 text-hover-primary me-2 text-break">
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
                                                <td class="text-gray-800 text-break">{{ $mahasiswa?->nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Fakultas</td>
                                                <td class="text-gray-800 text-break">
                                                    {{ $fakultas?->nama_fakultas ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Prodi</td>
                                                <td class="text-gray-800 text-break">
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
