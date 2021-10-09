<?php

namespace App\Models;

use Bryceandy\Laravel_Pesapal\Payment as Laravel_PesapalPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = "PENDING";
    public const STATUS_SUCCESSFUL = "COMPLETED";
    public const STATUS_FAILED = "FAILED";

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

    function pesapal_payments(){
        return $this->hasMany(Laravel_PesapalPayment::class, 'reference');
    }

    function latest_pesapal_payments(){
        return $this->hasOne(Laravel_PesapalPayment::class, 'reference')->latest();
    }

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
