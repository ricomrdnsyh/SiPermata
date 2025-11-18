<?php

namespace App\Http\Controllers\BAK;

use App\Models\Prodi;
use App\Models\SuratPKL;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\SuratObservasi;
use Illuminate\Support\Carbon;
use App\Models\SuratPenelitian;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class BAKHistoryPengajuanController extends Controller
{
    protected $listSurat = [
        'surat_aktif' => 'Surat Keterangan Aktif',
        'surat_izin_penelitian' => 'Surat Izin Penelitian',
        'surat_observasi' => 'Surat Permohonan Observasi',
        'surat_rekomendasi' => 'Surat Rekomendasi',
        'surat_pkl' => 'Surat Permohonan PKL',
        'surat_keterangan_lulus' => 'Surat Keterangan Lulus',
    ];

    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasIdUser = $user->penduduk?->fakultas_id;

        $listProdi = collect();
        if ($fakultasIdUser) {
            $listProdi = Prodi::where('fakultas_id', $fakultasIdUser)->get();
        }

        $listNamaSurat = $this->listSurat;
        $listNamaSurat = $this->listSurat;
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = $listTahunAkademik->first() ? $listTahunAkademik->first()->tahun_akademik : null;

        return view('bak.history.index', compact('listProdi', 'listNamaSurat', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function historyData(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            abort(403);
        }

        $fakultasIdUser = $user->penduduk?->fakultas_id;

        if (!$fakultasIdUser) {
            return DataTables::of(HistoryPengajuan::whereRaw('1=0'))->make(true);
        }

        $query = HistoryPengajuan::with(['mahasiswa.prodi', 'mahasiswa.prodi.fakultas'])
            ->where('fakultas_id', $fakultasIdUser)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'selesai', 'ditolak']);

        $tahunAkademikFilter = $request->input('tahun_akademik_filter');
        $tabelNames = array_keys($this->listSurat); // nama tabel surat

        if (!$request->has('tahun_akademik_filter')) {
            $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();
            if ($currentTahunAkademik) {
                $tahunAkademikFilter = $currentTahunAkademik->id_akademik;
            }
        }

        if (!empty($tahunAkademikFilter)) {
            $unionQueries = [];
            $tahunAkademikColumnName = 'akademik_id';

            foreach ($tabelNames as $tabel) {

                $pkColumn = match ($tabel) {
                    'surat_aktif' => 'id_surat_aktif',
                    'surat_izin_penelitian' => 'id_surat_izin_penelitian',
                    'surat_observasi' => 'id_surat_observasi',
                    'surat_rekomendasi' => 'id_surat_rekomendasi',
                    'surat_pkl' => 'id_surat_pkl',
                    'surat_keterangan_lulus' => 'id_surat_lulus',
                    default => 'id',
                };

                $queryPart = DB::table($tabel)
                    ->select(DB::raw("{$pkColumn} AS id_surat_terkait"))
                    ->where($tahunAkademikColumnName, $tahunAkademikFilter);

                $unionQueries[] = $queryPart;
            }

            if (!empty($unionQueries)) {
                $baseQuery = array_shift($unionQueries);

                foreach ($unionQueries as $nextQuery) {
                    $baseQuery->unionAll($nextQuery);
                }

                $idSuratTerkait = $baseQuery->pluck('id_surat_terkait')->toArray();

                if (!empty($idSuratTerkait)) {
                    // Filter HistoryPengajuan berdasarkan ID surat yang match
                    $query->whereIn('id_tabel_surat', $idSuratTerkait);

                    // Filter mengambil history dari tabel yang di loop
                    $query->whereIn('tabel', $tabelNames);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        }

        if ($request->filled('prodi_filter')) {
            $prodiId = $request->input('prodi_filter');
            $query->whereHas('mahasiswa', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }

        if ($request->filled('nama_surat_filter')) {
            $query->where('tabel', $request->input('nama_surat_filter'));
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
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
            ->filterColumn('nama_surat', function ($query, $keyword) {
                $keyword = strtolower($keyword);

                if (str_contains('aktif', $keyword)) {
                    $query->orWhere('tabel', 'surat_aktif');
                }
                if (str_contains('penelitian', $keyword) || str_contains('izin', $keyword)) {
                    $query->orWhere('tabel', 'surat_izin_penelitian');
                }
                if (str_contains('rekomendasi', $keyword)) {
                    $query->orWhere('tabel', 'surat_rekomendasi');
                }
                if (str_contains('pkl', $keyword)) {
                    $query->orWhere('tabel', 'surat_pkl');
                }
                if (str_contains('observasi', $keyword)) {
                    $query->orWhere('tabel', 'surat_observasi');
                }
                if (str_contains('lulus', $keyword)) {
                    $query->orWhere('tabel', 'surat_keterangan_lulus');
                }
            })
            ->addColumn('nama_mahasiswa', fn($row) => $row->mahasiswa?->nama ?? $row->nim)
            ->addColumn('prodi', fn($row) => $row->mahasiswa?->prodi?->nama_prodi ?? $row->nim)
            ->addColumn('nama_surat', fn($row) => $row->nama_surat)
            ->addColumn('tanggal_pengajuan', fn($row) => Carbon::parse($row->created_at)->locale('id')->isoFormat('D MMMM YYYY') ?? '—')
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
            ->addColumn('catatan', fn($row) => $row->catatan ?: '<em>Tidak ada catatan</em>')
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('bak.history.detail', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';
                return '<div class="text-center">' . $showBtn . '</div>';
            })
            ->rawColumns(['prodi', 'status', 'action', 'catatan'])
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

        $surat = null;
        $fileGeneratedPath = null;

        $modelClass = $this->getModelClass($pengajuan->tabel);

        if ($modelClass) {
            $surat = $modelClass::find($pengajuan->id_tabel_surat);

            if ($surat) {
                $fileGeneratedPath = $surat->file_generated ?? null;
            }
        }

        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan di tabel sumber.');
        }

        return view('bak.history.detail', compact('pengajuan', 'surat', 'fileGeneratedPath'));
    }

    protected $suratModels = [
        'surat_aktif'            => SuratAktif::class,
        'surat_izin_penelitian'  => SuratPenelitian::class,
        'surat_rekomendasi'      => SuratRekomendasi::class,
        'surat_pkl'              => SuratPKL::class,
        'surat_observasi'        => SuratObservasi::class,
        'surat_keterangan_lulus' => SuratLulus::class,
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

    private function getModelClass($tableName)
    {
        switch ($tableName) {
            case 'surat_aktif':
                return SuratAktif::class;
            case 'surat_izin_penelitian':
                return SuratPenelitian::class;
            case 'surat_rekomendasi':
                return SuratRekomendasi::class;
            case 'surat_pkl':
                return SuratPKL::class;
            case 'surat_observasi':
                return SuratObservasi::class;
            case 'surat_keterangan_lulus':
                return SuratLulus::class;
            default:
                return null;
        }
    }

    public function viewGeneratedFile(string $tabel, int $id): Response
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') {
            abort(403);
        }

        $modelClass = $this->getModelClass($tabel);

        if (!$modelClass) {
            abort(404, 'Jenis surat tidak valid.');
        }

        $surat = $modelClass::find($id);

        if (!$surat || empty($surat->file_generated)) {
            abort(404, 'File surat tidak ditemukan atau belum disetujui/digenerate.');
        }

        $filePath = $surat->file_generated;
        $disk = 'local';

        // Cek keberadaan file
        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $fileName = ucfirst(str_replace('_', ' ', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.pdf';

        return Storage::download($filePath, $fileName);
    }
}
