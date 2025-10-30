<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'mitra';

    protected $primaryKey = 'id_mitra';

    protected $fillable = [
        'nama_mitra',
    ];

    public function mitra()
    {
        return $this->hasMany(SuratPenelitian::class, 'mitra_id');
    }
}
