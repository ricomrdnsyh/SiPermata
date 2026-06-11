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
            background: #eef0f7;
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
            background: #fff;
            border: 2px solid #e4e6ef;
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
            color: #181c32;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .soft-panel {
            background: #f8f9fc;
            border: 1px solid #eef0f7;
            border-radius: .75rem;
        }

        .sticky-side {
            top: 18px;
            z-index: 9;
        }

        .profile-card {
            
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
        }

        .profile-head {
            padding: 18px 32px 10px 32px;
        }

        .profile-body {
            padding: 0 32px 24px 32px;
        }

        .profile-sep {
            height: 1px;
            background: #eef0f7;
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
            color: #181c32;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .profile-sub {
            color: #7e8299;
        }

        .profile-value {
            color: #3f4254;
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
            border: 1px solid #eef0f7;
            border-radius: 12px;
            padding: 10px 12px;
            background: #fff;
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
            color: #181c32;
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

                    $historyIndexUrl = route('dekan.history.index');
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
                                                {{ $pengajuan->created_at?->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm') ?? '—' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    @switch($pengajuan->status)
                                        @case('pengajuan')
                                            <span class="badge badge-light-warning fw-bold px-4 py-3">Menunggu BAK</span>
                                        @break

                                        @case('proses')
                                            <span class="badge badge-light-info fw-bold px-4 py-3">Menunggu Dekan</span>
                                        @break

                                        @case('diterima')
                                            <span class="badge badge-light-success fw-bold px-4 py-3">Disetujui</span>
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
                                <div class="soft-panel p-5 mb-6">
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
                                        <span class="text-gray-600 fw-semibold fs-7">Baca detail surat sebelum
                                            konfirmasi</span>
                                    </div>
                                </div>

                                <div class="bg-white border border-gray-200 rounded p-5">
                                    @if ($pengajuan->tabel === 'surat_aktif')
                                        @include('dekan.history.partials.surat_aktif', ['surat' => $surat])
                                    @elseif($pengajuan->tabel === 'surat_izin_penelitian')
                                        @include('dekan.history.partials.surat_penelitian', [
                                            'surat' => $surat,
                                        ])
                                    @elseif($pengajuan->tabel === 'surat_rekomendasi')
                                        @include('dekan.history.partials.surat_rekomendasi', [
                                            'surat' => $surat,
                                        ])
                                    @elseif($pengajuan->tabel === 'surat_pkl')
                                        @include('dekan.history.partials.surat_pkl', ['surat' => $surat])
                                    @elseif($pengajuan->tabel === 'surat_observasi')
                                        @include('dekan.history.partials.surat_observasi', [
                                            'surat' => $surat,
                                        ])
                                    @elseif($pengajuan->tabel === 'surat_keterangan_lulus')
                                        @include('dekan.history.partials.surat_lulus', ['surat' => $surat])
                                    @else
                                        <div class="text-center text-muted py-6 fw-semibold">Preview tidak tersedia untuk
                                            jenis surat ini.</div>
                                    @endif
                                </div>

                                <div class="pt-5 d-flex justify-content-end mt-3 flex-wrap gap-2">
                                    @if ($pengajuan->status === 'proses')
                                        <button type="button" class="btn btn-sm btn-danger fw-semibold"
                                            id="btn-reject-main">
                                            <i class="fas fa-times me-2"></i>Tolak Pengajuan
                                        </button>

                                        <a href="{{ route('dekan.surat.lampiran_preview', ['tabel' => $pengajuan->tabel, 'id' => $pengajuan->id_tabel_surat]) }}"
                                            class="btn btn-sm btn-primary fw-semibold" target="_blank"
                                            rel="noopener noreferrer">
                                            <i class="fas fa-eye me-2"></i>Periksa Surat
                                        </a>

                                        <button type="button" class="btn btn-sm btn-success fw-semibold"
                                            id="btn-approve-send">
                                            <i class="fas fa-paper-plane me-2"></i>Terima & Kirim Surat
                                        </button>
                                    @elseif ($pengajuan->status === 'diterima')
                                        @if (isset($fileGeneratedPath) && $fileGeneratedPath && $pengajuan->id_tabel_surat)
                                            <a href="{{ route('dekan.surat.view', ['tabel' => $pengajuan->tabel, 'id' => $pengajuan->id_tabel_surat]) }}"
                                                class="btn btn-sm btn-primary fw-semibold" target="_blank"
                                                rel="noopener noreferrer">
                                                <i class="fas fa-file-alt me-2"></i>Lihat Surat
                                            </a>

                                            <button type="button" class="btn btn-sm btn-success fw-semibold"
                                                id="btn-kirim-surat">
                                                <i class="fas fa-paper-plane me-2"></i>Kirim Surat ke Mahasiswa
                                            </button>
                                        @endif
                                    @elseif ($pengajuan->status === 'selesai')
                                        @if (isset($fileGeneratedPath) && $fileGeneratedPath && $pengajuan->id_tabel_surat)
                                            <a href="{{ route('dekan.surat.view', ['tabel' => $pengajuan->tabel, 'id' => $pengajuan->id_tabel_surat]) }}"
                                                class="btn btn-sm btn-primary fw-semibold" target="_blank"
                                                rel="noopener noreferrer">
                                                <i class="fas fa-file-alt me-2"></i>Lihat Surat
                                            </a>
                                        @endif
                                    @elseif ($pengajuan->status === 'pengajuan')
                                        <button class="btn btn-sm btn-warning fw-semibold" type="button">
                                            Menunggu BAK untuk validasi
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success fw-semibold me-2" type="button">
                                            <i class="fas fa-check-circle me-2"></i>Pengajuan sudah dikonfirmasi
                                        </button>
                                        <a href="{{ route('dekan.surat.lampiran_preview', ['tabel' => $pengajuan->tabel, 'id' => $pengajuan->id_tabel_surat]) }}"
                                            class="btn btn-sm btn-primary fw-semibold" target="_blank"
                                            rel="noopener noreferrer">
                                            <i class="fas fa-eye me-2"></i>Periksa Surat
                                        </a>
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
                                        <div class="stats-item">
                                            <div class="stats-k mb-1">Total Diajukan</div>
                                            <div class="stats-v">{{ $jumlahPengajuan }}</div>
                                        </div>
                                        <div class="stats-item" style="border-color: rgba(80,205,137,.35);">
                                            <div class="stats-k mb-1">Disetujui</div>
                                            <div class="stats-v text-success">{{ $jumlahDiterima ?? 0 }}</div>
                                        </div>
                                        <div class="stats-item" style="border-color: rgba(241,65,108,.35);">
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
                                                class="timeline-soft-dot {{ $dotType }} {{ $isActive ? 'is-active' : '' }}">
                                            </div>

                                            <div class="soft-panel p-4">
                                                <div
                                                    class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center justify-content-between mb-2">
                                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                                        @switch($log->status)
                                                            @case('pengajuan')
                                                                <span class="badge badge-light-warning fw-bold">Menunggu
                                                                    BAK</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge badge-light-info fw-bold">Menunggu
                                                                    Dekan</span>
                                                            @break

                                                            @case('diterima')
                                                                <span
                                                                    class="badge badge-light-success fw-bold">Disetujui</span>
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

                                                        <span class="text-gray-900 fw-semibold text-break">
                                                            {{ $log->user_role ?? 'System' }}
                                                        </span>
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
                                <div class="profile-card mb-6 shadow-sm border border-dashed border-dark">
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

        <div class="modal fade" id="rejectReasonModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered mw-500px">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">Tolak Pengajuan</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="fas fa-times fs-4"></i>
                        </div>
                    </div>
                    <div class="modal-body pt-5">
                        <div class="alert alert-warning d-flex align-items-center p-5 mb-5">
                            <i class="fas fa-exclamation-triangle fs-2hx text-warning me-4"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold fs-6">Konfirmasi Penolakan</span>
                                <span class="fw-semibold">Mahasiswa akan menerima alasan penolakan ini.</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Alasan Penolakan</label>
                            <textarea class="form-control" id="rejectReason" rows="4"
                                placeholder="Contoh: Format surat salah, data tidak lengkap..."></textarea>
                            <div id="rejectError" class="text-danger fs-7 mt-2" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger fw-semibold" id="btn-submit-reject">
                            <span class="indicator-label">Tolak Pengajuan</span>
                            <span class="indicator-progress" style="display:none;">
                                Memproses...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endsection

    @section('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const pengajuanId = {{ $pengajuan->id_history }};
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const historyIndexUrl = "{{ route('dekan.history.index') }}";

                const tabelSurat = "{{ $pengajuan->tabel }}";
                const idSurat = "{{ $pengajuan->id_tabel_surat }}";

                const approveUrl = "{{ route('dekan.history.approve', ':id') }}".replace(':id', pengajuanId);
                const rejectUrl = "{{ route('dekan.history.reject', ':id') }}".replace(':id', pengajuanId);
                const sendUrl = "{{ route('dekan.surat.send', ['tabel' => ':tabel', 'id' => ':id']) }}"
                    .replace(':tabel', tabelSurat)
                    .replace(':id', idSurat);

                const jsonHeaders = {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                };

                const showLoading = (text) => {
                    Swal.fire({
                        title: "Tunggu Sebentar..",
                        icon: "info",
                        text,
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                };

                const showSuccessRedirect = (message) => {
                    Swal.fire({
                        text: message,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(() => window.location.href = historyIndexUrl);
                };

                const showError = (message) => {
                    Swal.fire({
                        text: message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-danger"
                        }
                    });
                };

                const postJson = (url, body = null) => {
                    const opts = {
                        method: 'POST',
                        headers: jsonHeaders
                    };
                    if (body !== null) opts.body = JSON.stringify(body);
                    return fetch(url, opts).then(r => r.json());
                };

                const btnRejectMain = document.getElementById('btn-reject-main');
                if (btnRejectMain) {
                    btnRejectMain.addEventListener('click', function() {
                        const reasonEl = document.getElementById('rejectReason');
                        const errEl = document.getElementById('rejectError');
                        if (reasonEl) reasonEl.value = '';
                        if (errEl) {
                            errEl.style.display = 'none';
                            errEl.textContent = '';
                        }
                        const modalEl = document.getElementById('rejectReasonModal');
                        if (modalEl) new bootstrap.Modal(modalEl).show();
                    });
                }

                const btnSubmitReject = document.getElementById('btn-submit-reject');
                if (btnSubmitReject) {
                    btnSubmitReject.addEventListener('click', function() {
                        const reasonEl = document.getElementById('rejectReason');
                        const errEl = document.getElementById('rejectError');
                        const reason = (reasonEl?.value || '').trim();

                        if (!reason) {
                            if (errEl) {
                                errEl.textContent = 'Catatan penolakan wajib diisi.';
                                errEl.style.display = 'block';
                            }
                            return;
                        }
                        if (errEl) errEl.style.display = 'none';

                        const label = btnSubmitReject.querySelector('.indicator-label');
                        const progress = btnSubmitReject.querySelector('.indicator-progress');
                        if (label) label.style.display = 'none';
                        if (progress) progress.style.display = 'inline-block';
                        btnSubmitReject.disabled = true;

                        postJson(rejectUrl, {
                                catatan: reason
                            })
                            .then(data => {
                                const modalEl = document.getElementById('rejectReasonModal');
                                const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                                if (modal) modal.hide();

                                if (data.success) return showSuccessRedirect(data.message);
                                showError(data.message || 'Terjadi kesalahan saat menolak.');
                            })
                            .catch(() => {
                                const modalEl = document.getElementById('rejectReasonModal');
                                const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                                if (modal) modal.hide();
                                showError('Terjadi kesalahan saat menolak.');
                            })
                            .finally(() => {
                                btnSubmitReject.disabled = false;
                                if (label) label.style.display = 'inline';
                                if (progress) progress.style.display = 'none';
                            });
                    });
                }

                const btnApproveSend = document.getElementById('btn-approve-send');
                if (btnApproveSend) {
                    btnApproveSend.addEventListener('click', function() {
                        Swal.fire({
                            title: "Konfirmasi Terima & Kirim",
                            text: "Apakah Anda yakin ingin menyetujui pengajuan ini dan langsung mengirim surat ke email mahasiswa?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Terima & Kirim!",
                            cancelButtonText: "Batal",
                            customClass: {
                                confirmButton: "btn btn-success",
                                cancelButton: "btn btn-secondary"
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            showLoading('Memproses persetujuan & pengiriman email...');
                            postJson(approveUrl)
                                .then(data => {
                                    if (!data.success) {
                                        Swal.fire("Gagal!", data.message ||
                                            "Gagal menyetujui pengajuan.", "error");
                                        return null;
                                    }
                                    return postJson(sendUrl);
                                })
                                .then(sendRes => {
                                    if (!sendRes) return;

                                    if (sendRes.success) {
                                        Swal.fire("Berhasil!",
                                                "Pengajuan disetujui dan surat berhasil dikirim ke email mahasiswa.",
                                                "success")
                                            .then(() => window.location.href = historyIndexUrl);
                                        return;
                                    }

                                    Swal.fire(
                                        "Sebagian Berhasil",
                                        (sendRes.message ||
                                            "Pengajuan sudah disetujui, tetapi pengiriman email gagal. Silakan coba tombol 'Kirim Surat ke Mahasiswa' setelah ini."
                                        ),
                                        "warning"
                                    ).then(() => window.location.href = historyIndexUrl);
                                })
                                .catch(() => Swal.fire("Gagal!",
                                    "Terjadi kesalahan jaringan atau server.", "error"));
                        });
                    });
                }

                const btnKirimSurat = document.getElementById('btn-kirim-surat');
                if (btnKirimSurat) {
                    btnKirimSurat.addEventListener('click', function() {
                        Swal.fire({
                            title: "Konfirmasi Kirim Surat",
                            text: "Apakah Anda yakin ingin mengirim surat yang sudah ditandatangani ini ke email mahasiswa?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Kirim!",
                            cancelButtonText: "Batal",
                            customClass: {
                                confirmButton: "btn btn-success",
                                cancelButton: "btn btn-light text-black"
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            showLoading('Memproses pengiriman email...');
                            postJson(sendUrl)
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire("Berhasil!", data.message, "success")
                                            .then(() => window.location.href = historyIndexUrl);
                                        return;
                                    }
                                    Swal.fire("Gagal!", data.message ||
                                        'Terjadi kesalahan saat mengirim email.', "error");
                                })
                                .catch(() => Swal.fire("Gagal!",
                                    'Terjadi kesalahan jaringan atau server.', "error"));
                        });
                    });
                }
            });
        </script>
    @endsection
