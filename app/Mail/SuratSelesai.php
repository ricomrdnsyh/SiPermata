<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment; // 💡 Perlu diimpor

class SuratSelesai extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswa;
    public $surat;
    public $filePath;
    public $fileName;
    public $namaSurat;

    public function __construct($mahasiswa, $surat, $filePath, $fileName, $namaSurat)
    {
        $this->mahasiswa = $mahasiswa;
        $this->surat = $surat;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->namaSurat = $namaSurat;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Anda Telah Selesai Diproses dan Disetujui',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.surat-selesai',
        );
    }

    // 💡 Lampirkan File
    public function attachments(): array
    {
        // Asumsi file berada di storage/app/public
        return [
            Attachment::fromStorageDisk('local', $this->filePath)
                ->as($this->fileName)
                ->withMime('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ];
    }
}
