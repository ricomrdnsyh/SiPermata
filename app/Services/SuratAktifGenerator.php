<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratAktif;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratAktifGenerator
{
    /**
     * Escape special characters for XML to prevent DOCX corruption.
     */
    private function escapeXml($value): string
    {
        return htmlspecialchars((string)($value ?: '-'), ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

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

        $processor = new TemplateProcessor($templatePath);

        $mahasiswa = $surat->mahasiswa;
        $tmtCarbon = Carbon::parse($surat->tmt);
        $tglSuratCarbon = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon = Carbon::parse($surat->updated_at);
        $alamatTitleCase = ucwords($surat->alamat);

        $tmtOrtu = $tmtCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglSurat = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');

        $processor->setValue('NO_SURAT', $this->escapeXml($surat->no_surat));
        $processor->setValue('BULAN_SURAT', $this->escapeXml($bulanSurat));
        $processor->setValue('NAMA_MAHASISWA', $this->escapeXml($surat->mahasiswa?->nama));
        $processor->setValue('FAKULTAS', $this->escapeXml($mahasiswa?->fakultas?->nama_fakultas));
        $processor->setValue('PRODI', $this->escapeXml($mahasiswa?->prodi?->nama_prodi));
        $processor->setValue('NIM', $this->escapeXml($surat->nim));
        $processor->setValue('SEMESTER', $this->escapeXml($surat->semester));
        $processor->setValue('TAHUN_AKADEMIK', $this->escapeXml($surat->akademik->tahun_akademik ?? '-'));
        $processor->setValue('ALAMAT', $this->escapeXml($alamatTitleCase));
        $processor->setValue('TANGGAL_SURAT', $this->escapeXml($tglSurat));

        if (in_array($surat->kategori, ['PNS', 'PPPK'])) {
            $processor->setValue('NIP_ORTU', $this->escapeXml($surat->nip));
            $processor->setValue('NAMA_ORTU', $this->escapeXml($surat->nama_ortu));
            $processor->setValue('PENDIDIKAN_TERAKHIR_ORTU', $this->escapeXml($surat->pendidikan_terakhir));
            $processor->setValue('PANGKAT_ORTU', $this->escapeXml($surat->pangkat));
            $processor->setValue('GOLONGAN_ORTU', $this->escapeXml($surat->golongan));
            $processor->setValue('TMT_ORTU', $this->escapeXml($tmtOrtu));
            $processor->setValue('UNIT_KERJA_ORTU', $this->escapeXml($surat->unit_kerja));
        }

        $outputFileName    = "SURAT_KETERANGAN_AKTIF_{$surat->kategori}_{$surat->nim}_{$surat->id_surat_aktif}.docx";
        $outputFileRelatif = "surat_aktif/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        $processor->saveAs($outputPathAbsolut);

        return $outputFileRelatif;
    }
}
