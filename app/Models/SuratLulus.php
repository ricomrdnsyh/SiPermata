<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratLulus extends Model
{
    protected $table = 'surat_keterangan_lulus';

    protected $primaryKey = 'id_surat_lulus';

    protected $casts = [
        'tgl_lahir' => 'datetime',
    ];

    protected $fillable = [
        'template_id',
        'no_surat',
        'nim',
        'akademik_id',
        'tempat_lahir',
        'tgl_lahir',
        'judul_penelitian',
        'status',
        'catatan',
        'file_generated'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id_template');
    }

    protected static function kodeFakultas(?string $nama): string
    {
        $nama = strtolower(trim($nama ?? ''));

        $map = [
            'agama islam'            => '01',
            'fai'                    => '01',
            'teknik'                 => '02',
            'ft'                     => '02',
            'kesehatan'              => '03',
            'fkes'                   => '03',
            'sosial dan humaniora'   => '04',
            'soshum'                 => '04',
            'pascasarjana'           => '05',
            'pasca'                  => '05',
        ];

        return $map[$nama] ?? '00';
    }

    public static function getNextNoSurat($templateId, $akademikId): string
    {
        $template = Template::with('fakultas')->findOrFail($templateId);

        $namaFakultas = $template->fakultas->nama_fakultas
            ?? $template->fakultas->singkatan
            ?? null;

        $kodeFakultas = self::kodeFakultas($namaFakultas);

        $bulan = date('m');
        $tahun = date('Y');

        $prefix = "NJ-T06/{$kodeFakultas}/";
        $suffix = "/SKL/{$bulan}.{$tahun}";

        $last = self::where('template_id', $templateId)
            ->where('akademik_id', $akademikId)
            ->where('no_surat', 'like', $prefix . '%/SKL/%')
            ->orderBy('no_surat', 'desc')
            ->first();

        if ($last) {
            $parts = explode('/', $last->no_surat);
            $urut = isset($parts[2]) ? (int) $parts[2] + 1 : 1;
        } else {
            $urut = 1;
        }

        $noUrut = sprintf('%04d', $urut);

        return $prefix . $noUrut . $suffix;
    }

    public function akademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'akademik_id', 'id_akademik');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'id_mitra');
    }

    public function historyPengajuan()
    {
        return $this->hasOne(HistoryPengajuan::class, 'id_tabel_surat')
            ->where('tabel', 'surat_keterangan_lulus');
    }

    public function getTanggalPengajuanAsliAttribute()
    {
        $firstLog = $this->historyPengajuan?->statusLogs()->orderBy('id_log', 'asc')->first();
        return $firstLog?->created_at ?? $this->created_at;
    }
}
