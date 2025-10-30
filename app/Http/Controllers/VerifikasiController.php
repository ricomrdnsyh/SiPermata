<?php

namespace App\Http\Controllers;

use App\Models\TtdSurat;
use App\Models\SuratAktif;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function verifySuratAktif(string $id)
    {
        $surat = SuratAktif::where('id_surat_aktif', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
            ->first();

        if (!$surat) {
            return view('verifikasi.gagal', [
                'message' => 'Surat tidak ditemukan atau kode verifikasi tidak valid.'
            ]);
        }

        if ($surat->status !== 'diterima') {
            return view('verifikasi.gagal', [
                'message' => 'Surat ini belum disetujui (Status: ' . $surat->status . '). Verifikasi gagal.'
            ]);
        }

        $fakultasId = $surat->mahasiswa->fakultas_id;
        $templateId = $surat->template_id;

        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        return view('verifikasi.surat_aktif', [
            'surat' => $surat,
            'status_verifikasi' => 'Disetujui dan Ditandatangani oleh Dekan',
            'ttd_dekan' => $ttdDekan,
        ]);
    }
}
