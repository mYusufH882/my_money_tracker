<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * Return the collection of transactions.
     */
    public function collection()
    {
        return Transaction::with('category')->get();
    }

    /**
     * Define the headings for the Excel/PDF file.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Description',
            'Type',
            'Amount',
            'Category',
            'Formatted Amount',
            'Formatted Date',
        ];
    }

    /**
     * Map the transaction data to the Excel/PDF rows.
     */
    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->tgl_transaksi->toDateString(),
            $transaction->deskripsi,
            $transaction->tipe,
            $transaction->nominal,
            $transaction->category ? $transaction->category->name : 'N/A',
            $transaction->formatted_nominal,
            $transaction->formatted_tgl_transaksi,
        ];
    }

    /**
     * Register events to customize the export (e.g., set PDF to landscape).
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
