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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_penduduk)) {
                $year = date('y');
                $last = static::where('id_penduduk', 'like', "P{$year}%")
                    ->orderBy('id_penduduk', 'desc')
                    ->first();

                $number = $last ? intval(substr($last->id_penduduk, -4)) + 1 : 1;
                $model->id_penduduk = "P{$year}" . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $fillable = [
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
