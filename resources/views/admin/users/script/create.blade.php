    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeSelect = document.getElementById('userType');
            if (!userTypeSelect) {
                return;
            }

            const fieldGroups = {
                mahasiswa: {
                    wrapper: document.getElementById('mahasiswaFields'),
                    fields: ['m_reference_id', 'm_password'],
                },
                penduduk: {
                    wrapper: document.getElementById('pendudukFields'),
                    fields: ['p_reference_id', 'p_password'],
                },
                admin: {
                    wrapper: document.getElementById('adminFields'),
                    fields: ['identifier', 'nama', 'password'],
                },
            };

            const toggleUserFields = (selectedType) => {
                Object.entries(fieldGroups).forEach(([group, config]) => {
                    const isActive = group === selectedType;
                    if (config.wrapper) {
                        config.wrapper.style.display = isActive ? 'block' : 'none';
                    }
                    config.fields.forEach((name) => {
                        const field = document.querySelector(`[name="${name}"]`);
                        if (!field) {
                            return;
                        }
                        field.required = isActive;
                    });
                });
            };

            $('#userType').on('change', function() {
                toggleUserFields(this.value);
            });

            // Initialize on load
            $('#form_create').on('shown.bs.modal', function () {
                toggleUserFields(userTypeSelect.value);
            });
            toggleUserFields(userTypeSelect.value);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('kt_ecommerce_settings_general_form');
            if(form){
                const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        return;
                    }
                    submitButton.disabled = true;
                    submitButton.querySelector('.indicator-label').style.display = 'none';
                    submitButton.querySelector('.indicator-progress').style.display = 'inline-block';
                });
            }
        });
    </script>
