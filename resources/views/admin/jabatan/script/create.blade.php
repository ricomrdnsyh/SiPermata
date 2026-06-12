<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formCreate = document.getElementById('form_create_jabatan');
        const submitButtonCreate = formCreate.querySelector('[data-kt-contacts-type="submit"]');

        formCreate.addEventListener('submit', function(e) {
            let statusRadio = formCreate.querySelectorAll('input[name="status"]');
            let isStatusChecked = false;
            statusRadio.forEach(function(radio) {
                if (radio.checked) isStatusChecked = true;
            });
            
            let radioFeedback = formCreate.querySelector('.invalid-feedback-radio');
            if(!isStatusChecked) {
                radioFeedback.style.display = 'block';
            } else {
                radioFeedback.style.display = 'none';
            }

            if (!formCreate.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                formCreate.classList.add('was-validated');
                return;
            }

            submitButtonCreate.disabled = true;
            submitButtonCreate.querySelector('.indicator-label').style.display = 'none';
            submitButtonCreate.querySelector('.indicator-progress').style.display = 'inline-block';
        });
        
        document.getElementById('form_create').addEventListener('hidden.bs.modal', function () {
            formCreate.classList.remove('was-validated');
            formCreate.reset();
            $('#create_penduduk_id').val('').trigger('change');
            formCreate.querySelector('.invalid-feedback-radio').style.display = 'none';
            
            submitButtonCreate.disabled = false;
            submitButtonCreate.querySelector('.indicator-label').style.display = 'inline-block';
            submitButtonCreate.querySelector('.indicator-progress').style.display = 'none';
        });
    });
</script>
