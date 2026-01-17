<table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
    <tr>
        <td class="text-gray-400">Nama Surat</td>
        <td class="text-gray-800">{{ $pengajuan->nama_surat }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Tahun Akademik</td>
        <td class="text-gray-800">{{ $surat->akademik->tahun_akademik }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Keperluan Rekomendasi</td>
        <td class="text-gray-800">{{ ucwords($surat->keperluan) }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Penyelenggara</td>
        <td class="text-gray-800">{{ strtoupper($surat->penyelenggara) }}</td>
    </tr>
</table>
