<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratAktif;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratAktifGenerator
{
    /**
     * Memproses data dan template untuk membuat file Word.
     * * @param SuratAktif $surat Model SuratAktif yang baru dibuat.
     * @param Template $template Model Template yang sudah dipilih.
     * @return string Path relatif file Word yang berhasil dibuat.
     */
    public function generateWord(SuratAktif $surat, Template $template)
    {
        $relativePathTemplate = $template->file;

        $templatePath = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: " . $templatePath);
        }

        // Load Template
        $processor = new TemplateProcessor($templatePath);

        // 3. Set Data Variabel (Placeholder) ke dalam template
        $mahasiswa = $surat->mahasiswa;
        $tmtCarbon = Carbon::parse($surat->tmt);
        $tglSuratCarbon = Carbon::parse($surat->created_at);
        $bulanSuratCarbon = Carbon::parse($surat->created_at);

        $tmtOrtu = $tmtCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglSurat = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat = $tglSuratCarbon->locale('id')->isoFormat('MM.YYYY');

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MAHASISWA', $surat->mahasiswa?->nama ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('PRODI', $mahasiswa?->prodi?->nama_prodi ?? '-');
        $processor->setValue('NIM', $surat->nim);
        $processor->setValue('SEMESTER', $surat->semester ?? '-');
        $processor->setValue('TAHUN_AKADEMIK', $surat->akademik->tahun_akademik ?? '-');
        $processor->setValue('ALAMAT', $surat->alamat ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');

        // Data Khusus (Hanya diisi jika kategori PNS/PPPK)
        if (in_array($surat->kategori, ['PNS', 'PPPK'])) {
            $processor->setValue('NIP_ORTU', $surat->nip);
            $processor->setValue('NAMA_ORTU', $surat->nama_ortu);
            $processor->setValue('PENDIDIKAN_TERAKHIR_ORTU', $surat->pendidikan_terakhir ?? '-');
            $processor->setValue('PANGKAT_ORTU', $surat->pangkat);
            $processor->setValue('GOLONGAN_ORTU', $surat->golongan);
            $processor->setValue('TMT_ORTU', $tmtOrtu);
            $processor->setValue('UNIT_KERJA_ORTU', $surat->unit_kerja);
        }

        // Direktori Output
        $outputFileName = "surat_aktif_{$surat->nim}_{$surat->id_surat_aktif}.docx";
        $outputFileRelatif = "surat_aktif/output/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        // Simpan File Word Hasil Generate
        $processor->saveAs($outputPathAbsolut);

        // Kembalikan path relatif untuk disimpan di database SuratAktif
        return $outputFileRelatif;
    }
}
