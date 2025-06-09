<?php

namespace App\Exports;

use App\Models\GoodsOut;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Log;

class ProjectCostingExport implements FromCollection, WithHeadings
{
    protected $materials;
    protected $projectName;

    public function __construct($materials, $projectName)
    {
        $this->materials = $materials;
        $this->projectName = $projectName;
    }


    public function collection()
    {
        return $this->materials->map(function ($item) {
            $pricePerUnit = $item->inventory->price ?? 0; // Harga per unit
            $quantity = $item->quantity ?? 0; // Jumlah material
            $exchangeRate = $item->inventory->currency->exchange_rate ?? 1; // Kurs ke IDR (default 1 jika tidak ada)

            $totalCost = $pricePerUnit * $quantity; // Total biaya sebelum konversi
            $totalCostInIDR = $totalCost * $exchangeRate; // Konversi ke IDR

            return [
                'Material' => $item->inventory->name ?? 'N/A',
                'Quantity' => $quantity,
                'Unit Price' => $pricePerUnit,
                'Total Price' => $totalCost,
                'Total Cost (IDR)' => $totalCostInIDR,
            ];
        });
    }
    public function headings(): array
    {
        return ['Material', 'Quantity', 'Unit Price', 'Total Price', 'Total Cost (IDR)'];
    }
}
