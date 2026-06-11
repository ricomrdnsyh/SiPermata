<div class="table-responsive">
    <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700 min-w-150px">Nama Surat</td>
            <td class="text-gray-800">{{ $pengajuan->nama_surat }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">IPK</td>
            <td class="text-gray-800">{{ $dataSimpt?->ipk_ketuntasan ? number_format((float) $dataSimpt->ipk_ketuntasan, 2) : '-' }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Tempat, Tanggal Lahir</td>
            <td class="text-gray-800">{{ ucwords($surat->tempat_lahir) }},
                {{ $surat->tgl_lahir?->locale('id')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Judul Penelitian/Tugas Akhir</td>
            <td class="text-gray-800">{{ strtoupper($surat->judul_penelitian) }}</td>
        </tr>
    </table>
</div>
