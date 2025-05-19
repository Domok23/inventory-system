<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsIn extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'goods_in'; // Pastikan nama tabel sesuai dengan database

    protected $fillable = [
        'goods_out_id',
        'inventory_id',
        'project_id',
        'quantity',
        'returned_by',
        'returned_at',
        'remark',
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
