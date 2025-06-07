<?php

namespace App\Imports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;

class InventoryImport implements ToModel
{
    public function model(array $row)
    {
        if (!is_numeric($row[3])) {
            return null; // Skip baris jika harga tidak valid
        }
        
        return new Inventory([
            'name' => $row[0],
            'quantity' => $row[1],
            'unit' => $row[2],
            'price' => $row[3],
            'supplier' => $row[4],
            'location' => $row[5],
        ]);
    }
}
