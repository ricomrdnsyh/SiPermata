<?php

namespace App\Http\Controllers;

use App\Models\SuratPKL;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerifikasiController extends Controller
{
    /**
     * Helper untuk view gagal.
     */
    protected function gagal($surat, string $message)
    {
        return view('verifikasi.gagal', [
            'surat' => $surat,
            'status_verifikasi' => $message,
        ]);
    }

    /**
     * Helper untuk cek status approve.
     */
    protected function isSuratApproved($surat): bool
    {
        $approvedStatuses = ['diterima', 'selesai'];

        return in_array($surat->status, $approvedStatuses, true) ||
            in_array($surat->status_verifikasi, $approvedStatuses, true) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;
    }

    /**
     * Helper: cek file_generated dan keberadaan file di disk local.
     */
    protected function validateFileGenerated($surat)
    {
        if (empty($surat->file_generated)) {
            return 'File surat belum digenerate.';
        }

        // GANTI missing() -> exists()
        if (! Storage::disk('local')->exists($surat->file_generated)) {
            return 'File surat tidak ditemukan di server.';
        }

        return null; // null = tidak ada error
    }

    /**
     * Route umum untuk stream PDF dari disk local.
     * Dipanggil oleh iframe di view lihat_pdf.
     */
    public function streamPdf(string $jenis, string $id)
    {
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

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * ---- VERIFIKASI TIAP JENIS SURAT ----
     * Kalau sukses -> view lihat_pdf (iframe full-screen)
     */

    public function verifySuratAktif(string $id)
    {
        $surat = SuratAktif::where('id_surat_aktif', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
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
            'id'    => $surat->id_surat_aktif,
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }

    public function verifySuratPenelitian(string $id)
    {
        $surat = SuratPenelitian::where('id_surat_izin_penelitian', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
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
            'id'    => $surat->id_surat_izin_penelitian,
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }

    public function verifySuratRekomendasi(string $id)
    {
        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
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
            'id'    => $surat->id_surat_rekomendasi,
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }

    public function verifySuratPKL(string $id)
    {
        $surat = SuratPKL::where('id_surat_pkl', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
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
            'id'    => $surat->id_surat_pkl,
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }

    public function verifySuratObservasi(string $id)
    {
        $surat = SuratObservasi::where('id_surat_observasi', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
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
            'id'    => $surat->id_surat_observasi,
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }

    public function verifySuratLulus(string $id)
    {
        $surat = SuratLulus::where('id_surat_lulus', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
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
            'id'    => $surat->id_surat_lulus,
        ]);

        return view('verifikasi.lihat_pdf', [
            'pdf_url' => $pdfUrl,
        ]);
    }
}
