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
                                        <button type="button" class="btn btn-sm btn-light-danger" id="btn-reject-main">
                                            Tolak Pengajuan
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-success" id="btn-approve-main">
                                            Terima Pengajuan
                                        </button>
                                    @else
                                        <span class="badge bg-primary">Pengajuan sudah dikonfirmasi</span>
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
                                                    <td>{{ $pengajuan->created_at?->format('d M Y H:i') }}</td>
                                                    <td>
                                                        @switch($pengajuan->status)
                                                            @case('pengajuan')
                                                                <span class="badge bg-warning">Menunggu Persetujuan</span>
                                                            @break

                                                            @case('proses')
                                                                <span class="badge bg-info">Diproses Dekan</span>
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
                                            <span class="fw-bold text-gray-600 text-hover-primary">
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

    <!-- Modal Alasan Penolakan -->
    <div class="modal fade" id="rejectReasonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Catatan Penolakan</h5> <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"> <label class="required form-label">Catatan Penolakan</span></label>
                        <textarea class="form-control"name="catatan" id="rejectReason" rows="3" required
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                        <div id="rejectError" class="text-danger mt-2" style="display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer"> <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button> <button type="button" class="btn btn-danger"
                        id="btn-submit-reject"> <span class="indicator-label">Tolak Pengajuan</span> <span
                            class="indicator-progress" style="display: none;"> Memproses... <span
                                class="spinner-border spinner-border-sm align-middle ms-2"></span> </span> </button> </div>
            </div>
        </div>
    </div>

    <form id="approveForm" method="POST" action="{{ route('bak.history.approve', $pengajuan->id_history) }}"
        style="display: none;">
        @csrf
    </form>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pengajuanId = {{ $pengajuan->id_history }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // === 1. Klik Terima Pengajuan ===
            document.getElementById('btn-approve-main').addEventListener('click', function() {
                Swal.fire({
                    title: "Konfirmasi Persetujuan",
                    text: "Apakah Anda yakin ingin Menyetujui pengajuan ini?",
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
                        // Tampilkan loading
                        Swal.fire({
                            text: 'Memproses persetujuan...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Kirim request
                        fetch("{{ route('bak.history.approve', ':id') }}".replace(':id',
                                pengajuanId), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        text: data.message,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        text: data.message ||
                                            'Terjadi kesalahan saat menyetujui.',
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-danger"
                                        }
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    text: 'Terjadi kesalahan saat menyetujui.',
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-danger"
                                    }
                                });
                            });
                    }
                });
            });

            // === 2. Klik Tolak Pengajuan ===
            document.getElementById('btn-reject-main').addEventListener('click', function() {
                // Reset modal
                document.getElementById('rejectReason').value = '';
                document.getElementById('rejectError').style.display = 'none';

                // Tampilkan modal
                const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));
                rejectModal.show();
            });

            // === 3. Submit Penolakan ===
            document.getElementById('btn-submit-reject').addEventListener('click', function() {
                const reason = document.getElementById('rejectReason').value.trim();
                const errorDiv = document.getElementById('rejectError');

                if (!reason) {
                    errorDiv.textContent = 'Catatan penolakan wajib diisi.';
                    errorDiv.style.display = 'block';
                    return;
                }

                errorDiv.style.display = 'none';

                // Tampilkan loading di button
                const submitBtn = this;
                const label = submitBtn.querySelector('.indicator-label');
                const progress = submitBtn.querySelector('.indicator-progress');
                label.style.display = 'none';
                progress.style.display = 'inline-block';

                // Kirim request
                fetch("{{ route('bak.history.reject', ':id') }}".replace(':id', pengajuanId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            catatan: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Sembunyikan modal
                        const rejectModal = bootstrap.Modal.getInstance(document.getElementById(
                            'rejectReasonModal'));
                        rejectModal.hide();

                        if (data.success) {
                            Swal.fire({
                                text: data.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                text: data.message || 'Terjadi kesalahan saat menolak.',
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
                            });
                        }
                    })
                    .catch(error => {
                        // Sembunyikan modal
                        const rejectModal = bootstrap.Modal.getInstance(document.getElementById(
                            'rejectReasonModal'));
                        rejectModal.hide();

                        Swal.fire({
                            text: 'Terjadi kesalahan saat menolak.',
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-danger"
                            }
                        });
                    })
                    .finally(() => {
                        // Reset button
                        label.style.display = 'inline';
                        progress.style.display = 'none';
                    });
            });
        });
    </script>
@endsection
