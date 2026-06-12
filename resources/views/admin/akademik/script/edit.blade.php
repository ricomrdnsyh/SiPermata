<script>
    function editModal(element) {
        let id = $(element).data('id');
        let kode = $(element).data('kode');
        let tahun = $(element).data('tahun');

        $('#edit_kode_akademik').val(kode);
        $('#edit_tahun_akademik').val(tahun);

        let form = document.getElementById('form_edit_akademik');
        form.action = '/admin/akademik/' + id;

        $('#form_edit').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const formEdit = document.getElementById('form_edit_akademik');
        const submitButtonEdit = formEdit.querySelector('[data-kt-contacts-type="submit"]');

        formEdit.addEventListener('submit', function(e) {
            if (!formEdit.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                formEdit.classList.add('was-validated');
                return;
            }

            submitButtonEdit.disabled = true;
            submitButtonEdit.querySelector('.indicator-label').style.display = 'none';
            submitButtonEdit.querySelector('.indicator-progress').style.display = 'inline-block';
        });

        document.getElementById('form_edit').addEventListener('hidden.bs.modal', function () {
            formEdit.classList.remove('was-validated');
            submitButtonEdit.disabled = false;
            submitButtonEdit.querySelector('.indicator-label').style.display = 'inline-block';
            submitButtonEdit.querySelector('.indicator-progress').style.display = 'none';
        });
    });
</script>
