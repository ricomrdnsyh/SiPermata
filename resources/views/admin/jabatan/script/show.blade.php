<script>
    function showModal(btn) {
        let nama = $(btn).data('nama');
        let fakultas = $(btn).data('fakultas');
        let status = $(btn).data('status');
        
        $('#show_nama_penduduk').val(nama);
        $('#show_nama_fakultas').val(fakultas);
        
        if (status.toUpperCase() === 'BAK') {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge text-white bg-warning">BAK</span>
                </div>
            `);
        } else if (status.toUpperCase() === 'DEKAN') {
            $('#show_status_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <span class="badge text-white bg-primary">Dekan</span>
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
