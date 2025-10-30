<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = 'template';

    protected $primaryKey = 'id_template';

    protected $fillable = [
        'nama_template',
        'jenis_surat',
        'file',
        'fakultas_id',
        'prodi_id',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function ttdSurat()
    {
        return $this->hasMany(Jabatan::class, 'fakultas_id');
    }

    public function suratAktif()
    {
        return $this->hasMany(SuratAktif::class, 'akademik_id');
    }

    public function suratPenelitian()
    {
        return $this->hasMany(SuratPenelitian::class, 'akademik_id');
    }
}
