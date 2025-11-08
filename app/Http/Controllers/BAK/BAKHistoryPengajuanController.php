<?php

namespace App\Http\Controllers\BAK;

use App\Models\SuratPKL;
use App\Models\Mahasiswa;
use App\Models\SuratAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\SuratPenelitian;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use App\Http\Controllers\Controller;
use App\Models\SuratObservasi;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BAKHistoryPengajuanController extends Controller
{
    public function index()
    {
        return view('bak.history.index');
    }

    public function historyData()
    {
        $user = Auth::user();

        // Pastikan user adalah BAK
        if ($user->role !== 'BAK') {
            abort(403);
        }

        // Ambil fakultas_id dari data penduduk BAK
        $fakultasId = $user->penduduk?->fakultas_id;
        if (!$fakultasId) {
            return DataTables::of(HistoryPengajuan::whereRaw('1=0'))->make(true);
        }

        $query = HistoryPengajuan::with([])
            ->where('fakultas_id', $fakultasId)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'selesai', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('nama_mahasiswa', function ($row) {
                $mahasiswa = Mahasiswa::where('nim', $row->nim)->first();
                return $mahasiswa?->nama ?? $row->nim;
            })
            ->addColumn('prodi', function ($row) {
                $mahasiswa = Mahasiswa::where('nim', $row->nim)->first();
                return $mahasiswa?->prodi?->nama_prodi ?? $row->nim;
            })
            ->addColumn('nama_surat', function ($row) {
                return $row->nama_surat;
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                return Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM YYYY') ?? '—';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pengajuan' => '<span class="badge bg-warning">Menunggu BAK</span>',
                    'proses'    => '<span class="badge bg-info">Menunggu Dekan</span>',
                    'diterima'  => '<span class="badge bg-success">Disetujui</span>',
                    'selesai'   => '<span class="badge bg-primary">Selesai</span>',
                    'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
                    default     => '<span class="badge bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('bak.history.detail', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . '</div>';
            })
            ->rawColumns(['prodi', 'status', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') {
            abort(403);
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            abort(403, 'Surat ini bukan milik fakultas Anda.');
        }

        $surat = $pengajuan->surat;
        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan.');
        }

        return view('bak.history.detail', compact('pengajuan', 'surat'));
    }

    protected $suratModels = [
        'surat_aktif'           => SuratAktif::class,
        'surat_izin_penelitian' => SuratPenelitian::class,
        'surat_rekomendasi'     => SuratRekomendasi::class,
        'surat_pkl'             => SuratPKL::class,
        'surat_observasi'       => SuratObservasi::class,
        // Tambahkan jenis surat lain di sini
    ];

    public function approve($id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        if ($pengajuan->status !== 'pengajuan') {
            return redirect()->back()->with('failed', 'Surat ini sudah diproses.');
        }


        $jenisTabel   = $pengajuan->tabel; // Ambil nilai 'surat_aktif' atau 'surat_izin_penelitian'
        $idSuratUtama = $pengajuan->id_tabel_surat; // Ambil ID surat utama di tabel yang benar

        // A. Cek ketersediaan mapping
        if (!isset($this->suratModels[$jenisTabel])) {
            return response()->json(['success' => false, 'message' => "Jenis surat '{$jenisTabel}' tidak ditemukan dalam daftar mapping."], 400);
        }

        $ModelSurat = $this->suratModels[$jenisTabel];

        $suratUtama = $ModelSurat::find($idSuratUtama);

        if (!$suratUtama) {
            return response()->json(['success' => false, 'message' => "Data surat utama tidak ditemukan."], 404);
        }

        $pengajuan->update([
            'status'     => 'proses',
            'catatan'    => 'Disetujui oleh BAK',
            'jabatan_id' => $user->penduduk->jabatan->id_jabatan
        ]);

        $suratUtama->update([
            'status'  => 'proses',
            'catatan' => 'Disetujui oleh BAK',
        ]);

        $namaSurat = ucwords(str_replace(['_', 'surat'], [' ', ''], $jenisTabel));

        return response()->json(['success' => true, 'message' => "Pengajuan Surat {$namaSurat} berhasil disetujui!"]);
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $request->validate([
            'catatan' => 'required|string|max:500'
        ]);

        $pengajuan = HistoryPengajuan::findOrFail($id);

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        if ($pengajuan->status !== 'pengajuan') {
            return redirect()->back()->with('failed', 'Surat ini sudah diproses.');
        }


        $jenisTabel   = $pengajuan->tabel; // Contoh: 'surat_aktif' atau 'surat_izin_penelitian'
        $idSuratUtama = $pengajuan->id_tabel_surat; // ID surat di tabel utama

        // Cek ketersediaan mapping
        if (!isset($this->suratModels[$jenisTabel])) {
            return response()->json(['success' => false, 'message' => "Jenis surat '{$jenisTabel}' tidak ditemukan dalam daftar mapping."], 400);
        }

        $ModelSurat = $this->suratModels[$jenisTabel];

        $suratUtama = $ModelSurat::find($idSuratUtama);

        if (!$suratUtama) {
            return response()->json(['success' => false, 'message' => "Data surat utama tidak ditemukan."], 404);
        }

        $catatanPenolakan = 'Ditolak oleh BAK: ' . $request->catatan;

        $pengajuan->update([
            'status'  => 'ditolak',
            'catatan' => $catatanPenolakan,
            'jabatan_id' => $user->penduduk->jabatan->id_jabatan
        ]);

        $suratUtama->update([
            'status'  => 'ditolak',
            'catatan' => $catatanPenolakan,
        ]);

        $namaSurat = ucwords(str_replace(['_', 'surat'], [' ', ''], $jenisTabel));

        return response()->json(['success' => true, 'message' => "Pengajuan Surat {$namaSurat} berhasil ditolak!"]);
    }
}
