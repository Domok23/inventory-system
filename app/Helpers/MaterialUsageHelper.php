<?php
// app/Helpers/MaterialUsageHelper.php
namespace App\Helpers;

use App\Models\MaterialUsage;
use App\Models\GoodsOut;
use App\Models\GoodsIn;

class MaterialUsageHelper
{
    public static function sync($inventory_id, $project_id)
    {
        $goodsOutTotal = GoodsOut::where('inventory_id', $inventory_id)
            ->where('project_id', $project_id)
            ->sum('quantity');

        $goodsInTotal = GoodsIn::where('inventory_id', $inventory_id)
            ->where('project_id', $project_id)
            ->sum('quantity');

        $used = $goodsOutTotal - $goodsInTotal;

        MaterialUsage::updateOrCreate(
            [
                'inventory_id' => $inventory_id,
                'project_id' => $project_id,
            ],
            [
                'used_quantity' => $used,
            ]
        );
    }
}
