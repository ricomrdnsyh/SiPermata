<script>
    function editModal(btn) {
        let id = $(btn).data('id');
        let penduduk_id = $(btn).data('penduduk_id');
        let status = $(btn).data('status');
        
        let formAction = '{{ url('admin/jabatan') }}/' + id;
        $('#form_edit_jabatan').attr('action', formAction);
        
        $('#edit_penduduk_id').val(penduduk_id).trigger('change');
        
        if (status == 'BAK') {
            $('#edit_BAK').prop('checked', true);
        } else if (status == 'DEKAN') {
            $('#edit_DEKAN').prop('checked', true);
        }
        
        $('#form_edit').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const formEdit = document.getElementById('form_edit_jabatan');
        const submitButtonEdit = formEdit.querySelector('[data-kt-contacts-type="submit"]');

        formEdit.addEventListener('submit', function(e) {
            let statusRadio = formEdit.querySelectorAll('input[name="status"]');
            let isStatusChecked = false;
            statusRadio.forEach(function(radio) {
                if (radio.checked) isStatusChecked = true;
            });
            
            let radioFeedback = formEdit.querySelector('.invalid-feedback-radio-edit');
            if(!isStatusChecked) {
                radioFeedback.style.display = 'block';
            } else {
                radioFeedback.style.display = 'none';
            }

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
            formEdit.querySelector('.invalid-feedback-radio-edit').style.display = 'none';
            
            submitButtonEdit.disabled = false;
            submitButtonEdit.querySelector('.indicator-label').style.display = 'inline-block';
            submitButtonEdit.querySelector('.indicator-progress').style.display = 'none';
        });
    });
</script>
