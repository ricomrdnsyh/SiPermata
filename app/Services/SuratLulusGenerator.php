<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratLulus;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratLulusGenerator
{
    /**
     * Memproses data dan template untuk membuat file Word.
     * * @param SuratLulus
     * @param Template $template Model Template yang sudah dipilih.
     * @return string Path relatif file Word yang berhasil dibuat.
     */
    public function generateWord(SuratLulus $surat, Template $template)
    {
        $relativePathTemplate = $template->file;
        $tanggal_SK = $template->tgl_sk;

        $templatePath = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: " . $templatePath);
        }

        // Load Template
        $processor = new TemplateProcessor($templatePath);

        $mahasiswa          = $surat->mahasiswa;
        $tglSuratCarbon     = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon   = Carbon::parse($surat->updated_at);
        $tglLahirCarbon     = Carbon::parse($surat->tgl_lahir);
        $tglSKCarbon        = Carbon::parse($tanggal_SK);

        $tglSurat   = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglLahir   = $tglLahirCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');
        $tglSK      = $tglSKCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tempatLahirTitlecase = strtoupper($surat->tempat_lahir ?? '-');
        $judulPenelitianUppercase = strtoupper($surat->judul_penelitian ?? '-');

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MAHASISWA', $surat->mahasiswa?->nama ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('PRODI', $mahasiswa?->prodi?->nama_prodi ?? '-');
        $processor->setValue('NIM', $surat->nim);
        $processor->setValue('TEMPAT_LAHIR', $tempatLahirTitlecase ?? '-');
        $processor->setValue('TGL_LAHIR', $tglLahir ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');
        $processor->setValue('TGL_SK', $tglSK ?? '-');
        $processor->setValue('JUDUL_PENELITIAN', $judulPenelitianUppercase);

        // Direktori Output
        $outputFileName    = "SURAT_KETERANGAN_LULUS_{$surat->nim}_{$surat->id_surat_lulus}.docx";
        $outputFileRelatif = "surat_lulus/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        $processor->saveAs($outputPathAbsolut);

        return $outputFileRelatif;
    }
}
