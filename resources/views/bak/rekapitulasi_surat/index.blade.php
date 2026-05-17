@extends('layout.main')
@section('title', 'Rekapitulasi Surat')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables1/datatables.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/custom/datatables1/datatables.min.css') }}" type="text/css" />
    <style>
        .table-row-dashed tr { border-bottom: 1px dashed #cccccc !important; }
        #rekap-table thead tr th { vertical-align: middle; border-bottom: 1px dashed #cccccc !important; }
        .filter-container { margin-bottom: 2rem; padding-bottom: 0 !important; }
        .btn-export { border-radius: 8px; font-weight: 600; }
    </style>
@endsection
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">

                {{-- Summary Cards --}}
                @php
                    $gradients = [
                        ['bg' => 'linear-gradient(135deg, #6366f1 0%, #818cf8 100%)', 'icon' => 'fas fa-file-alt'],
                        ['bg' => 'linear-gradient(135deg, #22c55e 0%, #4ade80 100%)', 'icon' => 'fas fa-envelope-open-text'],
                        ['bg' => 'linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%)', 'icon' => 'fas fa-envelope-open-text'],
                        ['bg' => 'linear-gradient(135deg, #f59e0b 0%, #fb923c 100%)', 'icon' => 'fas fa-envelope-open-text'],
                        ['bg' => 'linear-gradient(135deg, #ef4444 0%, #f87171 100%)', 'icon' => 'fas fa-envelope-open-text'],
                        ['bg' => 'linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%)', 'icon' => 'fas fa-envelope-open-text'],
                        ['bg' => 'linear-gradient(135deg, #334155 0%, #475569 100%)', 'icon' => 'fas fa-envelope-open-text'],
                    ];
                @endphp
                <div class="row g-4 g-xl-6 mb-7">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-flush h-md-100 hover-elevate-up text-white"
                            style="background: {{ $gradients[0]['bg'] }};">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="symbol symbol-38px">
                                        <span class="symbol-label bg-white bg-opacity-20">
                                            <i class="{{ $gradients[0]['icon'] }} text-white"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="pt-6">
                                    <div class="fw-semibold fs-2x text-white">{{ number_format($totalSelesai) }}</div>
                                    <div class="fw-semibold text-white text-opacity-75 mt-1">Total Surat Selesai</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $gi = 1; @endphp
                    @foreach ($breakdownSurat as $nama => $count)
                        @if ($count > 0)
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-flush h-md-100 hover-elevate-up text-white"
                                style="background: {{ $gradients[$gi % count($gradients)]['bg'] }};">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="symbol symbol-38px">
                                            <span class="symbol-label bg-white bg-opacity-20">
                                                <i class="{{ $gradients[$gi % count($gradients)]['icon'] }} text-white"></i>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="pt-6">
                                        <div class="fw-semibold fs-2x text-white">{{ number_format($count) }}</div>
                                        <div class="fw-semibold text-white text-opacity-75 mt-1">{{ $nama }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php $gi++; @endphp
                        @endif
                    @endforeach
                </div>

                {{-- Main Card --}}
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">Rekapitulasi Surat Fakultas {{ $namaFakultas }}</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Hanya menampilkan surat dengan status <span class="badge bg-primary">Selesai</span></span>
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
                    <div class="separator mt-6"></div>
                    <div class="card-body py-4 px-8 filter-container">
                        <div class="row g-5">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Program Studi:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Semua Prodi" data-allow-clear="true" data-filter="prodi" id="filter-prodi">
                                    <option value="">Semua Prodi</option>
                                    @foreach ($listProdi as $prodi)
                                        <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Jenis Surat:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Semua Surat" data-allow-clear="true" data-filter="nama_surat" id="filter-nama-surat">
                                    <option value="">Semua Surat</option>
                                    @foreach ($listNamaSurat as $tabel => $nama)
                                        <option value="{{ $tabel }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="form-label fw-bold mb-2">Tahun Akademik:</label>
                                <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                    data-placeholder="Pilih Tahun Akademik" data-allow-clear="true" data-filter="tahun_akademik" id="filter-tahun-akademik">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($listTahunAkademik as $ta)
                                        <option value="{{ $ta->id_akademik }}" {{ $ta->tahun_akademik == $currentTahunAkademik ? 'selected' : '' }}>
                                            {{ $ta->tahun_akademik }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="rekap-table">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="text-center" style="width:50px;">No</th>
                                        <th class="min-w-125px">Nama Mahasiswa</th>
                                        <th class="min-w-100px">NIM</th>
                                        <th class="min-w-125px">Program Studi</th>
                                        <th class="min-w-150px">Jenis Surat</th>
                                        <th class="min-w-150px">No. Surat</th>
                                        <th class="min-w-100px">Tanggal</th>
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
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/datatables1/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let table = $('#rekap-table').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                pagingType: "simple_numbers",
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                dom: '<"row align-items-center"<"col-md-6"l>>' +
                     '<"row mb-4 mt-2"<"col-md-6 d-flex justify-content-start">' +
                     '<"col-md-6 d-flex justify-content-end"f>>' +
                     'rt' +
                     '<"row"<"col-sm-5"i><"col-sm-7 d-flex justify-content-end"p>>',
                ajax: {
                    url: '{{ route("bak.rekapitulasi.data") }}',
                    data: function(d) {
                        d.prodi_filter = $('#filter-prodi').val();
                        d.nama_surat_filter = $('#filter-nama-surat').val();
                        d.tahun_akademik_filter = $('#filter-tahun-akademik').val();
                    }
                },
                columns: [
                    { data: null, name: 'no', orderable: false, searchable: false, className: 'text-center',
                      render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }
                    },
                    { data: 'nama_mahasiswa', name: 'nama_mahasiswa', searchable: true },
                    { data: 'nim', name: 'nim', searchable: true },
                    { data: 'prodi', name: 'prodi', searchable: true },
                    { data: 'nama_surat', name: 'nama_surat', searchable: true },
                    { data: 'no_surat', name: 'no_surat', searchable: false },
                    { data: 'tanggal', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    search: "Search :_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: { previous: "Previous", next: "Next" }
                },
                drawCallback: function() {
                    $('#rekap-table [data-bs-toggle="tooltip"]').tooltip();
                }
            });

            $('[data-filter]').on('change', function() { table.draw(); });

            function getFilterParams() {
                return new URLSearchParams({
                    prodi_filter: $('#filter-prodi').val() || '',
                    nama_surat_filter: $('#filter-nama-surat').val() || '',
                    tahun_akademik_filter: $('#filter-tahun-akademik').val() || ''
                }).toString();
            }

            $('#btn-export-excel').on('click', function(e) {
                e.preventDefault();
                window.location.href = '{{ route("bak.rekapitulasi.exportExcel") }}?' + getFilterParams();
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
                    customClass: { confirmButton: 'btn btn-primary', cancelButton: 'btn btn-secondary text-black' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("bak.rekapitulasi.downloadBulk") }}?' + getFilterParams();
                    }
                });
            });
        });
    </script>
@endsection
