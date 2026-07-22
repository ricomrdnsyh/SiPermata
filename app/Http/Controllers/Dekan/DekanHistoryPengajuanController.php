<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Mail\NotifikasiStatusSurat;
use App\Mail\SuratSelesai;
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
use App\Models\TtdSurat;
use App\Services\SignatureService;
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

class DekanHistoryPengajuanController extends Controller
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

        if ($user->role !== 'DEKAN') {
            abort(403);
        }

        $fakultasIdUser = $user->penduduk?->fakultas_id;

        $listProdi = collect();
        if ($fakultasIdUser) {
            $listProdi = Prodi::where('fakultas_id', $fakultasIdUser)->get();
        }

        $listNamaSurat = $this->listSurat;
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = $listTahunAkademik->first() ? $listTahunAkademik->first()->tahun_akademik : null;

        return view('dekan.history.index', compact('listProdi', 'listNamaSurat', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function historyData(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
            abort(403);
        }

        $fakultasId = $user->penduduk?->fakultas_id;

        if (!$fakultasId) {
            return DataTables::of(HistoryPengajuan::whereRaw('1=0'))->make(true);
        }

        $query = HistoryPengajuan::with(['mahasiswa', 'mahasiswa.prodi'])
            ->where('fakultas_id', $fakultasId)
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
            ->addColumn('tabel_raw', fn($row) => (string) $row->tabel)
            ->addColumn('id_tabel_surat_raw', fn($row) => (int) $row->id_tabel_surat)

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
                $date = Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
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
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('dekan.history.detail', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . '</div>';
            })
            ->rawColumns(['prodi', 'status', 'action', 'tanggal_pengajuan', 'catatan'])
            ->make(true);
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

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
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

        return view('dekan.history.detail', compact(
            'pengajuan',
            'surat',
            'fileGeneratedPath',
            'jumlahPengajuan',
            'jumlahDitolak',
            'jumlahDiterima',
            'dataSimpt'
        ));
    }

    public function approve($id, SignatureService $signatureService)
    {
        $user = Auth::user();

        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya Dekan yang diizinkan.'], 403);
        }

        $pengajuan = HistoryPengajuan::find($id);

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan.'], 404);
        }

        if ($pengajuan->status !== 'proses') {
            return response()->json(['success' => false, 'message' => 'Surat ini sudah diproses atau ditolak sebelumnya.'], 400);
        }

        if (!isset($user->penduduk->fakultas_id) || $pengajuan->fakultas_id !== $user->penduduk->fakultas_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Fakultas tidak cocok.'], 403);
        }

        $modelClass = $this->getModelClass($pengajuan->tabel);

        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Jenis surat tidak valid atau tidak terdaftar.'], 400);
        }

        $detailSurat = $modelClass::find($pengajuan->id_tabel_surat);

        if (!$detailSurat) {
            return response()->json(['success' => false, 'message' => 'Detail surat (tabel ' . $pengajuan->tabel . ') tidak ditemukan.'], 404);
        }

        if (empty($detailSurat->file_generated)) {
            return response()->json(['success' => false, 'message' => 'File surat belum tersedia untuk ditandatangani.'], 400);
        }

        $fakultasId = $pengajuan->fakultas_id;
        $templateId = $detailSurat->template_id;

        $ttdDekan = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $templateId)
            ->where('status', 'aktif')
            ->first();

        if (!$ttdDekan) {
            return response()->json(['success' => false, 'message' => 'Data penanda tangan (TTD Dekan) untuk fakultas dan template ini tidak ditemukan atau tidak aktif.'], 404);
        }

        $namaDekan    = $ttdDekan->nama_ttd;
        $nidn         = $ttdDekan->nidn;
        $jabatanDekan = $user->penduduk?->jabatan?->status ?? 'Dekan';
        $idJabatan    = $user->penduduk?->jabatan?->id_jabatan ?? null;

        try {
            DB::beginTransaction();

            $docxFilePath = $signatureService->insertSignatureWithQR(
                $detailSurat,
                $jabatanDekan,
                $namaDekan,
                $nidn
            );

            $pdfFilePath = $signatureService->convertDocxToPdf($docxFilePath);

            $detailSurat->update([
                'status'        => 'diterima',
                'catatan'       => "Disetujui oleh Dekan: {$namaDekan}",
                'file_generated' => $pdfFilePath,
            ]);

            $pengajuan->update([
                'status'     => 'diterima',
                'catatan'    => 'Disetujui oleh Dekan: ' . $namaDekan,
                'jabatan_id' => $idJabatan,
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'diterima',
                'user_role'  => 'DEKAN',
                'user_id'    => $user->id,
                'catatan'    => 'Disetujui oleh Dekan: ' . $namaDekan,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menambahkan TTD QR pada pengajuan {$pengajuan->id_history}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses TTD QR. Silakan cek log server untuk detail lebih lanjut.'
            ], 500);
        }

        try {
            $mahasiswa = Mahasiswa::where('nim', $detailSurat->nim)->first();

            if ($mahasiswa && $mahasiswa->email) {
                Mail::to($mahasiswa->email)->send(
                    new NotifikasiStatusSurat(
                        $mahasiswa,
                        $pengajuan,
                        'disetujui',
                        "Disetujui oleh Dekan: {$namaDekan}"
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email notifikasi approve untuk pengajuan {$pengajuan->id_history}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil disetujui dan notifikasi email telah dikirim!'
        ], 200);
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'catatan' => 'required|string|max:500'
        ]);

        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Hanya Dekan yang diizinkan.'], 403);
        }

        $pengajuan = HistoryPengajuan::find($id);

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan.'], 404);
        }

        if ($pengajuan->status !== 'proses') {
            return response()->json(['success' => false, 'message' => 'Surat ini sudah diproses atau ditolak sebelumnya.'], 400);
        }

        if (!isset($user->penduduk->fakultas_id) || $pengajuan->fakultas_id !== $user->penduduk->fakultas_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Fakultas tidak cocok.'], 403);
        }

        $modelClass = $this->getModelClass($pengajuan->tabel);

        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Jenis surat tidak valid atau tidak terdaftar.'], 400);
        }

        $detailSurat = $modelClass::find($pengajuan->id_tabel_surat);

        if (!$detailSurat) {
            return response()->json(['success' => false, 'message' => 'Detail surat (tabel ' . $pengajuan->tabel . ') tidak ditemukan.'], 404);
        }

        try {
            DB::beginTransaction();

            $catatan   = 'Ditolak oleh Dekan: ' . $validated['catatan'];
            $idJabatan = $user->penduduk?->jabatan?->id_jabatan ?? null;

            $detailSurat->update([
                'status'  => 'ditolak',
                'catatan' => $catatan,
            ]);

            $pengajuan->update([
                'status'     => 'ditolak',
                'catatan'    => $catatan,
                'jabatan_id' => $idJabatan,
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'ditolak',
                'user_role'  => 'DEKAN',
                'user_id'    => $user->id,
                'catatan'    => $validated['catatan'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menolak pengajuan (ID History: {$id}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses penolakan. Silakan cek log server.'
            ], 500);
        }

        try {
            $mahasiswa = Mahasiswa::where('nim', $detailSurat->nim)->first();

            if ($mahasiswa && $mahasiswa->email) {
                Mail::to($mahasiswa->email)->send(
                    new NotifikasiStatusSurat(
                        $mahasiswa,
                        $pengajuan,
                        'ditolak',
                        $validated['catatan']
                    )
                );
            }
        } catch (\Exception $e) {
            Log::error("Gagal mengirim email notifikasi reject untuk pengajuan {$pengajuan->id_history}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil ditolak dan email notifikasi telah dikirim!.'
        ], 200);
    }

    public function bulkApprove(Request $request, SignatureService $signatureService)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:20'],
            'ids.*' => ['integer'],
        ]);

        $ids = $data['ids'];
        $fakultasId = $user->penduduk?->fakultas_id;

        $success = 0;
        $failed = [];

        foreach ($ids as $id) {
            try {
                DB::beginTransaction();

                $pengajuan = HistoryPengajuan::lockForUpdate()->find($id);
                if (!$pengajuan || $pengajuan->fakultas_id != $fakultasId || $pengajuan->status !== 'proses') {
                    Log::warning("Bulk approve gagal (id {$id}): Pengajuan tidak valid (tidak ditemukan, fakultas beda, atau status bukan proses). Fakultas ID Dekan: {$fakultasId}");
                    DB::rollBack();
                    $failed[] = $id;
                    continue;
                }

                $modelClass = $this->getModelClass($pengajuan->tabel);
                if (!$modelClass) {
                    Log::warning("Bulk approve gagal (id {$id}): Tabel surat tidak dikenali ({$pengajuan->tabel}).");
                    DB::rollBack();
                    $failed[] = $id;
                    continue;
                }

                $detailSurat = $modelClass::find($pengajuan->id_tabel_surat);
                if (!$detailSurat || empty($detailSurat->file_generated)) {
                    Log::warning("Bulk approve gagal (id {$id}): Detail surat tidak ditemukan atau file_generated kosong.");
                    DB::rollBack();
                    $failed[] = $id;
                    continue;
                }

                $ttdDekan = TtdSurat::where('fakultas_id', $pengajuan->fakultas_id)
                    ->where('template_id', $detailSurat->template_id)
                    ->where('status', 'aktif')
                    ->first();

                if (!$ttdDekan) {
                    Log::warning("Bulk approve gagal (id {$id}): TtdSurat aktif untuk fakultas {$pengajuan->fakultas_id} dan template {$detailSurat->template_id} tidak ditemukan.");
                    DB::rollBack();
                    $failed[] = $id;
                    continue;
                }

                $namaDekan = $ttdDekan->nama_ttd;
                $nidn = $ttdDekan->nidn;
                $jabatanDekan = $user->penduduk?->jabatan?->status ?? 'Dekan';
                $idJabatan = $user->penduduk?->jabatan?->id_jabatan ?? null;

                $docxFilePath = $signatureService->insertSignatureWithQR($detailSurat, $jabatanDekan, $namaDekan, $nidn);
                $pdfFilePath  = $signatureService->convertDocxToPdf($docxFilePath);

                $detailSurat->update([
                    'status' => 'diterima',
                    'catatan' => "Disetujui oleh Dekan: {$namaDekan}",
                    'file_generated' => $pdfFilePath,
                ]);

                $pengajuan->update([
                    'status' => 'diterima',
                    'catatan' => 'Disetujui oleh Dekan: ' . $namaDekan,
                    'jabatan_id' => $idJabatan,
                ]);

                PengajuanStatusLog::create([
                    'history_id' => $pengajuan->id_history,
                    'status' => 'diterima',
                    'user_role' => 'DEKAN',
                    'user_id' => $user->id,
                    'catatan' => 'Disetujui oleh Dekan: ' . $namaDekan,
                ]);

                DB::commit();
                $success++;

                try {
                    $mahasiswa = Mahasiswa::where('nim', $detailSurat->nim)->first();
                    if ($mahasiswa && $mahasiswa->email) {
                        Mail::to($mahasiswa->email)->send(
                            new NotifikasiStatusSurat($mahasiswa, $pengajuan, 'disetujui', "Disetujui oleh Dekan: {$namaDekan}")
                        );
                    }
                } catch (\Exception $e) {
                    Log::error("Bulk approve email gagal (history {$pengajuan->id_history}): " . $e->getMessage());
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Bulk approve gagal (id {$id}): " . $e->getMessage());
                $failed[] = $id;
            }
        }

        $msg = "Berhasil approve {$success} pengajuan.";
        if (count($failed) > 0) {
            $msg .= " Gagal: " . count($failed) . " (cek log / data tidak valid).";
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'success_count' => $success,
            'failed_count' => count($failed),
            'failed_ids' => $failed,
        ]);
    }

    public function bulkSend(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.tabel' => ['required', 'string'],
            'items.*.id_surat' => ['required', 'integer'],
            'items.*.id_history' => ['required'],
            'items.*.status_raw' => ['required', 'string'],
        ]);

        $fakultasId = $user->penduduk?->fakultas_id;

        $success = 0;
        $failed = [];

        foreach ($data['items'] as $item) {
            if ($item['status_raw'] !== 'diterima') {
                Log::warning("Bulk send gagal (id {$item['id_surat']}): Status bukan diterima ({$item['status_raw']}).");
                $failed[] = $item;
                continue;
            }

            try {

                $modelClass = $this->getModelClass($item['tabel']);
                if (!$modelClass) {
                    Log::warning("Bulk send gagal (id {$item['id_surat']}): Tabel tidak valid.");
                    $failed[] = $item;
                    continue;
                }

                $surat = $modelClass::find($item['id_surat']);
                if (!$surat || empty($surat->file_generated)) {
                    Log::warning("Bulk send gagal (id {$item['id_surat']}): Surat tidak ditemukan atau file_generated kosong.");
                    $failed[] = $item;
                    continue;
                }

                if ($surat->mahasiswa->fakultas_id != $fakultasId) {
                    Log::warning("Bulk send gagal (id {$item['id_surat']}): Fakultas mahasiswa berbeda dengan fakultas Dekan.");
                    $failed[] = $item;
                    continue;
                }

                $mahasiswa = Mahasiswa::where('nim', $surat->nim)->first();
                if (!$mahasiswa || !$mahasiswa->email) {
                    Log::warning("Bulk send gagal (id {$item['id_surat']}): Email mahasiswa kosong.");
                    $failed[] = $item;
                    continue;
                }

                $filePath = $surat->file_generated;
                if (!Storage::disk('local')->exists($filePath)) {
                    Log::warning("Bulk send gagal (id {$item['id_surat']}): File fisik tidak ditemukan di storage ({$filePath}).");
                    $failed[] = $item;
                    continue;
                }

                $pengajuanHistory = HistoryPengajuan::find($item['id_history']);

                if (!$pengajuanHistory || $pengajuanHistory->status !== 'diterima') {
                    Log::warning("Bulk send gagal (id {$item['id_surat']}): History tidak ditemukan atau status bukan diterima.");
                    $failed[] = $item;
                    continue;
                }

                $namaSurat = $pengajuanHistory->nama_surat;
                $fileName = strtoupper(str_replace(' ', '_', $item['tabel'])) . '_' . ($surat->nim ?? 'NoNIM') . '.pdf';

                DB::beginTransaction();

                Mail::to($mahasiswa->email)->send(
                    new SuratSelesai($mahasiswa, $surat, $filePath, $fileName, $namaSurat)
                );

                $surat->update([
                    'status' => 'selesai',
                    'catatan' => 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.',
                ]);

                $pengajuanHistory->update([
                    'status' => 'selesai',
                    'catatan' => 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.',
                    'updated_at' => now(),
                ]);

                PengajuanStatusLog::create([
                    'history_id' => $pengajuanHistory->id_history,
                    'status' => 'selesai',
                    'user_role' => 'DEKAN',
                    'user_id' => $user->id,
                    'catatan' => 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.',
                ]);

                DB::commit();
                $success++;
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Bulk send gagal: tabel={$item['tabel']}, id={$item['id_surat']}, err=" . $e->getMessage());
                $failed[] = $item;
            }
        }

        $msg = "Berhasil mengirim {$success} surat.";
        if (count($failed) > 0) {
            $msg .= " Gagal: " . count($failed) . " (cek log / data tidak valid).";
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'success_count' => $success,
            'failed_count' => count($failed),
        ]);
    }

    public function previewLampiranPdf(string $tabel, int $id): Response
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'DEKAN') abort(403);

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
        if ($user->role !== 'DEKAN') {
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

    public function sendEmailMahasiswa(Request $request, string $tabel, int $id)
    {
        $user = Auth::user();
        if ($user->role !== 'DEKAN') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $modelClass = $this->getModelClass($tabel);
        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Jenis surat tidak valid.'], 404);
        }

        
        $surat = $modelClass::find($id);

        $mahasiswa = Mahasiswa::where('nim', $surat->nim)->first();

        if (!$surat || empty($surat->file_generated) || !$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Surat atau data mahasiswa tidak valid.'], 404);
        }

        $filePath = $surat->file_generated;      
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $pengajuanHistory = HistoryPengajuan::where('tabel', $tabel)
            ->where('id_tabel_surat', $id)
            ->first();

        $namaSurat = $pengajuanHistory->nama_surat;

        $fileName = strtoupper(str_replace(' ', '_', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.pdf';
        try {
            DB::beginTransaction();

            Mail::to($mahasiswa->email)->send(new SuratSelesai($mahasiswa, $surat, $filePath, $fileName, $namaSurat));

            $surat->status = 'selesai';
            $surat->catatan = 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.';
            $surat->save();

            if ($pengajuanHistory) {
                $pengajuanHistory->update([
                    'status' => 'selesai',
                    'catatan' => 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.',
                    'updated_at' => now(),
                ]);
            }

            PengajuanStatusLog::create([
                'history_id' => $pengajuanHistory->id_history,
                'status'     => 'selesai',
                'user_role'  => 'DEKAN',
                'user_id'    => $user->id,
                'catatan'    => 'Surat sudah ditandatangani dan dikirim ke email mahasiswa oleh Dekan.',
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Surat berhasil dikirim ke email mahasiswa!']);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal mengirim email surat: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email atau memperbarui status. Silakan cek log server.'], 500);
        }
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
