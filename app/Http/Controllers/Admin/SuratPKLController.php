<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mitra;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\SuratPKL;
use App\Models\Template;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Carbon;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanStatusLog;
use App\Services\SuratPKLGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class SuratPKLController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') { abort(403); }

        $listFakultas = Fakultas::all();
        $listProdi = Prodi::all();
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();

        return view('admin.surat_pkl.index', compact('listFakultas', 'listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratPKL(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') { abort(403); }

        $query = SuratPKL::with(['mahasiswa.prodi', 'akademik', 'mitra']);

        if ($request->filled('fakultas_filter')) {
            $fakultasId = $request->input('fakultas_filter');
            $query->whereHas('mahasiswa', fn($q) => $q->where('fakultas_id', $fakultasId));
        }
        if ($request->filled('prodi_filter')) {
            $prodiId = $request->input('prodi_filter');
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', $prodiId));
        }
        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
        }
        if ($request->filled('tahun_akademik_filter')) {
            $query->where('akademik_id', $request->input('tahun_akademik_filter'));
        }

        return DataTables::of($query)
            ->order(fn($q) => $q->orderBy('created_at', 'desc'))
            ->filterColumn('nama_mahasiswa', fn($q, $k) => $q->whereHas('mahasiswa', fn($qq) => $qq->where('nama', 'like', "%{$k}%")))
            ->filterColumn('prodi', fn($q, $k) => $q->whereHas('mahasiswa.prodi', fn($qq) => $qq->where('nama_prodi', 'like', "%{$k}%")))
            ->addColumn('nama_mahasiswa', fn($r) => $r->mahasiswa?->nama ?? $r->nim)
            ->addColumn('prodi', fn($r) => $r->mahasiswa?->prodi?->nama_prodi ?? $r->nim)
            ->addColumn('tanggal_pengajuan', function ($r) {
                $date = \Carbon\Carbon::parse($r->tanggal_pengajuan_asli)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
            })
            ->addColumn('catatan', fn($r) => $r->catatan ?: '<em>Tidak ada catatan</em>')
            ->addColumn('status', fn($r) => match ($r->status) {
                'pengajuan' => '<span class="badge text-white bg-warning">Menunggu BAK</span>',
                'proses'    => '<span class="badge text-white bg-info">Menunggu Dekan</span>',
                'diterima'  => '<span class="badge text-white bg-success">Disetujui</span>',
                'selesai'   => '<span class="badge text-white bg-primary">Selesai</span>',
                'ditolak'   => '<span class="badge text-white bg-danger">Ditolak</span>',
                default     => '<span class="badge text-white bg-secondary">Tidak Diketahui</span>'
            })
            ->addColumn('action', function ($row) {
                $s = '<a href="' . route('admin.surat-pkl.show', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';
                $e = '<a href="' . route('admin.surat-pkl.edit', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-edit"></i></a>';
                return '<div class="d-flex justify-content-center gap-2">' . $s . ' ' . $e . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    public function getDataMahasiswaSimpt(string $nim)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $dataSimpt = $this->getDataSimpt($nim);

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $isValidKrs = false;
        
        if ($dataSimpt && $latestAkademik) {
            $isValidKrs = ($dataSimpt->id_smt == $latestAkademik->kode_akademik);
        }

        if (!$dataSimpt) {
            return response()->json([
                'semester'     => null,
                'is_valid_krs' => $isValidKrs,
                'message'      => 'Data SIMPT tidak ditemukan untuk mahasiswa ini.',
            ]);
        }

        return response()->json([
            'semester'     => $dataSimpt->semester,
            'is_valid_krs' => $isValidKrs,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') { abort(403); }

        $mahasiswa = Mahasiswa::with('prodi')->orderBy('nama', 'asc')->get();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra = Mitra::all();

        return view('admin.surat_pkl.create', compact('mahasiswa', 'latestAkademik', 'mitra'));
    }

    public function store(Request $request, SuratPKLGenerator $generatorService)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') { abort(403); }

        $request->validate($this->rules());

        $mahasiswa = Mahasiswa::with('prodi')->where('nim', $request->nim)->first();
        if (!$mahasiswa) { return back()->with('failed', 'Data mahasiswa tidak ditemukan.'); }

        $fakultasId = $mahasiswa->fakultas_id;
        if (!$fakultasId) { return back()->with('failed', 'Fakultas mahasiswa terpilih belum ditentukan.'); }

        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $semester = $dataSimpt?->semester ?? null;

        if (blank($semester)) {
            return back()
                ->withInput()
                ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
        }

        $akademik = TahunAkademik::find($request->akademik_id);
        if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
            return back()
                ->withInput()
                ->with('failed', 'Mahasiswa belum mengisi KRS pada semester ini, sehingga tidak dapat dibuatkan surat.');
        }

        $supportsAnggota = $this->supportsAnggotaKelompok();
        $hasAnggotaInput = collect($request->input('anggota_kelompok', []))
            ->contains(fn($a) => filled(data_get($a, 'nama')) || filled(data_get($a, 'nim')));

        if (!$supportsAnggota && $hasAnggotaInput) {
            return back()->withInput()->with('failed', 'Fitur pengajuan PKL kelompok belum aktif di database.');
        }

        $anggotaKelompok = $supportsAnggota
            ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa->nim)
            : [];
        $isKelompok = !empty($anggotaKelompok);

        $template = $this->resolveTemplatePKL($fakultasId, $isKelompok);
        if (!$template) { return back()->with('failed', $this->missingTemplateMessage($isKelompok)); }

        $noSurat = SuratPKL::getNextNoSurat($template->id_template, $request->akademik_id);

        $payload = [
            'template_id' => $template->id_template, 'no_surat' => $noSurat,
            'nim' => $mahasiswa->nim, 'akademik_id' => $request->akademik_id,
            'mitra_id' => $request->mitra_id, 'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai, 'status' => 'pengajuan',
            'catatan' => 'Diajukan oleh Admin untuk mahasiswa', 'file_generated' => null,
        ];
        if ($supportsAnggota) { $payload['anggota_kelompok'] = $anggotaKelompok; }

        $surat = SuratPKL::create($payload);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template);
            $surat->update(['file_generated' => $generatedFilePath]);
        } catch (\Exception $e) {
            $surat->delete();
            return back()->with('failed', 'Gagal memproses template dokumen. Error: ' . $e->getMessage());
        }

        $pengajuan = HistoryPengajuan::create([
            'id_tabel_surat' => $surat->id_surat_pkl, 'nim' => $mahasiswa->nim,
            'fakultas_id' => $mahasiswa->fakultas_id, 'tabel' => 'surat_pkl',
            'status' => 'pengajuan', 'catatan' => 'Diajukan oleh Admin untuk mahasiswa', 'jabatan_id' => null,
        ]);

        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history, 'status' => 'pengajuan',
            'user_role' => 'Admin', 'user_id' => $user->id,
            'catatan' => 'Diajukan oleh Admin untuk mahasiswa',
        ]);

        return redirect()->route('admin.surat-pkl.index')->with('success', 'Pengajuan surat berhasil diajukan!');
    }

    public function show(string $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') { abort(403); }

        $surat = SuratPKL::with('mahasiswa.prodi')->where('id_surat_pkl', $id)->firstOrFail();
        return view('admin.surat_pkl.show', compact('surat'));
    }

    public function edit(string $id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') { abort(403); }

        $surat = SuratPKL::with('mahasiswa.prodi')->where('id_surat_pkl', $id)->firstOrFail();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra = Mitra::all();
        $mahasiswa = Mahasiswa::with('prodi')->orderBy('nama', 'asc')->get();

        return view('admin.surat_pkl.edit', compact('surat', 'latestAkademik', 'mitra', 'mahasiswa'));
    }

    public function update(Request $request, string $id, SuratPKLGenerator $generatorService)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') { abort(403); }

        $request->validate($this->rules());

        $surat = SuratPKL::findOrFail($id);
        $mahasiswa = Mahasiswa::with('prodi')->where('nim', $request->nim)->first();
        if (!$mahasiswa) { return back()->with('failed', 'Data mahasiswa tidak ditemukan.'); }
        if (!$mahasiswa->fakultas_id) { return back()->with('failed', 'Fakultas mahasiswa terpilih belum ditentukan.'); }

        $pengajuan = $surat->historyPengajuan()->firstOrFail();

        $dataSimpt = $this->getDataSimpt($mahasiswa->nim);
        $semester = $dataSimpt?->semester ?? null;

        if (blank($semester)) {
            return back()
                ->withInput()
                ->with('failed', 'Data semester mahasiswa tidak ditemukan di SIMPT. Silakan coba lagi atau hubungi admin.');
        }

        $akademik = TahunAkademik::find($request->akademik_id);
        if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
            return back()
                ->withInput()
                ->with('failed', 'Mahasiswa belum mengisi KRS pada semester ini, sehingga tidak dapat dibuatkan surat.');
        }

        $supportsAnggota = $this->supportsAnggotaKelompok();
        $hasAnggotaInput = collect($request->input('anggota_kelompok', []))
            ->contains(fn($a) => filled(data_get($a, 'nama')) || filled(data_get($a, 'nim')));

        if (!$supportsAnggota && $hasAnggotaInput) {
            return back()->withInput()->with('failed', 'Fitur pengajuan PKL kelompok belum aktif di database.');
        }

        $anggotaKelompok = $supportsAnggota
            ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa->nim)
            : [];
        $isKelompok = !empty($anggotaKelompok);

        $template = $this->resolveTemplatePKL($mahasiswa->fakultas_id, $isKelompok);
        if (!$template) { return back()->with('failed', $this->missingTemplateMessage($isKelompok)); }

        $payload = [
            'template_id' => $template->id_template, 'nim' => $request->nim,
            'akademik_id' => $request->akademik_id, 'mitra_id' => $request->mitra_id,
            'tgl_mulai' => $request->tgl_mulai, 'tgl_selesai' => $request->tgl_selesai,
            'status' => 'pengajuan', 'catatan' => 'Diajukan ulang oleh Admin untuk mahasiswa',
        ];
        if ($supportsAnggota) { $payload['anggota_kelompok'] = $anggotaKelompok; }

        $surat->update($payload);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template);
            $surat->update(['file_generated' => $generatedFilePath]);

            $pengajuan->update([
                'nim' => $mahasiswa->nim, 'fakultas_id' => $mahasiswa->fakultas_id,
                'status' => 'pengajuan', 'catatan' => 'Diajukan ulang oleh Admin untuk mahasiswa'
            ]);

            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history, 'status' => 'pengajuan',
                'user_role' => 'Admin', 'user_id' => $user->id,
                'catatan' => 'Diajukan ulang oleh Admin untuk mahasiswa',
            ]);

            return redirect()->route('admin.surat-pkl.index')->with('success', 'Data surat berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('failed', 'Gagal memperbarui dokumen. Error: ' . $e->getMessage());
        }
    }

    public function destroy(string $id) {}

    private function rules(): array
    {
        return [
            'nim' => 'required|string|max:50',
            'akademik_id' => 'required|exists:tahun_akademik,id_akademik',
            'mitra_id' => 'required|exists:mitra,id_mitra',
            'tgl_mulai' => 'required', 'tgl_selesai' => 'required',
            'anggota_kelompok' => 'nullable|array',
            'anggota_kelompok.*.nim' => 'nullable|string|max:50',
        ];
    }

    private function supportsAnggotaKelompok(): bool
    {
        return Schema::hasColumn('surat_pkl', 'anggota_kelompok');
    }

    private function resolveTemplatePKL(int $fakultasId, bool $isKelompok): ?Template
    {
        $jenisSurat = $isKelompok ? 'surat_pkl_kelompok' : 'surat_pkl';
        return Template::where('jenis_surat', $jenisSurat)->where('fakultas_id', $fakultasId)->first();
    }

    private function missingTemplateMessage(bool $isKelompok): string
    {
        return $isKelompok
            ? 'Template surat PKL kelompok belum tersedia untuk fakultas mahasiswa terpilih.'
            : 'Template surat PKL belum tersedia untuk fakultas mahasiswa terpilih.';
    }

    private function resolveAnggotaKelompok(array $anggotaKelompok, ?string $nimKetua): array
    {
        $anggotaKelompok = collect($anggotaKelompok)
            ->map(fn($a, $i) => ['index' => $i, 'nim' => trim((string) data_get($a, 'nim'))])
            ->filter(fn($a) => $a['nim'] !== '')->values();

        if ($anggotaKelompok->isEmpty()) { return []; }

        $errors = [];
        $nimAnggota = $anggotaKelompok->pluck('nim')->filter()->all();
        $mahasiswaAnggota = Mahasiswa::with('prodi')->whereIn('nim', $nimAnggota)->get()->keyBy('nim');
        $nimUsed = [];
        $resolved = [];

        foreach ($anggotaKelompok as $anggota) {
            $index = $anggota['index'];
            $nim = $anggota['nim'];

            if ($nimKetua && $nim === $nimKetua) {
                $errors["anggota_kelompok.{$index}.nim"] = 'Mahasiswa anggota tidak boleh sama dengan ketua pengaju.';
                continue;
            }
            if (isset($nimUsed[$nim])) {
                $errors["anggota_kelompok.{$index}.nim"] = 'Mahasiswa anggota tidak boleh duplikat.';
                continue;
            }
            $mhs = $mahasiswaAnggota->get($nim);
            if (!$mhs) {
                $errors["anggota_kelompok.{$index}.nim"] = "Mahasiswa anggota {$nim} tidak ditemukan.";
                continue;
            }
            $nimUsed[$nim] = true;
            $resolved[] = ['nama' => $mhs->nama, 'nim' => $mhs->nim, 'prodi' => $mhs->prodi?->nama_prodi ?? '-'];
        }

        if ($errors !== []) { throw ValidationException::withMessages($errors); }
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
                    
                    IFNULL(
                        b.ipk_ketuntasan,
                        (SELECT tkm.ipk_ketuntasan 
                         FROM dbsimpt.tbbak_kuliah_mahasiswa tkm 
                         WHERE tkm.id_mahasiswa_pt = b.id_mahasiswa_pt 
                           AND tkm.ipk_ketuntasan IS NOT NULL 
                           AND tkm.id_smt < b.id_smt 
                         ORDER BY tkm.id_smt DESC 
                         LIMIT 1)
                    ) AS ipk_ketuntasan,
                    
                    (
                        (LEFT(b.id_smt, 4) - LEFT(a.mulai_smt, 4)) * 2
                        + (RIGHT(b.id_smt, 1) - RIGHT(a.mulai_smt, 1))
                        + 1
                    ) AS semester
                FROM dbsimpt.tbmas_mahasiswa_pt a
                LEFT JOIN dbsimpt.tbbak_kuliah_mahasiswa b 
                    ON a.id_mahasiswa_pt = b.id_mahasiswa_pt
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

