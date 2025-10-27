<?php

namespace App\Http\Controllers\BAK;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Http\Controllers\Controller;
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
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

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

    public function approve($id)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $pengajuan = HistoryPengajuan::findOrFail($id);

        $suratAktif = $pengajuan->suratAktif;

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        if ($pengajuan->status !== 'pengajuan') {
            return redirect()->back()->with('failed', 'Surat ini sudah diproses.');
        }

        $pengajuan->update([
            'status' => 'proses',
            'catatan' => 'Disetujui oleh BAK',
            'jabatan_id' => $user->penduduk->jabatan->id_jabatan
        ]);

        $suratAktif->update([
            'status' => 'proses',
            'catatan' => 'Disetujui oleh BAK',
        ]);

        return response()->json(['success' => true, 'message' => 'Pengajuan berhasil disetujui!']);
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        $request->validate([
            'catatan' => 'required'
        ]);

        $pengajuan = HistoryPengajuan::findOrFail($id);

        $suratAktif = $pengajuan->suratAktif;

        if ($pengajuan->fakultas_id !== $user->penduduk?->fakultas_id) {
            return redirect()->back()->with('failed', 'Akses ditolak');
        }

        if ($pengajuan->status !== 'pengajuan') {
            return redirect()->back()->with('failed', 'Surat ini sudah diproses.');
        }

        $pengajuan->update([
            'status'     => 'ditolak',
            'catatan'    => 'Ditolak oleh BAK: ' . $request->catatan,
            'jabatan_id' => $user->penduduk->jabatan->id_jabatan
        ]);

        $suratAktif->update([
            'status'     => 'ditolak',
            'catatan'    => 'Ditolak oleh BAK: ' . $request->catatan,
        ]);

        return response()->json(['success' => true, 'message' => 'Pengajuan berhasil ditolak!']);
    }
}
