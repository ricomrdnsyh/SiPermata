<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratRekomendasiGenerator
{
    private function escapeXml($value): string
    {
        return htmlspecialchars((string)($value ?: '-'), ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    public function generateWord(
        SuratRekomendasi $surat,
        Template $template,
        string|int|null $semester = null,
        string|float|null $ipk = null
    ) {
        $relativePathTemplate = $template->file;
        $templatePath = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: " . $templatePath);
        }

        $processor = new TemplateProcessor($templatePath);

        $mahasiswa            = $surat->mahasiswa;
        $tglSuratCarbon       = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon     = Carbon::parse($surat->updated_at);
        $tglPelaksanaanCarbon = Carbon::parse($surat->tgl_pelaksanaan);

        $tglSurat               = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat             = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');
        $tglPelaksanaan         = $tglPelaksanaanCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $penyelenggaraUppercase = strtoupper($surat->penyelenggara);
        $keperluanTitleCase     = ucwords($surat->keperluan);

        $ipkFormatted = $ipk !== null ? number_format((float) $ipk, 2) : '-';

        $processor->setValue('NO_SURAT',            $this->escapeXml($surat->no_surat));
        $processor->setValue('BULAN_SURAT',         $this->escapeXml($bulanSurat));
        $processor->setValue('NAMA_MAHASISWA',      $this->escapeXml($mahasiswa?->nama));
        $processor->setValue('FAKULTAS',            $this->escapeXml($mahasiswa?->fakultas?->nama_fakultas));
        $processor->setValue('PRODI',               $this->escapeXml($mahasiswa?->prodi?->nama_prodi));
        $processor->setValue('NIM',                 $this->escapeXml($surat->nim));
        $processor->setValue('KEPERLUAN',           $this->escapeXml($keperluanTitleCase));
        $processor->setValue('PENYELENGGARA',       $this->escapeXml($penyelenggaraUppercase));
        $processor->setValue('TANGGAL_SURAT',       $this->escapeXml($tglSurat));
        $processor->setValue('TANGGAL_PELAKSANAAN', $this->escapeXml($tglPelaksanaan));

        $processor->setValue('SEMESTER', $this->escapeXml($semester));
        $processor->setValue('IPK',      $this->escapeXml($ipkFormatted));

        $outputFileName    = "SURAT_REKOMENDASI_{$surat->nim}_{$surat->id_surat_rekomendasi}.docx";
        $outputFileRelatif = "surat_rekomendasi/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        $processor->saveAs($outputPathAbsolut);

        return $outputFileRelatif;
    }
}
