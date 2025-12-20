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
        <td class="text-gray-400">Tempat Penelitian</td>
        <td class="text-gray-800">{{ $surat->mitra->nama_mitra }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Tanggal Mulai</td>
        <td class="text-gray-800">{{ $surat->tgl_mulai?->locale('id')->isoFormat('D MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Tanggal Selesai</td>
        <td class="text-gray-800">{{ $surat->tgl_selesai?->locale('id')->isoFormat('D MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td class="text-gray-400">Judul Penelitian</td>
        <td class="text-gray-800">{{ $surat->judul_penelitian }}</td>
    </tr>
</table>
