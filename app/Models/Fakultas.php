<?php

namespace App\Models;

use App\Models\Prodi;
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
}
