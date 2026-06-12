<script>
    function showModal(element) {
        let kode = $(element).data('kode');
        let tahun = $(element).data('tahun');

        $('#show_kode_akademik').val(kode);
        $('#show_tahun_akademik').val(tahun);

        $('#form_show').modal('show');
    }
</script>
