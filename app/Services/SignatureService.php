<?php

namespace App\Services;

use ZipArchive;

use App\Models\SuratPKL;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use chillerlan\QRCode\QRCode;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use chillerlan\QRCode\Output\QROutputInterface;

class SignatureService
{
    private function escapeXml($value): string
    {
        return htmlspecialchars((string)($value ?: '-'), ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    public function insertSignatureWithQR(Model $suratModel, string $jabatan, string $nama, $nidn): string
    {
        $filePath          = $suratModel->file_generated;
        $outputPathAbsolut = storage_path("app/{$filePath}");
        $logoPath          = public_path('assets/media/logos/unuja.png');

        if (!file_exists($outputPathAbsolut)) {
            throw new \Exception("File surat tidak ditemukan: " . $outputPathAbsolut);
        }

        if ($suratModel instanceof SuratAktif) {
            $token  = Crypt::encryptString((string) $suratModel->id_surat_aktif);
            $qrData = route('verifikasi.surat-aktif', ['id' => $token]);
        } elseif ($suratModel instanceof SuratPenelitian) {
            $token  = Crypt::encryptString((string) $suratModel->id_surat_izin_penelitian);
            $qrData = route('verifikasi.surat-izin-penelitian', ['id' => $token]);
        } elseif ($suratModel instanceof SuratRekomendasi) {
            $token  = Crypt::encryptString((string) $suratModel->id_surat_rekomendasi);
            $qrData = route('verifikasi.surat-rekomendasi', ['id' => $token]);
        } elseif ($suratModel instanceof SuratPKL) {
            $token  = Crypt::encryptString((string) $suratModel->id_surat_pkl);
            $qrData = route('verifikasi.surat-pkl', ['id' => $token]);
        } elseif ($suratModel instanceof SuratObservasi) {
            $token  = Crypt::encryptString((string) $suratModel->id_surat_observasi);
            $qrData = route('verifikasi.surat-observasi', ['id' => $token]);
        } elseif ($suratModel instanceof SuratLulus) {
            $token  = Crypt::encryptString((string) $suratModel->id_surat_lulus);
            $qrData = route('verifikasi.surat-keterangan-lulus', ['id' => $token]);
        } else {
            throw new \Exception("Jenis surat tidak didukung untuk penandatanganan.");
        }

        $options = new QROptions([
            'outputType'     => QROutputInterface::GDIMAGE_PNG,
            'eccLevel'       => QRCode::ECC_H,
            'scale'          => 16,
            'outputBase64'   => false,
            'returnResource' => true,
            'addQuietzone'   => false,
            'quietzoneSize'  => 0,
        ]);

        $qrImage = (new QRCode($options))->render($qrData);

        $qrWidth  = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);

        if ($logoPath && file_exists($logoPath)) {
            $logo = imagecreatefrompng($logoPath);

            $logoSrcW = imagesx($logo);
            $logoSrcH = imagesy($logo);

            $logoMaxW = (int)($qrWidth * 0.30);
            $logoMaxH = (int)($qrHeight * 0.30);

            $ratio = min($logoMaxW / $logoSrcW, $logoMaxH / $logoSrcH);
            $logoW = (int)($logoSrcW * $ratio);
            $logoH = (int)($logoSrcH * $ratio);

            $logoResized = imagecreatetruecolor($logoW, $logoH);
            imagealphablending($logoResized, false);
            imagesavealpha($logoResized, true);
            $transparent = imagecolorallocatealpha($logoResized, 0, 0, 0, 127);
            imagefill($logoResized, 0, 0, $transparent);

            imagecopyresampled(
                $logoResized,
                $logo,
                0,
                0,
                0,
                0,
                $logoW,
                $logoH,
                $logoSrcW,
                $logoSrcH
            );

            $dstX = (int)(($qrWidth - $logoW) / 2);
            $dstY = (int)(($qrHeight - $logoH) / 2);

            imagecopy($qrImage, $logoResized, $dstX, $dstY, 0, 0, $logoW, $logoH);

            imagedestroy($logoResized);
            imagedestroy($logo);
        }

        $marginTop    = 5;
        $marginBottom = 5;

        $finalWidth  = $qrWidth;
        $finalHeight = $qrHeight + $marginTop + $marginBottom;

        $finalImage = imagecreatetruecolor($finalWidth, $finalHeight);
        $white      = imagecolorallocate($finalImage, 255, 255, 255);
        imagefill($finalImage, 0, 0, $white);

        imagecopy($finalImage, $qrImage, 0, $marginTop, 0, 0, $qrWidth, $qrHeight);

        ob_start();
        imagepng($finalImage);
        $qrCodeBinary = ob_get_clean();

        imagedestroy($qrImage);
        imagedestroy($finalImage);

        $qrTempFileName = 'temp_qr_' . time() . '.png';
        $qrTempPath     = storage_path("app/temp/{$qrTempFileName}");
        Storage::put("temp/{$qrTempFileName}", $qrCodeBinary);

        try {
            $processor = new TemplateProcessor($outputPathAbsolut);

            $processor->setImageValue('TTD_QR', [
                'path'   => $qrTempPath,
                'width'  => 140,
                'height' => 140,
                'ratio'  => true,
            ]);

            $processor->setValue('JABATAN', $this->escapeXml($jabatan));
            $processor->setValue('NAMA_DEKAN', $this->escapeXml($nama));
            $processor->setValue('NIDN', $this->escapeXml($nidn));

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

        $this->repairDocx($docxPath);

        $isWindows     = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $uniqueId      = uniqid('pdf_', true);
        $outputDir     = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $uniqueId;
        mkdir($outputDir, 0755, true);

        try {
            $uniqueProfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'lo_profile_' . uniqid('', true);
            $pdfFilter     = 'pdf:writer_pdf_Export:EmbedStandardFonts=true;SelectPdfVersion=1;Quality=100';
            $command       = $this->buildLibreOfficeCommand($pdfFilter, $outputDir, $docxPath, $uniqueProfile);

            Log::info("LibreOffice command: {$command}");

            $returnCode = $this->runCommand($command, $output);

            Log::info("LibreOffice exit code: {$returnCode}, output: " . implode(' | ', $output));

            $maxWait       = $isWindows ? 10 : 5;
            $waited        = 0.0;
            $checkInterval = 0.5;
            $foundPdf      = null;

            while ($waited < $maxWait) {
                clearstatcache();
                $pdfs = glob($outputDir . DIRECTORY_SEPARATOR . '*.pdf') ?: [];
                if (!empty($pdfs)) {
                    $foundPdf = $pdfs[0];
                    break;
                }
                usleep((int)($checkInterval * 1_000_000));
                $waited += $checkInterval;
            }

            if (!$foundPdf || !file_exists($foundPdf) || filesize($foundPdf) <= 1000) {
                Log::error("Konversi DOCX ke PDF gagal", [
                    'docxPath'  => $docxPath,
                    'exitCode'  => $returnCode,
                    'output'    => $output,
                    'command'   => $command,
                ]);

                throw new \Exception(
                    "Konversi DOCX ke PDF gagal (exit: {$returnCode}). Output: \n" . implode("\n", $output)
                );
            }

            $relativePdfPath = preg_replace('/\.(docx?|DOCX?)$/', '.pdf', $wordFilePath);
            $relativePdfPath = str_replace('\\', '/', $relativePdfPath);
            $finalPdfPath    = storage_path("app/{$relativePdfPath}");

            rename($foundPdf, $finalPdfPath);

            @unlink($docxPath);

            if (file_exists($uniqueProfile)) {
                $this->deleteDirectory($uniqueProfile);
            }

            return $relativePdfPath;

        } finally {
            if (is_dir($outputDir)) {
                $this->deleteDirectory($outputDir);
            }
        }
    }

    public function convertDocxToPdfPreview(string $docxAbsolutePath, string $outputDir): ?string
    {
        if (!file_exists($docxAbsolutePath)) {
            Log::error("convertDocxToPdfPreview: file DOCX tidak ditemukan: {$docxAbsolutePath}");
            return null;
        }

        $this->repairDocx($docxAbsolutePath);

        $uniqueOutputDir = $outputDir . DIRECTORY_SEPARATOR . uniqid('prev_', true);
        mkdir($uniqueOutputDir, 0755, true);

        $uniqueProfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'lo_profile_' . uniqid('', true);
        $pdfFilter     = 'pdf:writer_pdf_Export:EmbedStandardFonts=true;SelectPdfVersion=1;Quality=100';
        $command       = $this->buildLibreOfficeCommand($pdfFilter, $uniqueOutputDir, $docxAbsolutePath, $uniqueProfile);

        $returnCode = $this->runCommand($command, $output);

        $isWindows     = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $maxWait       = $isWindows ? 10 : 5;
        $waited        = 0.0;
        $checkInterval = 0.5;

        while ($waited < $maxWait) {
            clearstatcache();
            $pdfs = glob($uniqueOutputDir . DIRECTORY_SEPARATOR . '*.pdf') ?: [];
            if (!empty($pdfs)) {
                break;
            }
            usleep((int)($checkInterval * 1_000_000));
            $waited += $checkInterval;
        }

        $pdfs = glob($uniqueOutputDir . DIRECTORY_SEPARATOR . '*.pdf') ?: [];

        if (empty($pdfs)) {
            Log::error('convertDocxToPdfPreview: PDF tidak ditemukan setelah konversi', [
                'exitCode' => $returnCode,
                'output'   => $output,
                'command'  => $command,
            ]);
            $this->deleteDirectory($uniqueOutputDir);
            return null;
        }

        $finalPdf = $pdfs[0];

        if (file_exists($uniqueProfile)) {
            $this->deleteDirectory($uniqueProfile);
        }

        return $finalPdf;
    }

    private function runCommand(string $command, ?array &$output = []): int
    {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (!is_resource($process)) {
            Log::error('proc_open gagal membuka proses', ['command' => $command]);
            $output = [];
            return -1;
        }

        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        $output = array_values(array_filter(
            array_merge(
                explode("\n", $stdout ?? ''),
                explode("\n", $stderr ?? '')
            )
        ));

        return $exitCode;
    }

    private function findBinary(string $binaryName): string
    {
        $command = PHP_OS_FAMILY === 'Windows'
            ? "where {$binaryName}"
            : "which {$binaryName}";

        $this->runCommand($command . ' 2>&1', $output);

        $result = trim(implode("\n", $output));
        $lines  = array_filter(explode("\n", $result));

        return trim(reset($lines) ?: '');
    }

    private function repairDocx(string $docxPath): void
    {
        $tempPath = $docxPath . '.repair.tmp';

        $srcZip = new ZipArchive();
        $dstZip = new ZipArchive();

        if ($srcZip->open($docxPath) !== true) {
            Log::warning("repairDocx: gagal membuka source DOCX: {$docxPath}");
            return;
        }

        if ($dstZip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $srcZip->close();
            Log::warning("repairDocx: gagal membuat temp DOCX: {$tempPath}");
            return;
        }

        for ($i = 0; $i < $srcZip->numFiles; $i++) {
            $name    = $srcZip->getNameIndex($i);
            $content = $srcZip->getFromIndex($i);

            if ($content !== false) {
                $dstZip->addFromString($name, $content);
            }
        }

        $dstZip->close();
        $srcZip->close();

        if (file_exists($tempPath) && filesize($tempPath) > 0) {
            @unlink($docxPath);
            rename($tempPath, $docxPath);
            Log::info("repairDocx: berhasil memperbaiki DOCX: {$docxPath}");
        } else {
            @unlink($tempPath);
            Log::warning("repairDocx: file repair kosong, skip.");
        }
    }

    private function buildLibreOfficeCommand(string $pdfFilter, string $outputDir, string $docxPath, string $uniqueProfile): string
    {
        $envPath   = env('LIBREOFFICE_PATH');
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $command   = null;

        $profileFlag = '-env:UserInstallation=file:///' . str_replace('\\', '/', $uniqueProfile);
        $baseFlags   = '--headless --nologo --nofirststartwizard --nodefault --norestore --nolockcheck ' . $profileFlag;

        if ($envPath && file_exists($envPath)) {
            if ($isWindows) {
                $command = sprintf(
                    '"%s" %s --convert-to "%s" --outdir "%s" "%s" 2>&1',
                    $envPath,
                    $baseFlags,
                    $pdfFilter,
                    $outputDir,
                    $docxPath
                );
            } else {
                $command = sprintf(
                    'HOME=/tmp %s %s --convert-to %s --outdir %s %s 2>&1',
                    escapeshellcmd($envPath),
                    $baseFlags,
                    escapeshellarg($pdfFilter),
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
                    '"%s" %s --convert-to "%s" --outdir "%s" "%s" 2>&1',
                    $soffice,
                    $baseFlags,
                    $pdfFilter,
                    $outputDir,
                    $docxPath
                );
            } else {
                $soffice = $this->findBinary('libreoffice');
                if ($soffice === '') {
                    $soffice = $this->findBinary('soffice');
                }

                if ($soffice === '') {
                    throw new \Exception('LibreOffice/soffice tidak ditemukan di PATH.');
                }

                $command = sprintf(
                    'HOME=/tmp %s %s --convert-to %s --outdir %s %s 2>&1',
                    escapeshellcmd($soffice),
                    $baseFlags,
                    escapeshellarg($pdfFilter),
                    escapeshellarg($outputDir),
                    escapeshellarg($docxPath)
                );
            }
        }

        if (!$command) {
            throw new \Exception('Perintah LibreOffice tidak dapat dibentuk.');
        }

        return $command;
    }

    private function deleteDirectory(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}