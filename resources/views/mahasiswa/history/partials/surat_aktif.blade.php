<div class="table-responsive">
    <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
        <tr class="border-bottom border-dashed">
            <td class="text-gray-700 min-w-175px w-175px">Kategori</td>
            <td class="text-gray-800 min-w-200px">
                @switch($surat->kategori)
                    @case('UMUM')
                        Surat Keterangan Aktif Umum
                    @break

                    @case('PNS')
                        Surat Keterangan Aktif PNS
                    @break

                    @case('PPPK')
                        Surat Keterangan Aktif PPPK
                    @break

                    @default
                        {{ $surat->kategori }}
                @endswitch
            </td>
        </tr>

        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Tahun Akademik</td>
            <td class="text-gray-800">{{ $surat->akademik->tahun_akademik }}</td>
        </tr>

        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Semester</td>
            <td class="text-gray-800">{{ $surat->semester }}</td>
        </tr>

        @if (in_array($surat->kategori, ['PNS', 'PPPK']))
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">NIP Orang Tua</td>
                <td class="text-gray-800">{{ $surat->nip ?? '-' }}</td>
            </tr>
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Nama Orang Tua</td>
                <td class="text-gray-800">{{ $surat->nama_ortu ?? '-' }}</td>
            </tr>
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Pendidikan Terakhir</td>
                <td class="text-gray-800">{{ $surat->pendidikan_terakhir ?? '-' }}</td>
            </tr>
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Pangkat</td>
                <td class="text-gray-800">{{ $surat->pangkat ?? '-' }}</td>
            </tr>
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Golongan</td>
                <td class="text-gray-800">{{ $surat->golongan ?? '-' }}</td>
            </tr>
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Tahun Mulai Tugas</td>
                <td class="text-gray-800">
                    {{ $surat->tmt?->locale('id')->isoFormat('D MMMM YYYY') ?? '-' }}
                </td>
            </tr>
            <tr class="border-bottom border-dashed">
                <td class="text-gray-700">Unit Kerja</td>
                <td class="text-gray-800">{{ $surat->unit_kerja ?? '-' }}</td>
            </tr>
        @endif

        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Alamat</td>
            <td class="text-gray-800">{{ ucwords($surat->alamat ?? '-') }}</td>
        </tr>

        <tr class="border-bottom border-dashed">
            <td class="text-gray-700">Keperluan Surat</td>
            <td class="text-gray-800">{{ $surat->keperluan ?? '-' }}</td>
        </tr>
    </table>
</div>
