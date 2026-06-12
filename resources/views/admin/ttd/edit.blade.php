<div class="modal fade" id="form_edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit TTD Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_edit_ttd" class="form" method="POST" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">Nama Template</label>
                                <select class="form-select form-select-sm w-100" data-control="select2" data-dropdown-parent="#form_edit" data-placeholder="Pilih Template" name="template_id" id="edit_template_id" required>
                                    <option value="">Pilih Template...</option>
                                    @foreach ($template as $t)
                                        <option value="{{ $t->id_template }}">{{ $t->nama_template }} - {{ $t->fakultas ? $t->fakultas->nama_fakultas : 'Tanpa Fakultas' }}</option>
                                    @endforeach
                                </select>
                                <div id="error-edit_template_id" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">Nama TTD</label>
                                <input type="text" name="nama_ttd" id="edit_nama_ttd" class="form-control form-control-sm mb-3 mb-lg-0" required />
                                <div id="error-edit_nama_ttd" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">NIDN</label>
                                <input type="number" name="nidn" id="edit_nidn" class="form-control form-control-sm mb-3 mb-lg-0" required />
                                <div id="error-edit_nidn" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">Fakultas</label>
                                <select class="form-select form-select-sm w-100" data-control="select2" data-dropdown-parent="#form_edit" data-placeholder="Pilih Fakultas" name="fakultas_id" id="edit_fakultas_id" required>
                                    <option value="">Pilih Fakultas...</option>
                                    @foreach ($fakultas as $f)
                                        <option value="{{ $f->id_fakultas }}">{{ $f->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                                <div id="error-edit_fakultas_id" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">Status</label>
                                <div class="d-flex align-items-center mt-2">
                                    <div class="form-check form-check-custom form-check-solid form-check-sm me-5">
                                        <input class="form-check-input" type="radio" value="aktif" name="status" id="edit_status_aktif" required />
                                        <label class="form-check-label text-gray-800" for="edit_status_aktif">Aktif</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid form-check-sm">
                                        <input class="form-check-input" type="radio" value="nonaktif" name="status" id="edit_status_nonaktif" required />
                                        <label class="form-check-label text-gray-800" for="edit_status_nonaktif">Nonaktif</label>
                                    </div>
                                </div>
                                <div id="error-edit_status" class="invalid-feedback mt-2" style="display:none;"></div>
                            </div>
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
            </form>
        </div>
    </div>
</div>
