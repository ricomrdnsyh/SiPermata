<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mitra;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Template;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\SuratPenelitian;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SuratPenelitianGenerator;

class SuratPenelitianController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $listFakultas = Fakultas::all();
        $listProdi = Prodi::all();
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();

        return view('admin.surat_penelitian.index', compact('listFakultas', 'listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratPenelitian(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $query = SuratPenelitian::with(['mahasiswa.prodi', 'akademik', 'mitra']);

        if ($request->filled('fakultas_filter')) {
            $fakultasId = $request->input('fakultas_filter');
            $query->whereHas('mahasiswa', function ($q) use ($fakultasId) {
                $q->where('fakultas_id', $fakultasId);
            });
        }

        if ($request->filled('prodi_filter')) {
            $prodiId = $request->input('prodi_filter');
            $query->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
        }

        if ($request->filled('tahun_akademik_filter')) {
            $akademikId = $request->input('tahun_akademik_filter');
            $query->where('akademik_id', $akademikId);
        }

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->filterColumn('nama_mahasiswa', function ($query, $keyword) {
                $query->whereHas('mahasiswa', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('prodi', function ($query, $keyword) {
                $query->whereHas('mahasiswa.prodi', function ($q) use ($keyword) {
                    $q->where('nama_prodi', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('nama_mahasiswa', function ($row) {
                return $row->mahasiswa?->nama ?? $row->nim;
            })
            ->addColumn('prodi', function ($row) {
                return $row->mahasiswa?->prodi?->nama_prodi ?? $row->nim;
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                $date = \Carbon\Carbon::parse($row->tanggal_pengajuan_asli)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pengajuan' => '<span class="badge text-white bg-warning">Menunggu BAK</span>',
                    'proses'    => '<span class="badge text-white bg-info">Menunggu Dekan</span>',
                    'diterima'  => '<span class="badge text-white bg-success">Disetujui</span>',
                    'selesai'   => '<span class="badge text-white bg-primary">Selesai</span>',
                    'ditolak'   => '<span class="badge text-white bg-danger">Ditolak</span>',
                    default     => '<span class="badge text-white bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.surat-izin-penelitian.show', $row->id_surat_izin_penelitian) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.surat-izin-penelitian.edit', $row->id_surat_izin_penelitian) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $mahasiswa = Mahasiswa::all();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra     = Mitra::all();

        return view('admin.surat_penelitian.create', compact('mahasiswa', 'latestAkademik', 'mitra'));
    }

    
    public function store(Request $request, SuratPenelitianGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id'         => 'required|exists:mitra,id_mitra',
            'tgl_mulai'        => 'required',
            'tgl_selesai'      => 'required',
            'judul_penelitian' => 'required',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas Anda belum ditentukan.');
        }

        $namaTemplate = 'surat_izin_penelitian';

        $template = Template::where('jenis_surat', $namaTemplate)
            ->where('fakultas_id', $fakultasId)
            ->first();

        if (!$template) {
            return back()->with('failed', "Template untuk {$namaTemplate} belum tersedia untuk fakultas Anda.");
        }

        
        $noSurat = SuratPenelitian::getNextNoSurat($template->id_template, $request->akademik_id);

        $surat = SuratPenelitian::create([
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'mitra_id'            => $request->mitra_id,
            'tgl_mulai'           => $request->tgl_mulai,
            'tgl_selesai'         => $request->tgl_selesai,
            'judul_penelitian'    => $request->judul_penelitian,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan oleh Admin untuk mahasiswa',
            'file_generated'      => null,
        ]);

        try {
            
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            
            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            $surat->delete();
            return back()->with('failed', 'Gagal memproses template dokumen. Silakan coba lagi atau hubungi admin. Error: ' . $e->getMessage());
        }

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_izin_penelitian,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_izin_penelitian',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh mahasiswa',
            'jabatan_id'     => null,
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history,
            'status'     => 'pengajuan',
            'user_role'  => 'Admin',
            'user_id'    => $user->id,
            'catatan'    => 'Diajukan oleh Admin untuk mahasiswa',
        ]);

        return redirect()->route('admin.surat-izin-penelitian.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratPenelitian::with('mahasiswa')
            ->where('id_surat_izin_penelitian', $id)
            ->firstOrFail();

        return view('admin.surat_penelitian.show', compact('surat'));
    }

    
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratPenelitian::with('mahasiswa')
            ->where('id_surat_izin_penelitian', $id)
            ->firstOrFail();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra     = Mitra::all();
        $mahasiswa = Mahasiswa::all();

        return view('admin.surat_penelitian.edit', compact('surat', 'latestAkademik', 'mitra', 'mahasiswa'));
    }

    
    public function update(Request $request, string $id, SuratPenelitianGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'akademik_id'      => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id'         => 'required|exists:mitra,id_mitra',
            'tgl_mulai'        => 'required',
            'tgl_selesai'      => 'required',
            'judul_penelitian' => 'required',
        ]);

        $surat = SuratPenelitian::findOrFail($id);

        $pengajuan = $surat->historyPengajuan()
            ->where('nim', $request->nim)->firstOrFail();

        $surat->update([
            'nim'              => $request->nim,
            'akademik_id'      => $request->akademik_id,
            'mitra_id'         => $request->mitra_id,
            'tgl_mulai'        => $request->tgl_mulai,
            'tgl_selesai'      => $request->tgl_selesai,
            'judul_penelitian' => $request->judul_penelitian,
            'status'           => 'pengajuan',
            'catatan'          => 'Diajukan ulang oleh Admin untuk mahasiswa',
        ]);

        try {
            $template = Template::findOrFail($surat->template_id);

            $generatedFilePath = $generatorService->generateWord($surat, $template);

            $surat->update([
                'file_generated' => $generatedFilePath
            ]);

            $pengajuan->update([
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh BAK untuk mahasiswa'
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'Admin',
                'user_id'    => $user->id,
                'catatan'    => 'Diajukan ulang oleh Admin untuk mahasiswa',
            ]);

            return redirect()->route('admin.surat-izin-penelitian.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    
    public function destroy(string $id)
    {
        
    }
}
