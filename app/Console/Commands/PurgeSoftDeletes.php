<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Inventory;
use App\Models\GoodsIn;
use App\Models\GoodsOut;
use App\Models\Project;
use App\Models\User;
use App\Models\MaterialUsage;
use App\Models\MaterialRequest;
use App\Models\Category;
use App\Models\Currency;

class PurgeSoftDeletes extends Command
{
    protected $signature = 'purge:softdeletes';
    protected $description = 'Permanently delete soft deleted records older than 30 days';

    public function handle()
    {
        $models = [
            Inventory::class,
            GoodsIn::class,
            GoodsOut::class,
            Project::class,
            User::class,
            MaterialUsage::class,
            MaterialRequest::class,
            Category::class,
            Currency::class,
        ];

        $date = Carbon::now()->subDays(30);

        foreach ($models as $model) {
            $count = $model::onlyTrashed()->where('deleted_at', '<', $date)->forceDelete();
            $this->info("Purged $count records from {$model}");
        }

        $this->info('Soft deleted records older than 30 days have been purged.');
    }
}
