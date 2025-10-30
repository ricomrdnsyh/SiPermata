<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratPenelitian;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MITRA', $surat->mitra->nama_mitra ?? '-');
        $processor->setValue('NAMA_MAHASISWA', $surat->mahasiswa?->nama ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('PRODI', $mahasiswa?->prodi?->nama_prodi ?? '-');
        $processor->setValue('NIM', $surat->nim);
        $processor->setValue('TGL_MULAI', $tglMulai ?? '-');
        $processor->setValue('TGL_SELESAI', $tglSelesai ?? '-');
        $processor->setValue('JUDUL_PENELITIAN', $surat->judul_penelitian ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');

        // Direktori Output
        $outputFileName    = "SURAT_IZIN_PENELITIAN_{$surat->nim}.docx";
        $outputFileRelatif = "surat_penelitian/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        $processor->saveAs($outputPathAbsolut);

        return $outputFileRelatif;
    }

    public function insertSignatureWithQR(SuratPenelitian $surat, string $jabatan, string $nama, $nidn)
    {
        $filePath = $surat->file_generated;
        $outputPathAbsolut = storage_path("app/{$filePath}");

        if (!file_exists($outputPathAbsolut)) {
            throw new \Exception("File surat tidak ditemukan: " . $outputPathAbsolut);
        }

        $qrData = route('verifikasi.surat-penelitian', ['id' => $surat->id_surat_izin_penelitian]);

        $qrCodeBinary = QrCode::size(100)
            ->format('png')
            ->errorCorrection('H')
            ->margin(1)
            ->generate($qrData);

        $qrTempFileName = 'temp_qr_' . time() . '.png';
        $qrTempPath = storage_path("app/temp/{$qrTempFileName}");
        Storage::put("temp/{$qrTempFileName}", $qrCodeBinary);

        try {
            $processor = new TemplateProcessor($outputPathAbsolut);

            $processor->setImageValue('TTD_QR', [
                'path' => $qrTempPath,
                'width' => 100,
                'height' => 100,
                'ratio' => true
            ]);

            $processor->setValue('JABATAN', $jabatan);
            $processor->setValue('NAMA_DEKAN', $nama);
            $processor->setValue('NIDN', $nidn);

            $processor->saveAs($outputPathAbsolut);
        } finally {
            Storage::delete("temp/{$qrTempFileName}");
        }

        return $filePath;
    }
}
