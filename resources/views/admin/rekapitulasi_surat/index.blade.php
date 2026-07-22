@extends('layout.main')
@section('title', 'Rekapitulasi Surat')
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

                    @php
                        $suratIcons = [
                            'Surat Keterangan Aktif' => 'fas fa-file-alt',
                            'Surat Izin Penelitian' => 'fas fa-flask',
                            'Surat Permohonan Observasi' => 'fas fa-eye',
                            'Surat Rekomendasi' => 'fas fa-thumbs-up',
                            'Surat Permohonan PKL' => 'fas fa-briefcase',
                            'Surat Keterangan Lulus' => 'fas fa-graduation-cap',
                        ];
                    @endphp
                    <div class="row g-5 g-xl-8 mb-7">
                        @foreach ($breakdownSurat as $nama => $count)
                            @php
                                $icon = $suratIcons[$nama] ?? 'fas fa-file';
                            @endphp
                            <div class="col-xl-4 col-md-6">
                                <div class="card h-100 border border-dashed border-primary shadow-sm hover-elevate-up">
                                    <div class="card-body p-6">
                                        <div class="d-flex align-items-center mb-5">
                                            <div class="symbol symbol-50px me-4">
                                                <div class="symbol-label bg-light-primary rounded-3">
                                                    <i class="{{ $icon }} text-primary fs-2"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-900 fw-bolder fs-4 mb-1">{{ $nama }}</span>
                                                <span class="text-gray-500 fw-semibold fs-7">Total disetujui</span>
                                            </div>
                                        </div>
                                        <div class="border border-dashed border-gray-300 rounded p-4">
                                            <div class="d-flex flex-stack mb-0">
                                                <span class="text-gray-600 fw-bold fs-6">Total Surat Selesai</span>
                                                <span class="text-primary fw-bolder fs-3">{{ number_format($count) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card shadow-sm border border-dashed border-dark rounded">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Rekapitulasi Surat Admin</span>
                                    <span class="text-muted mt-1 fw-semibold fs-7">Menampilkan surat dari seluruh fakultas
                                        dengan status <span class="badge text-white bg-primary">Selesai</span></span>
                                </h3>
                            </div>
                            <div class="card-toolbar gap-2">
                                <a href="#" id="btn-export-excel" class="btn btn-sm btn-success btn-export">
                                    <i class="fas fa-file-excel me-1"></i> Export Excel
                                </a>
                                <a href="#" id="btn-download-bulk" class="btn btn-sm btn-primary btn-export">
                                    <i class="fas fa-download me-1"></i> Download Semua PDF (ZIP)
                                </a>
                            </div>
                        </div>
                        <div class="card-body py-4 px-8 filter-container mt-4">
                            <div class="border border-dashed rounded p-5 mb-5" style="border-color: #b5b5c3 !important;">
                                <h5 class="text-primary mb-4"><i class="fas fa-filter text-primary me-2"></i>Filter Data
                                </h5>
                                <div class="row g-5">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label class="form-label fw-bold mb-2">Fakultas:</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            data-placeholder="Semua Fakultas" data-allow-clear="true" data-filter="fakultas"
                                            id="filter-fakultas">
                                            <option value="">Semua Fakultas</option>
                                            @foreach ($listFakultas as $fak)
                                                <option value="{{ $fak->id_fakultas }}">{{ $fak->nama_fakultas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
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
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label class="form-label fw-bold mb-2">Jenis Surat:</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            data-placeholder="Semua Surat" data-allow-clear="true" data-filter="nama_surat"
                                            id="filter-nama-surat">
                                            <option value="">Semua Surat</option>
                                            @foreach ($listNamaSurat as $tabel => $nama)
                                                <option value="{{ $tabel }}">{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label class="form-label fw-bold mb-2">Tahun Akademik:</label>
                                        <select class="form-select form-select-sm" data-control="select2"
                                            data-placeholder="Pilih Tahun Akademik" data-allow-clear="true"
                                            data-filter="tahun_akademik" id="filter-tahun-akademik">
                                            <option value="">Semua Tahun</option>
                                            @foreach ($listTahunAkademik as $ta)
                                                <option value="{{ $ta->id_akademik }}"
                                                    {{ $ta->tahun_akademik == $currentTahunAkademik ? 'selected' : '' }}>
                                                    {{ $ta->tahun_akademik }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="rekap-table">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="text-center p-0" style="width:28px; min-width:28px;"></th>
                                            <th class="text-center" style="width:50px;">No</th>
                                            <th class="min-w-125px">Nama Mahasiswa</th>
                                            <th class="min-w-100px">NIM</th>
                                            <th class="min-w-125px">Fakultas</th>
                                            <th class="min-w-125px">Program Studi</th>
                                            <th class="min-w-150px">Jenis Surat</th>
                                            <th class="min-w-150px">No. Surat</th>
                                            <th class="min-w-100px">Tanggal Pengajuan</th>
                                            <th class="text-center" style="width:80px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-800"></tbody>
                                </table>
                            </div>
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

            $('#filter-fakultas').on('change', function() {
                let fakultasId = $(this).val();
                let $prodi = $('#filter-prodi');
                $prodi.empty().append('<option value="">Semua Prodi</option>');

                if (fakultasId) {
                    $.get('{{ route('admin.prodi.byFakultas', ':id') }}'.replace(':id', fakultasId),
                        function(data) {
                            data.forEach(function(p) {
                                $prodi.append(
                                    `<option value="${p.id_prodi}">${p.nama_prodi}</option>`
                                );
                            });
                            $prodi.trigger('change.select2');
                        });
                } else {
                    @foreach ($listProdi as $prodi)
                        $prodi.append(
                            '<option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>'
                        );
                    @endforeach
                    $prodi.trigger('change.select2');
                }
            });

            let table = $('#rekap-table').DataTable({
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
                dom: 'lfrtip',
                ajax: {
                    url: '{{ route('admin.rekapitulasi.data') }}',
                    data: function(d) {
                        d.fakultas_filter = $('#filter-fakultas').val();
                        d.prodi_filter = $('#filter-prodi').val();
                        d.nama_surat_filter = $('#filter-nama-surat').val();
                        d.tahun_akademik_filter = $('#filter-tahun-akademik').val();
                    }
                },
                columns: [{
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: null,
                        name: 'no',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'nama_mahasiswa',
                        name: 'nama_mahasiswa',
                        searchable: true
                    },
                    {
                        data: 'nim',
                        name: 'nim',
                        searchable: true
                    },
                    {
                        data: 'fakultas',
                        name: 'fakultas',
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
                        data: 'no_surat',
                        name: 'no_surat',
                        searchable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],

                drawCallback: function() {
                    $('#rekap-table [data-bs-toggle="tooltip"]').tooltip();
                }
            });

            $('[data-filter]').on('change', function() {
                table.draw();
            });

            function getFilterParams() {
                return new URLSearchParams({
                    fakultas_filter: $('#filter-fakultas').val() || '',
                    prodi_filter: $('#filter-prodi').val() || '',
                    nama_surat_filter: $('#filter-nama-surat').val() || '',
                    tahun_akademik_filter: $('#filter-tahun-akademik').val() || ''
                }).toString();
            }

            $('#btn-export-excel').on('click', function(e) {
                e.preventDefault();
                window.location.href = '{{ route('admin.rekapitulasi.exportExcel') }}?' +
                    getFilterParams();
            });

            $('#btn-download-bulk').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Download Semua PDF?',
                    text: 'Semua file surat yang selesai akan di-download dalam format ZIP.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Download!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-secondary text-black'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('admin.rekapitulasi.downloadBulk') }}?' +
                            getFilterParams();
                    }
                });
            });
        });
    </script>
@endsection
