<script>
    function showModal(element) {
        let nama = $(element).data('nama');
        $('#show_nama_mitra').val(nama);
        $('#form_show').modal('show');
    }
</script>
