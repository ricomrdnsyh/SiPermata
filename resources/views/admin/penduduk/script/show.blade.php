<script>
    function showModal(element) {
        let nidn = $(element).data('nidn');
        let nama = $(element).data('nama');
        let fakultas = $(element).data('fakultas');
        let prodi = $(element).data('prodi');
        let email = $(element).data('email');
        let nohp = $(element).data('nohp');

        $('#show_nidn').val(nidn);
        $('#show_nama_penduduk').val(nama);
        $('#show_nama_fakultas').val(fakultas);
        $('#show_nama_prodi').val(prodi);
        $('#show_email').val(email);
        $('#show_no_hp').val(nohp);

        $('#form_show').modal('show');
    }
</script>
