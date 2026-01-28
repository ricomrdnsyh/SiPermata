<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SuratPKL;
use App\Models\TtdSurat;
use App\Models\SuratAktif;
use App\Models\SuratLulus;
use App\Models\SuratObservasi;
use App\Models\SuratPenelitian;
use App\Models\SuratRekomendasi;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Storage;

class VerifikasiController extends Controller
{
    protected function gagal($surat, string $message)
    {
        return view('verifikasi.gagal', [
            'surat' => $surat,
            'status_verifikasi' => $message,
        ]);
    }

    protected function isSuratApproved($surat): bool
    {
        $approvedStatuses = ['diterima', 'selesai'];

        return in_array($surat->status ?? null, $approvedStatuses, true) ||
            in_array($surat->status_verifikasi ?? null, $approvedStatuses, true) ||
            ($surat->is_diterima ?? false) === true ||
            ($surat->is_approved ?? false) === true;
    }

    protected function fmtDateTime($value): string
    {
        if (empty($value)) return '-';

        return Carbon::parse($value)
            ->timezone('Asia/Jakarta')
            ->locale('id')
            ->isoFormat('D MMMM YYYY, HH:mm:ss') . ' WIB';
    }

    protected function getJenisSuratLabel(string $jenis): string
    {
        $map = [
            'aktif'       => 'Surat Keterangan Aktif Kuliah',
            'penelitian'  => 'Surat Izin Penelitian',
            'rekomendasi' => 'Surat Rekomendasi',
            'pkl'         => 'Surat Permohonan PKL',
            'observasi'   => 'Surat Permohonan Observasi',
            'lulus'       => 'Surat Keterangan Lulus',
        ];

        return $map[$jenis] ?? 'Surat';
    }

    protected function legalitasPayload($surat, string $jenis): array
    {
        $mhs = $surat->mahasiswa;

        $fakultas = optional($mhs?->fakultas)->nama_fakultas ?? '-';
        $prodi    = optional($mhs?->prodi)->nama_prodi ?? '-';

        $tahunAkademik = optional($surat->akademik)->tahun_akademik ?? '-';

        $fakultasId = data_get($surat, 'template.fakultas_id')
            ?? data_get($mhs, 'fakultas_id');

        $ttd = TtdSurat::where('fakultas_id', $fakultasId)
            ->where('template_id', $surat->template_id)
            ->where('status', 'aktif')
            ->first();

        $penandatangan = $ttd->nama_ttd ?? '-';
        $nidn          = $ttd->nidn ?? '-';

        $jabatan = 'Dekan';

        return [
            'jenis_surat'     => $this->getJenisSuratLabel($jenis),
            'nama'            => $mhs->nama ?? '-',
            'nim'             => $mhs->nim ?? ($surat->nim ?? '-'),
            'fakultas'        => $fakultas,
            'prodi'           => $prodi,
            'tahun_akademik'  => $tahunAkademik,
            'no_surat'        => $surat->no_surat ?? '-',
            'tgl_pengajuan'   => $this->fmtDateTime($surat->created_at),
            'tgl_persetujuan' => $this->fmtDateTime($surat->updated_at),
            'penandatangan'   => $penandatangan,
            'jabatan'         => $jabatan,
            'nidn'            => $nidn,
        ];
    }

    protected function findSuratByAnyId(string $id, string $modelClass, string $pkField)
    {
        try {
            $decryptedId = Crypt::decryptString($id);
        } catch (DecryptException $e) {
            $decryptedId = null;
        }

        return $modelClass::with([
            'mahasiswa.fakultas',
            'mahasiswa.prodi',
            'akademik',
            'template.ttdSurat',
            'template.ttdSurat.fakultas',
        ])
            ->where(function ($q) use ($id, $decryptedId, $pkField) {
                if ($decryptedId !== null) {
                    $q->where($pkField, $decryptedId);
                }
                $q->orWhere($pkField, $id)
                    ->orWhere('no_surat', $id);
            })
            ->first();
    }

    protected function sanitizeStoragePath(?string $path): ?string
    {
        if (!$path) return null;

        $p = str_replace('\\', '/', $path);
        $p = ltrim($p, '/');

        if (str_contains($p, '..')) return null;

        return $p;
    }

