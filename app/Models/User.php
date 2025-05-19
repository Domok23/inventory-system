<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes;
    use HasFactory, Notifiable;

    protected $fillable = ['username', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    public function isRole($role)
    {
        return $this->role === $role;
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
}
