<table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
    <!--begin::Row-->
    <tr>
        <td class="text-gray-400 min-w-175px w-175px">Kategori</td>
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
    <!--end::Row-->
    <!--begin::Row-->
    <tr>
        <td class="text-gray-400">Tahun Akademik</td>
        <td class="text-gray-800">@php
            $akademik = \App\Models\TahunAkademik::find($surat->akademik_id);
        @endphp
            {{ $akademik?->tahun_akademik ?? '-' }}</td>
    </tr>
    <!--end::Row-->
    <!--begin::Row-->
    <tr>
        <td class="text-gray-400">Semester</td>
        <td class="text-gray-800">{{ $surat->semester }}</td>
    </tr>
    <!--end::Row-->
    <!--begin::Row-->
    @if (in_array($surat->kategori, ['PNS', 'PPPK']))
        <tr>
            <td class="text-gray-400">NIP Orang Tua</td>
            <td class="text-gray-800">{{ $surat->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-gray-400">Nama Orang Tua</td>
            <td class="text-gray-800">{{ $surat->nama_ortu ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-gray-400">Pendidikan Terakhir</td>
            <td class="text-gray-800">{{ $surat->pendidikan_terakhir ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-gray-400">Pangkat</td>
            <td class="text-gray-800">{{ $surat->pangkat ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-gray-400">Golongan</td>
            <td class="text-gray-800">{{ $surat->golongan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-gray-400">Tahun Mulai Tugas</td>
            <td class="text-gray-800">{{ $surat->tmt ?? '-' }}</td>
        </tr>
        <tr>
            <td class="text-gray-400">Unit Kerja</td>
            <td class="text-gray-800">{{ $surat->unit_kerja ?? '-' }}</td>
        </tr>
    @endif
    <tr>
        <td class="text-gray-400">Alamat</td>
        <td class="text-gray-800">{{ $surat->alamat ?? '-' }}</td>
    </tr>
    <!--end::Row-->
</table>
