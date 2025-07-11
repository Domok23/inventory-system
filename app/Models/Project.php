<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Project extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'qty', 'department', 'start_date', 'deadline', 'finish_date','img', 'created_by'];

    public function materialUsages()
{
    return $this->hasMany(\App\Models\MaterialUsage::class);
}
public function parts()
{
    return $this->hasMany(\App\Models\ProjectPart::class);
}
}
