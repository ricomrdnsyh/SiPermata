<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratRekomendasiGenerator
{
    /**
     * Memproses data dan template untuk membuat file Word.
     * * @param SuratRekomendasi
     * @param Template $template Model Template yang sudah dipilih.
     * @return string Path relatif file Word yang berhasil dibuat.
     */
    public function generateWord(SuratRekomendasi $surat, Template $template)
    {
        $relativePathTemplate = $template->file;

        $templatePath = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: " . $templatePath);
        }

        // Load Template
        $processor = new TemplateProcessor($templatePath);

        $mahasiswa                = $surat->mahasiswa;
        $tglSuratCarbon           = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon         = Carbon::parse($surat->updated_at);
        $tahunKeperluanCarbon     = Carbon::parse($surat->updated_at);

        $tglSurat       = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat     = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');
        $tahunKeperluan = $tahunKeperluanCarbon->locale('id')->isoFormat('YYYY');
        $penyelenggaraUppercase = strtoupper($surat->penyelenggara ?? '-');

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MAHASISWA', $surat->mahasiswa?->nama ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('PRODI', $mahasiswa?->prodi?->nama_prodi ?? '-');
        $processor->setValue('NIM', $surat->nim);
        $processor->setValue('KEPERLUAN', $surat->keperluan ?? '-');
        $processor->setValue('PENYELENGGARA', $penyelenggaraUppercase ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');
        $processor->setValue('TAHUN_KEPERLUAN', $tahunKeperluan ?? '-');

        // Direktori Output
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
