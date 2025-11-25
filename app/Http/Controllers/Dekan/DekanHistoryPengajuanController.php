<?php

namespace App\Http\Controllers\Dekan;

use App\Models\Prodi;
use App\Models\SuratPKL;
use App\Models\TtdSurat;
use App\Models\Mahasiswa;
use App\Mail\SuratSelesai;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\SuratObservasi;
use Illuminate\Support\Carbon;
use App\Models\SuratPenelitian;
use App\Models\HistoryPengajuan;
use App\Models\SuratRekomendasi;
use App\Models\PengajuanStatusLog;
use App\Services\SignatureService;
use Illuminate\Support\Facades\DB;
use App\Mail\NotifikasiStatusSurat;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

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

        $query = HistoryPengajuan::with([])
            ->where('fakultas_id', $fakultasId)
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
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—';
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
                $showBtn = '<a href="' . route('dekan.history.detail', $row->id_history) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                return '<div class="text-center">' . $showBtn . '</div>';
            })
            ->rawColumns(['catatan', 'status', 'action'])
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

        return view('dekan.history.detail', compact(
            'pengajuan',
            'surat',
            'fileGeneratedPath',
            'jumlahPengajuan',
            'jumlahDitolak',
            'jumlahDiterima'
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

        if ($surat->mahasiswa->fakultas_id !== $user->penduduk?->fakultas_id) {
            abort(403, 'Anda tidak berhak melihat surat ini.');
        }

        $filePath = $surat->file_generated;      // sekarang 'surat/aktif/surat_1.pdf'
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $fileName = ucfirst(str_replace('_', ' ', $tabel)) . '_' . ($surat->nim ?? 'NoNIM') . '.pdf';

        return Storage::download($filePath, $fileName);
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

        // Ambil data Surat dan Mahasiswa
        $surat = $modelClass::find($id);

        $mahasiswa = Mahasiswa::where('nim', $surat->nim)->first();

        if (!$surat || empty($surat->file_generated) || !$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Surat atau data mahasiswa tidak valid.'], 404);
        }

        $filePath = $surat->file_generated;      // sekarang 'surat/aktif/surat_1.pdf'
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'File di server tidak ditemukan.');
        }

        $pengajuanHistory = HistoryPengajuan::where('tabel', $tabel)
            ->where('id_tabel_surat', $id)
            ->first();

        $namaSurat = $pengajuanHistory->nama_surat;

        $fileName = ucfirst(str_replace('_', ' ', $tabel)) . '_' . $surat->nim . '.pdf';
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
}
