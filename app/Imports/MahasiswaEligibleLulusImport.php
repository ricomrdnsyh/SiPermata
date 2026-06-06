<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\MahasiswaEligibleLulus;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class MahasiswaEligibleLulusImport implements ToCollection, WithHeadingRow
{
    protected ?int $fakultasId;
    protected int $akademikId;
    protected int $addedBy;

    public int $imported = 0;
    public int $skipped = 0;
    public int $updated = 0;
    public int $failed = 0;
    public array $errors = [];

    public function __construct(?int $fakultasId, int $akademikId, int $addedBy)
    {
        $this->fakultasId = $fakultasId;
        $this->akademikId = $akademikId;
        $this->addedBy = $addedBy;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $nim = trim($row['nim'] ?? '');
            $nama = trim($row['nama'] ?? '');

            if (empty($nim)) {
                $this->failed++;
                $this->errors[] = "Baris " . ($index + 2) . ": NIM kosong.";
                continue;
            }

            $namaLabel = !empty($nama) ? " ({$nama})" : "";

            // Cek apakah NIM ada di tabel mahasiswa
            $mahasiswaQuery = Mahasiswa::where('nim', $nim);
            if ($this->fakultasId !== null) {
                $mahasiswaQuery->where('fakultas_id', $this->fakultasId);
            }
            $mahasiswa = $mahasiswaQuery->first();

            if (!$mahasiswa) {
                $this->failed++;
                $errorMsg = "Baris " . ($index + 2) . ": NIM {$nim}{$namaLabel} tidak ditemukan";
                if ($this->fakultasId !== null) {
                    $errorMsg .= " atau bukan mahasiswa fakultas Anda.";
                } else {
                    $errorMsg .= ".";
                }
                $this->errors[] = $errorMsg;
                continue;
            }

            $judulPenelitian = trim($row['judul_penelitian'] ?? trim($row['judul'] ?? ''));

            // Cek duplikasi
            $existing = MahasiswaEligibleLulus::where('nim', $nim)
                ->where('akademik_id', $this->akademikId)
                ->first();

            if ($existing) {
                if ($judulPenelitian !== '') {
                    $existing->update([
                        'judul_penelitian' => $judulPenelitian,
                    ]);
                    $this->updated++;
                } else {
                    $this->skipped++;
                }
                continue;
            }

            MahasiswaEligibleLulus::create([
                'nim'              => $nim,
                'fakultas_id'      => $mahasiswa->fakultas_id,
                'akademik_id'      => $this->akademikId,
                'added_by'         => $this->addedBy,
                'judul_penelitian' => $judulPenelitian ?: null,
            ]);

            $this->imported++;
        }
    }
}
