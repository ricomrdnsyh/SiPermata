<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_create_ttd');
        const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const inputs = {
            template_id: document.getElementById('create_template_id'),
            nama_ttd: document.getElementById('create_nama_ttd'),
            nidn: document.getElementById('create_nidn'),
            fakultas_id: document.getElementById('create_fakultas_id')
            // status is radio, so no single input element to highlight
        };

        const errors = {
            template_id: document.getElementById('error-create_template_id'),
            nama_ttd: document.getElementById('error-create_nama_ttd'),
            nidn: document.getElementById('error-create_nidn'),
            fakultas_id: document.getElementById('error-create_fakultas_id'),
            status: document.getElementById('error-create_status')
        };

        function setLoading(on) {
            submitButton.disabled = on;
            submitButton.querySelector('.indicator-label').style.display = on ? 'none' : 'inline-block';
            submitButton.querySelector('.indicator-progress').style.display = on ? 'inline-block' : 'none';
        }

        function clearErrors() {
            Object.keys(inputs).forEach(key => {
                if (inputs[key]) inputs[key].classList.remove('is-invalid');
                if (errors[key]) {
                    errors[key].style.display = 'none';
                    errors[key].textContent = '';
                }
            });
        }

        function showErrors(errorData) {
            Object.keys(errorData).forEach(key => {
                if (inputs[key]) inputs[key].classList.add('is-invalid');
            if (errors[key]) {
                errors[key].textContent = errorData[key][0];
                errors[key].style.display = 'block';
                if (!errors[key].classList.contains('d-block')) {
                    errors[key].classList.add('d-block');
                }
            }
            });
        }

        $('#form_create').on('hidden.bs.modal', function () {
            form.reset();
            clearErrors();
            $(inputs.template_id).val(null).trigger('change');
            $(inputs.fakultas_id).val(null).trigger('change');
            document.querySelectorAll('input[name="status"]').forEach(r => r.checked = false);
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            setLoading(true);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: new FormData(form),
                    credentials: 'same-origin',
                });

                const ct = res.headers.get('content-type') || '';
                const data = ct.includes('application/json') ? await res.json() : null;

                if (res.status === 422) {
                    showErrors(data.errors);
                    setLoading(false);
                    return;
                }

                if (!res.ok) {
                    Swal.fire("Error!", "Terjadi kesalahan. Silakan coba lagi.", "error");
                    setLoading(false);
                    return;
                }

                $('#form_create').modal('hide');
                
                Swal.fire({
                    text: "Data berhasil ditambahkan!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

                $('#ttdsurat-table').DataTable().ajax.reload(null, false);
                setLoading(false);

            } catch (err) {
                Swal.fire("Error!", "Gagal mengirim data. Silakan coba lagi.", "error");
                setLoading(false);
            }
        });
    });
</script>
