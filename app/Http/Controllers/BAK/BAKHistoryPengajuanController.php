<?php

namespace App\Http\Controllers\BAK;

use App\Http\Controllers\Controller;
use App\Mail\NotifikasiStatusBak;
use App\Models\HistoryPengajuan;
use App\Models\Mahasiswa;
use App\Models\PengajuanStatusLog;
use App\Models\Prodi;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratPKL;
use App\Models\SuratRekomendasi;
use App\Models\TahunAkademik;
use App\Services\NotifikasiDekanService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

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
        $tabelNames = array_keys($this->listSurat); 

        if (!$request->has('tahun_akademik_filter')) {
            $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();
            if ($currentTahunAkademik) {
                $tahunAkademikFilter = $currentTahunAkademik->id_akademik;
            }
        }

        if (!empty($tahunAkademikFilter)) {

            $tahunAkademikColumnName = 'akademik_id';
            $idsPerTable = [];

            foreach ($tabelNames as $tabel) {

                $pkColumn = match ($tabel) {
                    'surat_aktif'            => 'id_surat_aktif',
                    'surat_izin_penelitian'  => 'id_surat_izin_penelitian',
                    'surat_observasi'        => 'id_surat_observasi',
                    'surat_rekomendasi'      => 'id_surat_rekomendasi',
                    'surat_pkl'              => 'id_surat_pkl',
                    'surat_keterangan_lulus' => 'id_surat_lulus',
                    default                  => 'id',
                };

                $ids = DB::table($tabel)
                    ->where($tahunAkademikColumnName, $tahunAkademikFilter)
                    ->pluck($pkColumn)
                    ->toArray();

                if (!empty($ids)) {
                    $idsPerTable[$tabel] = $ids;
                }
            }

            if (!empty($idsPerTable)) {

                $query->where(function ($q) use ($idsPerTable) {
                    foreach ($idsPerTable as $tabel => $ids) {
                        $q->orWhere(function ($sub) use ($tabel, $ids) {
                            $sub->where('tabel', $tabel)
                                ->whereIn('id_tabel_surat', $ids);
                        });
                    }
                });
            } else {
                $query->whereRaw('1 = 0');
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
            ->addColumn('id_history', fn($row) => (string) $row->id_history)
            ->addColumn('status_raw', fn($row) => (string) $row->status)
            ->addColumn('nama_mahasiswa', fn($row) => $row->mahasiswa?->nama ?? $row->nim)
            ->addColumn('prodi', fn($row) => $row->mahasiswa?->prodi?->nama_prodi ?? $row->nim)
            ->addColumn('nama_surat', fn($row) => $row->nama_surat)
            ->addColumn('tanggal_pengajuan', fn($row) => Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—')
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
            ->addColumn('catatan', fn($row) => $row->catatan ?: '<em>Tidak ada catatan</em>')
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('bak.history.detail', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';
                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . '</div>';
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

        $pengajuan = HistoryPengajuan::with('statusLogs')->findOrFail($id);

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

        $jumlahPengajuan = $pengajuan->statusLogs->where('status', 'pengajuan')->count();
        $jumlahDitolak   = $pengajuan->statusLogs->where('status', 'ditolak')->count();
        $jumlahDiterima  = $pengajuan->statusLogs->where('status', 'diterima')->count();

        $dataSimpt = $this->getDataSimpt($surat->nim);

        return view('bak.history.detail', compact(
            'pengajuan',
            'surat',
            'fileGeneratedPath',
            'jumlahPengajuan',
            'jumlahDitolak',
            'jumlahDiterima',
            'dataSimpt'
        ));
    }

    protected $suratModels = [
        'surat_aktif'            => SuratAktif::class,
        'surat_izin_penelitian'  => SuratPenelitian::class,
        'surat_rekomendasi'      => SuratRekomendasi::class,
        'surat_pkl'              => SuratPKL::class,
        'surat_observasi'        => SuratObservasi::class,
        'surat_keterangan_lulus' => SuratLulus::class,
        
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

        $jenisTabel   = $pengajuan->tabel;         
        $idSuratUtama = $pengajuan->id_tabel_surat;

        if (!isset($this->suratModels[$jenisTabel])) {
            return response()->json([
                'success' => false,
                'message' => "Jenis surat '{$jenisTabel}' tidak ditemukan dalam daftar mapping."
            ], 400);
        }

        $ModelSurat = $this->suratModels[$jenisTabel];

        $suratUtama = $ModelSurat::find($idSuratUtama);

        if (!$suratUtama) {
            return response()->json([
                'success' => false,
                'message' => "Data surat utama tidak ditemukan."
            ], 404);
        }

        $pengajuan->update([
            'status'     => 'proses',
            'catatan'    => 'Disetujui oleh BAK',
            'jabatan_id' => $user->penduduk?->jabatan?->id_jabatan
        ]);

        $suratUtama->update([
            'status'  => 'proses',
            'catatan' => 'Disetujui oleh BAK',
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history,
            'status'     => 'proses',
            'user_role'  => 'BAK',
            'user_id'    => $user->id,
            'catatan'    => 'Disetujui oleh BAK',
        ]);

        $namaSurat = strtoupper(ucwords(str_replace(['_', 'surat'], [' ', ''], $jenisTabel)));

        try {
            $mahasiswa = Mahasiswa::where('nim', $suratUtama->nim)
                ->with('fakultas')
                ->first();

            if ($mahasiswa && $mahasiswa->email) {
                Mail::to($mahasiswa->email)->send(
                    new NotifikasiStatusBak(
                        $mahasiswa,
                        $pengajuan,
                        'disetujui',
                        $namaSurat,
                        'Pengajuan Anda telah disetujui oleh BAK dan akan diproses oleh Dekan.'
                    )
                );
            }

            if ($mahasiswa) {
                $urlDetail = 'https://sso.unuja.ac.id';

                NotifikasiDekanService::kirimMenungguDekan(
                    $mahasiswa,
                    $pengajuan,
                    $namaSurat,
                    $urlDetail
                );
            }
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email notifikasi BAK (approve) untuk pengajuan {$pengajuan->id_history}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Pengajuan SURAT {$namaSurat} berhasil disetujui!"
        ]);
    }

    public function bulkApprove(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:20'],
            'ids.*' => ['integer'],
        ]);

        $ids = $data['ids'];
        $fakultasIdUser = $user->penduduk?->fakultas_id;

        DB::beginTransaction();
        try {
            $pengajuans = HistoryPengajuan::whereIn('id_history', $ids)
                ->where('fakultas_id', $fakultasIdUser)
                ->where('status', 'pengajuan')
                ->lockForUpdate()
                ->get();

            $approvedCount = 0;

            foreach ($pengajuans as $pengajuan) {
                $jenisTabel   = $pengajuan->tabel;
                $idSuratUtama = $pengajuan->id_tabel_surat;

                if (!isset($this->suratModels[$jenisTabel])) {
                    continue;
                }

                $ModelSurat = $this->suratModels[$jenisTabel];
                $suratUtama = $ModelSurat::find($idSuratUtama);

                if (!$suratUtama) {
                    continue;
                }

                $pengajuan->update([
                    'status'     => 'proses',
                    'catatan'    => 'Disetujui oleh BAK',
                    'jabatan_id' => $user->penduduk?->jabatan?->id_jabatan
                ]);

                $suratUtama->update([
                    'status'  => 'proses',
                    'catatan' => 'Disetujui oleh BAK',
                ]);

                PengajuanStatusLog::create([
                    'history_id' => $pengajuan->id_history,
                    'status'     => 'proses',
                    'user_role'  => 'BAK',
                    'user_id'    => $user->id,
                    'catatan'    => 'Disetujui oleh BAK',
                ]);

                $namaSurat = strtoupper(ucwords(str_replace(['_', 'surat'], [' ', ''], $jenisTabel)));

                try {
                    $mahasiswa = Mahasiswa::where('nim', $suratUtama->nim)->with('fakultas')->first();

                    if ($mahasiswa && $mahasiswa->email) {
                        Mail::to($mahasiswa->email)->send(
                            new NotifikasiStatusBak(
                                $mahasiswa,
                                $pengajuan,
                                'disetujui',
                                $namaSurat,
                                'Pengajuan Anda telah disetujui oleh BAK dan akan diproses oleh Dekan.'
                            )
                        );
                    }

                    if ($mahasiswa) {
                        $urlDetail = 'https://sso.unuja.ac.id';

                        NotifikasiDekanService::kirimMenungguDekan(
                            $mahasiswa,
                            $pengajuan,
                            $namaSurat,
                            $urlDetail
                        );
                    }
                } catch (\Exception $e) {
                    Log::error("Bulk approve: gagal notifikasi untuk {$pengajuan->id_history}: " . $e->getMessage());
                }

                $approvedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil approve {$approvedCount} pengajuan.",
                'approved_count' => $approvedCount
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Bulk approve gagal: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal approve bulk.'
            ], 500);
        }
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

        $jenisTabel   = $pengajuan->tabel;
        $idSuratUtama = $pengajuan->id_tabel_surat;

        if (!isset($this->suratModels[$jenisTabel])) {
            return response()->json([
                'success' => false,
                'message' => "Jenis surat '{$jenisTabel}' tidak ditemukan dalam daftar mapping."
            ], 400);
        }

        $ModelSurat = $this->suratModels[$jenisTabel];

        $suratUtama = $ModelSurat::find($idSuratUtama);

        if (!$suratUtama) {
            return response()->json([
                'success' => false,
                'message' => "Data surat utama tidak ditemukan."
            ], 404);
        }

        $catatanPenolakan = 'Ditolak oleh BAK: ' . $request->catatan;

        $pengajuan->update([
            'status'     => 'ditolak',
            'catatan'    => $catatanPenolakan,
            'jabatan_id' => $user->penduduk?->jabatan?->id_jabatan
        ]);

        $suratUtama->update([
            'status'  => 'ditolak',
            'catatan' => $catatanPenolakan,
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history,
            'status'     => 'ditolak',
            'user_role'  => 'BAK',
            'user_id'    => $user->id,
            'catatan'    => $catatanPenolakan,
        ]);

        $namaSurat = strtoupper(ucwords(str_replace(['_', 'surat'], [' ', ''], $jenisTabel)));

        try {
            $mahasiswa = Mahasiswa::where('nim', $suratUtama->nim)
                ->with('fakultas')
                ->first();

            if ($mahasiswa && $mahasiswa->email) {
                Mail::to($mahasiswa->email)->send(
                    new NotifikasiStatusBak(
                        $mahasiswa,
                        $pengajuan,
                        'ditolak',
                        $namaSurat,
                        $request->catatan
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email notifikasi BAK (reject) untuk pengajuan {$pengajuan->id_history}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Pengajuan SURAT {$namaSurat} berhasil ditolak!"
        ]);
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

    public function previewLampiranPdf(string $tabel, int $id): Response
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'BAK') abort(403);

        $modelClass = $this->getModelClass($tabel);
        if (!$modelClass) abort(404, 'Jenis surat tidak valid.');

        $surat = $modelClass::findOrFail($id);

        $disk = 'local';
        $docRel = $surat->file_generated ?? null;
        if (!$docRel) return response('Lampiran tidak ditemukan.', 404);
        if (!Storage::disk($disk)->exists($docRel)) return response('File lampiran di server tidak ditemukan.', 404);

        $docAbs = Storage::disk($disk)->path($docRel);

        $lastMod = Storage::disk($disk)->lastModified($docRel);
        $cacheRelDir = "preview_surat/{$tabel}";
        $cacheRelPdf = "{$cacheRelDir}/{$id}_{$lastMod}.pdf";
        Storage::disk($disk)->makeDirectory($cacheRelDir);

        if (!Storage::disk($disk)->exists($cacheRelPdf)) {
            $cacheAbsDir = Storage::disk($disk)->path($cacheRelDir);

            foreach (Storage::disk($disk)->files($cacheRelDir) as $f) {
                if (str_starts_with(basename($f), $id . '_') && str_ends_with($f, '.pdf')) {
                    Storage::disk($disk)->delete($f);
                }
            }

            $cmd = 'HOME=/tmp libreoffice --headless --nologo --nofirststartwizard --norestore '
                . '--convert-to pdf --outdir ' . escapeshellarg($cacheAbsDir) . ' ' . escapeshellarg($docAbs);

            $descriptors = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($cmd, $descriptors, $pipes);

            if (!is_resource($process)) {
                Log::error('Gagal membuka proses konversi DOCX->PDF', ['cmd' => $cmd]);
                return response('Gagal membuka proses konversi.', 500);
            }

            fclose($pipes[0]);

            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            $code = proc_close($process);

            $output = array_filter(
                array_merge(
                    explode("\n", $stdout),
                    explode("\n", $stderr)
                )
            );

            if ($code !== 0) {
                Log::error('Gagal konversi DOCX->PDF', [
                    'code'   => $code,
                    'output' => array_values($output),
                    'cmd'    => $cmd,
                ]);
                return response("Gagal konversi DOCX->PDF:\n" . implode("\n", $output), 500);
            }

            $pdfs = glob($cacheAbsDir . DIRECTORY_SEPARATOR . '*.pdf') ?: [];
            if (!$pdfs) return response('PDF hasil konversi tidak ditemukan.', 500);

            usort($pdfs, fn($a, $b) => filemtime($b) <=> filemtime($a));
            $generatedAbs = $pdfs[0];

            $finalAbs = Storage::disk($disk)->path($cacheRelPdf);

            if (!@rename($generatedAbs, $finalAbs)) {
                @copy($generatedAbs, $finalAbs);
                @unlink($generatedAbs);
            }

            if (!file_exists($finalAbs)) return response('Gagal menyimpan PDF cache.', 500);
        }

        $pdfAbs = Storage::disk($disk)->path($cacheRelPdf);

        return response()->file($pdfAbs, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="PREVIEW_' . $tabel . '_' . $id . '.pdf"',
        ]);
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

        
        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $fileName = strtoupper(str_replace(' ', '_', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.pdf';

        $absolutePath = Storage::disk($disk)->path($filePath);

        return response()->file($absolutePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    private function getDataSimpt(?string $nim): ?object
    {
        if (!$nim) return null;

        try {
            return DB::selectOne('
                SELECT
                    b.id_smt,
                    b.ipk_ketuntasan,
                    (
                        (LEFT(b.id_smt, 4) - LEFT(a.mulai_smt, 4)) * 2
                        + (RIGHT(b.id_smt, 1) - RIGHT(a.mulai_smt, 1))
                        + 1
                        + IF(max_smt.id_smt > b.id_smt, 1, 0)
                    ) AS semester
                FROM dbsimpt.tbmas_mahasiswa_pt a
                LEFT JOIN dbsimpt.tbbak_kuliah_mahasiswa b
                    ON a.id_mahasiswa_pt = b.id_mahasiswa_pt
                    AND b.ipk_ketuntasan IS NOT NULL
                LEFT JOIN (
                    SELECT id_mahasiswa_pt, MAX(id_smt) AS id_smt
                    FROM dbsimpt.tbbak_kuliah_mahasiswa
                    GROUP BY id_mahasiswa_pt
                ) max_smt ON a.id_mahasiswa_pt = max_smt.id_mahasiswa_pt
                WHERE a.nipd = ?
                ORDER BY b.id_smt DESC
                LIMIT 1
            ', [$nim]);
        } catch (Throwable $e) {
            Log::warning("Gagal mengambil data SIMPT untuk NIM: {$nim}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