    protected function buildPdfName($surat, string $jenis): string
    {
        $nim = data_get($surat, 'mahasiswa.nim') ?? ($surat->nim ?? 'NoNIM');
        $no  = $surat->no_surat ?? $jenis;

        $nim = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $nim);
        $no  = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $no);

        return strtoupper($jenis) . '_' . $nim . '_' . $no . '.pdf';
    }

    protected function previewRouteName(string $jenis): ?string
    {
        $map = [
            'aktif'       => 'verifikasi.aktif.preview',
            'penelitian'  => 'verifikasi.penelitian.preview',
            'rekomendasi' => 'verifikasi.rekomendasi.preview',
            'pkl'         => 'verifikasi.pkl.preview',
            'observasi'   => 'verifikasi.observasi.preview',
            'lulus'       => 'verifikasi.lulus.preview',
        ];

        return $map[$jenis] ?? null;
    }

    protected function verifyGeneric(string $id, string $modelClass, string $pkField, string $jenis)
    {
        $surat = $this->findSuratByAnyId($id, $modelClass, $pkField);

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        $filePath = $this->sanitizeStoragePath($surat->file_generated ?? null);
        $hasFile = $filePath && Storage::disk('local')->exists($filePath);

        $routeName = $this->previewRouteName($jenis);
        $previewUrl = ($hasFile && $routeName) ? route($routeName, ['id' => $id]) : null;

        return view('verifikasi.lihat_pdf', [
            'data' => $this->legalitasPayload($surat, $jenis),
            'preview_url' => $previewUrl,
            'has_file' => $hasFile,
        ]);
    }

    protected function previewGeneric(string $id, string $modelClass, string $pkField, string $jenis)
    {
        $surat = $this->findSuratByAnyId($id, $modelClass, $pkField);

        if (! $surat) {
            return $this->gagal(null, 'Surat tidak ditemukan.');
        }

        if (! $this->isSuratApproved($surat)) {
            return $this->gagal($surat, 'Surat belum disetujui atau masih dalam proses.');
        }

        $filePath = $this->sanitizeStoragePath($surat->file_generated ?? null);

        if (!$filePath) {
            return $this->gagal($surat, 'File surat belum tersedia.');
        }

        if (!Storage::disk('local')->exists($filePath)) {
            return $this->gagal($surat, 'File di server tidak ditemukan.');
        }

        $absolutePath = Storage::disk('local')->path($filePath);
        $fileName = $this->buildPdfName($surat, $jenis);

        return response()->file($absolutePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function verifySuratAktif(string $id)
    {
        return $this->verifyGeneric($id, SuratAktif::class, 'id_surat_aktif', 'aktif');
    }

    public function verifySuratPenelitian(string $id)
    {
        return $this->verifyGeneric($id, SuratPenelitian::class, 'id_surat_izin_penelitian', 'penelitian');
    }

    public function verifySuratRekomendasi(string $id)
    {
        return $this->verifyGeneric($id, SuratRekomendasi::class, 'id_surat_rekomendasi', 'rekomendasi');
    }

    public function verifySuratPKL(string $id)
    {
        return $this->verifyGeneric($id, SuratPKL::class, 'id_surat_pkl', 'pkl');
    }

    public function verifySuratObservasi(string $id)
    {
        return $this->verifyGeneric($id, SuratObservasi::class, 'id_surat_observasi', 'observasi');
    }

    public function verifySuratLulus(string $id)
    {
        return $this->verifyGeneric($id, SuratLulus::class, 'id_surat_lulus', 'lulus');
    }

    public function previewSuratAktif(string $id)
    {
        return $this->previewGeneric($id, SuratAktif::class, 'id_surat_aktif', 'aktif');
    }

    public function previewSuratPenelitian(string $id)
    {
        return $this->previewGeneric($id, SuratPenelitian::class, 'id_surat_izin_penelitian', 'penelitian');
    }

    public function previewSuratRekomendasi(string $id)
    {
        return $this->previewGeneric($id, SuratRekomendasi::class, 'id_surat_rekomendasi', 'rekomendasi');
    }

    public function previewSuratPKL(string $id)
    {
        return $this->previewGeneric($id, SuratPKL::class, 'id_surat_pkl', 'pkl');
    }

    public function previewSuratObservasi(string $id)
    {
        return $this->previewGeneric($id, SuratObservasi::class, 'id_surat_observasi', 'observasi');
    }

    public function previewSuratLulus(string $id)
    {
        return $this->previewGeneric($id, SuratLulus::class, 'id_surat_lulus', 'lulus');
    }
}
