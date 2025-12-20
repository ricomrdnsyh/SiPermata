<?php

namespace App\Models;

use App\Models\SuratAktif;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $primaryKey = 'id_akademik';

    protected $fillable = [
        'kode_akademik',
        'tahun_akademik',
    ];

    public function suratAktif()
    {
        return $this->hasMany(SuratAktif::class, 'akademik_id');
    }

    public function suratPenelitian()
    {
        return $this->hasMany(SuratPenelitian::class, 'akademik_id');
    }

    public function suratRekomendasi()
    {
        return $this->hasMany(SuratRekomendasi::class, 'akademik_id');
    }

    public function suratPKL()
    {
        return $this->hasMany(SuratPKL::class, 'akademik_id');
    }

    public function suratObservasi()
    {
        return $this->hasMany(SuratObservasi::class, 'akademik_id');
    }
    public function suratLulus()
    {
        return $this->hasMany(SuratLulus::class, 'akademik_id');
    }
}
