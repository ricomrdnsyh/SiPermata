<div class="modal fade" id="form_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form method="POST" action="" id="form_edit_jabatan" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jabatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="fv-row mb-3">
                        <label class="required fw-semibold fs-6 mb-2">Nama Penduduk</label>
                        <select class="form-select form-select-sm w-100" data-control="select2" data-dropdown-parent="#form_edit" data-placeholder="Pilih Penduduk" name="penduduk_id" id="edit_penduduk_id" required>
                            <option value="">Pilih Penduduk...</option>
                            @foreach ($penduduk as $item)
                                <option value="{{ $item->id_penduduk }}">
                                    {{ $item->fakultas?->nama_fakultas ?? 'Fakultas belum diisi' }} - {{ $item->nama_penduduk }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Penduduk wajib dipilih.</div>
                    </div>
                    <div class="mb-3">
                        <label class="required fw-semibold fs-6 mb-3">Status Jabatan</label>
                        <div class="d-flex fv-col">
                            <div class="form-check form-check-custom form-check-solid mx-4">
                                <input class="form-check-input" name="status" type="radio" value="BAK" id="edit_BAK" required />
                                <label class="form-check-label" for="edit_BAK">
                                    <div class="fw-bold text-gray-800">BAK</div>
                                </label>
                            </div>
                            <div class="form-check form-check-custom form-check-solid mx-4">
                                <input class="form-check-input" name="status" type="radio" value="DEKAN" id="edit_DEKAN" required />
                                <label class="form-check-label" for="edit_DEKAN">
                                    <div class="fw-bold text-gray-800">Dekan</div>
                                </label>
                            </div>
                        </div>
                        <div class="invalid-feedback" style="display: block;">
                            <span class="invalid-feedback-radio-edit" style="display:none; color:#dc3545; font-size:.875em;">Status Jabatan wajib dipilih.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
                        <span class="indicator-label">Update</span>
                        <span class="indicator-progress" style="display:none;">
                            Tunggu sebentar...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
