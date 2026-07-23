@extends('layout.main')
@section('title', 'Detail Surat Pengajuan')

@section('css')
    <style>
        .timeline-soft {
            position: relative;
        }

        .timeline-soft:before {
            content: '';
            position: absolute;
            left: 14px;
            top: 2px;
            bottom: 2px;
            width: 2px;
            background: var(--bs-border-color, #eef0f7);
            border-radius: 2px;
        }

        .timeline-soft-item {
            position: relative;
            padding-left: 44px;
            padding-bottom: 18px;
        }

        .timeline-soft-item:last-child {
            padding-bottom: 0;
        }

        .timeline-soft-dot {
            position: absolute;
            left: 6px;
            top: 2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--bs-border-color, #e4e6ef);
        }

        .timeline-soft-dot.dot-warning {
            border-color: #f6c000;
        }

        .timeline-soft-dot.dot-info {
            border-color: #009ef7;
        }

        .timeline-soft-dot.dot-success {
            border-color: #50cd89;
        }

        .timeline-soft-dot.dot-primary {
            border-color: #7239ea;
        }

        .timeline-soft-dot.dot-danger {
            border-color: #f1416c;
        }

        .timeline-soft-dot.is-active.dot-warning {
            box-shadow: 0 0 0 4px rgba(246, 192, 0, 0.16);
        }

        .timeline-soft-dot.is-active.dot-info {
            box-shadow: 0 0 0 4px rgba(0, 158, 247, 0.16);
        }

        .timeline-soft-dot.is-active.dot-success {
            box-shadow: 0 0 0 4px rgba(80, 205, 137, 0.16);
        }

        .timeline-soft-dot.is-active.dot-primary {
            box-shadow: 0 0 0 4px rgba(114, 57, 234, 0.16);
        }

        .timeline-soft-dot.is-active.dot-danger {
            box-shadow: 0 0 0 4px rgba(241, 65, 108, 0.16);
        }

        .meta-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #a1a5b7;
            font-weight: 700;
        }

        .meta-value {
            font-weight: 600;
            color: inherit;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .soft-panel {
            border: 1px solid var(--bs-border-color, #eef0f7);
            border-radius: .75rem;
        }

        .sticky-side {
            top: 18px;
            z-index: 9;
        }

        .profile-card {
            border-radius: 14px;
            overflow: hidden;
        }

        .profile-head {
            padding: 18px 32px 10px 32px;
        }

        .profile-body {
            padding: 0 32px 24px 32px;
        }

        .profile-sep {
            height: 1px;
            background: var(--bs-border-color, #eef0f7);
            margin: 14px 0;
        }

        .profile-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #a1a5b7;
            margin-bottom: 6px;
        }

        .profile-name {
            color: inherit;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .profile-sub {
            color: #7e8299;
        }

        .profile-value {
            color: inherit;
            word-break: break-word;
            overflow-wrap: anywhere;
            line-height: 1.35rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .stats-item {
            border: 1px solid var(--bs-border-color, #eef0f7);
            border-radius: 12px;
            padding: 10px 12px;
        }

        .stats-k {
            font-size: .72rem;
            color: #7e8299;
            font-weight: 800;
            letter-spacing: .03em;
        }

        .stats-v {
            font-size: 1.15rem;
            font-weight: 900;
            color: inherit;
            line-height: 1.35rem;
        }

        @media (max-width: 575.98px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">

                @php
                    $latestLog = $pengajuan->statusLogs?->sortByDesc('created_at')->first();

                    $mahasiswa = \App\Models\Mahasiswa::where('nim', $pengajuan->nim)->first();
                    $fakultas = $mahasiswa?->fakultas_id ? \App\Models\Fakultas::find($mahasiswa->fakultas_id) : null;
                @endphp

                <div class="row g-5 g-xl-8">
                    <div class="col-xl-8 order-2 order-xl-1">

                        <div class="card card-flush mb-6 shadow-sm border border-dashed border-dark rounded">
                            <div class="card-header pt-6 pb-4">
                                <div class="card-title d-flex flex-column">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="symbol symbol-40px">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="fas fa-file-signature text-primary fs-3"></i>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column min-w-0">
                                            <span class="fs-3 fw-semibold text-gray-900">{{ $pengajuan->nama_surat }}</span>
                                            <span class="text-gray-600 fw-semibold fs-7">
                                                Diajukan:
                                                {{ $pengajuan->tanggal_pengajuan_asli?->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') ?? '—' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    @switch($pengajuan->status)
                                        @case('pengajuan')
                                            <span class="badge badge-light-warning fw-bold px-4 py-3">Menunggu Verifikasi</span>
                                        @break

                                        @case('proses')
                                            <span class="badge badge-light-info fw-bold px-4 py-3">Diproses</span>
                                        @break

                                        @case('diterima')
                                            <span class="badge badge-light-success fw-bold px-4 py-3">Disetujui Dekan</span>
                                        @break

                                        @case('selesai')
                                            <span class="badge badge-light-primary fw-bold px-4 py-3">Selesai</span>
                                        @break

                                        @case('ditolak')
                                            <span class="badge badge-light-danger fw-bold px-4 py-3">Ditolak</span>
                                        @break

                                        @default
                                            <span
                                                class="badge badge-light fw-bold px-4 py-3">{{ ucfirst($pengajuan->status) }}</span>
                                    @endswitch
                                </div>
                            </div>

                            <div class="card-body pt-4">
                                <div class="soft-panel bg-light p-5 mb-6">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="meta-label mb-1">Jenis Surat</div>
                                            <div class="meta-value">{{ $pengajuan->nama_surat }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="meta-label mb-1">Catatan</div>
                                            <div class="meta-value">{{ $pengajuan->catatan ?: '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <span class="symbol symbol-35px">
                                        <span class="symbol-label bg-light-info">
                                            <i class="fas fa-align-left text-info fs-5"></i>
                                        </span>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-gray-900">Detail Surat</span>
                                        <span class="text-gray-600 fw-semibold fs-7">Periksa isi surat sebelum menunggu
                                            hasil</span>
                                    </div>
                                </div>

                                <div class="bg-body border border-gray-200 rounded p-5">
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
                                        <div class="text-center text-muted py-6 fw-semibold">Detail untuk jenis surat ini
                                            belum tersedia.</div>
                                    @endif
                                </div>

                                <div class="pt-5 d-flex justify-content-end mt-3 flex-wrap gap-2">
                                    @if ($pengajuan->status === 'selesai')
                                        @if (!empty($fileGeneratedPath) && $pengajuan->id_tabel_surat)
                                            <a href="{{ route('mahasiswa.surat.view', ['tabel' => $pengajuan->tabel, 'id' => $pengajuan->id_tabel_surat]) }}"
                                                class="btn btn-sm btn-primary fw-semibold" target="_blank"
                                                rel="noopener noreferrer">
                                                <i class="fas fa-file-alt me-2"></i>Lihat Surat
                                            </a>
                                        @else
                                            <button class="btn btn-sm-warning fw-semibold" type="button">
                                                Surat belum tersedia
                                            </button>
                                        @endif
                                    @else
                                        <button class="btn btn-sm btn-light fw-semibold" type="button" disabled>
                                            Surat belum selesai diproses
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card card-flush shadow-sm border border-dashed border-dark rounded">
                            <div class="card-header pt-6 pb-4">
                                <div class="card-title d-flex flex-column">
                                    <span class="fw-semibold text-gray-900">Riwayat Proses</span>
                                    <span class="text-gray-600 fw-semibold fs-7">Jejak perubahan status pengajuan</span>
                                </div>
                            </div>

                            <div class="card-body pt-4">
                                @isset($jumlahPengajuan)
                                    <div class="stats-grid mb-6">
                                        <div class="stats-item bg-body">
                                            <div class="stats-k mb-1">Total Diajukan</div>
                                            <div class="stats-v">{{ $jumlahPengajuan }}</div>
                                        </div>
                                        <div class="stats-item bg-body" style="border-color: rgba(80,205,137,.35);">
                                            <div class="stats-k mb-1">Disetujui</div>
                                            <div class="stats-v text-success">{{ $jumlahDiterima ?? 0 }}</div>
                                        </div>
                                        <div class="stats-item bg-body" style="border-color: rgba(241,65,108,.35);">
                                            <div class="stats-k mb-1">Ditolak</div>
                                            <div class="stats-v text-danger">{{ $jumlahDitolak ?? 0 }}</div>
                                        </div>
                                    </div>
                                @endisset

                                <div class="timeline-soft">
                                    @forelse($pengajuan->statusLogs ?? [] as $log)
                                        @php
                                            $isActive = false;
                                            if ($latestLog) {
                                                if (isset($latestLog->id) && isset($log->id)) {
                                                    $isActive = $latestLog->id === $log->id;
                                                } else {
                                                    $isActive =
                                                        optional($latestLog->created_at)->timestamp ===
                                                        optional($log->created_at)->timestamp;
                                                }
                                            }

                                            $dotType = 'dot-info';
                                            if ($log->status === 'pengajuan') {
                                                $dotType = 'dot-warning';
                                            }
                                            if ($log->status === 'proses') {
                                                $dotType = 'dot-info';
                                            }
                                            if ($log->status === 'diterima') {
                                                $dotType = 'dot-success';
                                            }
                                            if ($log->status === 'selesai') {
                                                $dotType = 'dot-primary';
                                            }
                                            if ($log->status === 'ditolak') {
                                                $dotType = 'dot-danger';
                                            }
                                        @endphp

                                        <div class="timeline-soft-item">
                                            <div
                                                class="timeline-soft-dot bg-body {{ $dotType }} {{ $isActive ? 'is-active' : '' }}">
                                            </div>

                                            <div class="soft-panel bg-light p-4">
                                                <div
                                                    class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center justify-content-between mb-2">
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        @switch($log->status)
                                                            @case('pengajuan')
                                                                <span class="badge badge-light-warning fw-bold">Diajukan</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge badge-light-info fw-bold">Diproses</span>
                                                            @break

                                                            @case('diterima')
                                                                <span class="badge badge-light-success fw-bold">Disetujui
                                                                    Dekan</span>
                                                            @break

                                                            @case('selesai')
                                                                <span class="badge badge-light-primary fw-bold">Selesai</span>
                                                            @break

                                                            @case('ditolak')
                                                                <span class="badge badge-light-danger fw-bold">Ditolak</span>
                                                            @break

                                                            @default
                                                                <span
                                                                    class="badge badge-light fw-bold">{{ ucfirst($log->status) }}</span>
                                                        @endswitch
                                                    </div>

                                                    <span class="text-gray-600 fw-semibold fs-7">
                                                        {{ $log->created_at?->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') ?? '—' }}
                                                    </span>
                                                </div>

                                                <div class="text-gray-700 fw-semibold text-break">
                                                    {{ $log->catatan ?: '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                            <div class="text-center text-muted fw-semibold py-6">Belum ada riwayat.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-xl-4 order-1 order-xl-2">
                            <div class="position-xl-sticky sticky-side">
                                <div class="profile-card bg-body mb-6 shadow-sm border border-dashed border-dark">
                                    <div class="profile-head">
                                        <div class="fw-bolder fs-4 text-gray-900">Data Mahasiswa</div>
                                    </div>

                                    <div class="profile-body">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="symbol symbol-50px symbol-circle flex-shrink-0">
                                                <img alt="Pic" src="{{ asset('assets/media/avatars/profile.png') }}" />
                                            </div>
                                            <div class="min-w-0">
                                                <div class="profile-name fs-4 fw-bold">{{ $mahasiswa?->nama ?? '-' }}</div>
                                                <div class="profile-sub fs-4">{{ $pengajuan->nim }}</div>
                                            </div>
                                        </div>

                                        <div class="profile-sep"></div>

                                        <div class="mb-4">
                                            <div class="profile-label fs-8">Program Studi</div>
                                            <div class="profile-value fs-5 fw-bold">
                                                {{ $mahasiswa?->prodi->nama_prodi ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="profile-label fs-8">Fakultas</div>
                                            <div class="profile-value fs-5 fw-bold">
                                                {{ $fakultas?->nama_fakultas ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="mb-0">
                                            <div class="profile-label fs-8">Email</div>
                                            <div class="profile-value fs-5 fw-bold">
                                                {{ $mahasiswa?->email ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
        @endsection
