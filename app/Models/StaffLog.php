<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLog extends Model
{
    use HasFactory;

    protected $table = 'staff_logs';

    const ITEM_ADVERT = 'advert';
    const ITEM_CATEGORY = 'category';
    const ITEM_USER = 'user';
    const ITEM_AGENT = 'agent';
    const ITEM_STAFF = 'staff';
    const ITEM_INVOICE = 'invoice';
    const ITEM_PACKAGE = 'package';
    const ITEM_SCREEN = 'screen';

    const CATEGORIES = [
        'advert' => 'Adverts',
        'category' => 'Categories',
        'user' => 'Clients',
        'staff' => 'Staff ACcounts',
        'invoice' => 'Invoices',
        'package' => 'Packages',
        'screen' => 'Screens'
    ];

    public $timestamps = false;

    public $fillable = [
        'staff_id',
        'activity',
        'time',
        'item',
        'item_id'
    ];

    function account(){
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
