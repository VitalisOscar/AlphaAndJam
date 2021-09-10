<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesapalPayment extends Model
{
    public $fillable = [
        'payment_id', 'status', 'tracking_id'
    ];

    public $timestamps = false;
}
