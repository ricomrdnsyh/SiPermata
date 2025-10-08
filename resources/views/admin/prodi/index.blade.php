@extends('layout.main')

@section('title', 'Prodi')

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
    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-fluid">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">List Prodi</span>
                                </h3>
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <!--begin::Add user-->
                                <a href="{{ route('admin.prodi.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-plus"></i> Add Prodi</a>
                                <!--end::Add user-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--begin::Separator-->
                    <div class="separator my-5"></div>
                    <!--end::Separator-->
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="prodi-table">
                            <!--begin::Table head-->
                            <thead class="">
                                <!--begin::Table row-->
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Nama Fakultas</th>
                                    <th class="min-w-125px">Nama Prodi</th>
                                    <th class="min-w-125px">Singkatan Prodi</th>
                                    <th class="min-w-125px">Gelar</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-bold text-gray-800">
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let table = $('#prodi-table').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: '{{ route('admin.prodi.data') }}',
                columns: [{
                        data: 'nama_fakultas',
                        name: 'fakultas.nama_fakultas'
                    },
                    {
                        data: 'nama_prodi',
                        name: 'prodi.nama_prodi'
                    },
                    {
                        data: 'singkatan',
                        name: 'prodi.singkatan'
                    },
                    {
                        data: 'gelar',
                        name: 'prodi.gelar'
                    },
                    {
                        data: 'status',
                        name: 'prodi.status',
                        orderable: false // Jika kolom status tidak perlu diurutkan
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],

                language: {
                    search: "Search :_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                }
            });

            $('#prodi-table').on('click', '.btn-active-light-danger', function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.data('id');

                if (id) {
                    confirmDelete(id);
                } else {
                    console.error('ID tidak ditemukan pada tombol hapus');
                }
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
                        url: '/admin/prodi/' + id,
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
                            $('#prodi-table').DataTable().ajax.reload(null, false);
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
