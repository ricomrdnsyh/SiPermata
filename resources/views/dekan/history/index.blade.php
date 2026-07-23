@extends('layout.main')
@section('title', 'Pengajuan Surat Mahasiswa')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables/buttons.dataTables.min.css') }}">
    <style>
        .table-row-dashed tr {
            border-bottom: 1px dashed #cccccc !important;
        }

        .dataTable thead tr th {
            vertical-align: middle;
            border-bottom: 1px dashed #cccccc !important;
        }

        .dataTable th,
        .dataTable td {
            vertical-align: middle !important;
        }

        .dataTable td.dt-control:before,
        .dataTable th.dt-control:before {
            display: none !important;
            content: "" !important;
        }

        table.dataTable td.dt-control,
        table.dataTable th.dt-control {
            position: relative !important;
            width: 28px !important;
            min-width: 28px !important;
            padding: 0 !important;
            text-align: center !important;
            vertical-align: middle !important;
        }

        table.dataTable.collapsed tbody tr:not(.child) td.dt-control:before,
        table.dataTable.collapsed tbody tr:not(.child) th.dt-control:before {
            display: inline-flex !important;
            content: "+" !important;
            position: absolute !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, calc(-50% + 7px)) !important;
            width: 18px !important;
            height: 18px !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 999px !important;
            color: #fff !important;
            font-weight: 900 !important;
            font-size: 13px !important;
            line-height: 1 !important;
            background: #0d6efd !important;
            box-shadow: 0 0 0 2px #ffffff, 0 2px 6px rgba(0, 0, 0, .18) !important;
        }

        table.dataTable.collapsed tbody tr.parent:not(.child) td.dt-control:before,
        table.dataTable.collapsed tbody tr.parent:not(.child) th.dt-control:before {
            content: "-" !important;
            background: #dc3545 !important;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.child,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.child,
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dataTables_empty {
            cursor: default !important;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.child:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.child:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dataTables_empty:before {
            display: none !important;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
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

        .dt-buttons .btn-export-primary i {
            color: #fff !important;
        }
    </style>
@endsection
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid mt-7">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="card shadow-sm border border-dashed border-dark rounded">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">List Pengajuan</span>
                                    </h3>
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-success fw-bold me-2"
                                    id="btn-bulk-approve-send" disabled>
                                    <i class="fas fa-paper-plane"></i> Terima & Kirim Terpilih (<span
                                        id="selected-count">0</span>)
                                </button>
                                <button type="button" class="btn btn-sm btn-primary fw-bold" id="btn-bulk-send" disabled
                                    style="display: none;">
                                    <i class="fas fa-paper-plane"></i> Kirim Surat Ke Mahasiswa Terpilih (<span
                                        id="selected-count-send">0</span>)
                                </button>
                            </div>
                        </div>
                        <div class="card-body py-4 px-8 filter-container mt-4">
                            <div class="border border-dashed rounded p-5 mb-5" style="border-color: #b5b5c3 !important;">
                                <h5 class="text-primary mb-4"><i class="fas fa-filter text-primary me-2"></i>Filter Data
                                </h5>
                                <div class="row g-5">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <label class="form-label fw-bold mb-2">Program Studi:</label>
                                        <select class="form-select form-select-sm" data-control="select2"
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
                                        <select class="form-select form-select-sm" data-control="select2"
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
                                        <select class="form-select form-select-sm" data-control="select2"
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
                                        <select class="form-select form-select-sm" data-control="select2"
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
                        </div>
                        <div class="card-body pt-0">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="history-table">
                                <thead class="">
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="text-center p-0" style="width:28px; min-width:28px;"></th>
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
                                        <th class="min-w-125px">Status Pengajuan</th>
                                        <th class="min-w-125px">Tanggal Pengajuan</th>
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
    </div>
@endsection
@section('js')

    <script src="{{ asset('assets/plugins/custom/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/custom/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/print.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/responsive.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');
            if (statusParam) {
                $('#filter-status').val(statusParam).trigger('change');
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let selected = new Map();
            const $count = $('#selected-count');
            const $btnApproveSend = $('#btn-bulk-approve-send');
            const $btnSend = $('#btn-bulk-send');

            function refreshUI() {
                const items = Array.from(selected.values());
                const count = items.length;
                const allProses = count > 0 && items.every(x => x.status_raw === 'proses');
                const allDiterima = count > 0 && items.every(x => x.status_raw === 'diterima');

                if (allDiterima) {
                    $btnApproveSend.hide();
                    $btnSend.show().prop('disabled', false);
                    $('#selected-count-send').text(count);
                } else {
                    $btnSend.hide();
                    $btnApproveSend.show().prop('disabled', !allProses);
                    $('#selected-count').text(count);
                }
            }

            function clearSelection() {
                selected.clear();
                $('#select-all').prop('checked', false);
                refreshUI();
            }
            let table = $('#history-table').DataTable({
                processing: false,
                serverSide: true,
                responsive: {
                    details: {
                        type: 'column',
                        target: 0
                    }
                },
                columnDefs: [{
                        targets: 0,
                        className: 'dt-control',
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 1,
                        orderable: false,
                        searchable: false
                    }
                ],
                lengthMenu: [
                    [10, 15, 20, 25],
                    [10, 15, 20, 25]
                ],
                searchHighlight: true,
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'colvis',
                        collectionLayout: 'fixed columns',
                        collectionTitle: 'Pengaturan Kolom',
                        className: 'btn btn-sm btn-primary mt-2 rounded-2',
                        columns: ':not(.noVis)'
                    },
                    {
                        extend: 'csv',
                        titleAttr: 'Csv',
                        title: 'Data SiPermata',
                        action: newexportaction,
                        className: 'btn btn-sm btn-primary mt-2 rounded-2'
                    },
                    {
                        extend: 'excel',
                        titleAttr: 'Excel',
                        title: 'Data SiPermata',
                        action: newexportaction,
                        className: 'btn btn-sm btn-primary mt-2 rounded-2'
                    }
                ],
                ajax: {
                    url: '{{ route('dekan.history.data') }}',
                    data: function(d) {
                        d.prodi_filter = $('#filter-prodi').val();
                        d.nama_surat_filter = $('#filter-nama-surat').val();
                        d.status_filter = $('#filter-status').val();
                        d.tahun_akademik_filter = $('#filter-tahun-akademik').val();
                    }
                },
                columns: [{
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'id_history',
                        name: 'id_history',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const selectable = (row.status_raw === 'proses' || row.status_raw ===
                                'diterima');
                            const disabled = selectable ? '' : 'disabled';
                            return `
                        <div class="form-check form-check-sm form-check-custom form-check-solid d-flex justify-content-center">
                            <input class="form-check-input row-check"
                                   type="checkbox"
                                   value="${data}"
                                   data-status="${row.status_raw}"
                                   data-tabel="${row.tabel_raw}"
                                   data-id-surat="${row.id_tabel_surat_raw}"
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
                        name: 'nama_mahasiswa'
                    },
                    {
                        data: 'prodi',
                        name: 'prodi'
                    },
                    {
                        data: 'nama_surat',
                        name: 'nama_surat'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'tanggal_pengajuan',
                        name: 'created_at'
                    },
                    {
                        data: 'catatan',
                        name: 'catatan'
                    }
                ],
                drawCallback: function() {
                    $('#history-table [data-bs-toggle="tooltip"]').tooltip();
                    $('#history-table .row-check').each(function() {
                        const id = $(this).val();
                        $(this).prop('checked', selected.has(id));
                    });
                    const $checks = $('#history-table .row-check:not(:disabled)');
                    const checkedCount = $checks.filter(':checked').length;
                    $('#select-all').prop('checked', $checks.length > 0 && checkedCount === $checks
                        .length);
                    refreshUI();
                }
            });
            $('[data-filter]').on('change', function() {
                clearSelection();
                table.draw();
            });
            $('#history-table').on('change', '.row-check', function() {
                const id = $(this).val();
                if ($(this).is(':checked')) {
                    selected.set(id, {
                        status_raw: $(this).data('status'),
                        tabel: $(this).data('tabel'),
                        id_surat: $(this).data('id-surat'),
                        id_history: id
                    });
                } else {
                    selected.delete(id);
                }
                const $checks = $('#history-table .row-check:not(:disabled)');
                const checkedCount = $checks.filter(':checked').length;
                $('#select-all').prop('checked', $checks.length > 0 && checkedCount === $checks.length);
                refreshUI();
            });
            $('#select-all').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#history-table .row-check:not(:disabled)').each(function() {
                    const id = $(this).val();
                    $(this).prop('checked', isChecked);
                    if (isChecked) {
                        selected.set(id, {
                            status_raw: $(this).data('status'),
                            tabel: $(this).data('tabel'),
                            id_surat: $(this).data('id-surat'),
                            id_history: id
                        });
                    } else {
                        selected.delete(id);
                    }
                });
                refreshUI();
            });
            $btnApproveSend.on('click', function() {
                const ids = Array.from(selected.keys());
                const items = Array.from(selected.values());

                Swal.fire({
                    title: "Konfirmasi Terima & Kirim",
                    text: `Terima dan kirim ${ids.length} pengajuan terpilih ke email mahasiswa? Ini akan generate surat (TTD+QR).`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Terima & Kirim!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-secondary text-black"
                    }
                }).then((res) => {
                    if (!res.isConfirmed) return;
                    Swal.fire({
                        icon: "info",
                        title: 'Mohon tunggu...',
                        text: 'Memproses persetujuan dan pengiriman email...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch("{{ route('dekan.history.bulkApprove') }}", {
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
                            if (!data.success || data.success_count === 0) {
                                Swal.fire("Gagal!", data.message || "Bulk approve & send gagal.",
                                    "error");
                            } else {
                                Swal.fire("Berhasil!", data.message, "success").then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch(() => Swal.fire("Gagal!", "Terjadi kesalahan server/jaringan.",
                            "error"));
                });
            });
            $btnSend.on('click', function() {
                const items = Array.from(selected.values());
                Swal.fire({
                    title: "Konfirmasi Kirim Surat",
                    text: `Kirim ${items.length} surat terpilih ke email mahasiswa?`,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Kirim!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-secondary text-black"
                    }
                }).then((res) => {
                    if (!res.isConfirmed) return;
                    Swal.fire({
                        icon: "info",
                        title: 'Mohon tunggu...',
                        text: 'Memproses pengiriman email...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    fetch("{{ route('dekan.history.bulkSend') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                items
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success && data.success_count > 0) {
                                Swal.fire("Berhasil!", data.message, "success").then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire("Gagal!", data.message || "Bulk send gagal.",
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
