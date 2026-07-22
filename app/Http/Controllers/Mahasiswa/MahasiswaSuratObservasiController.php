<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Mitra;
use App\Models\Mahasiswa;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\SuratObservasi;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\NotifikasiBAKService;
use Illuminate\Validation\ValidationException;
use Throwable;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SuratObservasiGenerator;

class MahasiswaSuratObservasiController extends Controller
{
    
    public function index()
    {
        return view('mahasiswa.surat_observasi.index');
    }

    public function getSuratObservasi()
    {
        $user = Auth::user();
        $nim = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = SuratObservasi::with(['akademik', 'mahasiswa', 'mahasiswa.prodi', 'mitra'])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                $date = \Carbon\Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
            })
            ->addColumn('akademik', function ($row) {
                return $row?->akademik?->tahun_akademik ?? "-";
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
            })
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'pengajuan' => '<span class="badge text-white bg-warning">Menunggu BAK</span>',
                    'proses'    => '<span class="badge text-white bg-info">Menunggu Dekan</span>',
                    'diterima'  => '<span class="badge text-white bg-success">Disetujui</span>',
                    'ditolak'   => '<span class="badge text-white bg-danger">Ditolak</span>',
                    default     => '<span class="badge text-white bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('mahasiswa.surat-observasi.show', $row->id_surat_observasi) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-observasi.edit', $row->id_surat_observasi) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';
                }

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['status', 'akademik', 'catatan', 'action', 'tanggal_pengajuan'])
            ->make(true);
    }

    public function lookupAnggota(string $nim)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }

        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', trim($nim))
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => "NIM {$nim} tidak ditemukan pada data mahasiswa.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'prodi' => $mahasiswa->prodi?->nama_prodi ?? '-',
            ],
        ]);
    }

    
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra = Mitra::all();
        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        return view('mahasiswa.surat_observasi.create', compact('latestAkademik', 'mitra', 'dataSimpt'));
    }

    
    public function store(Request $request, SuratObservasiGenerator $generatorService)
    {
        try {
            $request->validate($this->rules());

            $user = Auth::user();

            $mahasiswa = $user->mahasiswa;

            if (!$mahasiswa) {
                return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
            }

            $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
            $semester = $dataSimpt?->semester;

            if (blank($semester)) {
                return back()
                    ->withInput()
                    ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
            }

            $supportsAnggotaKelompok = $this->supportsAnggotaKelompok();
            $hasAnggotaKelompokInput = collect($request->input('anggota_kelompok', []))
                ->contains(fn($anggota) => filled(data_get($anggota, 'nama')) || filled(data_get($anggota, 'nim')));

            if (!$supportsAnggotaKelompok && $hasAnggotaKelompokInput) {
                return back()
                    ->withInput()
                    ->with('failed', 'Fitur pengajuan observasi kelompok belum aktif di database. Jalankan migrasi terlebih dahulu.');
            }

            $anggotaKelompok = $supportsAnggotaKelompok
                ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa->nim)
                : [];
            $isKelompok = !empty($anggotaKelompok);

            $fakultasId = $mahasiswa->fakultas_id;

            if (!$fakultasId) {
                return back()->with('failed', 'Fakultas Anda belum ditentukan.');
            }

            $template = $this->resolveTemplateObservasi($fakultasId, $isKelompok);

            if (!$template) {
                return back()->with('failed', $this->missingTemplateMessage($isKelompok));
            }

            
            $noSurat = SuratObservasi::getNextNoSurat($template->id_template, $request->akademik_id);

            $payload = [
                'template_id'         => $template->id_template,
                'no_surat'            => $noSurat,
                'nim'                 => $mahasiswa->nim,
                'akademik_id'         => $request->akademik_id,
                'mitra_id'            => $request->mitra_id,
                'semester'            => $semester,
                'tgl_observasi'       => $request->tgl_observasi,
                'keperluan'           => $request->keperluan,
                'status'              => 'pengajuan',
                'catatan'             => 'Diajukan oleh mahasiswa',
                'file_generated'      => null,
            ];

            if ($supportsAnggotaKelompok) {
                $payload['anggota_kelompok'] = $anggotaKelompok;
            }

            \Illuminate\Support\Facades\DB::beginTransaction();
            $surat = SuratObservasi::create($payload);

            try {
                
                $generatedFilePath = $generatorService->generateWord($surat, $template);

                
                $surat->update([
                    'file_generated' => $generatedFilePath,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('failed', 'Gagal memproses template dokumen. Silakan coba lagi atau hubungi admin. Error: ' . $e->getMessage());
            }

            $pengajuan = HistoryPengajuan::create([
                'id_tabel_surat' => $surat->id_surat_observasi,
                'nim'            => $mahasiswa->nim,
                'fakultas_id'    => $mahasiswa->fakultas_id,
                'tabel'          => 'surat_observasi',
                'status'         => 'pengajuan',
                'catatan'        => 'Diajukan oleh mahasiswa',
                'jabatan_id'     => null,
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'Mahasiswa',
                'user_id'    => $user->id,
                'catatan'    => 'Pengajuan baru dibuat oleh mahasiswa.',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            $namaSurat = "Surat Permohonan Observasi";

            $urlDetail = 'https://sso.unuja.ac.id';

            NotifikasiBAKService::kirimPengajuanBaru(
                $mahasiswa->nim,
                $pengajuan,
                $namaSurat,
                $urlDetail
            );

            return redirect()->route('mahasiswa.surat-observasi.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('failed', $this->validationExceptionMessage($e, 'anggota kelompok'));
        } catch (\Throwable $e) {
            $this->logObservasiException('store', $request, $e);

            return back()
                ->withInput()
                ->with('failed', 'Terjadi kesalahan saat membuat pengajuan observasi. Detail disimpan di storage/logs/observasi-debug.log. Error: ' . $e->getMessage());
        }
    }

    
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratObservasi::where('id_surat_observasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $mitra    = Mitra::all();

        return view('mahasiswa.surat_observasi.show', compact('surat', 'mitra'));
    }

    
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratObservasi::where('id_surat_observasi', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        if ($surat->status !== 'ditolak') {
            return redirect()->route('mahasiswa.surat-observasi.index')->with('failed', 'Hanya pengajuan yang ditolak yang dapat diedit.');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra = Mitra::all();
        $dataSimpt = $this->getDataSimpt($user->mahasiswa?->nim);

        return view('mahasiswa.surat_observasi.edit', compact('surat', 'latestAkademik', 'mitra', 'dataSimpt'));
    }

    
    public function update(Request $request, string $id, SuratObservasiGenerator $generatorService)
    {
        try {
            $request->validate($this->rules());

            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;

            if (!$mahasiswa) {
                return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
            }

            $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
            $semester = $dataSimpt?->semester;

            if (blank($semester)) {
                return back()
                    ->withInput()
                    ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
            }

            $surat = SuratObservasi::where('id_surat_observasi', $id)
                ->where('nim', $mahasiswa?->nim)
                ->firstOrFail();

            $pengajuan = $surat->historyPengajuan()
                ->where('nim', $mahasiswa?->nim)->firstOrFail();

            $supportsAnggotaKelompok = $this->supportsAnggotaKelompok();
            $hasAnggotaKelompokInput = collect($request->input('anggota_kelompok', []))
                ->contains(fn($anggota) => filled(data_get($anggota, 'nama')) || filled(data_get($anggota, 'nim')));

            if (!$supportsAnggotaKelompok && $hasAnggotaKelompokInput) {
                return back()
                    ->withInput()
                    ->with('failed', 'Fitur pengajuan observasi kelompok belum aktif di database. Jalankan migrasi terlebih dahulu.');
            }

            $anggotaKelompok = $supportsAnggotaKelompok
                ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa?->nim)
                : [];
            $isKelompok = !empty($anggotaKelompok);
            $fakultasId = $mahasiswa?->fakultas_id;

            if (!$fakultasId) {
                return back()->with('failed', 'Fakultas Anda belum ditentukan.');
            }

            $template = $this->resolveTemplateObservasi($fakultasId, $isKelompok);

            if (!$template) {
                return back()->with('failed', $this->missingTemplateMessage($isKelompok));
            }

            $payload = [
                'template_id'       => $template->id_template,
                'akademik_id'      => $request->akademik_id,
                'mitra_id'         => $request->mitra_id,
                'semester'         => $semester,
                'tgl_observasi'    => $request->tgl_observasi,
                'keperluan'        => $request->keperluan,
                'status'           => 'pengajuan',
                'catatan'          => 'Diajukan ulang oleh mahasiswa',
            ];

            if ($supportsAnggotaKelompok) {
                $payload['anggota_kelompok'] = $anggotaKelompok;
            }

            \Illuminate\Support\Facades\DB::beginTransaction();
            $surat->update($payload);

            try {
                $generatedFilePath = $generatorService->generateWord($surat, $template);

                \Illuminate\Support\Facades\DB::beginTransaction();
            $surat->update(['file_generated' => $generatedFilePath]);

                $pengajuan->update([
                    'status'  => 'pengajuan',
                    'catatan' => 'Diajukan ulang oleh mahasiswa'
                ]);

                PengajuanStatusLog::create([
                    'history_id' => $pengajuan->id_history,
                    'status'     => 'pengajuan',
                    'user_role'  => 'Mahasiswa',
                    'user_id'    => $user->id,
                    'catatan'    => 'Pengajuan ulang dibuat oleh mahasiswa.',
                ]);

                \Illuminate\Support\Facades\DB::commit();

                return redirect()->route('mahasiswa.surat-observasi.index')
                    ->with('success', 'Pengajuan surat berhasil diperbarui! Silakan tunggu proses persetujuan.');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
            }
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('failed', $this->validationExceptionMessage($e, 'anggota kelompok'));
        } catch (\Throwable $e) {
            $this->logObservasiException('update', $request, $e, ['id' => $id]);

            return back()
                ->withInput()
                ->with('failed', 'Terjadi kesalahan saat memperbarui pengajuan observasi. Detail disimpan di storage/logs/observasi-debug.log. Error: ' . $e->getMessage());
        }
    }

    
    public function destroy(string $id)
    {
        
    }

    private function rules(): array
    {
        return [
            'akademik_id' => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id' => 'required|exists:mitra,id_mitra',
            'tgl_observasi' => 'required',
            'keperluan' => 'required',
            'anggota_kelompok' => 'nullable|array',
            'anggota_kelompok.*.nim' => 'nullable|string|max:50',
        ];
    }

    private function resolveTemplateObservasi(int $fakultasId, bool $isKelompok): ?Template
    {
        $jenisSurat = $isKelompok ? 'surat_observasi_kelompok' : 'surat_observasi';

        return Template::where('jenis_surat', $jenisSurat)
            ->where('fakultas_id', $fakultasId)
            ->first();
    }

    private function missingTemplateMessage(bool $isKelompok): string
    {
        if ($isKelompok) {
            return 'Template surat observasi kelompok belum tersedia untuk fakultas Anda.';
        }

        return 'Template surat observasi biasa belum tersedia untuk fakultas Anda.';
    }

    private function supportsAnggotaKelompok(): bool
    {
        return Schema::hasColumn('surat_observasi', 'anggota_kelompok');
    }

    private function logObservasiException(string $action, Request $request, \Throwable $e, array $extra = []): void
    {
        $logPath = storage_path('logs/observasi-debug.log');
        $payload = [
            'time' => now()->toDateTimeString(),
            'action' => $action,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => Auth::id(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'extra' => $extra,
            'input' => collect($request->except(['_token', '_method']))->toArray(),
            'trace' => $e->getTraceAsString(),
        ];

        File::ensureDirectoryExists(dirname($logPath));
        File::append($logPath, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL . str_repeat('-', 120) . PHP_EOL);
    }

    private function validationExceptionMessage(ValidationException $e, string $fallback = 'Data tidak valid.'): string
    {
        $messages = collect($e->errors())
            ->flatten()
            ->filter()
            ->unique()
            ->values();

        if ($messages->isEmpty()) {
            return $fallback;
        }

        return $messages->implode("\n");
    }

    private function resolveAnggotaKelompok(array $anggotaKelompok, ?string $nimKetua): array
    {
        $anggotaKelompok = collect($anggotaKelompok)
            ->map(function ($anggota, $index) {
                return [
                    'index' => $index,
                    'nim' => trim((string) data_get($anggota, 'nim')),
                ];
            })
            ->filter(function ($anggota) {
                return $anggota['nim'] !== '';
            })
            ->values();

        if ($anggotaKelompok->isEmpty()) {
            return [];
        }

        $errors = [];
        $nimAnggota = $anggotaKelompok->pluck('nim')->filter()->all();
        $mahasiswaAnggota = Mahasiswa::with('prodi')
            ->whereIn('nim', $nimAnggota)
            ->get()
            ->keyBy('nim');
        $nimUsed = [];
        $resolved = [];

        foreach ($anggotaKelompok as $anggota) {
            $index = $anggota['index'];
            $nim = $anggota['nim'];

            if ($nim === '') {
                $errors["anggota_kelompok.{$index}.nim"] = 'NIM anggota wajib diisi.';
                continue;
            }

            if ($nimKetua && $nim === $nimKetua) {
                $errors["anggota_kelompok.{$index}.nim"] = 'NIM anggota tidak boleh sama dengan ketua pengaju.';
                continue;
            }

            if (isset($nimUsed[$nim])) {
                $errors["anggota_kelompok.{$index}.nim"] = 'NIM anggota tidak boleh duplikat.';
                continue;
            }

            $mahasiswa = $mahasiswaAnggota->get($nim);

            if (!$mahasiswa) {
                $errors["anggota_kelompok.{$index}.nim"] = "NIM anggota {$nim} tidak ditemukan pada data mahasiswa.";
                continue;
            }

            $nimUsed[$nim] = true;
            $resolved[] = [
                'nama' => $mahasiswa->nama,
                'nim' => $mahasiswa->nim,
                'prodi' => $mahasiswa->prodi?->nama_prodi ?? '-',
            ];
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        return $resolved;
    }

    private function getDataSimpt(?string $nim): ?object
    {
        if (!$nim) {
            return null;
        }

        try {
            return DB::selectOne(
                '
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
            ',
                [$nim]
            );
        } catch (Throwable $e) {
            Log::warning("Gagal mengambil data SIMPT untuk NIM: {$nim}", [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
