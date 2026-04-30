@php
    $daftarMahasiswa = collect($surat->daftar_mahasiswa ?? [])->values();
    $isKelompok = $daftarMahasiswa->count() > 1;
@endphp

<div class="rounded border border-gray-200 overflow-hidden bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 px-4 py-3 bg-light">
        <div>
            <div class="fw-bold fs-6 text-gray-900">Daftar Mahasiswa</div>
            <div class="text-gray-600 fs-7">
                {{ $isKelompok ? 'Susunan anggota pengajuan PKL kelompok.' : 'Data mahasiswa pengaju PKL.' }}
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-row-dashed align-middle mb-0">
            <thead>
                <tr class="text-uppercase fs-8 fw-bold text-muted bg-light-secondary">
                    <th class="ps-4" style="width: 70px;">No</th>
                    <th style="width: 75px;">Status</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th class="pe-4" style="width: 30%;">Prodi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarMahasiswa as $index => $anggota)
                    <tr>
                        <td class="ps-4 fw-semibold text-gray-700">{{ $index + 1 }}</td>
                        <td>
                            @if (data_get($anggota, 'is_ketua'))
                                <span class="badge badge-light-primary">Ketua</span>
                            @else
                                <span class="badge badge-light-success">Anggota</span>
                            @endif
                        </td>
                        <td class="fw-semibold text-gray-800">{{ data_get($anggota, 'nim', '-') }}</td>
                        <td>
                            <div class="fw-semibold text-gray-900">{{ data_get($anggota, 'nama', '-') }}</div>
                        </td>
                        <td class="pe-4 text-gray-700">{{ data_get($anggota, 'prodi', '-') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">Data mahasiswa tidak tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
