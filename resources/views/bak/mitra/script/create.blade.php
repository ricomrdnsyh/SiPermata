<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form_create_mitra');
        const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
        const inputNama = document.getElementById('create_nama_mitra');
        const errNama = document.getElementById('error-create_nama_mitra');
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function setLoading(on) {
            submitButton.disabled = on;
            submitButton.querySelector('.indicator-label').style.display = on ? 'none' : 'inline-block';
            submitButton.querySelector('.indicator-progress').style.display = on ? 'inline-block' : 'none';
        }

        function clearNamaError() {
            inputNama.classList.remove('is-invalid');
            errNama.style.display = 'none';
            errNama.textContent = '';
        }

        function showNamaError(msg) {
            inputNama.classList.add('is-invalid');
            errNama.textContent = msg;
            errNama.style.display = 'block';
        }

        $('#form_create').on('hidden.bs.modal', function () {
            form.reset();
            clearNamaError();
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearNamaError();

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
                    const msg = data?.errors?.nama_mitra?.[0] || 'Data tidak valid.';
                    showNamaError(msg);
                    inputNama.focus();
                    setLoading(false);
                    return;
                }

                if (!res.ok) {
                    showNamaError('Terjadi kesalahan. Silakan coba lagi.');
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

                $('#mitra-table').DataTable().ajax.reload(null, false);
                setLoading(false);

            } catch (err) {
                showNamaError('Gagal mengirim data. Silakan coba lagi.');
                setLoading(false);
            }
        });
    });
</script>
