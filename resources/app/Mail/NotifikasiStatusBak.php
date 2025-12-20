<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiStatusBak extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $pengajuan;
    public $status;    // 'disetujui' atau 'ditolak'
    public $namaSurat; // nama surat human readable
    public $catatan;   // catatan singkat (opsional)

    /**
     * @param  $mahasiswa  instance Mahasiswa
     * @param  $pengajuan  instance HistoryPengajuan
     * @param  string $status 'disetujui' | 'ditolak'
     * @param  string|null $namaSurat
     * @param  string|null $catatan
     */
    public function __construct($mahasiswa, $pengajuan, $status, $namaSurat = null, $catatan = null)
    {
        $this->mahasiswa = $mahasiswa;
        $this->pengajuan = $pengajuan;
        $this->status    = $status;
        $this->namaSurat = $namaSurat;
        $this->catatan   = $catatan;
    }

    public function build()
    {
        $statusUpper = strtoupper($this->status); // DISETUJUI / DITOLAK
        $subject = "Status Pengajuan Surat (BAK): {$statusUpper}";

        return $this->subject($subject)
            ->view('emails.notifikasi_status_bak');
    }
}
