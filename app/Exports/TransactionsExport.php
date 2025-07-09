<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithStyles, ShouldAutoSize
{
    protected $transactions;
    protected $totalIncome;
    protected $totalExpense;
    protected $balance;
    protected $forPdf;

    public function __construct($forPdf = false)
    {
        $this->forPdf = $forPdf;

        // Load data dan hitung summary
        $this->transactions = Transaction::with(['category:id,name'])
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        $this->totalIncome = $this->transactions->where('tipe', 'pemasukan')->sum('nominal');
        $this->totalExpense = $this->transactions->where('tipe', 'pengeluaran')->sum('nominal');
        $this->balance = $this->totalIncome - $this->totalExpense;
    }

    /**
     * Return the collection of transactions.
     */
    public function collection()
    {
        return $this->transactions;
    }

    /**
     * Define the headings for the Excel/PDF file.
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Deskripsi',
            'Tipe',
            'Nominal',
            'Kategori',
        ];
    }

    /**
     * Map the transaction data to the Excel/PDF rows.
     */
    public function map($transaction): array
    {
        static $no = 1;

        // Handle null category dengan lebih aman
        $categoryName = 'Tanpa Kategori';

        if ($transaction->kategori_id) {
            // Try to get category relation
            $category = $transaction->category;
            if ($category && isset($category->name)) {
                $categoryName = $category->name;
            } else {
                // Fallback: load category manually if relation failed
                try {
                    $categoryModel = \App\Models\Category::find($transaction->kategori_id);
                    $categoryName = $categoryModel ? $categoryModel->name : 'Kategori Tidak Ditemukan';
                } catch (\Exception $e) {
                    $categoryName = 'Kategori Tidak Ditemukan';
                }
            }
        }

        return [
            $no++,
            $transaction->formatted_tgl_transaksi ?? $transaction->tgl_transaksi,
            $transaction->deskripsi ?? '',
            ucfirst($transaction->tipe ?? ''),
            $transaction->formatted_nominal ?? number_format($transaction->nominal ?? 0, 0, ',', '.'),
            $categoryName,
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Register events to customize the export (e.g., set PDF to landscape).
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set orientation to landscape for PDF
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

                if ($this->forPdf) {
                    // PDF specific settings
                    $sheet->getPageSetup()->setFitToPage(true);
                    $sheet->getPageSetup()->setFitToWidth(1);
                    $sheet->getPageSetup()->setFitToHeight(0);

                    // Smaller margins for PDF
                    $sheet->getPageMargins()
                        ->setTop(0.3)
                        ->setRight(0.15)
                        ->setBottom(0.3)
                        ->setLeft(0.15);
                } else {
                    // Excel specific settings
                    $sheet->getPageMargins()
                        ->setTop(0.5)
                        ->setRight(0.3)
                        ->setBottom(0.5)
                        ->setLeft(0.3);
                }

                $lastRow = $sheet->getHighestRow();

                // Insert rows for title and summary
                if ($this->forPdf) {
                    $sheet->insertNewRowBefore(1, 9); // 9 rows for PDF
                } else {
                    $sheet->insertNewRowBefore(1, 8); // 8 rows for Excel
                }

                // HEADER SECTION
                $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI KEUANGAN');
                $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i:s'));

                // SUMMARY SECTION
                if ($this->forPdf) {
                    // Compact summary for PDF
                    $sheet->setCellValue('A4', 'RINGKASAN:');
                    $sheet->setCellValue('A5', 'Pemasukan: Rp ' . number_format($this->totalIncome, 0, ',', '.'));
                    $sheet->setCellValue('A6', 'Pengeluaran: Rp ' . number_format($this->totalExpense, 0, ',', '.'));
                    $sheet->setCellValue('A7', 'Saldo: Rp ' . number_format($this->balance, 0, ',', '.'));
                    $headerRow = 9;
                } else {
                    // Detailed summary for Excel
                    $sheet->setCellValue('A4', 'RINGKASAN KEUANGAN');
                    $sheet->setCellValue('A5', 'Total Pemasukan:');
                    $sheet->setCellValue('B5', 'Rp ' . number_format($this->totalIncome, 0, ',', '.'));
                    $sheet->setCellValue('A6', 'Total Pengeluaran:');
                    $sheet->setCellValue('B6', 'Rp ' . number_format($this->totalExpense, 0, ',', '.'));
                    $sheet->setCellValue('A7', 'Saldo:');
                    $sheet->setCellValue('B7', 'Rp ' . number_format($this->balance, 0, ',', '.'));
                    $headerRow = 9;
                }

                // Style title
                $titleSize = $this->forPdf ? 16 : 18;
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => $titleSize,
                        'color' => ['rgb' => '1F2937'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Style date
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'size' => $this->forPdf ? 9 : 10,
                        'italic' => true,
                        'color' => ['rgb' => '6B7280'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                if ($this->forPdf) {
                    // Style compact summary for PDF
                    $sheet->getStyle('A4')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1F2937']],
                    ]);
                    $sheet->getStyle('A5')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '059669']],
                    ]);
                    $sheet->getStyle('A6')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'DC2626']],
                    ]);
                    $balanceColor = $this->balance >= 0 ? '059669' : 'DC2626';
                    $sheet->getStyle('A7')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => $balanceColor]],
                    ]);
                } else {
                    // Style detailed summary for Excel
                    $sheet->getStyle('A4')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1F2937']],
                    ]);
                    $sheet->getStyle('A5:A7')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12],
                    ]);
                    $sheet->getStyle('B5')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '059669']],
                    ]);
                    $sheet->getStyle('B6')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'DC2626']],
                    ]);
                    $balanceColor = $this->balance >= 0 ? '059669' : 'DC2626';
                    $sheet->getStyle('B7')->applyFromArray([
                        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => $balanceColor]],
                    ]);
                }

                // Merge title cells
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');

                // Style table headers
                $headerFontSize = $this->forPdf ? 10 : 11;
                $sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => $headerFontSize,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '374151'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F2937'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Style data rows
                $dataStartRow = $headerRow + 1;
                $insertedRows = $this->forPdf ? 9 : 8;
                $dataEndRow = $lastRow + $insertedRows;

                // Data font size
                $dataFontSize = $this->forPdf ? 9 : 10;

                // Alternate row colors
                for ($row = $dataStartRow; $row <= $dataEndRow; $row++) {
                    $fillColor = ($row % 2 == 0) ? 'F9FAFB' : 'FFFFFF';
                    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                        'font' => ['size' => $dataFontSize],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $fillColor],
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => 'D1D5DB'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'wrapText' => $this->forPdf,
                        ],
                    ]);
                }

                // Set column widths
                if ($this->forPdf) {
                    // Optimized widths for PDF landscape A4
                    $sheet->getColumnDimension('A')->setWidth(2);   // No
                    $sheet->getColumnDimension('B')->setWidth(11);  // Tanggal
                    $sheet->getColumnDimension('C')->setWidth(28);  // Deskripsi
                    $sheet->getColumnDimension('D')->setWidth(11);  // Tipe
                    $sheet->getColumnDimension('E')->setWidth(16);  // Nominal
                    $sheet->getColumnDimension('F')->setWidth(13);  // Kategori
                } else {
                    // Comfortable widths for Excel
                    $sheet->getColumnDimension('A')->setWidth(4);   // No
                    $sheet->getColumnDimension('B')->setWidth(15);  // Tanggal
                    $sheet->getColumnDimension('C')->setWidth(35);  // Deskripsi
                    $sheet->getColumnDimension('D')->setWidth(15);  // Tipe
                    $sheet->getColumnDimension('E')->setWidth(20);  // Nominal
                    $sheet->getColumnDimension('F')->setWidth(20);  // Kategori
                }

                // Set row heights
                $titleHeight = $this->forPdf ? 22 : 30;
                $headerHeight = $this->forPdf ? 18 : 25;
                $defaultHeight = $this->forPdf ? 14 : 15;

                $sheet->getRowDimension(1)->setRowHeight($titleHeight);
                $sheet->getRowDimension($headerRow)->setRowHeight($headerHeight);
                $sheet->getDefaultRowDimension()->setRowHeight($defaultHeight);

                // Text alignment
                $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("B{$dataStartRow}:B{$dataEndRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("D{$dataStartRow}:D{$dataEndRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E{$dataStartRow}:E{$dataEndRow}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}
