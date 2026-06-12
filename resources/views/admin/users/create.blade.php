<div class="modal fade" id="form_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kt_ecommerce_settings_general_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="fv-row mb-3">
                        <label class="required fw-semibold fs-6 mb-2">Role</label>
                        <select class="form-select form-select-sm w-100"
                            data-control="select2" data-dropdown-parent="#form_create" data-placeholder="Pilih Role" name="type"
                            id="userType"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="penduduk" {{ old('type') == 'penduduk' ? 'selected' : '' }}>Penduduk</option>
                            <option value="mahasiswa" {{ old('type') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>
                        @error('type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div id="mahasiswaFields" class="fv-row mb-3" style="display:none;">
                        <div class="mb-3">
                            <label class="required fw-semibold fs-6 mb-2">Mahasiswa</label>
                            <select
                                class="form-select form-select-sm w-100"
                                data-control="select2" data-dropdown-parent="#form_create" data-placeholder="Pilih Mahasiswa"
                                name="m_reference_id">
                                <option value="">Pilih Mahasiswa</option>
                                @foreach ($mahasiswa as $m)
                                    <option value="{{ $m->nim }}">{{ $m->nim }} - {{ $m->nama }}</option>
                                @endforeach
                            </select>
                            @error('m_reference_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Password</label>
                            <input type="password" name="m_password"
                                class="form-control form-control-sm mb-3 mb-lg-0" minlength="6" />
                            @error('m_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div id="pendudukFields" class="fv-row mb-3" style="display:none;">
                        <div class="mb-3">
                            <label class="required fw-semibold fs-6 mb-2">Penduduk</label>
                            <select
                                class="form-select form-select-sm w-100"
                                data-control="select2" data-dropdown-parent="#form_create" data-placeholder="Pilih Penduduk"
                                name="p_reference_id">
                                <option value="">Pilih Penduduk</option>
                                @foreach ($penduduk as $p)
                                    <option value="{{ $p->id_penduduk }}">
                                        {{ $p->id_penduduk }} - {{ $p->nama_penduduk }}
                                        @if ($p->jabatan)
                                            ({{ $p->jabatan->status }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('p_reference_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="required fw-semibold fs-6 mb-2">Password</label>
                            <input type="password" name="p_password"
                                class="form-control form-control-sm mb-3 mb-lg-0" minlength="6" />
                            @error('p_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div id="adminFields" class="fv-row mb-3" style="display:none;">
                        <div class="mb-3">
                            <label class="required fw-semibold fs-6 mb-2">Username</label>
                            <input type="text" name="identifier" class="form-control form-control-sm mb-3 mb-lg-0"
                                value="{{ old('identifier') }}" />
                            @error('identifier')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="required fw-semibold fs-6 mb-2">Nama</label>
                            <input type="text" name="nama" class="form-control form-control-sm mb-3 mb-lg-0"
                                value="{{ old('nama') }}" />
                            @error('nama')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="required fw-semibold fs-6 mb-2">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm mb-3 mb-lg-0"
                                minlength="6" />
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" data-kt-contacts-type="submit" class="btn btn-sm btn-primary">
                        <span class="indicator-label">Tambah</span>
                        <span class="indicator-progress">
                            Tunggu sebentar... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
