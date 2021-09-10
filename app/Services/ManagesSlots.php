<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Screen;
use App\Models\ScreenPrice;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait ManagesSlots{

    /**
     * Get earliest booking date for a slot
     * @return string
     */
    function getEarliestSlotBookingDate(){
        return Carbon::today()->addDays(config('business.min_advance_booking_days'))->format('Y-m-d');
    }

    function validateDates(){

        $adService = resolve(AdService::class);

        $dates = $this->getDates();

        foreach($dates as $date){
            if(!Carbon::hasFormat($date, 'Y-m-d')){
                return $date.' is in an invalid format. Please use the provided form to submit the booking data';
            }

            $min_date = $adService->getEarliestSlotBookingDate();
            if($date < $min_date){
                return 'The earliest date you can book slots is '.$min_date;
            }
        }

        return true;
    }

    function getDates(){
        $request = request();
        $dates = [];

        if($request->filled('play_date_multi')){
            $dates = $request->get('play_date_multi');
            $dates = explode(', ', $dates);
        }else{
            if($request->filled('play_date_from') && $request->filled('play_date_to')){
                $from = Carbon::createFromDate($request->get('play_date_from'));
                $to = Carbon::createFromDate($request->get('play_date_to'));

                for(;$to->isAfter($from);){
                    array_push($dates, $from->format('Y-m-d'));
                    $from->addDay();
                }

                array_push($dates, $to->format('Y-m-d'));
            }else if($request->filled('play_date_from')){
                $from = Carbon::createFromDate($request->get('play_date_from'));
                array_push($dates, $from->format('Y-m-d'));
            }else if($request->filled('play_date_to')){
                $to = Carbon::createFromDate($request->get('play_date_to'));
                array_push($dates, $to->format('Y-m-d'));
            }
        }

        return $dates;
    }

    /**
     * Get slots from request data
     * @param array $data Request data
     * @return Slot[]
     */
    function getSlotsFromRequest($data, $advert_id = null){
        $slots = [];

        foreach($data['slots'] as $s){
            // if(isset($s['id'])){
            //     $slot = Slot::where('advert_id', $advert_id)->where('id', $s['id'])->first();
            //     if($slot == null) continue;
            // }else{
            //     $slot = new Slot();
            // }

            // array
            $play_dates = $s['play_date'];

            foreach($play_dates as $date){
                $slot = new Slot();

                $slot->screen_id = $s['screen_id'];
                $slot->package_id = $s['package'];

                $slot->play_date = $date;

                if($slot->price == null){
                    $slot->price = $this->payment->getSlotPrice($slot);
                }

                array_push($slots, $slot);
            }
        }

        return $slots;
    }

    /**
     * Checks for slots booked on the same screen, same day and hour and makes them one
     * @param Slot[] $slots Slots to be optimized
     */
    function optimizeSlots($slots){
    // TODO
        return $slots;
    }

    /**
     * Check if a slot can be booked
     * @param Slot $slot
     */
    function slotIsAvailable($slot){
        $package = Package::where('id', $slot->package_id)->first();

        $booked_slots = Slot::where([
            'play_date' => $slot->play_date,
            'screen_id' => $slot->screen_id,
            'package_id' => $slot->package_id
        ])->count();

        // full
        if(($booked_slots + 1) > $package->clients){
            return false;
        }else{
            return true;
        }
    }

    function checkAvailability($data){
        $result = [
            'available' => [],
            'unavailable' => [],
            'price' => 0
        ];

        // Screen
        $screen = Screen::where('id', $data['screen_id'])->first();
        $package = Package::where('id', $data['package_id'])->first();
        $price = ScreenPrice::where(['package_id' => $package->id, 'screen_id'=>$screen->id])->first();

        if($package == null || $screen == null || $price == null) return false;

        // Get max clients
        $clients = $package->clients;

        // Check availability for each date
        foreach($data['play_date'] as $date){
            $booked_slots = Slot::where([
                'screen_id' => $screen->id,
                'package_id' => $package->id,
                'play_date' => $date
            ])->count();

            // full
            if(($booked_slots + 1) > $clients){
                array_push($result['unavailable'], $date);
            }else{
                array_push($result['available'], $date);
            }
        }

        $result['loops'] = $package->loops;
        $result['price'] = count($result['available']) * $price->price;
        $result['display_price'] = 'KSh '.number_format(count($result['available']) * $price->price);

        return $result;
    }

    /**
     * Get the earliest airing date for an advert
     * @param Slot[] $slots
     * @return string
     */
    function getEarliestSlotPlayingTime($slots){

        if(count($slots) == 0) return Carbon::today()->format('Y-m-d');

        $earliest = $slots[0];

        if(count($slots) > 1){
            foreach($slots as $slot){
                if($slot->play_date < $earliest->play_date) $earliest = $slot;
            }
        }

        $date = Carbon::createFromDate($earliest->play_date);
        // TODO date
        $date->setHour($earliest->package->from - 1);
        return $date->toDateTimeString();
    }
}
