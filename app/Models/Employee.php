<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Timing;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'position', 'department', 'email', 'phone', 'hire_date', 'salary', 'status', 'notes'];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function timings()
    {
        return $this->hasMany(Timing::class);
    }

    // Accessor untuk format salary
    public function getFormattedSalaryAttribute()
    {
        return $this->salary ? 'Rp ' . number_format($this->salary, 0, ',', '.') : '-';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'active' => 'success',
            'inactive' => 'warning',
            'terminated' => 'danger',
        ];

        return [
            'color' => $colors[$this->status] ?? 'secondary',
            'text' => ucfirst($this->status),
        ];
    }
}
