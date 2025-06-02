<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventoryExport implements FromView
{
    protected $inventories;

    public function __construct($inventories)
    {
        $this->inventories = $inventories;
    }

    public function view(): View
    {
        return view('inventory.export', [
            'inventories' => $this->inventories
        ]);
    }
}