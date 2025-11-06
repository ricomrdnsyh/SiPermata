<?php

namespace App\Http\Controllers;

use App\Models\SuratPKL;
use App\Models\TtdSurat;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;

class VerifikasiController extends Controller
{
    public function verifySuratAktif(string $id)
    {
        $surat = SuratAktif::where('id_surat_aktif', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
            ->first();

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

    public function verifySuratPenelitian(string $id)
    {
        $surat = SuratPenelitian::where('id_surat_izin_penelitian', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
            ->first();

        $fakultasId = $surat->mahasiswa->fakultas_id;
        $templateId = $surat->template_id;

        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        return view('verifikasi.surat_penelitian', [
            'surat' => $surat,
            'status_verifikasi' => 'Disetujui dan Ditandatangani oleh Dekan',
            'ttd_dekan' => $ttdDekan,
        ]);
    }

    public function verifySuratRekomendasi(string $id)
    {
        $surat = SuratRekomendasi::where('id_surat_rekomendasi', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
            ->first();

        $fakultasId = $surat->mahasiswa->fakultas_id;
        $templateId = $surat->template_id;

        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        return view('verifikasi.surat_rekomendasi', [
            'surat' => $surat,
            'status_verifikasi' => 'Disetujui dan Ditandatangani oleh Dekan',
            'ttd_dekan' => $ttdDekan,
        ]);
    }

    public function verifySuratPKL(string $id)
    {
        $surat = SuratPKL::where('id_surat_pkl', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
            ->first();

        $fakultasId = $surat->mahasiswa->fakultas_id;
        $templateId = $surat->template_id;

        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        return view('verifikasi.surat_pkl', [
            'surat' => $surat,
            'status_verifikasi' => 'Disetujui dan Ditandatangani oleh Dekan',
            'ttd_dekan' => $ttdDekan,
        ]);
    }
}
