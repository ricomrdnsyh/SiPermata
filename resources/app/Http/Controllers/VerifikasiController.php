<?php

namespace App\Http\Controllers;

use App\Models\SuratPKL;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use Illuminate\Http\Request;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

class VerifikasiController extends Controller
{
    protected function gagal($surat, string $message)
    {
        return view('verifikasi.gagal', [
            'surat' => $surat,
            'status_verifikasi' => $message,
        ]);
    }

    protected function isSuratApproved($surat): bool
    {
        $approvedStatuses = ['diterima', 'selesai'];

        return in_array($surat->status, $approvedStatuses, true) ||
            in_array($surat->status_verifikasi, $approvedStatuses, true) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;
    }

    protected function validateFileGenerated($surat)
    {
        if (empty($surat->file_generated)) {
            return 'File surat belum digenerate.';
        }

        if (! Storage::disk('local')->exists($surat->file_generated)) {
            return 'File surat tidak ditemukan di server.';
        }

        return null;
    }

    public function streamPdf(string $jenis, string $encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        switch ($jenis) {
            case 'aktif':
                $surat = SuratAktif::where('id_surat_aktif', $id)->first();
                break;
            case 'penelitian':
                $surat = SuratPenelitian::where('id_surat_izin_penelitian', $id)->first();
                break;
            case 'rekomendasi':
                $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)->first();
                break;
            case 'pkl':
                $surat = SuratPKL::where('id_surat_pkl', $id)->first();
                break;
            case 'observasi':
                $surat = SuratObservasi::where('id_surat_observasi', $id)->first();
                break;
            case 'lulus':
                $surat = SuratLulus::where('id_surat_lulus', $id)->first();
                break;
            default:
                abort(404);
        }

        if (
            ! $surat ||
            empty($surat->file_generated) ||
            ! Storage::disk('local')->exists($surat->file_generated)
        ) {
            abort(404);
        }

        $fullPath = Storage::disk('local')->path($surat->file_generated);
        $mimeType = mime_content_type($fullPath) ?? 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($surat->file_generated) . '"',
            'Cache-Control' => 'public, max-age=7200',
            'X-Content-Type-Options' => 'nosniff',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 7200) . ' GMT',
            'X-Frame-Options' => 'SAMEORIGIN', // Izinkan iframe dari domain yang sama
            // Atau gunakan CSP yang lebih fleksibel:
            'Content-Security-Policy' => "frame-ancestors 'self'",
        ]);
    }

    public function verifySuratAktif(string $id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            $decryptedId = null;
        }

        $surat = SuratAktif::with(['mahasiswa', 'akademik'])
            ->where(function ($q) use ($id, $decryptedId) {
                if ($decryptedId !== null) {
                    $q->where('id_surat_aktif', $decryptedId);
                }

                $q->orWhere('id_surat_aktif', $id)
                    ->orWhere('no_surat', $id);
            })
            ->first();

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        if ($msg = $this->validateFileGenerated($surat)) {
            return $this->gagal($surat, $msg);
        }

        $pdfUrl = route('verifikasi.streamPdf', [
            'jenis' => 'aktif',
            'id'    => Crypt::encryptString($surat->id_surat_aktif),
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }


    public function verifySuratPenelitian(string $id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            $decryptedId = null;
        }

        $surat = SuratPenelitian::with(['mahasiswa', 'akademik'])
            ->where(function ($q) use ($id, $decryptedId) {
                if ($decryptedId !== null) {
                    $q->where('id_surat_izin_penelitian', $decryptedId);
                }

                $q->orWhere('id_surat_izin_penelitian', $id)
                    ->orWhere('no_surat', $id);
            })
            ->first();

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        if ($msg = $this->validateFileGenerated($surat)) {
            return $this->gagal($surat, $msg);
        }

        $pdfUrl = route('verifikasi.streamPdf', [
            'jenis' => 'penelitian',
            'id'    => Crypt::encryptString($surat->id_surat_izin_penelitian),
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }


    public function verifySuratRekomendasi(string $id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            $decryptedId = null;
        }

        $surat = SuratRekomendasi::with(['mahasiswa', 'akademik'])
            ->where(function ($q) use ($id, $decryptedId) {
                if ($decryptedId !== null) {
                    $q->where('id_surat_rekomendasi', $decryptedId);
                }

                $q->orWhere('id_surat_rekomendasi', $id)
                    ->orWhere('no_surat', $id);
            })
            ->first();

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        if ($msg = $this->validateFileGenerated($surat)) {
            return $this->gagal($surat, $msg);
        }

        $pdfUrl = route('verifikasi.streamPdf', [
            'jenis' => 'rekomendasi',
            'id'    => Crypt::encryptString($surat->id_surat_rekomendasi),
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }


    public function verifySuratPKL(string $id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            $decryptedId = null;
        }

        $surat = SuratPKL::with(['mahasiswa', 'akademik'])
            ->where(function ($q) use ($id, $decryptedId) {
                if ($decryptedId !== null) {
                    $q->where('id_surat_pkl', $decryptedId);
                }

                $q->orWhere('id_surat_pkl', $id)
                    ->orWhere('no_surat', $id);
            })
            ->first();

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        if ($msg = $this->validateFileGenerated($surat)) {
            return $this->gagal($surat, $msg);
        }

        $pdfUrl = route('verifikasi.streamPdf', [
            'jenis' => 'pkl',
            'id'    => Crypt::encryptString($surat->id_surat_pkl),
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }


    public function verifySuratObservasi(string $id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            $decryptedId = null;
        }

        $surat = SuratObservasi::with(['mahasiswa', 'akademik'])
            ->where(function ($q) use ($id, $decryptedId) {
                if ($decryptedId !== null) {
                    $q->where('id_surat_observasi', $decryptedId);
                }

                $q->orWhere('id_surat_observasi', $id)
                    ->orWhere('no_surat', $id);
            })
            ->first();

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        if ($msg = $this->validateFileGenerated($surat)) {
            return $this->gagal($surat, $msg);
        }

        $pdfUrl = route('verifikasi.streamPdf', [
            'jenis' => 'observasi',
            'id'    => Crypt::encryptString($surat->id_surat_observasi),
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }


    public function verifySuratLulus(string $id)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            $decryptedId = null;
        }

        $surat = SuratLulus::with(['mahasiswa', 'akademik'])
            ->where(function ($q) use ($id, $decryptedId) {
                if ($decryptedId !== null) {
                    $q->where('id_surat_lulus', $decryptedId);
                }

                $q->orWhere('id_surat_lulus', $id)
                    ->orWhere('no_surat', $id);
            })
            ->first();

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        if ($msg = $this->validateFileGenerated($surat)) {
            return $this->gagal($surat, $msg);
        }

        $pdfUrl = route('verifikasi.streamPdf', [
            'jenis' => 'lulus',
            'id'    => Crypt::encryptString($surat->id_surat_lulus),
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }
}
