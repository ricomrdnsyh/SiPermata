<?php

namespace App\Services;

use App\Models\Template;
use App\Models\SuratAktif;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SuratAktifGenerator
{
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

        // Load Template
        $processor = new TemplateProcessor($templatePath);

        // 3. Set Data Variabel (Placeholder) ke dalam template
        $mahasiswa = $surat->mahasiswa;
        $tmtCarbon = Carbon::parse($surat->tmt);
        $tglSuratCarbon = Carbon::parse($surat->updated_at);
        $bulanSuratCarbon = Carbon::parse($surat->updated_at);

        $tmtOrtu = $tmtCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $tglSurat = $tglSuratCarbon->locale('id')->isoFormat('D MMMM YYYY');
        $bulanSurat = $bulanSuratCarbon->locale('id')->isoFormat('MM.YYYY');

        $processor->setValue('NO_SURAT', $surat->no_surat ?? '-');
        $processor->setValue('BULAN_SURAT', $bulanSurat ?? '-');
        $processor->setValue('NAMA_MAHASISWA', $surat->mahasiswa?->nama ?? '-');
        $processor->setValue('FAKULTAS', $mahasiswa?->fakultas?->nama_fakultas ?? '-');
        $processor->setValue('PRODI', $mahasiswa?->prodi?->nama_prodi ?? '-');
        $processor->setValue('NIM', $surat->nim);
        $processor->setValue('SEMESTER', $surat->semester ?? '-');
        $processor->setValue('TAHUN_AKADEMIK', $surat->akademik->tahun_akademik ?? '-');
        $processor->setValue('ALAMAT', $surat->alamat ?? '-');
        $processor->setValue('TANGGAL_SURAT', $tglSurat ?? '-');

        // Data Khusus (Hanya diisi jika kategori PNS/PPPK)
        if (in_array($surat->kategori, ['PNS', 'PPPK'])) {
            $processor->setValue('NIP_ORTU', $surat->nip);
            $processor->setValue('NAMA_ORTU', $surat->nama_ortu);
            $processor->setValue('PENDIDIKAN_TERAKHIR_ORTU', $surat->pendidikan_terakhir ?? '-');
            $processor->setValue('PANGKAT_ORTU', $surat->pangkat);
            $processor->setValue('GOLONGAN_ORTU', $surat->golongan);
            $processor->setValue('TMT_ORTU', $tmtOrtu);
            $processor->setValue('UNIT_KERJA_ORTU', $surat->unit_kerja);
        }

        // Direktori Output
        $outputFileName    = "SURAT_KETERANGAN_AKTIF_{$surat->kategori}_{$surat->nim}.docx";
        $outputFileRelatif = "surat_aktif/{$outputFileName}";
        $outputPathAbsolut = storage_path("app/{$outputFileRelatif}");

        $outputDirectory = dirname($outputPathAbsolut);
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0755, true);
        }

        // Simpan File Word Hasil Generate
        $processor->saveAs($outputPathAbsolut);

        // Kembalikan path relatif untuk disimpan di database SuratAktif
        return $outputFileRelatif;
    }

    public function insertSignatureWithQR(SuratAktif $surat, string $jabatan, string $nama, $nidn)
    {
        $filePath = $surat->file_generated;
        $outputPathAbsolut = storage_path("app/{$filePath}");

        if (!file_exists($outputPathAbsolut)) {
            throw new \Exception("File surat tidak ditemukan: " . $outputPathAbsolut);
        }

        // 1. Definisikan Data QR Code
        // Anda harus menentukan data apa yang akan dienkripsi di QR (misalnya, URL verifikasi)
        $qrData = route('verifikasi.surat-aktif', ['id' => $surat->id_surat_aktif]); // *Asumsi Anda memiliki route verifikasi*

        // 2. Generate Gambar QR Code
        // Buat QR code sebagai string biner PNG
        $qrCodeBinary = QrCode::size(100)
            ->format('png')
            ->errorCorrection('H')
            ->margin(1)
            ->generate($qrData);

        // Simpan sementara QR Code sebagai file untuk disisipkan oleh TemplateProcessor
        $qrTempFileName = 'temp_qr_' . time() . '.png';
        $qrTempPath = storage_path("app/temp/{$qrTempFileName}");
        Storage::put("temp/{$qrTempFileName}", $qrCodeBinary);

        try {
            // Load file Word yang sudah ada
            $processor = new TemplateProcessor($outputPathAbsolut);

            // 3. Sisipkan QR Code
            // Pastikan placeholder {TTD_QR} ada di template Word Anda
            $processor->setImageValue('TTD_QR', [
                'path' => $qrTempPath,
                'width' => 100,
                'height' => 100,
                'ratio' => true
            ]);

            // 4. Sisipkan Data Dekan
            $processor->setValue('JABATAN', $jabatan);
            $processor->setValue('NAMA_DEKAN', $nama);
            $processor->setValue('NIDN', $nidn);

            // 5. Simpan (Overwrite) file Word yang sudah dimodifikasi
            $processor->saveAs($outputPathAbsolut);
        } finally {
            // Hapus file QR Code sementara setelah selesai
            Storage::delete("temp/{$qrTempFileName}");
        }

        return $filePath; // Kembalikan path relatif yang sama
    }
}
