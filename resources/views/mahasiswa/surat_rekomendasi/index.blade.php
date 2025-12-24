@extends('layout.main')
@section('title', 'Surat Rekomendasi')
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
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">List Surat Rekomendasi</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <a href="{{ route('mahasiswa.surat-rekomendasi.create') }}"
                                    class="btn btn-sm  btn-primary"><i class="fas fa-plus"></i>Add Pengajuan</a>
                            </div>
                        </div>
                    </div>
                    <div class="separator my-5"></div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="surat-rekomendasi-table">
                            <thead
                                class="table table-sm table-row-dashed table-row-gray-900 align-middle gs-0 gy-2 dt-responsive nowrap"
                                style="width:100%;">
                                <tr class="text-start text-muted fs-sm-8 fs-lg-6 text-uppercase">
                                    <th class="text-center">Actions</th>
                                    <th class="min-w-125px">Tanggal Pengajuan</th>
                                    <th class="min-w-125px">Tahun Akademik</th>
                                    <th class="min-w-125px">Status Pengajuan</th>
                                    <th class="min-w-125px">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800 fw-bolder fs-sm-8 fs-lg-6">
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
            let table = $('#surat-rekomendasi-table').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                pagingType: "simple_numbers",
                ajax: '{{ route('mahasiswa.surat-rekomendasi.data') }}',
                columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'tanggal_pengajuan',
                        name: 'created_at'
                    },
                    {
                        data: 'akademik',
                        name: 'akademik'
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
                    $('#surat-rekomendasi-table [data-bs-toggle="tooltip"]').tooltip();
                }
            });
            table.on('draw', function() {
                $('#surat-rekomendasi-table [data-bs-toggle="tooltip"]').tooltip();
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
