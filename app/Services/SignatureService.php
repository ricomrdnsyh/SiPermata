<?php

namespace App\Services;

use App\Models\SuratPKL;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SignatureService
{
    public function insertSignatureWithQR(Model $suratModel, string $jabatan, string $nama, $nidn): string
    {
        $filePath          = $suratModel->file_generated;
        $outputPathAbsolut = storage_path("app/{$filePath}");
        $logoPath          = public_path('assets/media/logos/unuja.png');

        if (!file_exists($outputPathAbsolut)) {
            throw new \Exception("File surat tidak ditemukan: " . $outputPathAbsolut);
        }

        if (!file_exists($logoPath)) {
            Log::error("File logo untuk QR Code tidak ditemukan di: " . $logoPath);
            $logoPath = null;
        }

        if ($suratModel instanceof SuratAktif) {
            $qrData = route('verifikasi.surat-aktif', ['id' => $suratModel->id_surat_aktif]);
        } elseif ($suratModel instanceof SuratPenelitian) {
            $qrData = route('verifikasi.surat-izin-penelitian', ['id' => $suratModel->id_surat_izin_penelitian]);
        } elseif ($suratModel instanceof SuratRekomendasi) {
            $qrData = route('verifikasi.surat-rekomendasi', ['id' => $suratModel->id_surat_rekomendasi]);
        } elseif ($suratModel instanceof SuratPKL) {
            $qrData = route('verifikasi.surat-pkl', ['id' => $suratModel->id_surat_pkl]);
        } elseif ($suratModel instanceof SuratObservasi) {
            $qrData = route('verifikasi.surat-observasi', ['id' => $suratModel->id_surat_observasi]);
        } elseif ($suratModel instanceof SuratLulus) {
            $qrData = route('verifikasi.surat-keterangan-lulus', ['id' => $suratModel->id_surat_lulus]);
        } else {
            throw new \Exception("Jenis surat tidak didukung untuk penandatanganan.");
        }

        $qrCodeBuilder = QrCode::size(100)
            ->format('png')
            ->errorCorrection('H')
            ->margin(1);

        if ($logoPath) {
            $qrCodeBuilder->merge($logoPath, 0.3, true);
        }

        $qrCodeBinary = $qrCodeBuilder->generate($qrData);

        $qrTempFileName = 'temp_qr_' . time() . '.png';
        $qrTempPath     = storage_path("app/temp/{$qrTempFileName}");
        Storage::put("temp/{$qrTempFileName}", $qrCodeBinary);

        try {
            $processor = new TemplateProcessor($outputPathAbsolut);

            $processor->setImageValue('TTD_QR', [
                'path'   => $qrTempPath,
                'width'  => 100,
                'height' => 100,
                'ratio'  => true,
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

    public function convertDocxToPdf(string $wordFilePath): string
    {
        $docxPath = storage_path("app/{$wordFilePath}");

        if (!file_exists($docxPath)) {
            throw new \Exception("File Word tidak ditemukan: " . $docxPath);
        }

        $outputDir = dirname($docxPath);
        $pdfPath   = preg_replace('/\.(docx?|DOCX?)$/', '.pdf', $docxPath);

        $envPath   = env('LIBREOFFICE_PATH');
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $command   = null;

        if ($envPath && file_exists($envPath)) {
            if ($isWindows) {
                $command = sprintf(
                    '"%s" --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
                    $envPath,
                    $outputDir,
                    $docxPath
                );
            } else {
                $command = sprintf(
                    '%s --headless --convert-to pdf --outdir %s %s 2>&1',
                    escapeshellcmd($envPath),
                    escapeshellarg($outputDir),
                    escapeshellarg($docxPath)
                );
            }
        } else {
            if ($isWindows) {
                $librePaths = [
                    'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
                    'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
                ];

                if (getenv('PROGRAMFILES')) {
                    $librePaths[] = getenv('PROGRAMFILES') . '\\LibreOffice\\program\\soffice.exe';
                }
                if (getenv('PROGRAMFILES(X86)')) {
                    $librePaths[] = getenv('PROGRAMFILES(X86)') . '\\LibreOffice\\program\\soffice.exe';
                }

                $soffice = null;
                foreach ($librePaths as $path) {
                    if (file_exists($path)) {
                        $soffice = $path;
                        break;
                    }
                }

                if (!$soffice) {
                    throw new \Exception('LibreOffice tidak ditemukan di Windows.');
                }

                $command = sprintf(
                    '"%s" --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
                    $soffice,
                    $outputDir,
                    $docxPath
                );
            } else {
                $soffice = trim(shell_exec('which libreoffice') ?? '');
                if ($soffice === '') {
                    $soffice = trim(shell_exec('which soffice') ?? '');
                }

                if ($soffice === '') {
                    throw new \Exception('LibreOffice/soffice tidak ditemukan di PATH.');
                }

                $command = sprintf(
                    '%s --headless --convert-to pdf --outdir %s %s 2>&1',
                    escapeshellcmd($soffice),
                    escapeshellarg($outputDir),
                    escapeshellarg($docxPath)
                );
            }
        }

        if (!$command) {
            throw new \Exception('Perintah LibreOffice tidak dapat dibentuk.');
        }

        $output     = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        $maxWait       = $isWindows ? 10 : 5;
        $waited        = 0.0;
        $checkInterval = 0.5;

        while (!file_exists($pdfPath) && $waited < $maxWait) {
            usleep((int)($checkInterval * 1_000_000));
            $waited += $checkInterval;
        }

        if (!file_exists($pdfPath) || filesize($pdfPath) <= 1000) {
            throw new \Exception(
                "Konversi DOCX ke PDF gagal. Output: \n" . implode("\n", $output)
            );
        }

        if (file_exists($docxPath)) {
            @unlink($docxPath);
        }

        $relativePdfPath = preg_replace('/\.(docx?|DOCX?)$/', '.pdf', $wordFilePath);
        $relativePdfPath = str_replace('\\', '/', $relativePdfPath);

        return $relativePdfPath;
    }
}
