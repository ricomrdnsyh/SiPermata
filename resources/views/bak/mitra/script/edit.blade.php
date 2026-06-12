<script>
    function editModal(element) {
        let id = $(element).data('id');
        let nama = $(element).data('nama');

        $('#edit_nama_mitra').val(nama);
        $('#error-edit_nama_mitra').hide().text('');
        $('#edit_nama_mitra').removeClass('is-invalid');

        // Setup the action URL
        let actionUrl = "{{ route('bak.mitra.update', ':id') }}".replace(':id', id);
        $('#form_edit_mitra').attr('action', actionUrl);

        $('#form_edit').modal('show');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form_edit_mitra');
        if (!form) return;

        const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
        const inputNama = document.getElementById('edit_nama_mitra');
        const errNama = document.getElementById('error-edit_nama_mitra');

        const meta = document.querySelector('meta[name="csrf-token"]');
        const csrf = meta?.getAttribute('content') || form.querySelector('input[name="_token"]')?.value || '';

        const label = submitButton.querySelector('.indicator-label');
        const progress = submitButton.querySelector('.indicator-progress');

        function setLoading(on) {
            submitButton.disabled = on;
            if (label) label.style.display = on ? 'none' : 'inline-block';
            if (progress) progress.style.display = on ? 'inline-block' : 'none';
        }

        function clearError() {
            if (inputNama) inputNama.classList.remove('is-invalid');
            if (errNama) {
                errNama.style.display = 'none';
                errNama.textContent = '';
            }
        }

        function showError(msg) {
            if (inputNama) inputNama.classList.add('is-invalid');
            if (errNama) {
                errNama.textContent = msg;
                errNama.style.display = 'block';
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearError();

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
                        ...(csrf ? {
                            'X-CSRF-TOKEN': csrf
                        } : {}),
                    },
                    body: new FormData(form),
                    credentials: 'same-origin',
                    redirect: 'follow',
                });

                const ct = res.headers.get('content-type') || '';
                const isJson = ct.includes('application/json');
                const data = isJson ? await res.json() : null;

                if (res.status === 422) {
                    const msg = data?.errors?.nama_mitra?.[0] || 'Data tidak valid.';
                    showError(msg);
                    inputNama?.focus();
                    setLoading(false);
                    return;
                }

                if (!res.ok) {
                    showError('Terjadi kesalahan. Silakan coba lagi.');
                    setLoading(false);
                    return;
                }

                $('#form_edit').modal('hide');
                
                Swal.fire({
                    text: "Data berhasil diupdate!",
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
                showError('Gagal mengirim data. Silakan coba lagi.');
                setLoading(false);
            }
        });
    });
</script>
