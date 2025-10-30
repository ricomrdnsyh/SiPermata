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
                                <div class="card-toolbar gap-3">
                                    @if ($pengajuan->status === 'pengajuan')
                                        <button type="button" class="btn btn-sm btn-light-danger" data-action="reject"
                                            data-stage="bak" id="btn-reject-main">
                                            Tolak Pengajuan(BAK)
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-success" data-action="approve"
                                            data-stage="bak" id="btn-approve-main"><i class="fas fa-check-circle"></i>
                                            Terima Pengajuan(BAK)
                                        </button>
                                    @elseif ($pengajuan->status === 'proses')
                                        <button type="button" class="btn btn-sm btn-light-danger" data-action="reject"
                                            data-stage="dekan" id="btn-reject-dekan">
                                            Tolak Pengajuan(Dekan)
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-success" data-action="approve"
                                            data-stage="dekan" id="btn-approve-dekan"><i class="fas fa-check-circle"></i>
                                            Terima Pengajuan(Dekan)
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-check-circle"></i> Pengajuan
                                            {{ $pengajuan->status === 'diterima' ? 'Disetujui' : 'Ditolak' }}
                                        </button>
                                    @endif
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

                                                            @case('selesai')
                                                                <span class="badge bg-success">Selesai</span>
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
                                                @include('admin.history.partials.surat_aktif', [
                                                    'surat' => $surat,
                                                ])
                                            @elseif($pengajuan->tabel === 'surat_lulus')
                                                @include('admin.history.partials.surat_lulus', [
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

    <!-- Modal Penolakan -->
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
                        <textarea class="form-control" name="catatan" id="rejectReason" rows="4" required
                            placeholder="Jelaskan alasan penolakan secara rinci...">
                    </textarea>
                        <div id="rejectError" class="text-danger mt-2" style="display: none;"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btn-submit-reject" data-stage="">
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
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pengajuanId = {{ $pengajuan->id_history }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const actionUrl = "{{ route('admin.history.action', ['id' => $pengajuan->id_history]) }}";

            const approveButtons = document.querySelectorAll('[data-action="approve"]');
            const rejectButtons = document.querySelectorAll('[data-action="reject"]');

            // HANDLER APPROVE
            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const stage = this.getAttribute('data-stage');
                    const action = 'approve';
                    const confirmText = stage === 'bak' ?
                        "Apakah Anda yakin ingin menyetujui (BAK)? Status akan menjadi Proses." :
                        "Apakah Anda yakin ingin menyetujui (Dekan)? Status akan menjadi Diterima.";

                    Swal.fire({
                        title: "Konfirmasi Persetujuan " + stage.toUpperCase(),
                        text: confirmText,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Ya, Setujui!",
                        cancelButtonText: "Batal",
                        customClass: {
                            confirmButton: "btn btn-success",
                            cancelButton: "btn btn-light text-black"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            performAction(action, stage);
                        }
                    });
                });
            });

            // HANDLER REJECT
            rejectButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const stage = this.getAttribute('data-stage');

                    document.getElementById('btn-submit-reject').setAttribute('data-stage', stage);
                    document.getElementById('rejectReasonModalLabel').textContent =
                        `Catatan Penolakan (${stage.toUpperCase()})`;

                    const rejectModal = new bootstrap.Modal(document.getElementById(
                        'rejectReasonModal'));
                    rejectModal.show();
                });
            });

            // SUBMIT PENOLAKAN MODAL
            document.getElementById('btn-submit-reject').addEventListener('click', function() {
                const reason = document.getElementById('rejectReason').value.trim();
                const errorDiv = document.getElementById('rejectError');
                const stage = this.getAttribute('data-stage');

                if (!reason) {
                    errorDiv.textContent = 'Catatan penolakan wajib diisi.';
                    errorDiv.style.display = 'block';
                    return;
                }

                errorDiv.style.display = 'none';
                performAction('reject', stage, reason);
            });

            function performAction(action, stage, reason = null) {

                Swal.fire({
                    text: `Memproses ${action === 'approve' ? 'persetujuan' : 'penolakan'}...`,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const payload = {
                    _token: csrfToken,
                    action: action,
                    stage: stage,
                    catatan: reason
                };

                fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => {
                        const rejectModalEl = document.getElementById('rejectReasonModal');
                        if (rejectModalEl) {
                            const rejectModal = bootstrap.Modal.getInstance(rejectModalEl);
                            if (rejectModal) rejectModal.hide();
                        }

                        Swal.fire({
                            text: data.message,
                            icon: data.success ? "success" : "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: data.success ? "btn btn-primary" : "btn btn-danger"
                            }
                        }).then(() => {
                            if (data.success) {
                                window.location.reload();
                            }
                        });
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Terjadi kesalahan koneksi.", "error");
                    });
            }
        });
    </script>
@endsection
