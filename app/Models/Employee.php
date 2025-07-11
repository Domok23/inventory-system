<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Timing;

class Employee extends Model
{
    protected $fillable = ['name', 'position'];

    public function timings()
    {
        return $this->hasMany(Timing::class);
    }
}