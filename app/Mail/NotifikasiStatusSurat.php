<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiStatusSurat extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $pengajuan;
    public $status;
    public $catatan;

    public function __construct($mahasiswa, $pengajuan, $status, $catatan = null)
    {
        $this->mahasiswa = $mahasiswa;
        $this->pengajuan = $pengajuan;
        $this->status    = $status;
        $this->catatan   = $catatan;
    }

    public function build()
    {
        $statusTitle = strtoupper($this->status); // DISETUJUI / DITOLAK
        $subject = "Status Pengajuan Surat: {$statusTitle}";

        return $this->subject($subject)
            ->view('emails.notifikasi_status_surat');
    }
}
