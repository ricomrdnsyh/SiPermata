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
            <td class="text-gray-800">{{ $dataSimpt?->semester ?? '-' }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">IPK</td>
            <td class="text-gray-800">{{ $dataSimpt?->ipk_ketuntasan ?? '-' }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Keperluan Rekomendasi</td>
            <td class="text-gray-800">{{ ucwords($surat->keperluan) }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Penyelenggara</td>
            <td class="text-gray-800">{{ strtoupper($surat->penyelenggara) }}</td>
        </tr>
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Tanggal Pelaksanaan</td>
            <td class="text-gray-800">{{ $surat->tgl_pelaksanaan?->locale('id')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
    </table>
</div>
