<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public $timestamps = false;

    const STATUS_PAID = 'paid';
    const STATUS_PENDING = 'pending';
    const STATUS_UNPAID = 'unpaid';
    const STATUS_OVERDUE = 'overdue';

    const PAYMENT_METHODS = [
        'mpesa' => 'M-Pesa',
        'cash' => 'Cash',
        'cheque' => 'Cheque'
    ];

    const FETCH_LIMIT = 15;

    protected $casts = [
        'totals' => 'array',
    ];

    function advert()
    {
        return $this->belongsTo(Advert::class);
    }

    function payment()
    {
        return $this->hasOne(Payment::class, 'invoice_id', 'id')->orderBy('time', 'desc');
    }

    function scopePostPay($q){
        $q->whereHas('advert', function($q){
            $q->whereHas('user', function($q1){
                $q1->where('payment->post_pay', true);
            });
        });
    }

    function scopePrePay($q){
        $q->whereDoesntHave('advert', function($q){
            $q->whereHas('user', function($q1){
                $q1->where('payment->post_pay', true);
            });
        });
    }

    function scopeOverDue($q){
        $q->unpaid()->whereRaw("date(due) <= '".Carbon::today()->format('Y-m-d')."'");
    }

    function scopePending($q){
        $q->unpaid()->whereRaw("date(due) > '".Carbon::today()->format('Y-m-d')."'");
    }

    function scopeUnpaid($q){
        $q->whereDoesntHave('payment', function($q){
            $q->whereRaw("status = '".Payment::STATUS_SUCCESSFUL."'");
        });
    }

    function scopePaid($q){
        $q->whereHas('payment', function($q){
            $q->whereRaw("status = '".Payment::STATUS_SUCCESSFUL."'");
        });
    }

    function getUserAttribute()
    {
        return $this->advert->user;
    }

    function getStatusAttribute(){
        $payment = $this->getAttribute('payment');

        if($payment == null) return self::STATUS_UNPAID;
        if($payment->status == Payment::STATUS_SUCCESSFUL) return self::STATUS_PAID;
        if($payment->status == Payment::STATUS_PENDING) return self::STATUS_PENDING;
        return self::STATUS_UNPAID;
    }

    function isPaid()
    {
        // return false;
        return $this->getAttribute('status') == self::STATUS_PAID;
    }

    function isPending()
    {
        // return false;
        return $this->getAttribute('status') == self::STATUS_PENDING;
    }

    function isUnpaid()
    {
        return !$this->isPaid();
    }

    function isOverDue()
    {
        $due = Carbon::createFromTimeString($this->getAttribute('due'));
        $now = Carbon::now();

        return $now->isAfter($due);
    }

    function getDueDateAttribute()
    {
        $time = Carbon::createFromTimeString($this->getAttribute('due'));

        $tm = substr($time->monthName, 0, 3) . ' ';

        if ($time->day < 10) $tm .= '0';
        $tm .= $time->day . ', ' . $time->year . ' ';

        // if($time->hour < 12) $tm .= $time->hour.':<min> AM';
        // else if($time->hour > 12) $tm .= ($time->hour - 12).':<min> PM';
        // else if($time->minute > 0) $tm .= '12:<min> PM';
        // else $tm .= '12:<min> Noon';

        // $mins = $time->minute;

        // return str_replace('<min>', ($mins>9 ? $mins:'0'.$mins),$tm);
        return $tm;
    }

    static function exportHeaders()
    {
        return [
            'Invoice No', 'Client Name', 'Time Generated', 'Amount', 'Date Due', 'Status'
        ];
    }

    function getExportDataAttribute()
    {
        $row = [
            $this->number,
            $this->user->name,
            $this->created_at,
            $this->totals['total'],
            $this->due_date,
            $this->status
        ];

        return $row;
    }
}
