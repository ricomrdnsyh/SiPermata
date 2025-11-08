<?php

namespace App\Http\Controllers;

use App\Models\SuratPKL;
use App\Models\TtdSurat;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use App\Models\SuratObservasi;
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

        $approvedStatuses = ['diterima', 'selesai'];

        if (!$surat) {
            // Surat tidak ditemukan sama sekali
            return view('verifikasi.gagal', [
                'surat' => null,
                'status_verifikasi' => 'Surat tidak ditemukan.',
            ]);
        }

        $isApproved = in_array($surat->status, $approvedStatuses) ||
            in_array($surat->status_verifikasi, $approvedStatuses) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;

        if (!$isApproved) {
            return view('verifikasi.gagal', [
                'surat' => $surat,
                'status_verifikasi' => 'Surat belum disetujui atau masih dalam proses.',
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

    public function verifySuratPenelitian(string $id)
    {
        $surat = SuratPenelitian::where('id_surat_izin_penelitian', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
            ->first();

        $approvedStatuses = ['diterima', 'selesai'];

        if (!$surat) {
            // Surat tidak ditemukan sama sekali
            return view('verifikasi.gagal', [
                'surat' => null,
                'status_verifikasi' => 'Surat tidak ditemukan.',
            ]);
        }

        $isApproved = in_array($surat->status, $approvedStatuses) ||
            in_array($surat->status_verifikasi, $approvedStatuses) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;

        if (!$isApproved) {
            return view('verifikasi.gagal', [
                'surat' => $surat,
                'status_verifikasi' => 'Surat belum disetujui atau masih dalam proses.',
            ]);
        }

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

        $approvedStatuses = ['diterima', 'selesai'];

        if (!$surat) {
            // Surat tidak ditemukan sama sekali
            return view('verifikasi.gagal', [
                'surat' => null,
                'status_verifikasi' => 'Surat tidak ditemukan.',
            ]);
        }

        $isApproved = in_array($surat->status, $approvedStatuses) ||
            in_array($surat->status_verifikasi, $approvedStatuses) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;

        if (!$isApproved) {
            return view('verifikasi.gagal', [
                'surat' => $surat,
                'status_verifikasi' => 'Surat belum disetujui atau masih dalam proses.',
            ]);
        }

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

        $approvedStatuses = ['diterima', 'selesai'];

        if (!$surat) {
            // Surat tidak ditemukan sama sekali
            return view('verifikasi.gagal', [
                'surat' => null,
                'status_verifikasi' => 'Surat tidak ditemukan.',
            ]);
        }

        $isApproved = in_array($surat->status, $approvedStatuses) ||
            in_array($surat->status_verifikasi, $approvedStatuses) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;

        if (!$isApproved) {
            return view('verifikasi.gagal', [
                'surat' => $surat,
                'status_verifikasi' => 'Surat belum disetujui atau masih dalam proses.',
            ]);
        }

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

    public function verifySuratObservasi(string $id)
    {
        $surat = SuratObservasi::where('id_surat_observasi', $id)
            ->orWhere('no_surat', $id)
            ->with(['mahasiswa', 'akademik'])
            ->first();

        $approvedStatuses = ['diterima', 'selesai'];

        if (!$surat) {
            // Surat tidak ditemukan sama sekali
            return view('verifikasi.gagal', [
                'surat' => null,
                'status_verifikasi' => 'Surat tidak ditemukan.',
            ]);
        }

        $isApproved = in_array($surat->status, $approvedStatuses) ||
            in_array($surat->status_verifikasi, $approvedStatuses) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;

        if (!$isApproved) {
            return view('verifikasi.gagal', [
                'surat' => $surat,
                'status_verifikasi' => 'Surat belum disetujui atau masih dalam proses.',
            ]);
        }

        $fakultasId = $surat->mahasiswa->fakultas_id;
        $templateId = $surat->template_id;

        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        return view('verifikasi.surat_observasi', [
            'surat' => $surat,
            'status_verifikasi' => 'Disetujui dan Ditandatangani oleh Dekan',
            'ttd_dekan' => $ttdDekan,
        ]);
    }
}
