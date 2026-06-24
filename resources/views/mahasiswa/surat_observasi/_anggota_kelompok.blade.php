@php
    $anggotaKelompok = collect($anggotaKelompok ?? [])->values();
@endphp


<div class="col-12 mt-5">
    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-4 p-4">
        <i class="fas fa-users fs-2x text-primary me-4 mt-1"></i>
        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
            <div class="mb-3 mb-md-0 fw-semibold">
                <h5 class="text-gray-900 fw-bold mb-1">Pengajuan Kelompok? (Opsional)</h5>
                <div class="fs-7 text-gray-700 pe-7">Jika surat ini diajukan untuk kelompok, silakan tambahkan anggota. Nama dan Prodi akan terisi otomatis.</div>
            </div>
            <button type="button" class="btn btn-primary btn-sm px-4 py-2 align-self-center text-nowrap hover-elevate-up shadow-sm" id="add-anggota-kelompok" onclick="window.addAnggotaKelompokRow && window.addAnggotaKelompokRow()">
                <i class="fas fa-user-plus me-1"></i> Tambah Anggota
            </button>
        </div>
    </div>

    <div class="fv-row mb-3">
        <div class="table-responsive border rounded bg-body shadow-sm">
            <table class="table table-sm table-row-bordered table-row-gray-200 align-middle gs-0 gy-3 mb-0">
                <thead>
                    <tr class="fw-bold text-muted bg-light fs-7">
                        <th class="ps-3 rounded-start" style="width: 25%">NIM Anggota</th>
                        <th style="width: 30%">Nama Mahasiswa</th>
                        <th style="width: 30%">Program Studi</th>
                        <th class="pe-4 text-center rounded-end" style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="anggota-kelompok-container">
                    @foreach ($anggotaKelompok as $index => $anggota)
                        <tr>
                            <td class="ps-3">
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
                            </td>
                            <td>
                                <input type="text"
                                    class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-nama-input"
                                    value="{{ old("anggota_kelompok.$index.nama", data_get($anggota, 'nama')) }}"
                                    placeholder="Otomatis terisi..." disabled />
                                <input type="hidden" name="anggota_kelompok[{{ $index }}][nama]"
                                    class="anggota-nama-hidden-input"
                                    value="{{ old("anggota_kelompok.$index.nama", data_get($anggota, 'nama')) }}" />
                            </td>
                            <td>
                                <input type="text"
                                    class="form-control form-control-sm form-control-solid anggota-autofill-input anggota-prodi-input"
                                    value="{{ old("anggota_kelompok.$index.prodi", data_get($anggota, 'prodi')) }}"
                                    placeholder="Otomatis terisi..." disabled />
                                <input type="hidden" name="anggota_kelompok[{{ $index }}][prodi]"
                                    class="anggota-prodi-hidden-input"
                                    value="{{ old("anggota_kelompok.$index.prodi", data_get($anggota, 'prodi')) }}" />
                            </td>
                            <td class="pe-4 text-center">
                                <button type="button" class="btn btn-icon btn-sm btn-light-danger remove-anggota-kelompok hover-elevate-up"
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
</div>
