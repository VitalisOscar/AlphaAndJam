<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $casts = [
        'slots' => 'array',
        'play_data' => 'array'
    ];

    protected $guarded = [];

    function screen(){
        return $this->belongsTo(Screen::class);
    }

    function package(){
        return $this->belongsTo(Package::class);
    }

    function getRangeAttribute(){
        $slots = $this->getAttribute('slots');

        if(!isset($slots['range'])) return null;

        return $slots['range']['from'].' to '.$slots['range']['to'];
    }

    function getDatesAttribute(){
        $dates = [];

        $range = $this->range;

        if($range != null){
            $from = Carbon::createFromDate($range['from']);
            $to = Carbon::createFromDate($range['to']);

            for(;$to->isAfter($from);){
                array_push($dates, $from->format('Y-m-d'));
                $from->addDay();
            }

            array_push($dates, $to->format('Y-m-d'));

            return $dates;
        }else{
            $slots = $this->getAttribute('slots');
            return $slots['dates'];
        }
    }
}
