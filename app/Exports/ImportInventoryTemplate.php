<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportInventoryTemplate implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        $headings = ['name', 'quantity', 'unit', 'currency', 'price', 'supplier', 'location'];
        return array_map('ucwords', $headings);
    }

    public function array(): array
    {
        // Kosongkan data, hanya header yang diperlukan
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Gaya untuk header (baris pertama)
            1 => [
                'font' => [
                    'bold' => true, // Cetak tebal
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Lebar kolom 'name'
            'B' => 15, // Lebar kolom 'quantity'
            'C' => 15, // Lebar kolom 'unit'
            'D' => 15, // Lebar kolom 'currency'
            'E' => 15, // Lebar kolom 'price'
            'F' => 20, // Lebar kolom 'supplier'
            'G' => 25, // Lebar kolom 'location'
        ];
    }
}
