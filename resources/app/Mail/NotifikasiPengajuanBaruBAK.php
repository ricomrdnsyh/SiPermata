<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPengajuanBaruBAK extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $pengajuan;
    public $namaSurat; // human readable, misal: SURAT AKTIF
    public $urlDetail; // link ke halaman BAK untuk proses

    /**
     * @param  $mahasiswa   instance Mahasiswa
     * @param  $pengajuan   instance HistoryPengajuan
     * @param  string $namaSurat
     * @param  string|null $urlDetail
     */
    public function __construct($mahasiswa, $pengajuan, $namaSurat, $urlDetail = null)
    {
        $this->mahasiswa = $mahasiswa;
        $this->pengajuan = $pengajuan;
        $this->namaSurat = $namaSurat;
        $this->urlDetail = $urlDetail;
    }

    public function build()
    {
        $subject = "Pengajuan Surat Baru (Mahasiswa): {$this->namaSurat} ({$this->mahasiswa->nim})";

        return $this->subject($subject)
            ->view('emails.notifikasi_pengajuan_baru_bak');
    }
}
