<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'staff';

    public $timestamps = false;

    protected $table = "staff";

    public $fillable = [
        'username', 'name', 'password', 'role'
    ];

    function isAdmin(){
        return $this->getAttribute('role') == self::ROLE_ADMIN;
    }
}
