<script>
    function showModal(element) {
        let nama = $(element).data('nama');
        let nidn = $(element).data('nidn');
        let status = $(element).data('status');
        let template = $(element).data('template');
        let fakultas = $(element).data('fakultas');

        $('#show_nama_ttd').val(nama);
        $('#show_nidn').val(nidn);

        if (status.toLowerCase() === 'aktif') {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge badge-light-success">Aktif</span>
                </div>
            `);
        } else if (status.toLowerCase() === 'nonaktif') {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge badge-light-danger">Nonaktif</span>
                </div>
            `);
        } else {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge badge-light-secondary">${status}</span>
                </div>
            `);
        }
        $('#show_template').val(template);
        $('#show_fakultas').val(fakultas);

        $('#form_show').modal('show');
    }
</script>
