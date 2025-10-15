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
}
