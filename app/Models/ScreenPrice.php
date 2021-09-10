<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenPrice extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = ['screen_id', 'package_id', 'price'];
}
