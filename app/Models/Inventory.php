<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'quantity',
        'unit',
        'price',
        'currency_id',
        'supplier',
        'location',
        'remark',
        'img',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function goodsOuts()
    {
        return $this->hasMany(GoodsOut::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
