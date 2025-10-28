<?php

namespace App\Models;

use App\Models\Prodi;
use App\Models\Penduduk;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';

    protected $primaryKey = 'id_fakultas';

    protected $fillable = [
        'nama_fakultas',
        'singkatan',
        'status',
    ];

    public function prodi()
    {
        return $this->hasMany(Prodi::class, 'fakultas_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'fakultas_id');
    }

    public function penduduk()
    {
        return $this->hasMany(Penduduk::class, 'fakultas_id');
    }

    public function template()
    {
        return $this->hasMany(Template::class, 'fakultas_id');
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'fakultas_id');
    }

    public function ttdSurat()
    {
        return $this->hasMany(Jabatan::class, 'fakultas_id');
    }
}
