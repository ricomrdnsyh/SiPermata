<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::truncate();

        Mahasiswa::chunk(100, function ($list) {
            foreach ($list as $m) {
                User::create([
                    'identifier' => $m->nim,
                    'nama' => $m->nama,
                    'type' => 'mahasiswa',
                    'reference_id' => $m->nim, // karena PK mahasiswa = nim
                    'password' => bcrypt('123456')
                ]);
            }
        });

        // Penduduk → reference_id = id_penduduk
        Penduduk::chunk(100, function ($list) {
            foreach ($list as $p) {
                $identifier = $p->nidn ?: $p->email;
                if (!$identifier) return;

                User::create([
                    'identifier' => $identifier,
                    'nama' => $p->nama_penduduk,
                    'type' => 'penduduk',
                    'reference_id' => $p->id_penduduk,
                    'password' => bcrypt('123456')
                ]);
            }
        });

        // Admin khusus
        User::create([
            'identifier' => 'admin',
            'nama' => 'Super Admin',
            'type' => 'admin',
            'reference_id' => 'admin', // bisa string '0' atau 'admin'
            'password' => bcrypt('admin123')
        ]);
    }
}
