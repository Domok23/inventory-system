<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsIn extends Model
{
    use HasFactory;

    protected $table = 'goods_in'; // Pastikan nama tabel sesuai dengan database

    protected $fillable = [
        'goods_out_id',
        'inventory_id',
        'project_id',
        'quantity',
        'returned_by',
        'returned_at',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function goodsOut()
    {
        return $this->belongsTo(GoodsOut::class);
    }
}