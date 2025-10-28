<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TtdSurat extends Model
{
    protected $table = 'ttd_surat';

    protected $primaryKey = 'id_ttd';

    protected $fillable = [
        'template_id',
        'nama_ttd',
        'fakultas_id',
        'status',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }
}
