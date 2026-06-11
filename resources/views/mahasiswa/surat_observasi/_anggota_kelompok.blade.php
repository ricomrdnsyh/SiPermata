@php
    $anggotaKelompok = collect($anggotaKelompok ?? [])->values();
@endphp

<style>
    .anggota-autofill-input[disabled] {
        background-color: #f5f8fa !important;
        color: #7e8299 !important;
        opacity: 1 !important;
        cursor: not-allowed;
    }

</style>

<div class="col-12">
    <div class="separator border-gray-200 my-4"></div>
    <div class="fv-row mb-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div>
                <label class="fw-semibold fs-6 mb-1 d-block">Anggota Kelompok</label>
                <div class="text-muted fs-7">
                    Tambahkan anggota dengan memasukkan NIM. Nama mahasiswa dan prodi akan terisi otomatis dari data mahasiswa.
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light-primary" id="add-anggota-kelompok"
                onclick="window.addAnggotaKelompokRow && window.addAnggotaKelompokRow()">
                <i class="fas fa-plus me-2"></i>
                Tambah Anggota
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle table-row-dashed mb-0">
                <thead>
                    <tr class="text-muted fw-bold">
                        <th style="width: 24%">NIM Anggota</th>
                        <th style="width: 30%">Nama Mahasiswa</th>
                        <th style="width: 30%">Prodi</th>
                        <th style="width: 16%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="anggota-kelompok-container">
                    @foreach ($anggotaKelompok as $index => $anggota)
                        <tr>
                            <td>
                                <input type="text" name="anggota_kelompok[{{ $index }}][nim]"
                                    class="form-control form-control-sm anggota-nim-input"
                                    value="{{ old("anggota_kelompok.$index.nim", data_get($anggota, 'nim')) }}"
                                    placeholder="NIM mahasiswa" autocomplete="off" />
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
                            </td>
                            <td>
                                <input type="text"
                                    class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-nama-input"
                                    value="{{ old("anggota_kelompok.$index.nama", data_get($anggota, 'nama')) }}"
                                    placeholder="Nama mahasiswa" disabled />
                                <input type="hidden" name="anggota_kelompok[{{ $index }}][nama]"
                                    class="anggota-nama-hidden-input"
                                    value="{{ old("anggota_kelompok.$index.nama", data_get($anggota, 'nama')) }}" />
                            </td>
                            <td>
                                <input type="text"
                                    class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-prodi-input"
                                    value="{{ old("anggota_kelompok.$index.prodi", data_get($anggota, 'prodi')) }}"
                                    placeholder="Prodi mahasiswa" disabled />
                                <input type="hidden" name="anggota_kelompok[{{ $index }}][prodi]"
                                    class="anggota-prodi-hidden-input"
                                    value="{{ old("anggota_kelompok.$index.prodi", data_get($anggota, 'prodi')) }}" />
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger remove-anggota-kelompok"
                                    title="Hapus anggota" aria-label="Hapus anggota">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @error('anggota_kelompok')
            <small class="text-danger d-block mt-2">{{ $message }}</small>
        @enderror
    </div>
</div>
