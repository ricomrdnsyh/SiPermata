<?php

namespace App\Services;

use App\Models\SuratAktif;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SignatureService
{
    /**
     * Menyisipkan TTD QR dan data dekan ke dalam file Word.
     * @param Model $suratModel Model surat dinamis (SuratAktif, SuratPenelitian, dll.).
     * @param string $jabatan Jabatan penanda tangan.
     * @param string $nama Nama penanda tangan.
     * @param string|null $nidn NIDN penanda tangan.
     * @return string Path relatif file Word yang sudah ditandatangani.
     */
    public function insertSignatureWithQR(Model $suratModel, string $jabatan, string $nama, $nidn): string
    {
        $filePath = $suratModel->file_generated;
        $outputPathAbsolut = storage_path("app/{$filePath}");

        if (!file_exists($outputPathAbsolut)) {
            throw new \Exception("File surat tidak ditemukan: " . $outputPathAbsolut);
        }

        // 1. Definisikan Data QR Code secara dinamis
        // Tentukan route verifikasi berdasarkan jenis model yang masuk
        if ($suratModel instanceof SuratAktif) {
            $qrData = route('verifikasi.surat-aktif', ['id' => $suratModel->id_surat_aktif]);
        } elseif ($suratModel instanceof SuratPenelitian) {
            $qrData = route('verifikasi.surat-izin-penelitian', ['id' => $suratModel->id_surat_izin_penelitian]);
        } elseif ($suratModel instanceof SuratRekomendasi) {
            $qrData = route('verifikasi.surat-rekomendasi', ['id' => $suratModel->id_surat_rekomendasi]);
        } else {
            // Jika Anda memiliki banyak jenis surat, pertimbangkan field 'tabel' di History
            throw new \Exception("Jenis surat tidak didukung untuk penandatanganan.");
        }

        // ... (Logika Generate QR Code dan Penyimpanan Sementara - IDENTIK) ...
        $qrCodeBinary = QrCode::size(100)
            ->format('png')
            ->errorCorrection('H')
            ->margin(1)
            ->generate($qrData);

        $qrTempFileName = 'temp_qr_' . time() . '.png';
        $qrTempPath = storage_path("app/temp/{$qrTempFileName}");
        Storage::put("temp/{$qrTempFileName}", $qrCodeBinary);

        try {
            // Load file Word yang sudah ada
            $processor = new TemplateProcessor($outputPathAbsolut);

            // 3. Sisipkan QR Code dan Data Dekan (IDENTIK)
            $processor->setImageValue('TTD_QR', [
                'path' => $qrTempPath,
                'width' => 100,
                'height' => 100,
                'ratio' => true
            ]);

            $processor->setValue('JABATAN', $jabatan);
            $processor->setValue('NAMA_DEKAN', $nama);
            $processor->setValue('NIDN', $nidn);

            // 4. Simpan (Overwrite) file Word yang sudah dimodifikasi
            $processor->saveAs($outputPathAbsolut);
        } finally {
            Storage::delete("temp/{$qrTempFileName}");
        }

        return $filePath;
    }
}
