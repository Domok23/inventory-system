<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialRequest extends Model
{
    use SoftDeletes;
    protected $fillable = ['inventory_id', 'project_id', 'qty', 'processed_qty', 'requested_by', 'status', 'remark'];

    protected $casts = [
        'created_at' => 'datetime', // Pastikan created_at di-cast sebagai datetime
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function goodsOuts()
    {
        return $this->hasMany(GoodsOut::class);
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'pending' => 'text-bg-warning',
            'approved' => 'text-bg-primary',
            'delivered' => 'text-bg-success',
            'canceled' => 'text-bg-danger',
            default => '',
        };
    }

    public function getRemainingQtyAttribute()
    {
        return $this->qty - $this->processed_qty;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by', 'username');
    }

    public function getDepartmentNameAttribute()
    {
        return $this->user && $this->user->department ? $this->user->department->name : null;
    }
}
