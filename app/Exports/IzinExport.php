<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class IzinExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    WithColumnWidths,
    WithEvents
{
    /** Baris tempat judul kolom (baris 1-4 dipakai untuk judul laporan). */
    private const HEADING_ROW = 6;

    /** Kolom terakhir tabel (A sampai I). */
    private const LAST_COL = 'I';

    private int $no = 0;

    public function __construct(
        private Collection $pengajuan,
        private ?string $periode = null,
        private ?string $tahun = null
    ) {
    }

    public function collection(): Collection
    {
        return $this->pengajuan;
    }

    public function startCell(): string
    {
        return 'A' . self::HEADING_ROW;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Tanggal Pengajuan',
            'Nama Peserta',
            'No. Peserta',
            'Unit',
            'Izin',
            'Tanggal Izin',
            'Durasi',
            'Alasan',
        ];
    }

    public function map($row): array
    {
        $tanggalIzin = optional($row->tanggal_mulai)->format('d/m/Y') ?? '-';
        if ($row->tanggal_selesai && $row->tanggal_mulai && !$row->tanggal_mulai->equalTo($row->tanggal_selesai)) {
            $tanggalIzin .= ' - ' . $row->tanggal_selesai->format('d/m/Y');
        }

        return [
            ++$this->no,
            optional($row->tanggal_pengajuan)->format('d/m/Y'),
            $row->user->nama ?? '-',
            $row->user->nip ?? '-',
            $row->user->unit->nama_unit ?? '-',
            $row->jenisIzin->nama_izin ?? '-',
            $tanggalIzin,
            $row->durasi . ' Hari',
            $row->alasan ?: '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 18,
            'C' => 28,
            'D' => 16,
            'E' => 24,
            'F' => 20,
            'G' => 26,
            'H' => 10,
            'I' => 40,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $last    = self::LAST_COL;
                $head    = self::HEADING_ROW;
                $lastRow = $head + max($this->pengajuan->count(), 1);

                // ---- Judul laporan (baris 1-4) ----
                $judul = [
                    1 => ['REKAP PENGAJUAN IZIN PESERTA MAGANG', 14],
                    2 => ['DINAS KOPERASI UKM PROV JATENG', 12],
                    3 => [$this->periode ?: 'Pengajuan Disetujui', 11],
                    4 => [$this->tahun ?: (string) now()->year, 11],
                ];

                foreach ($judul as $baris => [$teks, $ukuran]) {
                    $sheet->mergeCells("A{$baris}:{$last}{$baris}");
                    $sheet->setCellValue("A{$baris}", $teks);
                    $sheet->getStyle("A{$baris}")->getFont()->setBold(true)->setSize($ukuran);
                    $sheet->getStyle("A{$baris}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }

                // ---- Judul kolom ----
                $headRange = "A{$head}:{$last}{$head}";
                $sheet->getStyle($headRange)->getFont()->setBold(true);
                $sheet->getStyle($headRange)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D9D9D9');
                $sheet->getStyle($headRange)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // ---- Garis tabel ----
                $tableRange = "A{$head}:{$last}{$lastRow}";
                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle($tableRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Kolom pendek rata tengah, alasan boleh turun baris
                foreach (['A', 'B', 'D', 'F', 'G', 'H'] as $kolom) {
                    $dataRange = $kolom . ($head + 1) . ':' . $kolom . $lastRow;
                    $sheet->getStyle($dataRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $sheet->getStyle('I' . ($head + 1) . ":I{$lastRow}")
                    ->getAlignment()->setWrapText(true);

                // ---- Pengaturan kertas A4 ----
                $pageSetup = $sheet->getPageSetup();
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $pageSetup->setFitToPage(true);
                $pageSetup->setFitToWidth(1);
                $pageSetup->setFitToHeight(0);
                $pageSetup->setHorizontalCentered(true);
                $pageSetup->setRowsToRepeatAtTopByStartAndEnd($head, $head);
                $pageSetup->setPrintArea("A1:{$last}{$lastRow}");

                $margins = $sheet->getPageMargins();
                $margins->setTop(0.6)->setBottom(0.6)->setLeft(0.5)->setRight(0.5);
            },
        ];
    }
}