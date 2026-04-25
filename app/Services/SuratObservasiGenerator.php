<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratObservasi;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

class SuratObservasiGenerator
{
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
     * * @param SuratObservasi
     * @param Template $template Model Template yang sudah dipilih.
     * @return string Path relatif file Word yang berhasil dibuat.
     */
    public function generateWord(SuratObservasi $surat, Template $template)
    {
        $surat->loadMissing(['mahasiswa.fakultas', 'mahasiswa.prodi', 'mitra']);

        $relativePathTemplate = $template->file;

        $templatePath = storage_path("app/{$relativePathTemplate}");

        if (!file_exists($templatePath)) {
            throw new \Exception("File template tidak ditemukan di: " . $templatePath);
        }

        // Load Template
        $processor = new TemplateProcessor($templatePath);

        $mahasiswa          = $surat->mahasiswa;
        $daftarMahasiswa    = $surat->daftar_mahasiswa;
        $tglSuratCarbon     = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon   = Carbon::parse($surat->updated_at);
        $tglObservasiCarbon = Carbon::parse($surat->tgl_observasi);

        $tglSurat       = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglObservasi   = $tglObservasiCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat     = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');
        $keperluanTitleCase = ucwords(strtolower($surat->keperluan));
        $namaMahasiswa = $daftarMahasiswa->pluck('nama')->filter()->implode(', ');
        $nimMahasiswa = $daftarMahasiswa->pluck('nim')->filter()->implode(', ');
        $prodiMahasiswa = $daftarMahasiswa->pluck('prodi')->filter()->implode(', ');

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MITRA', $surat->mitra?->nama_mitra ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('TGL_OBSERVASI', $tglObservasi ?? '-');
        $processor->setValue('KEPERLUAN', $keperluanTitleCase ?? '-');
        $processor->setValue('SEMESTER', $surat->semester ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');

        $variables = method_exists($processor, 'getVariables') ? $processor->getVariables() : [];
        $anggotaTableAnchor = $this->resolveAnggotaTableAnchor($templatePath, $variables);

        if ($anggotaTableAnchor !== null) {
            $processor->cloneRowAndSetValues(
                $anggotaTableAnchor,
                $daftarMahasiswa->values()->map(function ($anggota, $index) {
                    $row = [
                        'NAMA_MAHASISWA' => $anggota['nama'] ?? '-',
                        'NIM' => $anggota['nim'] ?? '-',
                        'PRODI' => $anggota['prodi'] ?? '-',
                    ];

                    if ($index !== null) {
                        $row['NO_ANGGOTA'] = $index + 1;
                    }

                    return $row;
                })->all()
            );
        } else {
            $processor->setValue('NAMA_MAHASISWA', $namaMahasiswa ?: '-');
            $processor->setValue('NIM', $nimMahasiswa ?: '-');
            $processor->setValue('PRODI', $prodiMahasiswa ?: ($mahasiswa?->prodi?->nama_prodi ?? '-'));
        }

        // Direktori Output
        $outputFileName    = "SURAT_IZIN_OBSERVASI_{$surat->nim}_{$surat->id_surat_observasi}.docx";
        $outputFileRelatif = "surat_observasi/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        $processor->saveAs($outputPathAbsolut);

        return $outputFileRelatif;
    }
}
