<?php

namespace App\Services;

use App\Models\SuratPKL;
use App\Models\Template;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratPKLGenerator
{
    /**
     * @param string|null $value
     * @return string
     */
    private function sanitize(?string $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    /**
     * Memproses data dan template untuk membuat file Word.
     *
     * @param SuratPKL $surat
     * @param Template $template Model Template yang sudah dipilih.
     * @return string Path relatif file Word yang berhasil dibuat.
     * @throws \Exception
     */
    public function generateWord(SuratPKL $surat, Template $template): string
    {
        $relativePathTemplate = $template->file;
        $templatePath         = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: {$templatePath}");
        }

        if (!is_readable($templatePath)) {
            throw new \Exception("File template tidak bisa dibaca (permission denied): {$templatePath}");
        }

        $templateSize = filesize($templatePath);
        if ($templateSize === false || $templateSize < 1000) {
            throw new \Exception(
                "File template tampaknya corrupt atau terlalu kecil ({$templateSize} bytes): {$templatePath}"
            );
        }

        try {
            $processor = new TemplateProcessor($templatePath);
        } catch (\Exception $e) {
            throw new \Exception(
                "Gagal membaca template PhpWord. Pastikan file template adalah .docx yang valid. Error: "
                    . $e->getMessage()
            );
        }

        $mahasiswa = $surat->mahasiswa;

        $tglSuratCarbon   = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon = Carbon::parse($surat->updated_at);
        $tglMulaiCarbon   = Carbon::parse($surat->tgl_mulai);
        $tglSelesaiCarbon = Carbon::parse($surat->tgl_selesai);

        $tglSurat   = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglMulai   = $tglMulaiCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglSelesai = $tglSelesaiCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');

        $processor->setValue('NO_SURAT',       $this->sanitize($surat->no_surat));
        $processor->setValue('BULAN_SURAT',    $this->sanitize($bulanSurat));
        $processor->setValue('NAMA_MITRA',     $this->sanitize($surat->mitra?->nama_mitra));
        $processor->setValue('NAMA_MAHASISWA', $this->sanitize($mahasiswa?->nama));
        $processor->setValue('FAKULTAS',       $this->sanitize($mahasiswa?->fakultas?->nama_fakultas));
        $processor->setValue('PRODI',          $this->sanitize($mahasiswa?->prodi?->nama_prodi));
        $processor->setValue('NIM',            $this->sanitize((string) $surat->nim));
        $processor->setValue('TGL_MULAI',      $this->sanitize($tglMulai));
        $processor->setValue('TGL_SELESAI',    $this->sanitize($tglSelesai));
        $processor->setValue('TANGGAL_SURAT',  $this->sanitize($tglSurat));

        $outputFileName    = "SURAT_PKL_{$surat->nim}_{$surat->id_surat_pkl}.docx";
        $outputFileRelatif = "surat_pkl/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");
        $outputDirectory   = dirname($outputPathAbsolut);

        if (!file_exists($outputDirectory)) {
            if (!mkdir($outputDirectory, 0755, true)) {
                throw new \Exception("Gagal membuat direktori output: {$outputDirectory}");
            }
        }

        if (!is_writable($outputDirectory)) {
            throw new \Exception("Direktori output tidak bisa ditulis (permission denied): {$outputDirectory}");
        }

        try {
            $processor->saveAs($outputPathAbsolut);
        } catch (\Exception $e) {
            throw new \Exception(
                "Gagal menyimpan file Word ke: {$outputPathAbsolut}. Error: " . $e->getMessage()
            );
        }

        if (!file_exists($outputPathAbsolut)) {
            throw new \Exception("File output tidak ditemukan setelah saveAs: {$outputPathAbsolut}");
        }

        $outputSize = filesize($outputPathAbsolut);
        if ($outputSize === false || $outputSize < 1000) {
            @unlink($outputPathAbsolut);
            throw new \Exception(
                "File output terdeteksi corrupt atau terlalu kecil ({$outputSize} bytes). "
                    . "Periksa template dan data yang digunakan."
            );
        }

        Log::info("[SuratPKLGenerator] Berhasil membuat surat PKL.", [
            'surat_id'    => $surat->id_surat_pkl,
            'nim'         => $surat->nim,
            'output_path' => $outputFileRelatif,
            'file_size'   => $outputSize . ' bytes',
        ]);

        return $outputFileRelatif;
    }
}
