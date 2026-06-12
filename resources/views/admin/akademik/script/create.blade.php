<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formCreate = document.getElementById('form_create_akademik');
        const submitButtonCreate = formCreate.querySelector('[data-kt-contacts-type="submit"]');

        formCreate.addEventListener('submit', function(e) {
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
            submitButtonCreate.disabled = false;
            submitButtonCreate.querySelector('.indicator-label').style.display = 'inline-block';
            submitButtonCreate.querySelector('.indicator-progress').style.display = 'none';
        });
    });
</script>
