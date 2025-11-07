<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratObservasi extends Model
{
    protected $table = 'surat_observasi';

    protected $primaryKey = 'id_surat_observasi';

    protected $casts = [
        'tgl_observasi' => 'datetime',
    ];

    protected $fillable = [
        'template_id',
        'no_surat',
        'nim',
        'akademik_id',
        'mitra_id',
        'keperluan',
        'semester',
        'tgl_observasi',
        'status',
        'catatan',
        'file_generated'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id_template');
    }

    public static function getNextNoSurat($templateId)
    {
        $last = self::where('template_id', $templateId)->orderBy('id_surat_observasi', 'desc')->first();
        $number = $last ? intval(substr($last->no_surat, -4)) + 1 : 1;
        return sprintf("%04d", $number);
    }

    public function akademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'akademik_id', 'id_akademik');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function historyPengajuan()
    {
        return $this->hasOne(HistoryPengajuan::class, 'id_tabel_surat')
            ->where('tabel', 'surat_observasi');
    }
}
