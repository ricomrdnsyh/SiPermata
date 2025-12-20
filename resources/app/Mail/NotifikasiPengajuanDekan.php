<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiPengajuanDekan extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $pengajuan;
    public $namaSurat;
    public $urlDetail;

    public function __construct($mahasiswa, $pengajuan, $namaSurat, $urlDetail = null)
    {
        $this->mahasiswa = $mahasiswa;
        $this->pengajuan = $pengajuan;
        $this->namaSurat = $namaSurat;
        $this->urlDetail = $urlDetail;
    }

    public function build()
    {
        $subject = "Pengajuan Surat Menunggu Persetujuan Dekan";

        return $this->subject($subject)
            ->view('emails.notifikasi_pengajuan_dekan');
    }
}
