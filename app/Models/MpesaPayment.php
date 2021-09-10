<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaPayment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = "PENDING";
    public const STATUS_SUCCESSFUL = "SUCCESSFUL";
    public const STATUS_FAILED = "FAILED";
    public const STATUS_REJECTED = "REJECTED";

    public $timestamps = false;

    public $fillable = [
        'merchant_request_id', 'checkout_request_id', 'advert_id', 'status'
    ];

    function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
