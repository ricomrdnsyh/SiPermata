<div class="modal fade" id="form_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form_create_template" class="form" action="{{ route('admin.template.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">Nama Template</label>
                                <input type="text" name="nama_template" id="create_nama_template" class="form-control form-control-sm mb-3 mb-lg-0" required />
                                <div id="error-create_nama_template" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">Jenis Surat(contoh_nama_jenis_surat)</label>
                                <input type="text" name="jenis_surat" id="create_jenis_surat" class="form-control form-control-sm mb-3 mb-lg-0" required />
                                <div id="error-create_jenis_surat" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">File Template(Doc/Docx)</label>
                                <input type="file" name="file" id="create_file" class="form-control form-control-sm mb-3 mb-lg-0" required />
                                <div id="error-create_file" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="required fw-semibold fs-6 mb-2">Nama Fakultas</label>
                                <select class="form-select form-select-sm w-100" data-control="select2" data-dropdown-parent="#form_create" data-placeholder="Pilih Fakultas" name="fakultas_id" id="create_fakultas_id" required>
                                    <option value="">Pilih Fakultas...</option>
                                    @foreach ($fakultas as $f)
                                        <option value="{{ $f->id_fakultas }}">{{ $f->nama_fakultas }}</option>
                                    @endforeach
                                </select>
                                <div id="error-create_fakultas_id" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>



                        <div class="col-12 col-md-6">
                            <div class="fv-row mb-3">
                                <label class="fw-semibold fs-6 mb-2">Tanggal SK(Kosongkan jikan bukan surat keterangan lulus)</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">
                                        <i class="ki-duotone ki-calendar fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                    <input id="create_tgl_sk" type="text" name="tgl_sk" class="form-control form-control-sm" placeholder="Pilih tanggal SK" autocomplete="off" />
                                </div>
                                <div id="error-create_tgl_sk" class="invalid-feedback d-block" style="display:none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
                        <span class="indicator-label">Tambah</span>
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
