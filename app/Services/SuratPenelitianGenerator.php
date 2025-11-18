<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratPenelitian;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratPenelitianGenerator
{
    /**
     * Memproses data dan template untuk membuat file Word.
     * * @param SuratPenelitian
     * @param Template $template Model Template yang sudah dipilih.
     * @return string Path relatif file Word yang berhasil dibuat.
     */
    public function generateWord(SuratPenelitian $surat, Template $template)
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
        $judulPenelitianUppercase = strtoupper($surat->judul_penelitian ?? '-');

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MITRA', $surat->mitra->nama_mitra ?? '-');
        $processor->setValue('NAMA_MAHASISWA', $surat->mahasiswa?->nama ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('PRODI', $mahasiswa?->prodi?->nama_prodi ?? '-');
        $processor->setValue('NIM', $surat->nim);
        $processor->setValue('TGL_MULAI', $tglMulai ?? '-');
        $processor->setValue('TGL_SELESAI', $tglSelesai ?? '-');
        $processor->setValue('JUDUL_PENELITIAN', $judulPenelitianUppercase ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');

        // Direktori Output
        $outputFileName    = "SURAT_IZIN_PENELITIAN_{$surat->nim}_{$surat->id_surat_izin_penelitian}.docx";
        $outputFileRelatif = "surat_penelitian/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        $processor->saveAs($outputPathAbsolut);

        return $outputFileRelatif;
    }
}
