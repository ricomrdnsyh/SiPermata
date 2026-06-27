<script>
    $(document).ready(function() {
        let table = $('#template-table').DataTable({
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
                url: '{{ route('admin.template.data') }}',
                data: function(d) {
                    d.fakultas_filter = $('#filter-fakultas').val();
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
                }, {
                    data: 'nama_template',
                    name: 'nama_template'
                },
                {
                    data: 'jenis_surat',
                    name: 'jenis_surat',
                },
                {
                    data: 'file',
                    name: 'file',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_fakultas',
                    name: 'fakultas.nama_fakultas'
                },
                {
                    data: 'tgl_sk',
                    name: 'tgl_sk'
                }
            ],
            
            drawCallback: function() {
                $('#template-table [data-bs-toggle="tooltip"]').tooltip();
            }
        });
        table.on('draw', function() {
            $('#template-table [data-bs-toggle="tooltip"]').tooltip();
        });

        $('#filter-fakultas').on('change', function() {
            table.draw();
        });

        $('#template-table').on('click', '.btn-active-light-danger', function(e) {
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
                    url: '/admin/template/' + id,
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
                            text: response.success || response.message || "Data berhasil dihapus!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        $('#template-table').DataTable().ajax.reload(null, false);
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
