<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialUsage extends Model
{
    protected $fillable = ['inventory_id', 'project_id', 'used_quantity'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
