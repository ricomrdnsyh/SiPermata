<?php

namespace App\Models;

use App\Models\Fakultas;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    protected $primaryKey = 'id_prodi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_prodi)) {
                $model->id_prodi = Str::uuid()->toString();
            }
        });
    }

    protected $fillable = [
        'fakultas_id',
        'nama_prodi',
        'singkatan',
        'gelar',
        'status',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }
}
