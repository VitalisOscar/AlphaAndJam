<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = false;

    const ITEM_ADVERT = 'advert';
    const ITEM_INVOICE = 'invoice';
    const ITEM_USER = 'user';
    const ITEM_AGENT = 'agent';

    public $fillable = [
        'user_id', 'title', 'content', 'item', 'item_id', 'time'
    ];

    function user(){
        return $this->belongsTo(User::class);
    }
}
