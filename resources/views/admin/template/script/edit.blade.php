<script>
    let editTglSkPicker;

    document.addEventListener('DOMContentLoaded', function() {
        const tglEl = document.getElementById('edit_tgl_sk');
        editTglSkPicker = flatpickr(tglEl, {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            altInputClass: "form-control form-control-sm",
            allowInput: true,
            onReady: function(selectedDates, dateStr, instance) {
                if (instance.altInput) {
                    instance.altInput.placeholder = "Pilih tanggal SK";
                }
            }
        });
    });



    function editModal(element) {
        let id = $(element).data('id');
        let nama = $(element).data('nama');
        let jenis = $(element).data('jenis');
        let fakultas = $(element).data('fakultas');
        let tgl = $(element).data('tgl');
        let fileExists = $(element).data('file');
        let downloadUrl = $(element).data('download');

        $('#edit_nama_template').val(nama);
        $('#edit_jenis_surat').val(jenis);
        $('#edit_fakultas_id').val(fakultas).trigger('change.select2');

        if (tgl && tgl !== '') {
            editTglSkPicker.setDate(tgl);
        } else {
            editTglSkPicker.clear();
        }

        // Reset file input and errors
        $('#edit_file').val('');
        $('#form_edit_template').find('.is-invalid').removeClass('is-invalid');
        $('#form_edit_template').find('.invalid-feedback').hide().text('');

        if (fileExists && downloadUrl) {
            $('#edit_file_download').attr('href', downloadUrl);
            $('#edit_file_download_container').show();
        } else {
            $('#edit_file_download_container').hide();
            $('#edit_file_download').attr('href', '#');
        }

        // Setup the action URL
        let actionUrl = "{{ route('admin.template.update', ':id') }}".replace(':id', id);
        $('#form_edit_template').attr('action', actionUrl);

        $('#form_edit').modal('show');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form_edit_template');
        if (!form) return;

        const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const inputs = {
            nama_template: document.getElementById('edit_nama_template'),
            jenis_surat: document.getElementById('edit_jenis_surat'),
            file: document.getElementById('edit_file'),
            fakultas_id: document.getElementById('edit_fakultas_id'),
            tgl_sk: document.getElementById('edit_tgl_sk')
        };

        const errors = {
            nama_template: document.getElementById('error-edit_nama_template'),
            jenis_surat: document.getElementById('error-edit_jenis_surat'),
            file: document.getElementById('error-edit_file'),
            fakultas_id: document.getElementById('error-edit_fakultas_id'),
            tgl_sk: document.getElementById('error-edit_tgl_sk')
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

                $('#template-table').DataTable().ajax.reload(null, false);
                setLoading(false);

            } catch (err) {
                Swal.fire("Error!", "Gagal mengirim data. Silakan coba lagi.", "error");
                setLoading(false);
            }
        });
    });
</script>
