<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPenelitian extends Model
{
    protected $table = 'surat_izin_penelitian';

    protected $primaryKey = 'id_surat_izin_penelitian';

    protected $casts = [
        'tgl_mulai' => 'datetime',
        'tgl_selesai' => 'datetime',
    ];

    protected $fillable = [
        'template_id',
        'no_surat',
        'nim',
        'akademik_id',
        'judul_penelitian',
        'mitra_id',
        'tgl_mulai',
        'tgl_selesai',
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
            'fakultas agama islam'            => '01',
            'fai'                             => '01',
            'fakultas teknik'                 => '02',
            'ft'                              => '02',
            'fakultas kesehatan'              => '03',
            'fkes'                            => '03',
            'fakultas sosial dan humaniora'   => '04',
            'soshum'                          => '04',
        ];

        return $map[$nama] ?? '00';
    }

    public static function getNextNoSurat($templateId): string
    {
        $template = Template::with('fakultas')->findOrFail($templateId);

        $namaFakultas = $template->fakultas->nama_fakultas
            ?? $template->fakultas->singkatan
            ?? null;

        $kodeFakultas = self::kodeFakultas($namaFakultas);

        $bulan = date('m');
        $tahun = date('Y');

        $prefix = "NJ-T06/{$kodeFakultas}/";
        $suffix = "/SP/{$bulan}.{$tahun}";

        $last = self::where('template_id', $templateId)
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('no_surat', 'like', $prefix . '%' . $suffix)
            ->orderBy('id_surat_izin_penelitian', 'desc')
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
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function historyPengajuan()
    {
        return $this->hasOne(HistoryPengajuan::class, 'id_tabel_surat')
            ->where('tabel', 'surat_izin_penelitian');
    }
}
