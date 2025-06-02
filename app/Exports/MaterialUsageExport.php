<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MaterialUsageExport implements FromView
{
    protected $usages;

    public function __construct($usages)
    {
        $this->usages = $usages;
    }

    public function view(): View
    {
        return view('material_usage.export', [
            'usages' => $this->usages
        ]);
    }
}