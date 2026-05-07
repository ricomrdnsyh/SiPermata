@extends('layout.main')
@section('title', 'Surat Keterangan Aktif')
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
                                    <span class="card-label fw-bolder fs-3 mb-1">List Surat Keterangan Aktif</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <a href="{{ route('admin.surat-aktif.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-plus"></i>Add Pengajuan</a>
                            </div>
                        </div>
                    </div>
                    <div class="separator mt-6"></div>
                    <div class="card-body py-4 px-8 filter-container">
                        <div class="row g-5">
                            <div class="col-lg-6 col-md-6 col-sm-12">
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
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Program Studi:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Pilih Fakultas terlebih dahulu" data-allow-clear="true" data-filter="prodi"
                                    id="filter-prodi" disabled>
                                    <option value="">Semua Prodi</option>
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
                                            {{ $currentTahunAkademik && $tahunAkademik->id_akademik == $currentTahunAkademik->id_akademik ? 'selected' : '' }}>
                                            {{ $tahunAkademik->tahun_akademik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="surat-aktif-table">
                            <thead class="">
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="text-center">Actions</th>
                                    <th class="min-w-125px">NIM</th>
                                    <th class="min-w-125px">Nama Mahasiswa</th>
                                    <th class="min-w-125px">Program Studi</th>
                                    <th class="min-w-125px">Kategori</th>
                                    <th class="min-w-125px">Tanggal Pengajuan</th>
                                    <th class="min-w-125px">Keperluan Surat</th>
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
            let table = $('#surat-aktif-table').DataTable({
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
                    url: '{{ route('admin.surat-aktif.data') }}',
                    data: function(d) {
                        d.fakultas_filter = $('#filter-fakultas').val();
                        d.prodi_filter = $('#filter-prodi').val();
                        d.status_filter = $('#filter-status').val();
                        d.tahun_akademik_filter = $('#filter-tahun-akademik').val();
                    }
                },
                columns: [{
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
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'tanggal_pengajuan',
                        name: 'created_at'
                    },
                    {
                        data: 'keperluan',
                        name: 'keperluan'
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
                    $('#surat-aktif-table [data-bs-toggle="tooltip"]').tooltip();
                }
            });
            table.on('draw', function() {
                $('#surat-aktif-table [data-bs-toggle="tooltip"]').tooltip();
            });
            // Fakultas change: load prodi options, reset prodi, redraw table
            $('#filter-fakultas').on('change', function() {
                const fakultasId = $(this).val();
                const $prodi = $('#filter-prodi');

                // Reset prodi
                $prodi.val('').trigger('change.select2');

                if (fakultasId) {
                    // Fetch prodi for selected fakultas
                    $.get('{{ url("admin/prodi-by-fakultas") }}/' + fakultasId, function(data) {
                        $prodi.empty().append('<option value="">Semua Prodi</option>');
                        $.each(data, function(i, item) {
                            $prodi.append('<option value="' + item.id_prodi + '">' + item.nama_prodi + '</option>');
                        });
                        $prodi.prop('disabled', false).trigger('change.select2');
                    });
                } else {
                    // Semua Fakultas: disable prodi
                    $prodi.empty().append('<option value="">Semua Prodi</option>');
                    $prodi.prop('disabled', true).trigger('change.select2');
                }

                table.draw();
            });

            // Other filters: just redraw table
            $('[data-filter]').not('#filter-fakultas').on('change', function() {
                table.draw();
            });
        });
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
