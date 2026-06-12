<script>
    function showModal(element) {
        let nama = $(element).data('nama');
        let jenis = $(element).data('jenis');
        let fakultas = $(element).data('fakultas');
        let tgl = $(element).data('tgl');
        let fileExists = $(element).data('file');
        let downloadUrl = $(element).data('download');

        if (tgl) {
            let date = new Date(tgl);
            let options = { day: 'numeric', month: 'long', year: 'numeric' };
            tgl = date.toLocaleDateString('id-ID', options);
        } else {
            tgl = '—';
        }

        $('#show_nama_template').val(nama);
        $('#show_jenis_surat').val(jenis);
        $('#show_fakultas').val(fakultas);
        $('#show_tgl_sk').val(tgl);
        
        if (fileExists && downloadUrl) {
            let fileName = jenis ? jenis + '.docx' : 'file_template.docx';
            $('#show_file_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center" style="background-color: var(--bs-gray-200);">
                    <i class="fas fa-file-word text-primary me-2"></i>
                    <a href="${downloadUrl}" target="_blank" class="text-dark text-hover-primary text-decoration-none">${fileName}</a>
                </div>
            `);
        } else {
            $('#show_file_container').html(`
                <div class="form-control form-control-sm d-flex align-items-center text-muted" style="background-color: var(--bs-gray-200);">
                    <i class="fas fa-file-excel me-2"></i>
                    <span>File Tidak Tersedia</span>
                </div>
            `);
        }

        $('#form_show').modal('show');
    }
</script>
