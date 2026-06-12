@extends('layout.main')
@section('title', 'Data Mahasiswa Lulusan')
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
                                    <span class="card-label fw-bolder fs-3 mb-1">Data Mahasiswa Lulusan</span>
                                    <span class="text-muted mt-1 fw-semibold fs-7">Kelola daftar mahasiswa yang dapat mengajukan Surat Keterangan Lulus</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">
                                <a href="{{ route('bak.eligible-lulus.template') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-download me-1"></i>Template Excel
                                </a>
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#modalImport">
                                    <i class="fas fa-file-excel me-1"></i>Import Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambah">
                                    <i class="fas fa-plus me-1"></i>Tambah Manual
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-4 px-8 filter-container mt-4">
                        <div class="border border-dashed rounded p-5 mb-5" style="border-color: #b5b5c3 !important;">
                            <h5 class="text-primary mb-4"><i class="fas fa-filter text-primary me-2"></i>Filter Data</h5>
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
                                    <label class="form-label fw-bold mb-2">Tahun Akademik:</label>
                                    <select class="form-select form-select-sm" data-control="select2"
                                        data-placeholder="Pilih Tahun Akademik" data-allow-clear="true"
                                        data-filter="tahun_akademik" id="filter-tahun-akademik">
                                        <option value="">Semua Tahun</option>
                                        @foreach ($listTahunAkademik as $tahunAkademik)
                                            <option value="{{ $tahunAkademik->id_akademik }}"
                                                {{ $currentTahunAkademik && $tahunAkademik->id_akademik == $currentTahunAkademik->id_akademik ? 'selected' : '' }}>
                                                {{ $tahunAkademik->tahun_akademik }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="eligible-lulus-table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="text-center p-0" style="width:28px; min-width:28px;"></th>
                                    <th class="text-center" style="width: 60px;">Aksi</th>
                                    <th class="min-w-100px">NIM</th>
                                    <th class="min-w-150px">Nama Mahasiswa</th>
                                    <th class="min-w-125px">Program Studi</th>
                                    <th class="min-w-125px">Tahun Akademik</th>
                                    <th class="min-w-200px">Judul Penelitian</th>
                                    <th class="min-w-125px">Ditambahkan Oleh</th>
                                    <th class="min-w-125px">Tanggal Ditambahkan</th>
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

        <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('bak.eligible-lulus.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder" id="modalTambahLabel">Tambah Mahasiswa Lulusan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="fv-row mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Mahasiswa (NIM)</label>
                            <select name="nim" class="form-select form-select-sm" data-control="select2"
                                data-placeholder="Pilih Mahasiswa" data-dropdown-parent="#modalTambah" required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach ($mahasiswa as $mhs)
                                    <option value="{{ $mhs->nim }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                            <select name="akademik_id" class="form-select form-select-sm" data-control="select2"
                                data-placeholder="Pilih Tahun Akademik" data-dropdown-parent="#modalTambah" required>
                                <option value="">Pilih Tahun Akademik</option>
                                @foreach ($listTahunAkademik as $tahunAkademik)
                                    <option value="{{ $tahunAkademik->id_akademik }}"
                                        {{ $currentTahunAkademik && $tahunAkademik->id_akademik == $currentTahunAkademik->id_akademik ? 'selected' : '' }}>
                                        {{ $tahunAkademik->tahun_akademik }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-4">
                            <label class="fw-semibold fs-6 mb-2">Judul Penelitian</label>
                            <textarea name="judul_penelitian" class="form-control form-control-sm" rows="3" placeholder="Masukkan Judul Penelitian (Opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary" id="btn-tambah-submit">
                            <span class="indicator-label">
                                <i class="fas fa-save me-1"></i>Simpan
                            </span>
                            <span class="indicator-progress" style="display: none;">
                                Memproses...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('bak.eligible-lulus.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bolder" id="modalImportLabel">Import Data Mahasiswa Lulusan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="notice d-flex bg-light-info rounded border-info border border-dashed p-4 mb-4">
                            <i class="fas fa-info-circle fs-3 text-info me-3 mt-1"></i>
                            <div class="d-flex flex-column">
                                <span class="fs-7 text-gray-700">
                                    File Excel harus memiliki kolom header <strong>nim</strong> dan <strong>nama</strong> di baris pertama.
                                    <a href="{{ route('bak.eligible-lulus.template') }}" class="fw-bold text-primary">Download template di sini</a>.
                                </span>
                            </div>
                        </div>
                        <div class="fv-row mb-4">
                            <label class="required fw-semibold fs-6 mb-2">Tahun Akademik</label>
                            <select name="akademik_id" class="form-select form-select-sm" data-control="select2"
                                data-placeholder="Pilih Tahun Akademik" data-dropdown-parent="#modalImport" required>
                                <option value="">Pilih Tahun Akademik</option>
                                @foreach ($listTahunAkademik as $tahunAkademik)
                                    <option value="{{ $tahunAkademik->id_akademik }}"
                                        {{ $currentTahunAkademik && $tahunAkademik->id_akademik == $currentTahunAkademik->id_akademik ? 'selected' : '' }}>
                                        {{ $tahunAkademik->tahun_akademik }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row mb-4">
                            <label class="required fw-semibold fs-6 mb-2">File Excel (.xlsx / .xls)</label>
                            <input type="file" name="file" class="form-control form-control-sm"
                                accept=".xlsx,.xls" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-success" id="btn-import-submit">
                            <span class="indicator-label">
                                <i class="fas fa-file-import me-1"></i>Import
                            </span>
                            <span class="indicator-progress" style="display: none;">
                                Memproses...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
    <script>
        window.originaljQuery = window.jQuery;
        window.original$ = window.$;
    </script>
    
    
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
        if (window.originaljQuery && window.jQuery !== window.originaljQuery) {
            for (var prop in window.jQuery.fn) {
                if (window.jQuery.fn.hasOwnProperty(prop) && !window.originaljQuery.fn[prop]) {
                    window.originaljQuery.fn[prop] = window.jQuery.fn[prop];
                }
            }
            for (var prop in window.jQuery) {
                if (window.jQuery.hasOwnProperty(prop) && !window.originaljQuery[prop]) {
                    window.originaljQuery[prop] = window.jQuery[prop];
                }
            }
            window.jQuery = window.originaljQuery;
            window.$ = window.original$;
        }
    </script>
    <script>
        $(document).ready(function() {
            let table = $('#eligible-lulus-table').DataTable({
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
                    url: '{{ route("bak.eligible-lulus.data") }}',
                    data: function(d) {
                        d.prodi_filter = $('#filter-prodi').val();
                        d.tahun_akademik_filter = $('#filter-tahun-akademik').val();
                    }
                },
                columns: [{
                    data: null,
                    defaultContent: '',
                    orderable: false,
                    searchable: false
                }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                        data: 'tahun_akademik',
                        name: 'tahun_akademik'
                    },
                    {
                        data: 'judul_penelitian',
                        name: 'judul_penelitian'
                    },
                    {
                        data: 'ditambahkan_oleh',
                        name: 'ditambahkan_oleh'
                    },
                    {
                        data: 'tanggal_ditambahkan',
                        name: 'created_at'
                    }
                ],
                
                drawCallback: function() {
                    $('#eligible-lulus-table [data-bs-toggle="tooltip"]').tooltip();
                }
            });

            table.on('draw', function() {
                $('#eligible-lulus-table [data-bs-toggle="tooltip"]').tooltip();
            });

            $('[data-filter]').on('change', function() {
                table.draw();
            });

            $('#modalTambah').on('shown.bs.modal', function () {
                $('#modalTambah select[data-control="select2"]').each(function () {
                    $(this).select2({
                        dropdownParent: $('#modalTambah'),
                        width: '100%'
                    });
                });
            });

            $('#modalImport').on('shown.bs.modal', function () {
                $('#modalImport select[data-control="select2"]').each(function () {
                    $(this).select2({
                        dropdownParent: $('#modalImport'),
                        width: '100%'
                    });
                });
            });

            $('#modalTambah form').on('submit', function() {
                let btn = $('#btn-tambah-submit');
                btn.prop('disabled', true);
                btn.find('.indicator-label').hide();
                btn.find('.indicator-progress').show();
            });

            $('#modalImport form').on('submit', function() {
                let btn = $('#btn-import-submit');
                btn.prop('disabled', true);
                btn.find('.indicator-label').hide();
                btn.find('.indicator-progress').show();
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data akan dihapus permanen.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("bak.eligible-lulus.index") }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Tunggu Sebentar..',
                                icon: 'info',
                                text: 'Sedang memproses...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        },
                        success: function(response) {
                            Swal.fire({
                                text: response.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                            $('#eligible-lulus-table').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            let errorMessage = "Terjadi kesalahan saat menghapus data.";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire("Error!", errorMessage, "error");
                        }
                    });
                }
            });
        }
    </script>

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        </script>
    @endif
    @if ($message = Session::get('failed'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });
        </script>
    @endif
@endsection
