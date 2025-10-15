<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $primaryKey = 'nim';

    protected $fillable = [
        'nim',
        'prodi_id',
        'fakultas_id',
        'nama',
        'jenis_kelamin',
        'email',
        'no_hp',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function historyPengajuan()
    {
        return $this->hasMany(HistoryPengajuan::class, 'nim');
    }
}
