<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = "pending";
    public const STATUS_SUCCESSFUL = "successful";
    public const STATUS_FAILED = "failed";

    const METHODS = [
        'mpesa' => 'M-Pesa',
        'cash' => 'Cash',
        'cheque' => 'Cheque',
        'pesapal' => 'Pesapal',
    ];

    public $timestamps = false;

    // protected $with = ['invoice'];

    public $fillable = [
        'invoice_id', 'status', 'method', 'code', 'generated', 'data'
    ];

    function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    function getAmountAttribute(){
        return $this->invoice->totals['amount'];
    }

    function failed(){
        return $this->status == self::STATUS_FAILED;
    }

    function succeeded(){
        return $this->status == self::STATUS_SUCCESSFUL;
    }

    function isPending(){
        return $this->status == self::STATUS_PENDING;
    }
}
