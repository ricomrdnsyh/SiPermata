@php
    $daftarMahasiswa = collect($surat->daftar_mahasiswa ?? []);
    $isKelompok = $daftarMahasiswa->count() > 1;
@endphp

<div class="table-responsive">
    <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700 min-w-150px">Nama Surat</td>
            <td class="text-gray-800">{{ $pengajuan->nama_surat }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Tahun Akademik</td>
            <td class="text-gray-800">{{ $surat->akademik->tahun_akademik }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Semester</td>
            <td class="text-gray-800">{{ $surat->semester }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Tempat Observasi</td>
            <td class="text-gray-800">{{ $surat->mitra->nama_mitra }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Tanggal Observasi</td>
            <td class="text-gray-800">{{ $surat->tgl_observasi?->locale('id')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Keperluan Observasi</td>
            <td class="text-gray-800">{{ ucwords($surat->keperluan) }}</td>
        </tr>
        @if ($isKelompok)
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Jenis Pengajuan</td>
                <td class="text-gray-800">Kelompok</td>
            </tr>
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Jumlah Mahasiswa</td>
                <td class="text-gray-800">{{ $daftarMahasiswa->count() }}</td>
            </tr>
        @endif
    </table>
</div>

@if ($isKelompok)
    <div class="mt-5">
        @include('partials.surat_observasi_anggota', ['surat' => $surat])
    </div>
@endif
