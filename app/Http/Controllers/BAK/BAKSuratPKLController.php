<?php

namespace App\Http\Controllers\BAK;

use App\Models\Mitra;
use App\Models\Prodi;
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

class BAKSuratPKLController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') { abort(403); }

        $fakultasIdUser = $user->penduduk?->fakultas_id;
        $listProdi = $fakultasIdUser ? Prodi::where('fakultas_id', $fakultasIdUser)->get() : collect();
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->first();

        return view('bak.surat_pkl.index', compact('listProdi', 'listTahunAkademik', 'currentTahunAkademik'));
    }

    public function getSuratPKL(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') { abort(403); }

        $fakultasId = $user->penduduk?->fakultas_id;
        $query = SuratPKL::whereHas('mahasiswa', fn($q) => $q->where('fakultas_id', $fakultasId))->with('mahasiswa');

        if ($request->filled('prodi_filter')) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', $request->input('prodi_filter')));
        }
        if ($request->filled('status_filter')) { $query->where('status', $request->input('status_filter')); }
        if ($request->filled('tahun_akademik_filter')) { $query->where('akademik_id', $request->input('tahun_akademik_filter')); }

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
                $s = '<a href="' . route('bak.surat-pkl.show', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-info text-center" data-bs-toggle="tooltip" data-bs-title="Detail"><i class="fa fa-file-alt"></i></a>';
                $e = '<a href="' . route('bak.surat-pkl.edit', $row->id_surat_pkl) . '" class="btn btn-sm btn-light btn-active-light-warning text-center" data-bs-toggle="tooltip" data-bs-title="Edit"><i class="fas fa-edit"></i></a>';
                return '<div class="d-flex justify-content-center gap-2">' . $s . ' ' . $e . '</div>';
            })
            ->rawColumns(['nama_mahasiswa', 'prodi', 'tanggal_pengajuan', 'status', 'catatan', 'action'])
            ->make(true);
    }

    public function getDataMahasiswaSimpt(string $nim)
    {
        $user = Auth::user();

        if ($user->role !== 'BAK') {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        $isNers = $mahasiswa?->prodi_id === '423716ff-d094-41ef-99e6-02cbd05c72d1';

        if ($isNers) {
            return response()->json([
                'semester'     => 1,
                'is_valid_krs' => true,
            ]);
        }

        $dataSimpt = $this->getDataSimpt($nim);

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $isValidKrs = false;
        
        if ($dataSimpt && $latestAkademik) {
            $isValidKrs = ($dataSimpt->id_smt == $latestAkademik->kode_akademik);
        }

        if (!$dataSimpt) {
            return response()->json([
                'semester'     => 1,
                'is_valid_krs' => $isValidKrs,
                'message'      => 'Data SIMPT tidak ditemukan untuk mahasiswa ini.',
            ]);
        }

        return response()->json([
            'semester'     => (!empty($dataSimpt->semester)) ? $dataSimpt->semester : 1,
            'is_valid_krs' => $isValidKrs,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') { abort(403); }
        $fakultasId = $user->penduduk?->fakultas_id;
        if (!$fakultasId) { return redirect()->route('bak.dashboard')->with('failed', 'Anda belum terhubung ke fakultas manapun.'); }

        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra = Mitra::all();
        $mahasiswa = Mahasiswa::with('prodi')->where('fakultas_id', $fakultasId)->orderBy('nama', 'asc')->get();

        return view('bak.surat_pkl.create', compact('latestAkademik', 'mitra', 'mahasiswa'));
    }

    public function store(Request $request, SuratPKLGenerator $generatorService)
    {
        $userBak = Auth::user();
        if ($userBak->role !== 'BAK') { abort(403); }

        $fakultasIdBak = $userBak->penduduk?->fakultas_id;
        if (!$fakultasIdBak) { return back()->with('failed', 'Data BAK tidak terhubung ke fakultas manapun.'); }

        $request->validate($this->rules(), [
            'tgl_mulai.after_or_equal' => 'Tanggal mulai minimal hari ini.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $mahasiswa = Mahasiswa::with('prodi')->where('nim', $request->nim)->where('fakultas_id', $fakultasIdBak)->first();
        if (!$mahasiswa) { return back()->with('failed', 'Mahasiswa tersebut bukan bagian dari fakultas Anda.'); }

        $isNers = $mahasiswa->prodi_id === '423716ff-d094-41ef-99e6-02cbd05c72d1';
        $dataSimpt = $isNers ? null : $this->getDataSimpt($mahasiswa->nim);
        $semester = (!empty($dataSimpt?->semester)) ? $dataSimpt->semester : 1;

        if (!$isNers) {
            $akademik = TahunAkademik::find($request->akademik_id);
            if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
                return back()
                    ->withInput()
                    ->with('failed', 'Mahasiswa belum mengisi KRS pada semester ini, sehingga tidak dapat dibuatkan surat.');
            }
        }

        $supportsAnggota = $this->supportsAnggotaKelompok();
        $hasAnggotaInput = collect($request->input('anggota_kelompok', []))
            ->contains(fn($a) => filled(data_get($a, 'nama')) || filled(data_get($a, 'nim')));
        if (!$supportsAnggota && $hasAnggotaInput) {
            return back()->withInput()->with('failed', 'Fitur pengajuan PKL kelompok belum aktif di database.');
        }

        $anggotaKelompok = $supportsAnggota
            ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa->nim, $fakultasIdBak)
            : [];
        $isKelompok = !empty($anggotaKelompok);

        $template = $this->resolveTemplatePKL($fakultasIdBak, $isKelompok);
        if (!$template) { return back()->with('failed', $this->missingTemplateMessage($isKelompok)); }

        $noSurat = SuratPKL::getNextNoSurat($template->id_template, $request->akademik_id);
        $payload = [
            'template_id' => $template->id_template, 'no_surat' => $noSurat,
            'nim' => $mahasiswa->nim, 'akademik_id' => $request->akademik_id,
            'mitra_id' => $request->mitra_id, 'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai, 'status' => 'pengajuan',
            'catatan' => 'Diajukan oleh BAK Fakultas untuk mahasiswa', 'file_generated' => null,
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
            'status' => 'pengajuan', 'catatan' => 'Diajukan oleh BAK Fakultas untuk mahasiswa', 'jabatan_id' => null,
        ]);
        PengajuanStatusLog::create([
            'history_id' => $pengajuan->id_history, 'status' => 'pengajuan',
            'user_role' => 'BAK', 'user_id' => $userBak->id,
            'catatan' => 'Diajukan oleh BAK Fakultas untuk mahasiswa',
        ]);

        return redirect()->route('bak.surat-pkl.index')->with('success', 'Pengajuan surat berhasil diajukan!');
    }

    public function show(string $id)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') { abort(403); }
        $fakultasId = $user->penduduk?->fakultas_id;
        if (!$fakultasId) { abort(403, 'Anda tidak terhubung ke fakultas manapun.'); }

        $surat = SuratPKL::with('mahasiswa.prodi')->where('id_surat_pkl', $id)->firstOrFail();
        return view('bak.surat_pkl.show', compact('surat'));
    }

    public function edit(string $id)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') { abort(403); }
        $fakultasId = $user->penduduk?->fakultas_id;
        if (!$fakultasId) { abort(403, 'Anda tidak terhubung ke fakultas manapun.'); }

        $surat = SuratPKL::with('mahasiswa.prodi')->where('id_surat_pkl', $id)->firstOrFail();
        $latestAkademik = TahunAkademik::orderByDesc('id_akademik')->first();
        $mitra = Mitra::all();
        $mahasiswa = Mahasiswa::with('prodi')->where('fakultas_id', $fakultasId)->orderBy('nama', 'asc')->get();

        return view('bak.surat_pkl.edit', compact('surat', 'latestAkademik', 'mitra', 'mahasiswa'));
    }

    public function update(Request $request, string $id, SuratPKLGenerator $generatorService)
    {
        $userBak = Auth::user();
        if ($userBak->role !== 'BAK') { abort(403); }
        $fakultasIdBak = $userBak->penduduk?->fakultas_id;
        if (!$fakultasIdBak) { return back()->with('failed', 'Data BAK tidak terhubung ke fakultas manapun.'); }

        $request->validate($this->rules(), [
            'tgl_mulai.after_or_equal' => 'Tanggal mulai minimal hari ini.',
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $surat = SuratPKL::findOrFail($id);
        $mahasiswa = Mahasiswa::with('prodi')->where('nim', $request->nim)->where('fakultas_id', $fakultasIdBak)->first();
        if (!$mahasiswa) { return back()->with('failed', 'Mahasiswa tersebut bukan bagian dari fakultas Anda.'); }

        $pengajuan = $surat->historyPengajuan()->firstOrFail();

        $isNers = $mahasiswa->prodi_id === '423716ff-d094-41ef-99e6-02cbd05c72d1';
        $dataSimpt = $isNers ? null : $this->getDataSimpt($mahasiswa->nim);
        $semester = (!empty($dataSimpt?->semester)) ? $dataSimpt->semester : 1;

        if (!$isNers) {
            $akademik = TahunAkademik::find($request->akademik_id);
            if ($dataSimpt?->id_smt != $akademik?->kode_akademik) {
                return back()
                    ->withInput()
                    ->with('failed', 'Mahasiswa belum mengisi KRS pada semester ini, sehingga tidak dapat dibuatkan surat.');
            }
        }

        $supportsAnggota = $this->supportsAnggotaKelompok();
        $hasAnggotaInput = collect($request->input('anggota_kelompok', []))
            ->contains(fn($a) => filled(data_get($a, 'nama')) || filled(data_get($a, 'nim')));
        if (!$supportsAnggota && $hasAnggotaInput) {
            return back()->withInput()->with('failed', 'Fitur pengajuan PKL kelompok belum aktif di database.');
        }

        $anggotaKelompok = $supportsAnggota
            ? $this->resolveAnggotaKelompok($request->input('anggota_kelompok', []), $mahasiswa->nim, $fakultasIdBak)
            : [];
        $isKelompok = !empty($anggotaKelompok);

        $template = $this->resolveTemplatePKL($fakultasIdBak, $isKelompok);
        if (!$template) { return back()->with('failed', $this->missingTemplateMessage($isKelompok)); }

        $payload = [
            'template_id' => $template->id_template, 'nim' => $request->nim,
            'akademik_id' => $request->akademik_id, 'mitra_id' => $request->mitra_id,
            'tgl_mulai' => $request->tgl_mulai, 'tgl_selesai' => $request->tgl_selesai,
            'status' => 'pengajuan', 'catatan' => 'Diajukan ulang oleh BAK untuk mahasiswa',
        ];
        if ($supportsAnggota) { $payload['anggota_kelompok'] = $anggotaKelompok; }
        $surat->update($payload);

        try {
            $generatedFilePath = $generatorService->generateWord($surat, $template);
            $surat->update(['file_generated' => $generatedFilePath]);
            $pengajuan->update([
                'nim' => $mahasiswa->nim, 'fakultas_id' => $mahasiswa->fakultas_id,
                'status' => 'pengajuan', 'catatan' => 'Diajukan ulang oleh BAK untuk mahasiswa'
            ]);
            PengajuanStatusLog::create([
                'history_id' => $pengajuan->id_history, 'status' => 'pengajuan',
                'user_role' => 'BAK', 'user_id' => $userBak->id,
                'catatan' => 'Diajukan ulang oleh BAK Fakultas untuk mahasiswa',
            ]);
            return redirect()->route('bak.surat-pkl.index')->with('success', 'Data surat berhasil diperbarui!');
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
            'tgl_mulai' => 'required|date|after_or_equal:today', 'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'anggota_kelompok' => 'nullable|array',
            'anggota_kelompok.*.nim' => 'nullable|string|max:50',
        ];
    }

    private function supportsAnggotaKelompok(): bool { return Schema::hasColumn('surat_pkl', 'anggota_kelompok'); }

    private function resolveTemplatePKL(int $fakultasId, bool $isKelompok): ?Template
    {
        $jenisSurat = $isKelompok ? 'surat_pkl_kelompok' : 'surat_pkl';
        return Template::where('jenis_surat', $jenisSurat)->where('fakultas_id', $fakultasId)->first();
    }

    private function missingTemplateMessage(bool $isKelompok): string
    {
        return $isKelompok
            ? 'Template surat PKL kelompok belum tersedia untuk fakultas Anda.'
            : 'Template surat PKL belum tersedia untuk fakultas Anda.';
    }

    private function resolveAnggotaKelompok(array $anggotaKelompok, ?string $nimKetua, int $fakultasIdBak): array
    {
        $anggotaKelompok = collect($anggotaKelompok)
            ->map(fn($a, $i) => ['index' => $i, 'nim' => trim((string) data_get($a, 'nim'))])
            ->filter(fn($a) => $a['nim'] !== '')->values();

        if ($anggotaKelompok->isEmpty()) { return []; }

        $errors = [];
        $nimAnggota = $anggotaKelompok->pluck('nim')->filter()->all();
        $mahasiswaAnggota = Mahasiswa::with('prodi')
            ->where('fakultas_id', $fakultasIdBak)->whereIn('nim', $nimAnggota)->get()->keyBy('nim');
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
                $errors["anggota_kelompok.{$index}.nim"] = "Mahasiswa anggota {$nim} tidak ditemukan pada fakultas Anda.";
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

