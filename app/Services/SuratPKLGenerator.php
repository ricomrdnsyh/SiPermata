<?php

namespace App\Services;

use App\Models\SuratPKL;
use App\Models\Template;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratPKLGenerator
{
    /**
     * Memproses data dan template untuk membuat file Word.
     * * @param SuratPKL
     * @param Template $template Model Template yang sudah dipilih.
     * @return string Path relatif file Word yang berhasil dibuat.
     */
    public function generateWord(SuratPKL $surat, Template $template)
    {
        $relativePathTemplate = $template->file;

        $templatePath = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: " . $templatePath);
        }

        // Load Template
        $processor = new TemplateProcessor($templatePath);

        $mahasiswa          = $surat->mahasiswa;
        $tglSuratCarbon     = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon   = Carbon::parse($surat->updated_at);
        $tglMulaiCarbon     = Carbon::parse($surat->tgl_mulai);
        $tglSelesaiCarbon   = Carbon::parse($surat->tgl_selesai);

        $tglSurat   = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglMulai   = $tglMulaiCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglSelesai = $tglSelesaiCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MITRA', $surat->mitra->nama_mitra ?? '-');
        $processor->setValue('NAMA_MAHASISWA', $surat->mahasiswa?->nama ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('PRODI', $mahasiswa?->prodi?->nama_prodi ?? '-');
        $processor->setValue('NIM', $surat->nim);
        $processor->setValue('TGL_MULAI', $tglMulai ?? '-');
        $processor->setValue('TGL_SELESAI', $tglSelesai ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');

        // Direktori Output
        $outputFileName    = "SURAT_PKL_{$surat->nim}.docx";
        $outputFileRelatif = "surat_pkl/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        $processor->saveAs($outputPathAbsolut);

        return $outputFileRelatif;
    }
}
