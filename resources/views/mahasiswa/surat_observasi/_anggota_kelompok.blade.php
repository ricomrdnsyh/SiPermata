@php
    $anggotaKelompok = collect($anggotaKelompok ?? [])->values();
@endphp

<div class="form-group-box">
    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-6 p-6">
        <i class="fas fa-users fs-2x text-primary me-4 mt-1"></i>
        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
            <div class="mb-3 mb-md-0 fw-semibold">
                <h5 class="text-gray-900 fw-bold mb-1">Pengajuan Kelompok? (Opsional)</h5>
                <div class="fs-7 text-gray-700 pe-7">Jika surat ini diajukan untuk kelompok, silakan tambahkan anggota. Nama dan Prodi akan terisi otomatis.</div>
            </div>
            <button type="button" class="btn btn-primary btn-sm px-4 py-2 align-self-center text-nowrap hover-elevate-up shadow-sm mt-3 mt-md-0 w-100 w-md-auto" id="add-anggota-kelompok" onclick="window.addAnggotaKelompokRow && window.addAnggotaKelompokRow()">
                <i class="fas fa-user-plus me-1"></i> Tambah Anggota
            </button>
        </div>
    </div>

    <div class="fv-row mb-0">
        <div class="d-none d-md-flex row g-3 mb-3 fw-bold text-muted fs-7 px-2">
            <div class="col-md-3">NIM Anggota</div>
            <div class="col-md-4">Nama Mahasiswa</div>
            <div class="col-md-4">Program Studi</div>
            <div class="col-md-1 text-center">Aksi</div>
        </div>
        
        <div id="anggota-kelompok-container">
            @foreach ($anggotaKelompok as $index => $anggota)
                <div class="anggota-item bg-body border rounded p-3 mb-3 shadow-sm">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-3">
                            <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">NIM Anggota</label>
                            <input type="text" name="anggota_kelompok[{{ $index }}][nim]"
                                class="form-control form-control-sm anggota-nim-input"
                                value="{{ old("anggota_kelompok.$index.nim", data_get($anggota, 'nim')) }}"
                                placeholder="Masukkan NIM..." autocomplete="off" />
                            <div class="invalid-feedback anggota-nim-feedback">
                                @error("anggota_kelompok.$index.nim")
                                    {{ $message }}
                                @enderror
                            </div>
                            @error("anggota_kelompok.$index.nim")
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const input = document.querySelector('[name="anggota_kelompok[{{ $index }}][nim]"]');
                                        if (input) {
                                            input.classList.add('is-invalid');
                                        }
                                    });
                                </script>
                            @enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">Nama Mahasiswa</label>
                            <input type="text"
                                class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-nama-input"
                                value="{{ old("anggota_kelompok.$index.nama", data_get($anggota, 'nama')) }}"
                                placeholder="Otomatis terisi..." disabled />
                            <input type="hidden" name="anggota_kelompok[{{ $index }}][nama]"
                                class="anggota-nama-hidden-input"
                                value="{{ old("anggota_kelompok.$index.nama", data_get($anggota, 'nama')) }}" />
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="fw-semibold fs-7 mb-1 d-md-none text-muted">Program Studi</label>
                            <input type="text"
                                class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-prodi-input"
                                value="{{ old("anggota_kelompok.$index.prodi", data_get($anggota, 'prodi')) }}"
                                placeholder="Otomatis terisi..." disabled />
                            <input type="hidden" name="anggota_kelompok[{{ $index }}][prodi]"
                                class="anggota-prodi-hidden-input"
                                value="{{ old("anggota_kelompok.$index.prodi", data_get($anggota, 'prodi')) }}" />
                        </div>
                        <div class="col-12 col-md-1 text-end text-md-center mt-2 mt-md-0">
                            <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-anggota-kelompok hover-elevate-up"
                                title="Hapus anggota" aria-label="Hapus anggota">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @error('anggota_kelompok')
            <small class="text-danger d-block mt-2">{{ $message }}</small>
        @enderror
    </div>
</div>
