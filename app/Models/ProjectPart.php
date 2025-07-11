<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPart extends Model
{
    protected $fillable = ['project_id', 'part_name'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}