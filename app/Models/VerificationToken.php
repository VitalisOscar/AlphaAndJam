<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
    use HasFactory;

    public $timestamps = false;

    function user(){
        return $this->belongsTo(User::class);
    }

    function isExpired(){
        $expiry = Carbon::createFromTimeString($this->getAttribute('expires_at'));
        return Carbon::now()->isAfter($expiry);
    }
}
