<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'exchange_rate',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
