<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaEligibleLulus extends Model
{
    protected $table = 'mahasiswa_eligible_lulus';

    protected $fillable = [
        'nim',
        'fakultas_id',
        'akademik_id',
        'added_by',
        'keterangan',
        'judul_penelitian',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id_fakultas');
    }

    public function akademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'akademik_id', 'id_akademik');
    }

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
