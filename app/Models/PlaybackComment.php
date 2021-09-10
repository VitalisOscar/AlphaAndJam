<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaybackComment extends Model
{
    use HasFactory;

    const COMMENTS = [
        'played' => 'Played successfully with no errors',
        'played_with_errors' => 'Played but experienced some technical problems',
        'not_played' => 'Not played at all',
    ];

    protected $fillable = [
        'package_id', 'screen_id', 'play_date', 'comment', 'staff_id'
    ];

    function staff(){
        return $this->belongsTo(Staff::class);
    }

    function getTimeAttribute(){
        $time = Carbon::createFromTimeString($this->getAttribute('created_at'));

        $tm = substr($time->monthName, 0, 3).' ';

        if($time->day < 10) $tm .= '0';
        $tm .= $time->day.', '.$time->year.' ';

        if($time->hour < 12) $tm .= $time->hour.':<min> AM';
        else if($time->hour > 12) $tm .= ($time->hour - 12).':<min> PM';
        else if($time->minute > 0) $tm .= '12:<min> PM';
        else $tm .= '12:<min> Noon';

        $mins = $time->minute;

        return str_replace('<min>', ($mins>9 ? $mins:'0'.$mins),$tm);
        return $tm;
    }
}
