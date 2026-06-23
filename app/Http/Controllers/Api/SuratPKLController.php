<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SuratPKL;
use App\Models\Mahasiswa;

class SuratPKLController extends Controller
{
    public function checkStatus($nim)
    {
        $mahasiswa = Mahasiswa::with('prodi')->where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan.'
            ], 404);
        }

        $suratPkl = SuratPKL::with(['mitra'])
            ->where(function($query) use ($nim) {
                $query->where('nim', $nim)
                      ->orWhere('anggota_kelompok', 'LIKE', '%"nim":' . $nim . '%')
                      ->orWhere('anggota_kelompok', 'LIKE', '%"nim":"' . $nim . '"%');
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $dataMahasiswa = [
            'nim' => $mahasiswa->nim,
            'nama' => $mahasiswa->nama,
            'id_prodi' => $mahasiswa->prodi_id,
            'prodi' => $mahasiswa->prodi ? $mahasiswa->prodi->nama_prodi : null,
        ];

        if (!$suratPkl) {
            return response()->json([
                'success' => true,
                'is_mengajukan' => false,
                'message' => 'Mahasiswa belum pernah mengajukan surat PKL.',
                'data' => array_merge($dataMahasiswa, [
                    'no_surat' => null,
                    'status' => null,
                    'tgl_mulai' => null,
                    'tgl_selesai' => null,
                    'mitra' => null,
                    'catatan' => null,
                    'is_ketua' => null,
                    'tanggal_pengajuan' => null,
                ])
            ]);
        }

        return response()->json([
            'success' => true,
            'is_mengajukan' => true,
            'message' => 'Data pengajuan surat PKL ditemukan.',
            'data' => array_merge($dataMahasiswa, [
                'no_surat' => $suratPkl->no_surat,
                'status' => $suratPkl->status,
                'tgl_mulai' => $suratPkl->tgl_mulai ? $suratPkl->tgl_mulai->format('Y-m-d') : null,
                'tgl_selesai' => $suratPkl->tgl_selesai ? $suratPkl->tgl_selesai->format('Y-m-d') : null,
                'mitra' => $suratPkl->mitra ? $suratPkl->mitra->nama_mitra : null,
                'catatan' => $suratPkl->catatan,
                'is_ketua' => $suratPkl->nim === $nim,
                'tanggal_pengajuan' => $suratPkl->created_at->format('Y-m-d H:i:s'),
            ])
        ]);
    }
}
