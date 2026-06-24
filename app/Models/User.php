<?php

namespace App\Models;


use App\Models\Penduduk;
use App\Models\Mahasiswa;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    
    use HasFactory, Notifiable;

    
    protected $fillable = [
        'identifier',
        'nama',
        'type',
        'reference_id',
        'password',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'reference_id', 'nim');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'reference_id', 'id_penduduk');
    }

    public function getRoleAttribute()
    {
        if ($this->type === 'admin') {
            return 'admin';
        }
        if ($this->type === 'mahasiswa') {
            return 'mahasiswa';
        }
        
        $jabatan = $this->penduduk?->jabatan;
        return $jabatan ? strtoupper($jabatan->status) : '-';
    }
}
