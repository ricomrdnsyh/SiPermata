<script>
    $(document).ready(function() {
        let table = $('#jabatan-table').DataTable({
            processing: false,
            serverSide: true,
            order: [],
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
            ajax: '{{ route('admin.jabatan.data') }}',
            columns: [{
                data: null,
                defaultContent: '',
                orderable: false,
                searchable: false
            }, {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    exportable: false
                },
                {
                    data: 'nama_penduduk',
                    name: 'nama_penduduk',
                    searchable: true
                },
                {
                    data: 'nama_fakultas',
                    name: 'fakultas.nama_fakultas'
                },
                {
                    data: 'status',
                    name: 'status'
                }
            ],
            
            drawCallback: function() {
                $('#jabatan-table [data-bs-toggle="tooltip"]').tooltip();
            }
        });
        table.on('draw', function() {
            $('#jabatan-table [data-bs-toggle="tooltip"]').tooltip();
        });
        $('#jabatan-table').on('click', '.btn-active-light-danger', function(e) {
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
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/jabatan/' + id,
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
                        $('#jabatan-table').DataTable().ajax.reload(null, false);
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

    @if ($message = Session::get('success'))
        Swal.fire({
            text: "{{ $message }}",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    @endif
    @if ($message = Session::get('failed'))
        Swal.fire({
            text: "{{ $message }}",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-danger"
            }
        });
    @endif
</script>
