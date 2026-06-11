@extends('layout.main')
@section('title', 'Pengajuan Mahasiswa')
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
                            <button type="button" class="btn btn-sm btn-success fw-bold" id="btn-bulk-approve" disabled>
                                <i class="fas fa-check-circle"></i> Terima Pengajuan Terpilih (<span
                                    id="selected-count">0</span>)
                            </button>
                        </div>
                    </div>
                    <div class="separator mt-6"></div>
                    <div class="card-body py-4 px-8 filter-container">
                        <div class="row g-5">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label class="form-label fw-bold mb-2">Fakultas:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Semua Fakultas" data-allow-clear="true" data-filter="fakultas"
                                    id="filter-fakultas">
                                    <option value="">Semua Fakultas</option>
                                    @foreach ($listFakultas as $fakultas)
                                        <option value="{{ $fakultas->id_fakultas }}">{{ $fakultas->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label class="form-label fw-bold mb-2">Program Studi:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Pilih Fakultas terlebih dahulu" data-allow-clear="true" data-filter="prodi"
                                    id="filter-prodi" disabled>
                                    <option value="">Semua Prodi</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
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
                            <div class="col-lg-4 col-md-4 col-sm-12">
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
                            <div class="col-lg-4 col-md-4 col-sm-12">
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
                    url: '{{ route('admin.history.data') }}',
                    data: function(d) {
                        d.fakultas_filter = $('#filter-fakultas').val();
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

            $('#filter-fakultas').on('change', function() {
                const fakultasId = $(this).val();
                const $prodi = $('#filter-prodi');

                $prodi.val('').trigger('change.select2');

                if (fakultasId) {

                    $.get('{{ url("admin/prodi-by-fakultas") }}/' + fakultasId, function(data) {
                        $prodi.empty().append('<option value="">Semua Prodi</option>');
                        $.each(data, function(i, item) {
                            $prodi.append('<option value="' + item.id_prodi + '">' + item.nama_prodi + '</option>');
                        });
                        $prodi.prop('disabled', false).trigger('change.select2');
                    });
                } else {

                    $prodi.empty().append('<option value="">Semua Prodi</option>');
                    $prodi.prop('disabled', true).trigger('change.select2');
                }

                clearSelection();
                table.draw();
            });

            $('[data-filter]').not('#filter-fakultas').on('change', function() {
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
