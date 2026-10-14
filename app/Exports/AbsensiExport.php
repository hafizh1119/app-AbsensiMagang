<?php

namespace App\Exports;

use App\Models\Absensi;
use Carbon\Carbon;
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

class AbsensiExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    WithColumnWidths,
    WithEvents
{
    /** Baris tempat judul kolom (baris 1-4 dipakai untuk judul laporan). */
    private const HEADING_ROW = 6;

    private int $no = 0;

    /**
     * @param Collection  $absensis Data yang sudah terfilter.
     * @param string|null $periode  Label periode, contoh: "Bulan Oktober 2026".
     * @param string|null $tahun    Tahun untuk baris judul ke-4.
     */
    public function __construct(
        private Collection $absensis,
        private ?string $periode = null,
        private ?string $tahun = null
    ) {
    }

    public function collection(): Collection
    {
        return $this->absensis;
    }

    public function startCell(): string
    {
        return 'A' . self::HEADING_ROW;
    }

    public function headings(): array
    {
        return ['No.', 'Tanggal', 'No. Peserta', 'Nama Peserta', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterangan'];
    }

    public function map($absensi): array
    {
        return [
            ++$this->no,
            optional($absensi->tanggal)->format('d/m/Y'),
            $absensi->user->nip ?? '-',
            $absensi->user->nama ?? '-',
            $absensi->jam_masuk ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '-',
            $absensi->jam_pulang ? Carbon::parse($absensi->jam_pulang)->format('H:i') : '-',
            ucwords(str_replace('_', ' ', $absensi->status ?? '-')),
            $absensi->keterangan ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 14,
            'C' => 16,
            'D' => 28,
            'E' => 12,
            'F' => 12,
            'G' => 16,
            'H' => 32,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastRow = self::HEADING_ROW + max($this->absensis->count(), 1);

                // ---- Judul laporan (baris 1-4) ----
                $barisPeriode = $this->periode ?: 'Semua Tanggal';
                $tahun        = $this->tahun ?: (string) now()->year;

                $judul = [
                    1 => ['REKAP ABSENSI PESERTA MAGANG', 14],
                    2 => ['DINAS KOPERASI UKM PROV JATENG', 12],
                    3 => [$barisPeriode, 11],
                    4 => [$tahun, 11],
                ];

                foreach ($judul as $baris => [$teks, $ukuran]) {
                    $sheet->mergeCells("A{$baris}:H{$baris}");
                    $sheet->setCellValue("A{$baris}", $teks);
                    $sheet->getStyle("A{$baris}")->getFont()->setBold(true)->setSize($ukuran);
                    $sheet->getStyle("A{$baris}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }

                // ---- Judul kolom ----
                $headRange = 'A' . self::HEADING_ROW . ':H' . self::HEADING_ROW;
                $sheet->getStyle($headRange)->getFont()->setBold(true);
                $sheet->getStyle($headRange)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D9D9D9');
                $sheet->getStyle($headRange)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // ---- Garis tabel ----
                $tableRange = 'A' . self::HEADING_ROW . ":H{$lastRow}";
                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle($tableRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Kolom pendek rata tengah, keterangan boleh turun baris
                foreach (['A', 'B', 'C', 'E', 'F', 'G'] as $kolom) {
                    $dataRange = $kolom . (self::HEADING_ROW + 1) . ':' . $kolom . $lastRow;
                    $sheet->getStyle($dataRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $sheet->getStyle('H' . (self::HEADING_ROW + 1) . ":H{$lastRow}")
                    ->getAlignment()->setWrapText(true);

                // ---- Pengaturan kertas A4 ----
                $pageSetup = $sheet->getPageSetup();
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $pageSetup->setFitToPage(true);
                $pageSetup->setFitToWidth(1);
                $pageSetup->setFitToHeight(0);
                $pageSetup->setHorizontalCentered(true);
                $pageSetup->setRowsToRepeatAtTopByStartAndEnd(self::HEADING_ROW, self::HEADING_ROW);
                $pageSetup->setPrintArea("A1:H{$lastRow}");

                $margins = $sheet->getPageMargins();
                $margins->setTop(0.6)->setBottom(0.6)->setLeft(0.5)->setRight(0.5);
            },
        ];
    }
}