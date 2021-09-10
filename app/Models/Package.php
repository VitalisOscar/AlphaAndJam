<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'type',
        'clients',
        'loops',
        'plays_from',
        'plays_to'
    ];

    function getCategoryAttribute(){
        $val = $this->getAttribute(('type'));

        if($val == 'peak') return 'Peak';
        if($val == 'off_peak') return 'Off Peak';
        return $val;
    }

    function getSummaryAttribute(){
        $from = $this->getAttribute('plays_from');
        $to = $this->getAttribute('plays_to');

        $f = ($from > 12 ? (($from - 12).':00 PM') : ($from < 12 ? $from.':00 AM' : '12:00 Noon'));
        $t = ($to > 12 ? (($to - 12).':00 PM') : ($to < 12 ? $to.':00 AM' : '12:00 Noon'));

        // e.g from 7:00 AM to 12:00 Noon
        return $f.' to '.$t;
    }

    function getUnpricedScreensAttribute(){
        return Screen::query()->whereDoesntHave('packages', function($q){
            $q->where('package_id', $this->getAttribute('id'));
        })->get();
    }

    function priced_screens(){
        return $this->belongsToMany(Screen::class, 'screen_prices')->withPivot('price');
    }
}
