<?php

namespace App\Models;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    protected $table = 'penduduk';

    protected $primaryKey = 'id_penduduk';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_penduduk',
        'fakultas_id',
        'prodi_id',
        'nama_penduduk',
        'nidn',
        'email',
        'no_hp',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function jabatan()
    {
        return $this->hasOne(Jabatan::class, 'penduduk_id');
    }
}
