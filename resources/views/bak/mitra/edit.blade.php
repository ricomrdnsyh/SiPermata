@extends('layout.main')
@section('title', 'Edit Mitra')
@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid">
                    <div class="row g-7 ">
                        <div class="col-xl-6 py-3 py-lg-6 mb-5 w-100">
                            <div class="card card-flush h-lg-100" id="kt_contacts_main">
                                <div class="card-header pt-7" id="kt_chat_contacts_header">
                                    <div class="card-title">
                                        <h2>Edit Mitra</h2>
                                    </div>
                                </div>
                                <div class="separator border-gray-200 mt-4"></div>
                                <div class="card-body pt-5">
                                    <form id="form_mitra_edit" action="{{ route('bak.mitra.update', $mitra->id_mitra) }}"
                                        method="POST" novalidate>
                                        @csrf
                                        @method('PUT')

                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Nama Mitra</label>

                                            <input type="text" name="nama_mitra" id="nama_mitra"
                                                class="form-control form-control-sm mb-1"
                                                value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required />

                                            <div id="error-nama_mitra" class="invalid-feedback d-block"
                                                style="display:none;"></div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('bak.mitra.index') }}"
                                                class="btn btn-sm btn-light me-3">Batal</a>

                                            <button type="submit" data-kt-contacts-type="submit"
                                                class="btn btn-sm btn-primary">
                                                <span class="indicator-label">Update</span>
                                                <span class="indicator-progress" style="display:none;">
                                                    Tunggu sebentar...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form_mitra_edit');
            if (!form) return;

            const submitButton = form.querySelector('[data-kt-contacts-type="submit"]');
            const inputNama = document.getElementById('nama_mitra');
            const errNama = document.getElementById('error-nama_mitra');

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

                    window.location.href = data?.redirect || "{{ route('bak.mitra.index') }}";

                } catch (err) {
                    showError('Gagal mengirim data. Silakan coba lagi.');
                    setLoading(false);
                }
            });
        });
    </script>
@endsection
