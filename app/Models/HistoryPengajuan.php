<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function suratAktif()
    {
        return $this->belongsTo(SuratAktif::class, 'id_tabel_surat');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim');
    }

    // Mapping model surat
    protected $modelMapping = [
        'surat_aktif' => SuratAktif::class,
        // 'surat_lulus' => SuratLulus::class,
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
            'surat_aktif' => match ($surat->kategori ?? '') {
                'UMUM' => 'Surat Keterangan Aktif Umum',
                'PNS'  => 'Surat Keterangan Aktif PNS',
                'PPPK' => 'Surat Keterangan Aktif PPPK',
                default => 'Surat Lainnya'
            },
            'surat_lulus' => 'Surat Keterangan Lulus',
            'surat_pindah' => 'Surat Keterangan Pindah',
            default => 'Surat ' . ucwords(str_replace('_', ' ', $this->tabel))
        };
    }
}
