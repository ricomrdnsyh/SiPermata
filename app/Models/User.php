<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Penduduk;
use App\Models\Mahasiswa;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'identifier',
        'nama',
        'type',
        'reference_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['role'];

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
        // type = penduduk
        $jabatan = $this->penduduk?->jabatan;
        return $jabatan ? strtoupper($jabatan->status) : '-';
    }
}
