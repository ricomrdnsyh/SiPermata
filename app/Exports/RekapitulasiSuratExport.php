<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapitulasiSuratExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithEvents
{
    protected $data;
    protected $title;
    protected $isAdmin;

    public function __construct($data, string $title = 'Rekapitulasi Surat', bool $isAdmin = false)
    {
        $this->data = $data;
        $this->title = $title;
        $this->isAdmin = $isAdmin;
    }

    public function collection()
    {
        $rows = collect();
        $no = 1;

        foreach ($this->data as $item) {
            $row = [
                'No' => $no++,
                'Nama Mahasiswa' => $item->mahasiswa?->nama ?? $item->nim,
                'NIM' => $item->nim,
            ];

            if ($this->isAdmin) {
                $row['Fakultas'] = $item->mahasiswa?->prodi?->fakultas?->nama_fakultas ?? '-';
            }

            $row['Program Studi'] = $item->mahasiswa?->prodi?->nama_prodi ?? '-';
            $row['Jenis Surat'] = $item->nama_surat;
            $row['No. Surat'] = $this->getNoSurat($item);
            $row['Tanggal Pengajuan'] = $item->tanggal_pengajuan_asli
                ? \Carbon\Carbon::parse($item->tanggal_pengajuan_asli)->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM YYYY')
                : '-';
            $row['Status'] = ucfirst($item->status);

            $rows->push($row);
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = ['No', 'Nama Mahasiswa', 'NIM'];

        if ($this->isAdmin) {
            $headings[] = 'Fakultas';
        }

        $headings = array_merge($headings, ['Program Studi', 'Jenis Surat', 'No. Surat', 'Tanggal Pengajuan', 'Status']);

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $this->isAdmin ? 'I' : 'H';

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '004289'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Rekapitulasi Surat';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = $this->isAdmin ? 'I' : 'H';
                $lastRow = $this->data->count() + 1;

                // Auto-size columns
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Border semua data
                $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Alignment body center untuk No
                $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    private function getNoSurat($history)
    {
        $surat = $history->surat;
        if (!$surat) return '-';

        return $surat->no_surat ?? '-';
    }
}
