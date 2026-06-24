<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Mitra;
use App\Models\Mahasiswa;
use App\Models\SuratPKL;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Services\SuratPKLGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\NotifikasiBAKService;
use Illuminate\Validation\ValidationException;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaSuratPKLController extends Controller
{
    
    public function index()
    {
        return view('mahasiswa.surat_pkl.index');
    }

    public function getSuratPKL()
    {
        $user = Auth::user();
        $nim = $user->mahasiswa?->nim;

        if (!$nim) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan.'], 403);
        }

        $query = SuratPKL::with(['akademik', 'mahasiswa', 'mahasiswa.prodi', 'mitra'])->where('nim', $nim)
            ->whereIn('status', ['pengajuan', 'proses', 'diterima', 'ditolak']);

        return DataTables::of($query)
            ->order(function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->addColumn('tanggal_pengajuan', function ($row) {
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—';
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
                $showBtn = '<a href="' . route('mahasiswa.surat-pkl.show', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '';
                if ($row->status === 'ditolak') {
                    $editBtn = '<a href="' . route('mahasiswa.surat-pkl.edit', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-edit"></i></a>';
                }

                return '<div class="d-flex justify-content-center gap-2">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['status', 'akademik', 'catatan', 'action'])
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
        $user     = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403, 'Akses ditolak');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra    = Mitra::all();
        return view('mahasiswa.surat_pkl.create', compact('latestAkademik', 'mitra'));
    }

    
    public function store(Request $request, SuratPKLGenerator $generatorService)
    {
        try {
            $request->validate($this->rules());

            $user = Auth::user();

            $mahasiswa = $user->mahasiswa;

            if (!$mahasiswa) {
                return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
            }

            $supportsAnggotaKelompok = $this->supportsAnggotaKelompok();
            $hasAnggotaKelompokInput = collect($request->input('anggota_kelompok', []))
                ->contains(fn($anggota) => filled(data_get($anggota, 'nama')) || filled(data_get($anggota, 'nim')));

            if (!$supportsAnggotaKelompok && $hasAnggotaKelompokInput) {
                return back()
                    ->withInput()
                    ->with('failed', 'Fitur pengajuan PKL kelompok belum aktif di database. Jalankan migrasi terlebih dahulu.');
            }

            $anggotaKelompok = $supportsAnggotaKelompok
                ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa->nim)
                : [];
            $isKelompok = !empty($anggotaKelompok);

            $fakultasId = $mahasiswa->fakultas_id;

            if (!$fakultasId) {
                return back()->with('failed', 'Fakultas Anda belum ditentukan.');
            }

            $template = $this->resolveTemplatePKL($fakultasId, $isKelompok);

            if (!$template) {
                return back()->with('failed', $this->missingTemplateMessage($isKelompok));
            }

            
            $noSurat = SuratPKL::getNextNoSurat($template->id_template, $request->akademik_id);

            $payload = [
                'template_id'         => $template->id_template,
                'no_surat'            => $noSurat,
                'nim'                 => $mahasiswa->nim,
                'akademik_id'         => $request->akademik_id,
                'mitra_id'            => $request->mitra_id,
                'tgl_mulai'           => $request->tgl_mulai,
                'tgl_selesai'         => $request->tgl_selesai,
                'status'              => 'pengajuan',
                'catatan'             => 'Diajukan oleh mahasiswa',
                'file_generated'      => null,
            ];

            if ($supportsAnggotaKelompok) {
                $payload['anggota_kelompok'] = $anggotaKelompok;
            }

            \Illuminate\Support\Facades\DB::beginTransaction();

            $surat = SuratPKL::create($payload);

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
                'id_tabel_surat' => $surat->id_surat_pkl,
                'nim'            => $mahasiswa->nim,
                'fakultas_id'    => $mahasiswa->fakultas_id,
                'tabel'          => 'surat_pkl',
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

            $namaSurat = "Surat Permohonan PKL";

            $urlDetail = 'https://sso.unuja.ac.id';

            NotifikasiBAKService::kirimPengajuanBaru(
                $mahasiswa->nim,
                $pengajuan,
                $namaSurat,
                $urlDetail
            );

            return redirect()->route('mahasiswa.surat-pkl.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('failed', $this->validationExceptionMessage($e, 'anggota kelompok'));
        } catch (\Throwable $e) {
            $this->logPKLException('store', $request, $e);

            return back()
                ->withInput()
                ->with('failed', 'Terjadi kesalahan saat membuat pengajuan PKL. Detail disimpan di storage/logs/pkl-debug.log. Error: ' . $e->getMessage());
        }
    }

    
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratPKL::where('id_surat_pkl', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        $mitra    = Mitra::all();

        return view('mahasiswa.surat_pkl.show', compact('surat', 'mitra'));
    }

    
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'mahasiswa') {
            abort(403);
        }
        $surat = SuratPKL::where('id_surat_pkl', $id)
            ->where('nim', $user->mahasiswa?->nim)
            ->firstOrFail();

        if ($surat->status !== 'ditolak') {
            return redirect()->route('mahasiswa.surat-pkl.index')->with('failed', 'Hanya pengajuan yang ditolak yang dapat diedit.');
        }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra    = Mitra::all();

        return view('mahasiswa.surat_pkl.edit', compact('surat', 'latestAkademik', 'mitra'));
    }

    
    public function update(Request $request, string $id, SuratPKLGenerator $generatorService)
    {
        try {
            $request->validate($this->rules());

            $user = Auth::user();
            $mahasiswa = $user->mahasiswa;

            if (!$mahasiswa) {
                return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
            }

            $surat = SuratPKL::where('id_surat_pkl', $id)
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
                    ->with('failed', 'Fitur pengajuan PKL kelompok belum aktif di database. Jalankan migrasi terlebih dahulu.');
            }

            $anggotaKelompok = $supportsAnggotaKelompok
                ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa?->nim)
                : [];
            $isKelompok = !empty($anggotaKelompok);
            $fakultasId = $mahasiswa?->fakultas_id;

            if (!$fakultasId) {
                return back()->with('failed', 'Fakultas Anda belum ditentukan.');
            }

            $template = $this->resolveTemplatePKL($fakultasId, $isKelompok);

            if (!$template) {
                return back()->with('failed', $this->missingTemplateMessage($isKelompok));
            }

            $payload = [
                'template_id'       => $template->id_template,
                'akademik_id'      => $request->akademik_id,
                'mitra_id'         => $request->mitra_id,
                'tgl_mulai'        => $request->tgl_mulai,
                'tgl_selesai'      => $request->tgl_selesai,
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

                return redirect()->route('mahasiswa.surat-pkl.index')
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
            $this->logPKLException('update', $request, $e, ['id' => $id]);

            return back()
                ->withInput()
                ->with('failed', 'Terjadi kesalahan saat memperbarui pengajuan PKL. Detail disimpan di storage/logs/pkl-debug.log. Error: ' . $e->getMessage());
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
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'anggota_kelompok' => 'nullable|array',
            'anggota_kelompok.*.nim' => 'nullable|string|max:50',
        ];
    }

    private function resolveTemplatePKL(int $fakultasId, bool $isKelompok): ?Template
    {
        $jenisSurat = $isKelompok ? 'surat_pkl_kelompok' : 'surat_pkl';

        return Template::where('jenis_surat', $jenisSurat)
            ->where('fakultas_id', $fakultasId)
            ->first();
    }

    private function missingTemplateMessage(bool $isKelompok): string
    {
        if ($isKelompok) {
            return 'Template surat PKL kelompok belum tersedia untuk fakultas Anda.';
        }

        return 'Template surat PKL belum tersedia untuk fakultas Anda.';
    }

    private function supportsAnggotaKelompok(): bool
    {
        return Schema::hasColumn('surat_pkl', 'anggota_kelompok');
    }

    private function logPKLException(string $action, Request $request, \Throwable $e, array $extra = []): void
    {
        $logPath = storage_path('logs/pkl-debug.log');
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
}
