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
        <td class="text-gray-400">Semester</td>
        <td class="text-gray-800">{{ $surat->semester }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Tempat Observasi</td>
        <td class="text-gray-800">{{ $surat->mitra->nama_mitra }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Tanggal Observasi</td>
        <td class="text-gray-800">{{ $surat->tgl_observasi?->locale('id')->isoFormat('D MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Keperluan Observasi</td>
        <td class="text-gray-800">{{ $surat->keperluan }}</td>
    </tr>
</table>
