<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\SuratPKL;
use App\Models\SuratAktif;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data Mahasiswa tidak ditemukan.');
        }

        $mahasiswaId = $mahasiswa->nim;

        $getSuratStatus = function ($model) use ($mahasiswaId) {
            $baseQuery = $model::where('nim', $mahasiswaId);

            $total = $baseQuery->count();

            $baseQuery = $model::where('nim', $mahasiswaId);
            $proses = $baseQuery->whereIn('status', ['pengajuan', 'proses', 'diterima'])->count();

            $baseQuery = $model::where('nim', $mahasiswaId);
            $selesai = $baseQuery->where('status', 'selesai')->count();

            $baseQuery = $model::where('nim', $mahasiswaId);
            $ditolak = $baseQuery->where('status', 'ditolak')->count();

            return [
                'total'   => $total,
                'proses'  => $proses,
                'selesai' => $selesai,
                'ditolak' => $ditolak,
            ];
        };

        $data = [
            'user_name'          => $mahasiswa->nama,
            'surat_aktif'        => $getSuratStatus(new SuratAktif()),
            'surat_penelitian'   => $getSuratStatus(new SuratPenelitian()),
            'surat_observasi'    => $getSuratStatus(new SuratObservasi()),
            'surat_rekomendasi'  => $getSuratStatus(new SuratRekomendasi()),
            'surat_pkl'          => $getSuratStatus(new SuratPKL()),
        ];

        return view('mahasiswa.dashboard.index', $data);
    }
}
