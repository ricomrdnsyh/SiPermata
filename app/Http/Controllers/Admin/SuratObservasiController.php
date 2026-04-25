<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mitra;
use App\Models\Prodi;
use App\Models\Template;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\SuratObservasi;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SuratObservasiGenerator;

class SuratObservasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $listProdi = Prodi::all();
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();

        return view('admin.surat_observasi.index', compact('listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratObservasi(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $query = SuratObservasi::with(['mahasiswa.prodi', 'akademik', 'mitra']);

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
                return Carbon::parse($row->created_at)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY, HH:mm:ss') ?? '—';
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?: '<em>Tidak ada catatan</em>';
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
            ->addColumn('action', function ($row) {
                $showBtn = '<a href="' . route('admin.surat-observasi.show', $row->id_surat_observasi) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" 
                data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';

                $editBtn = '<a href="' . route('admin.surat-observasi.edit', $row->id_surat_observasi) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" 
                data-bs-title="Edit"><i class="fas fa-pen"></i></a>';

                return '<div class="text-center">' . $showBtn . ' ' . $editBtn . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $mahasiswa = Mahasiswa::with('prodi')
            ->orderBy('nama', 'asc')
            ->get();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra     = Mitra::all();

        return view('admin.surat_observasi.create', compact('mahasiswa', 'latestAkademik', 'mitra'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SuratObservasiGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate($this->rules());

        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', $request->nim)
            ->first();

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        $fakultasId = $mahasiswa->fakultas_id;

        if (!$fakultasId) {
            return back()->with('failed', 'Fakultas mahasiswa terpilih belum ditentukan.');
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

        $template = $this->resolveTemplateObservasi($fakultasId, $isKelompok);

        if (!$template) {
            return back()->with('failed', $this->missingTemplateMessage($isKelompok));
        }

        // Generate nomor surat
        $noSurat = SuratObservasi::getNextNoSurat($template->id_template);

        $payload = [
            'template_id'         => $template->id_template,
            'no_surat'            => $noSurat,
            'nim'                 => $mahasiswa->nim,
            'akademik_id'         => $request->akademik_id,
            'mitra_id'            => $request->mitra_id,
            'semester'            => $request->semester,
            'tgl_observasi'       => $request->tgl_observasi,
            'keperluan'           => $request->keperluan,
            'status'              => 'pengajuan',
            'catatan'             => 'Diajukan oleh Admin untuk mahasiswa',
            'file_generated'      => null,
        ];

        if ($supportsAnggotaKelompok) {
            $payload['anggota_kelompok'] = $anggotaKelompok;
        }

        $surat = SuratObservasi::create($payload);

        try {
            // GENERATE FILE WORD
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            // UPDATE MODEL DENGAN PATH FILE
            $surat->update([
                'file_generated' => $generatedFilePath,
            ]);
        } catch (\Exception $e) {
            $surat->delete();
            return back()->with('failed', 'Gagal memproses template dokumen. Silakan coba lagi atau hubungi admin. Error: ' . $e->getMessage());
        }

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_observasi,
            'nim'            => $mahasiswa->nim,
            'fakultas_id'    => $mahasiswa->fakultas_id,
            'tabel'          => 'surat_observasi',
            'status'         => 'pengajuan',
            'catatan'        => 'Diajukan oleh Admin untuk mahasiswa',
            'jabatan_id'     => null,
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history,
            'status'     => 'pengajuan',
            'user_role'  => 'Admin',
            'user_id'    => $user->id,
            'catatan'    => 'Diajukan oleh Admin untuk mahasiswa',
        ]);

        return redirect()->route('admin.surat-observasi.index')->with('success', 'Pengajuan surat berhasil diajukan! Silakan tunggu proses persetujuan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratObservasi::with('mahasiswa.prodi')
            ->where('id_surat_observasi', $id)
            ->firstOrFail();

        return view('admin.surat_observasi.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $surat = SuratObservasi::with('mahasiswa.prodi')
            ->where('id_surat_observasi', $id)
            ->firstOrFail();

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra     = Mitra::all();
        $mahasiswa = Mahasiswa::with('prodi')
            ->orderBy('nama', 'asc')
            ->get();

        return view('admin.surat_observasi.edit', compact('surat', 'latestAkademik', 'mitra', 'mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, SuratObservasiGenerator $generatorService)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate($this->rules());

        $surat = SuratObservasi::findOrFail($id);
        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', $request->nim)
            ->first();

        if (!$mahasiswa) {
            return back()->with('failed', 'Data mahasiswa tidak ditemukan.');
        }

        if (!$mahasiswa->fakultas_id) {
            return back()->with('failed', 'Fakultas mahasiswa terpilih belum ditentukan.');
        }

        $pengajuan = $surat->historyPengajuan()->firstOrFail();

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

        $template = $this->resolveTemplateObservasi($mahasiswa->fakultas_id, $isKelompok);

        if (!$template) {
            return back()->with('failed', $this->missingTemplateMessage($isKelompok));
        }

        $payload = [
            'template_id'      => $template->id_template,
            'nim'              => $request->nim,
            'akademik_id'      => $request->akademik_id,
            'mitra_id'         => $request->mitra_id,
            'semester'         => $request->semester,
            'tgl_observasi'    => $request->tgl_observasi,
            'keperluan'        => $request->keperluan,
            'status'           => 'pengajuan',
            'catatan'          => 'Diajukan ulang oleh Admin untuk mahasiswa',
        ];

        if ($supportsAnggotaKelompok) {
            $payload['anggota_kelompok'] = $anggotaKelompok;
        }

        $surat->update($payload);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template);

            $surat->update([
                'file_generated' => $generatedFilePath
            ]);

            $pengajuan->update([
                'nim'     => $mahasiswa->nim,
                'fakultas_id' => $mahasiswa->fakultas_id,
                'status'  => 'pengajuan',
                'catatan' => 'Diajukan ulang oleh Admin untuk mahasiswa'
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history,
                'status'     => 'pengajuan',
                'user_role'  => 'Admin',
                'user_id'    => $user->id,
                'catatan'    => 'Diajukan ulang oleh Admin untuk mahasiswa',
            ]);

            return redirect()->route('admin.surat-observasi.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function rules(): array
    {
        return [
            'nim' => 'required|string|max:50',
            'akademik_id' => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id' => 'required|exists:mitra,id_mitra',
            'semester' => 'required',
            'tgl_observasi' => 'required',
            'keperluan' => 'required',
            'anggota_kelompok' => 'nullable|array',
            'anggota_kelompok.*.nim' => 'nullable|string|max:50',
        ];
    }

    private function supportsAnggotaKelompok(): bool
    {
        return Schema::hasColumn('surat_observasi', 'anggota_kelompok');
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
            return 'Template surat observasi kelompok belum tersedia untuk fakultas mahasiswa terpilih.';
        }

        return 'Template surat observasi biasa belum tersedia untuk fakultas mahasiswa terpilih.';
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
            ->filter(fn($anggota) => $anggota['nim'] !== '')
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
                $errors["anggota_kelompok.{$index}.nim"] = 'Mahasiswa anggota wajib dipilih.';
                continue;
            }

            if ($nimKetua && $nim === $nimKetua) {
                $errors["anggota_kelompok.{$index}.nim"] = 'Mahasiswa anggota tidak boleh sama dengan ketua pengaju.';
                continue;
            }

            if (isset($nimUsed[$nim])) {
                $errors["anggota_kelompok.{$index}.nim"] = 'Mahasiswa anggota tidak boleh duplikat.';
                continue;
            }

            $mahasiswa = $mahasiswaAnggota->get($nim);

            if (!$mahasiswa) {
                $errors["anggota_kelompok.{$index}.nim"] = "Mahasiswa anggota {$nim} tidak ditemukan.";
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
