<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratLulus;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratLulusGenerator
{
    private function escapeXml($value): string
    {
        return htmlspecialchars((string)($value ?: '-'), ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    /**
     * Memproses data dan template untuk membuat file Word.
     * * @param SuratLulus
     * @param Template $template Model Template yang sudah dipilih.
     * @param string|float|null $ipk
     * @return string Path relatif file Word yang berhasil dibuat.
     */
    public function generateWord(SuratLulus $surat, Template $template, string|float|null $ipk = null)
    {
        $relativePathTemplate = $template->file;
        $tanggal_SK = $template->tgl_sk;

        $templatePath = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: " . $templatePath);
        }

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

        $ipkFormatted = $ipk !== null ? number_format((float) $ipk, 2) : '-';

        $processor->setValue('NO_SURAT', $this->escapeXml($surat->no_surat));
        $processor->setValue('BULAN_SURAT', $this->escapeXml($bulanSurat));
        $processor->setValue('NAMA_MAHASISWA', $this->escapeXml($surat->mahasiswa?->nama));
        $processor->setValue('FAKULTAS', $this->escapeXml($mahasiswa?->fakultas?->nama_fakultas));
        $processor->setValue('PRODI', $this->escapeXml($mahasiswa?->prodi?->nama_prodi));
        $processor->setValue('NIM', $this->escapeXml($surat->nim));
        $processor->setValue('TEMPAT_LAHIR', $this->escapeXml($tempatLahirTitlecase));
        $processor->setValue('TGL_LAHIR', $this->escapeXml($tglLahir));
        $processor->setValue('TANGGAL_SURAT', $this->escapeXml($tglSurat));
        $processor->setValue('TGL_SK', $this->escapeXml($tglSK));
        $processor->setValue('TAHUN_AKADEMIK', $this->escapeXml($surat->akademik->tahun_akademik ?? '-'));
        $processor->setValue('JUDUL_PENELITIAN', $this->escapeXml($judulPenelitianUppercase));
        $processor->setValue('IPK', $this->escapeXml($ipkFormatted));

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
