@extends('layout.main')
@section('title', 'Data Mahasiswa Lulusan')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables1/datatables.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables1/datatables.min.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .table-row-dashed tr {
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
                                    <span class="card-label fw-bolder fs-3 mb-1">Data Mahasiswa Lulusan</span>
                                    <span class="text-muted mt-1 fw-semibold fs-7">Kelola daftar mahasiswa yang dapat mengajukan Surat Keterangan Lulus</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end gap-2" data-kt-customer-table-toolbar="base">
                                <a href="{{ route('admin.eligible-lulus.template') }}" class="btn btn-sm btn-info">
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="eligible-lulus-table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="text-center" style="width: 60px;">Aksi</th>
                                    <th class="min-w-100px">NIM</th>
                                    <th class="min-w-150px">Nama Mahasiswa</th>
                                    <th class="min-w-125px">Fakultas</th>
                                    <th class="min-w-125px">Program Studi</th>
                                    <th class="min-w-125px">Tahun Akademik</th>
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

    {{-- Modal Tambah Manual --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('admin.eligible-lulus.store') }}" method="POST">
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

    {{-- Modal Import Excel --}}
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('admin.eligible-lulus.import') }}" method="POST" enctype="multipart/form-data">
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
                                    <a href="{{ route('admin.eligible-lulus.template') }}" class="fw-bold text-primary">Download template di sini</a>.
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

@endsection

@section('js')
    <script>
        window.originaljQuery = window.jQuery;
        window.original$ = window.$;
    </script>
    
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.min.js') }}"></script>
    
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
                        title: 'Data Mahasiswa Lulusan - SiPermata',
                        className: 'btn btn-sm me-2 rounded-2 btn-export-primary fw-bold'
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Data Mahasiswa Lulusan - SiPermata',
                        className: 'btn btn-sm rounded-2 btn-export-primary fw-bold'
                    }
                ],
                ajax: {
                    url: '{{ route("admin.eligible-lulus.data") }}',
                    data: function(d) {
                        d.fakultas_filter = $('#filter-fakultas').val();
                        d.prodi_filter = $('#filter-prodi').val();
                        d.tahun_akademik_filter = $('#filter-tahun-akademik').val();
                    }
                },
                columns: [{
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
                        data: 'tahun_akademik',
                        name: 'tahun_akademik'
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
                    $('#eligible-lulus-table [data-bs-toggle="tooltip"]').tooltip();
                }
            });

            table.on('draw', function() {
                $('#eligible-lulus-table [data-bs-toggle="tooltip"]').tooltip();
            });

            // Fakultas change: load prodi options, reset prodi, redraw table
            $('#filter-fakultas').on('change', function() {
                const fakultasId = $(this).val();
                const $prodi = $('#filter-prodi');

                // Reset prodi select
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

                table.draw();
            });

            $('[data-filter]').not('#filter-fakultas').on('change', function() {
                table.draw();
            });

            // Initialize Select2 in modals when shown to ensure correct width and parent overlay behavior
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

            // Handle tambah form submit loading
            $('#modalTambah form').on('submit', function() {
                let btn = $('#btn-tambah-submit');
                btn.prop('disabled', true);
                btn.find('.indicator-label').hide();
                btn.find('.indicator-progress').show();
            });

            // Handle import form submit loading
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
                    cancelButton: 'btn btn-light text-black'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.eligible-lulus.index") }}/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            Swal.fire({
                                text: 'Mohon tunggu sebentar...',
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
