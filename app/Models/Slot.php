<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    public $timestamps = false;

    public $fillable = [
        'id',
        'advert_id',
        'screen_id',
        'play_date',
        'airing_time_id',
        'slot_duration_id',
        'price',
    ];

    public $with = ['screen', 'package'];

    protected $casts = [
        'status' => 'array',
    ];

    function screen(){
        return $this->belongsTo(Screen::class);
    }

    function advert(){
        return $this->belongsTo(Advert::class);
    }

    function package(){
        return $this->belongsTo(Package::class);
    }

    function getSlotAttribute(){
        $date = Carbon::createFromFormat('Y-m-d', $this->getAttribute('play_date'));

        $package = $this->getAttribute('package');

        $dt = $date->year.' '.substr($date->monthName, 0, 3).' '.($date->day < 10 ? '0' : '').$date->day;

        return $dt.' - '.$package->name.' ('.$package->summary.')';
    }

    function getDateAttribute(){
        $date = Carbon::createFromFormat('Y-m-d', $this->getAttribute('play_date'));

        $dt = $date->year.' '.substr($date->monthName, 0, 3).' '.($date->day < 10 ? '0' : '').$date->day;

        return $dt;
    }
}
