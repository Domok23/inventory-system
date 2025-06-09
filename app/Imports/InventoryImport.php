<?php

namespace App\Imports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;

class InventoryImport implements ToModel
{
    public function model(array $row)
    {
        return new Inventory([
            'name' => $row[0],
            'category_id' => $row[1],
            'quantity' => $row[2],
            'unit' => $row[3],
            'price' => $row[4],
            'currency_id' => $row[5],
            'supplier' => $row[6],
            'location' => $row[7],
            'remark' => $row[8],
        ]);
    }
}