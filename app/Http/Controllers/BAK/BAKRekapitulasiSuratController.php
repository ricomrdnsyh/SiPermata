<?php

namespace App\Http\Controllers\BAK;

use App\Http\Controllers\Controller;
use App\Exports\RekapitulasiSuratExport;
use App\Models\Fakultas;
use App\Models\HistoryPengajuan;
use App\Models\Prodi;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratPKL;
use App\Models\SuratRekomendasi;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;

class BAKRekapitulasiSuratController extends Controller
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
        if ($user->role !== 'BAK') abort(403);

        $fakultasIdUser = $user->penduduk?->fakultas_id;
        $listProdi = collect();
        if ($fakultasIdUser) {
            $listProdi = Prodi::where('fakultas_id', $fakultasIdUser)->get();
        }

        $namaFakultas = $fakultasIdUser
            ? Fakultas::find($fakultasIdUser)?->nama_fakultas ?? 'Fakultas'
            : 'Fakultas';

        $listNamaSurat = $this->listSurat;
        $listTahunAkademik = TahunAkademik::orderBy('id_akademik', 'desc')->get();
        $currentTahunAkademik = $listTahunAkademik->first()?->tahun_akademik;

        $totalSelesai = 0;
        $breakdownSurat = [];
        if ($fakultasIdUser) {
            $totalSelesai = HistoryPengajuan::where('fakultas_id', $fakultasIdUser)
                ->where('status', 'selesai')->count();
            foreach ($this->listSurat as $tabel => $nama) {
                $breakdownSurat[$nama] = HistoryPengajuan::where('fakultas_id', $fakultasIdUser)
                    ->where('status', 'selesai')->where('tabel', $tabel)->count();
            }
        }

        return view('bak.rekapitulasi_surat.index', compact(
            'listProdi',
            'listNamaSurat',
            'listTahunAkademik',
            'currentTahunAkademik',
            'namaFakultas',
            'totalSelesai',
            'breakdownSurat'
        ));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') abort(403);

        $fakultasIdUser = $user->penduduk?->fakultas_id;
        if (!$fakultasIdUser) {
            return DataTables::of(HistoryPengajuan::whereRaw('1=0'))->make(true);
        }

        $query = HistoryPengajuan::with(['mahasiswa.prodi', 'mahasiswa.prodi.fakultas'])
            ->where('fakultas_id', $fakultasIdUser)
            ->where('status', 'selesai');

        $this->applyFilters($query, $request);

        return DataTables::of($query)
            ->order(fn($q) => $q->orderBy('created_at', 'desc'))
            ->filterColumn('nama_mahasiswa', fn($q, $kw) => $q->whereHas('mahasiswa', fn($sub) => $sub->where('nama', 'like', "%{$kw}%")))
            ->filterColumn('prodi', fn($q, $kw) => $q->whereHas('mahasiswa.prodi', fn($sub) => $sub->where('nama_prodi', 'like', "%{$kw}%")))
            ->addColumn('nama_mahasiswa', fn($r) => $r->mahasiswa?->nama ?? $r->nim)
            ->addColumn('prodi', fn($r) => $r->mahasiswa?->prodi?->nama_prodi ?? '-')
            ->addColumn('nama_surat', fn($r) => $r->nama_surat)
            ->addColumn('no_surat', fn($r) => $r->surat?->no_surat ?? '-')
            ->addColumn('tanggal', function ($r) {
                $date = Carbon::parse($r->tanggal_pengajuan_asli)->setTimezone('Asia/Jakarta')->locale('id');
                $formatted = $date->isoFormat('D MMMM YYYY, HH:mm');
                $diff = $date->diffForHumans();
                return "<div>{$formatted}</div><div class=\"text-muted fs-7\">{$diff}</div>";
            })
            ->addColumn('action', function ($r) {
                $url = route('bak.surat.lampiran_preview', ['tabel' => $r->tabel, 'id' => $r->id_tabel_surat]);
                return '<div class="text-center"><a href="' . $url . '" target="_blank" class="btn btn-sm btn-light-primary" data-bs-toggle="tooltip" data-bs-title="Preview PDF"><i class="fas fa-eye"></i></a></div>';
            })
            ->rawColumns(['action', 'tanggal'])
            ->make(true);
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') abort(403);

        $fakultasIdUser = $user->penduduk?->fakultas_id;
        if (!$fakultasIdUser) abort(404);

        $namaFakultas = Fakultas::find($fakultasIdUser)?->nama_fakultas ?? 'Fakultas';

        $query = HistoryPengajuan::with(['mahasiswa.prodi', 'mahasiswa.prodi.fakultas'])
            ->where('fakultas_id', $fakultasIdUser)->where('status', 'selesai');
        $this->applyFilters($query, $request);
        $data = $query->orderBy('created_at', 'desc')->get();

        $title = "Rekapitulasi Surat — {$namaFakultas}";
        $fileName = 'Rekapitulasi_Surat_' . str_replace(' ', '_', $namaFakultas) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new RekapitulasiSuratExport($data, $title, false), $fileName);
    }

    public function downloadBulkPdf(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'BAK') abort(403);

        $fakultasIdUser = $user->penduduk?->fakultas_id;
        if (!$fakultasIdUser) abort(404);

        $namaFakultas = Fakultas::find($fakultasIdUser)?->nama_fakultas ?? 'Fakultas';

        $query = HistoryPengajuan::with(['mahasiswa.prodi'])
            ->where('fakultas_id', $fakultasIdUser)->where('status', 'selesai');
        $this->applyFilters($query, $request);
        $histories = $query->orderBy('created_at', 'desc')->get();

        if ($histories->isEmpty()) {
            return back()->with('failed', 'Tidak ada surat untuk di-download.');
        }

        $disk = 'local';
        $tempDir = storage_path('app/temp_zip_' . uniqid());
        if (!file_exists($tempDir)) mkdir($tempDir, 0755, true);

        $zipPath = $tempDir . DIRECTORY_SEPARATOR . 'Rekapitulasi_Surat_' . str_replace(' ', '_', $namaFakultas) . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('failed', 'Gagal membuat file ZIP.');
        }

        $fileCount = 0;
        foreach ($histories as $h) {
            $surat = $h->surat;
            if (!$surat || empty($surat->file_generated)) continue;
            if (!Storage::disk($disk)->exists($surat->file_generated)) continue;

            $absPath = Storage::disk($disk)->path($surat->file_generated);
            $namaMhs = str_replace(' ', '_', $h->mahasiswa?->nama ?? $h->nim);
            $ext = pathinfo($surat->file_generated, PATHINFO_EXTENSION);
            $zip->addFile($absPath, "{$namaMhs}_{$h->nim}_{$h->nama_surat}.{$ext}");
            $fileCount++;
        }
        $zip->close();

        if ($fileCount === 0) {
            @unlink($zipPath);
            @rmdir($tempDir);
            return back()->with('failed', 'Tidak ada file surat tersedia.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function applyFilters($query, Request $request)
    {
        $tabelNames = array_keys($this->listSurat);
        $tahunAkademikFilter = $request->input('tahun_akademik_filter');

        if (!$request->has('tahun_akademik_filter')) {
            $cur = TahunAkademik::orderBy('id_akademik', 'desc')->first();
            if ($cur) $tahunAkademikFilter = $cur->id_akademik;
        }

        if (!empty($tahunAkademikFilter)) {
            $idsPerTable = [];
            foreach ($tabelNames as $tabel) {
                $pk = match ($tabel) {
                    'surat_aktif' => 'id_surat_aktif',
                    'surat_izin_penelitian' => 'id_surat_izin_penelitian',
                    'surat_observasi' => 'id_surat_observasi',
                    'surat_rekomendasi' => 'id_surat_rekomendasi',
                    'surat_pkl' => 'id_surat_pkl',
                    'surat_keterangan_lulus' => 'id_surat_lulus',
                    default => 'id',
                };
                $ids = DB::table($tabel)->where('akademik_id', $tahunAkademikFilter)->pluck($pk)->toArray();
                if (!empty($ids)) $idsPerTable[$tabel] = $ids;
            }

            if (!empty($idsPerTable)) {
                $query->where(function ($q) use ($idsPerTable) {
                    foreach ($idsPerTable as $tabel => $ids) {
                        $q->orWhere(fn($sub) => $sub->where('tabel', $tabel)->whereIn('id_tabel_surat', $ids));
                    }
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('prodi_filter')) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', $request->input('prodi_filter')));
        }
        if ($request->filled('nama_surat_filter')) {
            $query->where('tabel', $request->input('nama_surat_filter'));
        }
    }
}
