<script>
    function editModal(element) {
        let id = $(element).data('id');
        let nama = $(element).data('nama');
        let nidn = $(element).data('nidn');
        let status = $(element).data('status');
        let template = $(element).data('template');
        let fakultas = $(element).data('fakultas');

        $('#edit_nama_ttd').val(nama);
        $('#edit_nidn').val(nidn);
        if (status === 'aktif') {
            $('#edit_status_aktif').prop('checked', true);
        } else if (status === 'nonaktif') {
            $('#edit_status_nonaktif').prop('checked', true);
        }

        $('#edit_template_id').val(template).trigger('change.select2');
        $('#edit_fakultas_id').val(fakultas).trigger('change.select2');

        $('#form_edit_ttd').find('.is-invalid').removeClass('is-invalid');
        $('#form_edit_ttd').find('.invalid-feedback').hide().text('');

        // Setup the action URL
        let actionUrl = "{{ route('bak.ttdSurat.update', ':id') }}".replace(':id', id);
        $('#form_edit_ttd').attr('action', actionUrl);

        $('#form_edit').modal('show');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form_edit_ttd');
        if (!form) return;

        const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const inputs = {
            template_id: document.getElementById('edit_template_id'),
            nama_ttd: document.getElementById('edit_nama_ttd'),
            nidn: document.getElementById('edit_nidn'),
            fakultas_id: document.getElementById('edit_fakultas_id')
            // status is radio
        };

        const errors = {
            template_id: document.getElementById('error-edit_template_id'),
            nama_ttd: document.getElementById('error-edit_nama_ttd'),
            nidn: document.getElementById('error-edit_nidn'),
            fakultas_id: document.getElementById('error-edit_fakultas_id'),
            status: document.getElementById('error-edit_status')
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

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            setLoading(true);

            try {
                const res = await fetch(form.action, {
                    method: 'POST', // Method override is already @method('PUT') in form
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: new FormData(form),
                    credentials: 'same-origin',
                });

                const ct = res.headers.get('content-type') || '';
                const isJson = ct.includes('application/json');
                const data = isJson ? await res.json() : null;

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

                $('#ttdsurat-table').DataTable().ajax.reload(null, false);
                setLoading(false);

            } catch (err) {
                Swal.fire("Error!", "Gagal mengirim data. Silakan coba lagi.", "error");
                setLoading(false);
            }
        });
    });
</script>
