<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HistoryPengajuan extends Model
{
    protected $table = 'history_pengajuan';

    protected $primaryKey = 'id_history';

    protected $fillable = [
        'id_tabel_surat',
        'nim',
        'fakultas_id',
        'tabel',
        'jabatan_id',
        'status',
        'catatan'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim');
    }

    public function suratAktif()
    {
        return $this->belongsTo(SuratAktif::class, 'id_tabel_surat');
    }

    public function suratPenelitian()
    {
        return $this->belongsTo(SuratPenelitian::class, 'id_tabel_surat');
    }

    public function suratRekomendasi()
    {
        return $this->belongsTo(SuratRekomendasi::class, 'id_tabel_surat');
    }

    public function suratPKL()
    {
        return $this->belongsTo(SuratPKL::class, 'id_tabel_surat');
    }

    public function suratObservasi()
    {
        return $this->belongsTo(SuratObservasi::class, 'id_tabel_surat');
    }

    public function suratLulus()
    {
        return $this->belongsTo(SuratLulus::class, 'id_tabel_surat');
    }

    public function statusLogs()
    {
        return $this->hasMany(PengajuanStatusLog::class, 'history_id', 'id_history')
            ->orderBy('created_at');
    }

    protected static function booted()
    {
        static::deleted(function ($history) {
            if ($history->tabel === 'surat_aktif') {

                $suratAktif = $history->suratAktif;

                $filePath   = $suratAktif->file_generated ?? null;

                $history->suratAktif()->delete();

                if ($filePath) {
                    Storage::disk('local')->delete($filePath);
                }
            } elseif ($history->tabel === 'surat_izin_penelitian') {

                $suratPenelitian = $history->suratPenelitian;

                $filePath        = $suratPenelitian->file_generated ?? null;

                $history->suratPenelitian()->delete();

                if ($filePath) {
                    Storage::disk('local')->delete($filePath);
                }
            } elseif ($history->tabel === 'surat_rekomendasi') {

                $suratRekomendasi = $history->suratRekomendasi;

                $filePath         = $suratRekomendasi->file_generated ?? null;

                $history->suratRekomendasi()->delete();

                if ($filePath) {
                    Storage::disk('local')->delete($filePath);
                }
            } elseif ($history->tabel === 'surat_pkl') {

                $suratPKL = $history->suratPKL;

                $filePath = $suratPKL->file_generated ?? null;

                $history->suratPKL()->delete();

                if ($filePath) {
                    Storage::disk('local')->delete($filePath);
                }
            } elseif ($history->tabel === 'surat_observasi') {

                $suratObservasi = $history->suratObservasi;

                $filePath = $suratObservasi->file_generated ?? null;

                $history->suratObservasi()->delete();

                if ($filePath) {
                    Storage::disk('local')->delete($filePath);
                }
            } elseif ($history->tabel === 'surat_keterangan_lulus') {

                $suratLulus = $history->suratLulus;

                $filePath = $suratLulus->file_generated ?? null;

                $history->suratLulus()->delete();

                if ($filePath) {
                    Storage::disk('local')->delete($filePath);
                }
            }
        });
    }

    protected $modelMapping = [
        'surat_aktif'            => SuratAktif::class,
        'surat_izin_penelitian'  => SuratPenelitian::class,
        'surat_rekomendasi'      => SuratRekomendasi::class,
        'surat_pkl'              => SuratPKL::class,
        'surat_observasi'        => SuratObservasi::class,
        'surat_keterangan_lulus' => SuratLulus::class,
        // tambahkan jenis surat lain di sini
    ];

    // Akses data surat dinamis
    public function getSuratAttribute()
    {
        if (!$this->tabel || !$this->id_tabel_surat) {
            return null;
        }

        $modelClass = $this->modelMapping[$this->tabel] ?? null;
        if (!$modelClass || !class_exists($modelClass)) {
            return null;
        }

        return $modelClass::find($this->id_tabel_surat);
    }

    // 🔥 Nama surat dinamis
    public function getNamaSuratAttribute()
    {
        $surat = $this->surat;
        if (!$surat) return 'Surat Tidak Ditemukan';

        return match ($this->tabel) {
            'surat_aktif'  => match ($surat->kategori ?? '') {
                'UMUM'     => 'Surat Keterangan Aktif Umum',
                'PNS'      => 'Surat Keterangan Aktif PNS',
                'PPPK'     => 'Surat Keterangan Aktif PPPK',
                default    => 'Surat Lainnya'
            },
            'surat_izin_penelitian'  => 'Surat Izin Penelitian',
            'surat_rekomendasi'      => 'Surat Rekomendasi',
            'surat_pkl'              => 'Surat Permohonan PKL',
            'surat_observasi'        => 'Surat Permohonan Observasi',
            'surat_keterangan_lulus' => 'Surat Keterangan Lulus',
            // 'surat_pindah'           => 'Surat Keterangan Pindah',
            default                  => 'Surat ' . ucwords(str_replace('_', ' ', $this->tabel))
        };
    }
}
