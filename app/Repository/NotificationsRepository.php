<?php

namespace App\Repository;

use App\Helpers\ResultSet;
use Carbon\Carbon;

class NotificationsRepository{
    function getAll(){
        $notifications = auth()->user()->notifications();

        return new ResultSet($notifications, 5, function($notification){
            $this->modify($notification);
        });
    }

    function recent($n){
        $notifications = auth()->user()->notifications();

        return $notifications->orderBy('time', 'desc')->limit($n)->get()->each(function($notification){
            $this->modify($notification);
        });
    }

    function modify($notification){
        if($notification != null){
            // Date
            $time = Carbon::createFromTimeString($notification->time);
            if($time->isToday()){
                $notification->time = "Today";
            }else if($time->isYesterday()){
                $notification->time = "Yesterday";
            }else{
                $notification->time = substr($time->monthName, 0, 3)." ".$time->day.", ".$time->year;
            }

            $notification->time .= ' '.($time->hour > 12 ? ($time->hour - 12):$time->hour).':'.($time->minute < 10 ? '0':'').$time->minute.' '.(($time->hour > 12) ? 'PM': ($time->hour == 12 ? 'Noon':'AM'));

            return $notification;
        }
    }
}
