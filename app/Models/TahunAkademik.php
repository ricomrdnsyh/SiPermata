<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $primaryKey = 'id_akademik';

    protected $fillable = [
        'kode_akademik',
        'tahun_akademik',
    ];
}
