<?php

namespace App\Services;

use App\Mail\NotifikasiPengajuanBaruBAK;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifikasiBAKService
{
    public static function kirimPengajuanBaru(string $nim, $pengajuan, string $namaSurat, $urlDetail = null)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->with('fakultas')
            ->first();

        if (!$mahasiswa) {
            Log::warning("Mahasiswa dengan NIM {$nim} tidak ditemukan saat kirim notifikasi BAK.");
            return;
        }

        $bakUsers = User::whereHas('penduduk', function ($q) use ($mahasiswa) {
            $q->where('fakultas_id', $mahasiswa->fakultas_id)
                ->whereNotNull('email')
                ->whereHas('jabatan', function ($q2) {
                    $q2->where('status', 'BAK');
                });
        })
            ->with(['penduduk.jabatan'])
            ->get();

        if ($bakUsers->isEmpty()) {
            Log::warning("Tidak ada user BAK untuk fakultas_id {$mahasiswa->fakultas_id}.");
            return;
        }

        foreach ($bakUsers as $bak) {
            $email = $bak->penduduk->email ?? null;

            if (!$email) {
                continue;
            }

            try {
                Mail::to($email)->send(
                    new NotifikasiPengajuanBaruBAK(
                        $mahasiswa,
                        $pengajuan,
                        $namaSurat,
                        $urlDetail
                    )
                );
            } catch (\Exception $e) {
                Log::error(
                    "Gagal kirim email pengajuan baru ke BAK (user_id={$bak->id}, history_id={$pengajuan->id_history}): " .
                        $e->getMessage()
                );
            }
        }
    }
}
