<script>
    function showModal(element) {
        let nama = $(element).data('nama');
        let singkatan = $(element).data('singkatan');
        let status = $(element).data('status');

        $('#show_nama_fakultas').val(nama);
        $('#show_singkatan').val(singkatan);
        if (status.toLowerCase() === 'aktif') {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge text-white bg-success">Aktif</span>
                </div>
            `);
        } else if (status.toLowerCase() === 'nonaktif') {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge text-white bg-danger">Nonaktif</span>
                </div>
            `);
        } else {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge text-white bg-secondary">${status}</span>
                </div>
            `);
        }

        $('#form_show').modal('show');
    }
</script>
