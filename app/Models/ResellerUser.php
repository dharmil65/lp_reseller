<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ResellerUser extends Authenticatable
{
    use HasApiTokens;
    
    protected $table = 'reseller_users';
    
    protected $fillable = ['name', 'email', 'password'];
}