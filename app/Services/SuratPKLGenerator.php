<?php

namespace App\Services;

use App\Models\SuratPKL;
use App\Models\Template;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

class SuratPKLGenerator
{
    private function escapeXml($value): string
    {
        return htmlspecialchars((string)($value ?: '-'), ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function readTemplateDocumentXml(string $templatePath): ?string
    {
        $zip = new ZipArchive();

        if ($zip->open($templatePath) !== true) {
            return null;
        }

        $documentXml = $zip->getFromName('word/document.xml');
        $zip->close();

        return $documentXml !== false ? $documentXml : null;
    }

    private function resolveAnggotaTableAnchor(string $templatePath, array $variables): ?string
    {
        $documentXml = $this->readTemplateDocumentXml($templatePath);

        if ($documentXml !== null && preg_match_all('/<w:tr\b[^>]*>.*?<\/w:tr>/s', $documentXml, $matches)) {
            foreach ($matches[0] as $rowXml) {
                if (
                    str_contains($rowXml, 'NAMA_MAHASISWA')
                    && str_contains($rowXml, 'PRODI')
                    && str_contains($rowXml, '${')
                ) {
                    return 'NAMA_MAHASISWA';
                }
            }
        }

        if (in_array('NO_ANGGOTA', $variables, true)) {
            return 'NO_ANGGOTA';
        }

        return null;
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
        $surat->loadMissing(['mahasiswa.fakultas', 'mahasiswa.prodi', 'mitra']);

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

        $mahasiswa       = $surat->mahasiswa;
        $daftarMahasiswa = $surat->daftar_mahasiswa;

        $tglSuratCarbon   = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon = Carbon::parse($surat->updated_at);
        $tglMulaiCarbon   = Carbon::parse($surat->tgl_mulai);
        $tglSelesaiCarbon = Carbon::parse($surat->tgl_selesai);

        $tglSurat   = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglMulai   = $tglMulaiCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglSelesai = $tglSelesaiCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');

        // Gabungkan nama/nim/prodi semua mahasiswa untuk placeholder tunggal (fallback)
        $namaMahasiswa = $daftarMahasiswa->pluck('nama')->filter()->implode(', ');
        $nimMahasiswa  = $daftarMahasiswa->pluck('nim')->filter()->implode(', ');
        $prodiMahasiswa = $daftarMahasiswa->pluck('prodi')->filter()->implode(', ');

        $processor->setValue('NO_SURAT',       $this->escapeXml($surat->no_surat));
        $processor->setValue('BULAN_SURAT',    $this->escapeXml($bulanSurat));
        $processor->setValue('NAMA_MITRA',     $this->escapeXml($surat->mitra?->nama_mitra));
        $processor->setValue('FAKULTAS',       $this->escapeXml($mahasiswa?->fakultas?->nama_fakultas));
        $processor->setValue('TGL_MULAI',      $this->escapeXml($tglMulai));
        $processor->setValue('TGL_SELESAI',    $this->escapeXml($tglSelesai));
        $processor->setValue('TANGGAL_SURAT',  $this->escapeXml($tglSurat));

        $variables = method_exists($processor, 'getVariables') ? $processor->getVariables() : [];
        $anggotaTableAnchor = $this->resolveAnggotaTableAnchor($templatePath, $variables);

        if ($anggotaTableAnchor !== null) {
            $processor->cloneRowAndSetValues(
                $anggotaTableAnchor,
                $daftarMahasiswa->values()->map(function ($anggota, $index) {
                    $row = [
                        'NAMA_MAHASISWA' => $this->escapeXml($anggota['nama'] ?? null),
                        'NIM' => $this->escapeXml($anggota['nim'] ?? null),
                        'PRODI' => $this->escapeXml($anggota['prodi'] ?? null),
                    ];

                    if ($index !== null) {
                        $row['NO_ANGGOTA'] = $index + 1;
                    }

                    return $row;
                })->all()
            );
        } else {
            $processor->setValue('NAMA_MAHASISWA', $this->escapeXml($namaMahasiswa ?: ($mahasiswa?->nama)));
            $processor->setValue('NIM',            $this->escapeXml($nimMahasiswa ?: ((string) $surat->nim)));
            $processor->setValue('PRODI',          $this->escapeXml($prodiMahasiswa ?: ($mahasiswa?->prodi?->nama_prodi)));
        }

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

        return $outputFileRelatif;
    }
}

