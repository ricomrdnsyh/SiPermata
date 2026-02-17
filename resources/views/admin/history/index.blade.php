@extends('layout.main')
@section('title', 'Pengajuan Mahasiswa')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables1/datatables.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables1/datatables.min.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .table-row-dashed tr {
            border-bottom: 1px dashed #cccccc !important;
        }

        #users-table thead tr th {
            vertical-align: middle;
            border-bottom: 1px dashed #cccccc !important;
        }

        .filter-container {
            margin-bottom: 2rem;
            padding-bottom: 0 !important;
        }

        .dt-buttons .btn-export-primary,
        .dt-buttons .btn-export-primary:focus,
        .dt-buttons .btn-export-primary:hover,
        .dt-buttons .btn-export-primary:active {
            background: #004289 !important;
            border-color: #004289 !important;
            color: #fff !important;
        }

        .dt-buttons .btn-export-primary:focus {
            box-shadow: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">List Pengajuan</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-sm btn-success fw-bold" id="btn-bulk-approve" disabled>
                                <i class="fas fa-check-circle"></i> Terima Pengajuan Terpilih (<span
                                    id="selected-count">0</span>)
                            </button>
                        </div>
                    </div>
                    <div class="separator mt-6"></div>
                    <div class="card-body py-4 px-8 filter-container">
                        <div class="row g-5">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Program Studi:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Semua Prodi" data-allow-clear="true" data-filter="prodi"
                                    id="filter-prodi">
                                    <option value="">Semua Prodi</option>
                                    @foreach ($listProdi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Nama Surat:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Semua Surat" data-allow-clear="true" data-filter="nama_surat"
                                    id="filter-nama-surat">
                                    <option value="">Semua Surat</option>
                                    @foreach ($listNamaSurat as $tabel => $nama)
                                        <option value="{{ $tabel }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Status:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Semua Status" data-allow-clear="true" data-filter="status"
                                    id="filter-status">
                                    <option value="">Semua Status</option>
                                    <option value="pengajuan">Menunggu BAK</option>
                                    <option value="proses">Menunggu Dekan</option>
                                    <option value="diterima">Disetujui</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Tahun Akademik:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Pilih Tahun Akademik" data-allow-clear="true"
                                    data-filter="tahun_akademik" id="filter-tahun-akademik">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($listTahunAkademik as $tahunAkademik)
                                        <option value="{{ $tahunAkademik->id_akademik }}"
                                            {{ $tahunAkademik->tahun_akademik == $currentTahunAkademik ? 'selected' : '' }}>
                                            {{ $tahunAkademik->tahun_akademik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="history-table">
                            <thead class="">
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="text-center" style="width:40px;">
                                        <div
                                            class="form-check form-check-sm form-check-custom form-check-solid d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th class="text-center">Actions</th>
                                    <th class="min-w-125px">NIM</th>
                                    <th class="min-w-125px">Nama Mahasiswa</th>
                                    <th class="min-w-125px">Program Studi</th>
                                    <th class="min-w-125px">Nama Surat Pengajuan</th>
                                    <th class="min-w-125px">Tanggal Pengajuan</th>
                                    <th class="min-w-125px">Status Pengajuan</th>
                                    <th class="min-w-125px">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-800">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let selectedIds = new Set();

            function refreshBulkUI() {
                const count = selectedIds.size;
                $('#selected-count').text(count);
                $('#btn-bulk-approve').prop('disabled', count === 0);
            }

            function clearSelection() {
                selectedIds.clear();
                $('#select-all').prop('checked', false);
                refreshBulkUI();
            }
            let table = $('#history-table').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                pagingType: "simple_numbers",
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All']
                ],
                dom: '<"row align-items-center"<"col-md-6"l>>' +
                    '<"row mb-4 mt-2"<"col-md-6 d-flex justify-content-start"B><"col-md-6 d-flex justify-content-end"f>>' +
                    'rt' +
                    '<"row"<"col-sm-5"i><"col-sm-7 d-flex justify-content-end"p>>',
                buttons: [{
                        extend: 'colvis',
                        text: 'Column Visibility',
                        className: 'btn btn-sm me-2 rounded-2 btn-export-primary fw-bold'
                    }, {
                        extend: 'excelHtml5',
                        title: 'SiPermata Universitas Nurul Jadid',
                        className: 'btn btn-sm me-2 rounded-2 btn-export-primary fw-bold'
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'SiPermata Universitas Nurul Jadid',
                        className: 'btn btn-sm rounded-2 btn-export-primary fw-bold'
                    }
                ],
                ajax: {
                    url: '{{ route('admin.history.data') }}',
                    data: function(d) {
                        d.prodi_filter = $('#filter-prodi').val();
                        d.nama_surat_filter = $('#filter-nama-surat').val();
                        d.status_filter = $('#filter-status').val();
                        d.tahun_akademik_filter = $('#filter-tahun-akademik').val();
                    }
                },
                columns: [{
                        data: 'id_history',
                        name: 'id_history',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const disabled = (row.status_raw !== 'pengajuan') ? 'disabled' : '';
                            return `
                        <div class="form-check form-check-sm form-check-custom form-check-solid d-flex justify-content-center">
                            <input class="form-check-input row-check"
                                   type="checkbox"
                                   value="${data}"
                                   data-status="${row.status_raw}"
                                   ${disabled}>
                        </div>
                    `;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        exportable: false
                    },
                    {
                        data: 'nim',
                        name: 'nim',
                        searchable: true
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'nama_mahasiswa',
                        searchable: true
                    },
                    {
                        data: 'prodi',
                        name: 'prodi',
                        searchable: true
                    },
                    {
                        data: 'nama_surat',
                        name: 'nama_surat',
                        searchable: true
                    },
                    {
                        data: 'tanggal_pengajuan',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'catatan',
                        name: 'catatan'
                    }
                ],
                language: {
                    search: "Search :_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                drawCallback: function() {
                    $('#history-table [data-bs-toggle="tooltip"]').tooltip();
                    $('#history-table .row-check').each(function() {
                        const id = $(this).val();
                        $(this).prop('checked', selectedIds.has(id));
                    });
                    const $checks = $('#history-table .row-check:not(:disabled)');
                    const checkedCount = $checks.filter(':checked').length;
                    $('#select-all').prop('checked', $checks.length > 0 && checkedCount === $checks
                        .length);
                    refreshBulkUI();
                }
            });
            $('[data-filter]').on('change', function() {
                clearSelection();
                table.draw();
            });
            $('#history-table').on('change', '.row-check', function() {
                const id = $(this).val();
                if ($(this).is(':checked')) selectedIds.add(id);
                else selectedIds.delete(id);
                const $checks = $('#history-table .row-check:not(:disabled)');
                const checkedCount = $checks.filter(':checked').length;
                $('#select-all').prop('checked', $checks.length > 0 && checkedCount === $checks.length);
                refreshBulkUI();
            });
            $('#select-all').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#history-table .row-check:not(:disabled)').each(function() {
                    const id = $(this).val();
                    $(this).prop('checked', isChecked);
                    if (isChecked) selectedIds.add(id);
                    else selectedIds.delete(id);
                });
                refreshBulkUI();
            });
            $('#btn-bulk-approve').on('click', function() {
                const ids = Array.from(selectedIds);
                Swal.fire({
                    title: "Konfirmasi Pengajuan",
                    text: `Terima ${ids.length} pengajuan terpilih?`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Terima Pengajuan!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-secondary text-black"
                    }
                }).then((result) => {
                    if (!result.isConfirmed) return;
                    Swal.fire({
                        icon: "info",
                        title: 'Mohon tunggu...',
                        text: 'Memproses pengajuan...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    fetch("{{ route('admin.history.bulkApprove') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                ids
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire("Berhasil!", data.message, "success").then(() => {
                                    clearSelection();
                                    table.ajax.reload(null, false);
                                });
                            } else {
                                Swal.fire("Gagal!", data.message || "Bulk approve gagal.",
                                    "error");
                            }
                        })
                        .catch(() => Swal.fire("Gagal!", "Terjadi kesalahan server/jaringan.",
                            "error"));
                });
            });
        });
    </script>
@endsection
