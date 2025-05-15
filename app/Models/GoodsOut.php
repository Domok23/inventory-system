<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsOut extends Model
{
    use HasFactory;

    protected $table = 'goods_out'; // Pastikan nama tabel sesuai dengan database

    protected $fillable = [
        'material_request_id',
        'inventory_id',
        'project_id',
        'requested_by',
        'department',
        'quantity',
    ];

    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by', 'username');
    }
}
