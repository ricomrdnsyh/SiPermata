<?php

namespace App\Services;

use App\Mail\NotifikasiPengajuanDekan;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifikasiDekanService
{
    public static function kirimMenungguDekan(Mahasiswa $mahasiswa, $pengajuan, string $namaSurat, $urlDetail = null)
    {
        if (! $mahasiswa->fakultas_id) {
            Log::warning("Mahasiswa NIM {$mahasiswa->nim} tidak memiliki fakultas_id saat kirim notifikasi Dekan.");
            return;
        }

        $dekanUsers = User::whereHas('penduduk', function ($q) use ($mahasiswa) {
            $q->where('fakultas_id', $mahasiswa->fakultas_id)
                ->whereNotNull('email')
                ->whereHas('jabatan', function ($q2) {
                    $q2->where('status', 'DEKAN');
                });
        })
            ->with(['penduduk.jabatan'])
            ->get();

        if ($dekanUsers->isEmpty()) {
            Log::warning("Tidak ada user DEKAN untuk fakultas_id {$mahasiswa->fakultas_id}.");
            return;
        }

        foreach ($dekanUsers as $dekan) {
            $email = $dekan->penduduk->email ?? null;

            if (! $email) {
                continue;
            }

            try {
                Mail::to($email)->send(
                    new NotifikasiPengajuanDekan(
                        $mahasiswa,
                        $pengajuan,
                        $namaSurat,
                        $urlDetail
                    )
                );
            } catch (\Exception $e) {
                Log::error(
                    "Gagal kirim email pengajuan ke Dekan (user_id={$dekan->id}, history_id={$pengajuan->id_history}): " .
                        $e->getMessage()
                );
            }
        }
    }
}
