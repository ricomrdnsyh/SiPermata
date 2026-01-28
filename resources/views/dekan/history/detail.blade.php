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
                                                    <th class="min-w-125px">Tanggal Pengajuan</th>
                                                    <th class="min-w-125px">Status</th>
                                                    <th class="min-w-200px">Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-800 fw-bolder fs-sm-8 fs-lg-6">
                                                <tr>
                                                    <td>{{ $pengajuan->nama_surat }}</td>
                                                    <td>
                                                        {{ $pengajuan->created_at?->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') }}
                                                    </td>
                                                    <td>
                                                        @switch($pengajuan->status)
                                                            @case('pengajuan')
                                                                <span class="badge bg-warning">Menunggu BAK</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge bg-info">Menunggu Dekan</span>
                                                            @break

                                                            @case('diterima')
                                                                <span class="badge bg-success">Disetujui</span>
                                                            @break

                                                            @case('selesai')
                                                                <span class="badge bg-primary">Selesai</span>
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
                                <div class="mb-10">
                                    <h5 class="mb-4">Detail Surat</h5>
                                    <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            @if ($pengajuan->tabel === 'surat_aktif')
                                                @include('dekan.history.partials.surat_aktif', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_izin_penelitian')
                                                @include('dekan.history.partials.surat_penelitian', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_rekomendasi')
                                                @include('dekan.history.partials.surat_rekomendasi', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_pkl')
                                                @include('dekan.history.partials.surat_pkl', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_observasi')
                                                @include('dekan.history.partials.surat_observasi', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_keterangan_lulus')
                                                @include('dekan.history.partials.surat_lulus', [
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
                                    @if ($pengajuan->status === 'proses')
                                        <button type="button" class="btn btn-sm btn-danger me-3" id="btn-reject-main">
                                            <i class="fas fa-times"></i> Tolak Pengajuan
                                        </button>
                                        <a href="{{ route('dekan.surat.lampiran_preview', ['tabel' => $pengajuan->tabel, 'id' => $pengajuan->id_tabel_surat]) }}"
                                            class="btn btn-sm btn-primary" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-eye"></i> Periksa Surat
                                        </a>
                                        {{-- <button type="button" class="btn btn-sm btn-success ms-3" id="btn-approve-main">
                                            <i class="fas fa-check-circle"></i>
                                            Terima Pengajuan
                                        </button> --}}
                                        <button type="button" class="btn btn-sm btn-success ms-3" id="btn-approve-send">
                                            <i class="fas fa-paper-plane"></i>
                                            Terima & Kirim Surat ke Mahasiswa
                                        </button>
                                    @elseif($pengajuan->status === 'diterima')
                                        @if (isset($fileGeneratedPath) && $fileGeneratedPath && $pengajuan->id_tabel_surat)
                                            <a href="{{ route('dekan.surat.view', [
                                                'tabel' => $pengajuan->tabel,
                                                'id' => $pengajuan->id_tabel_surat,
                                            ]) }}"
                                                class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-file-alt"></i> Lihat Surat
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success ms-3" id="btn-kirim-surat">
                                                <i class="fas fa-paper-plane"></i> Kirim Surat ke Mahasiswa
                                            </button>
                                        @endif
                                    @elseif($pengajuan->status === 'selesai')
                                        @if (isset($fileGeneratedPath) && $fileGeneratedPath && $pengajuan->id_tabel_surat)
                                            <a href="{{ route('dekan.surat.view', [
                                                'tabel' => $pengajuan->tabel,
                                                'id' => $pengajuan->id_tabel_surat,
                                            ]) }}"
                                                class="btn btn-sm btn-primary" target="_blank" rel="noopener noreferrer">
                                                <i class="fas fa-file-alt"></i> Lihat Surat
                                            </a>
                                        @endif
                                    @elseif($pengajuan->status === 'pengajuan')
                                        <button class="btn btn-sm  btn-warning">
                                            Menunggu BAK untuk validasi
                                        </button>
                                    @else
                                        <button class="btn btn-sm  btn-success">
                                            <i class="fas fa-check-circle"></i>
                                            Pengajuan sudah dikonfirmasi
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
                                                <th class="min-w-100px">Pelaku</th>
                                                <th class="min-w-200px">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-800 fw-bolder fs-sm-8 fs-lg-6">
                                            @forelse($pengajuan->statusLogs ?? [] as $log)
                                                <tr>
                                                    <td>
                                                        {{ $log->created_at?->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—' }}
                                                    </td>
                                                    <td>
                                                        @switch($log->status)
                                                            @case('pengajuan')
                                                                <span class="badge bg-warning">Menunggu BAK</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge bg-info">Menunggu Dekan</span>
                                                            @break

                                                            @case('diterima')
                                                                <span class="badge bg-success">Disetujui</span>
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
                                                    <td>{{ $log->user_role ?? '-' }}</td>
                                                    <td>{{ $log->catatan ?: '-' }}</td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-muted text-center">
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
        <div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectReasonModalLabel">Catatan Penolakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejectReason" class="required form-label">Catatan Penolakan</label>
                            <textarea class="form-control form-control-sm" name="catatan" id="rejectReason" rows="4" required
                                placeholder="Jelaskan alasan penolakan secara rinci..."></textarea>
                            <div id="rejectError" class="text-danger mt-2" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-danger" id="btn-submit-reject" data-stage="">
                            <span class="indicator-label">Tolak Pengajuan</span>
                            <span class="indicator-progress" style="display: none;">
                                Memproses...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <form id="approveForm" method="POST" action="{{ route('dekan.history.approve', $pengajuan->id_history) }}"
            style="display: none;">
            @csrf
        </form>
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
                    }).then(() => {
                        window.location.href = historyIndexUrl;
                    });
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

                const btnApproveMain = document.getElementById('btn-approve-main');
                if (btnApproveMain) {
                    btnApproveMain.addEventListener('click', function() {
                        Swal.fire({
                            title: "Konfirmasi Persetujuan",
                            text: "Apakah Anda yakin ingin menyetujui pengajuan ini?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Setujui!",
                            cancelButtonText: "Batal",
                            customClass: {
                                confirmButton: "btn btn-success",
                                cancelButton: "btn btn-secondary"
                            }
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            showLoading('Memproses persetujuan...');
                            postJson(approveUrl)
                                .then(data => {
                                    if (data.success) return showSuccessRedirect(data.message);
                                    showError(data.message || 'Terjadi kesalahan saat menyetujui.');
                                })
                                .catch(() => showError('Terjadi kesalahan saat menyetujui.'));
                        });
                    });
                }

                const btnRejectMain = document.getElementById('btn-reject-main');
                if (btnRejectMain) {
                    btnRejectMain.addEventListener('click', function() {
                        const reasonEl = document.getElementById('rejectReason');
                        const errEl = document.getElementById('rejectError');
                        if (reasonEl) reasonEl.value = '';
                        if (errEl) errEl.style.display = 'none';

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

                        const submitBtn = this;
                        const label = submitBtn.querySelector('.indicator-label');
                        const progress = submitBtn.querySelector('.indicator-progress');
                        if (label) label.style.display = 'none';
                        if (progress) progress.style.display = 'inline-block';

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
                                if (label) label.style.display = 'inline';
                                if (progress) progress.style.display = 'none';
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
                            }
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            showLoading('Memproses pengiriman email...');
                            postJson(sendUrl)
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire("Berhasil!", data.message, "success").then(() => {
                                            window.location.href = historyIndexUrl;
                                        });
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
                            }
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
                                        Swal.fire(
                                            "Berhasil!",
                                            "Pengajuan disetujui dan surat berhasil dikirim ke email mahasiswa.",
                                            "success"
                                        ).then(() => window.location.href = historyIndexUrl);
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
            });
        </script>

    @endsection
