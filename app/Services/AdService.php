<?php

namespace App\Services;

use App\Models\Advert;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;


class AdService{
    use ManagesSlots, CreatesNewAds, ManagesExistingAds;

    private $payment;

    function __construct(PaymentService $payment){
        $this->payment = $payment;
    }

    private function getUploadDir($media_type){
        $date = Carbon::now();
        $location = $date->year."/";

        if($date->month<10){
            $location .= "0";
        }

        $location .= $date->month."/";

        if($date->day<10){
            $location .= "0";
        }

        $location .= $date->day."/";

        return Advert::UPLOAD_DIR.'/'.$media_type.'/'.$location;
    }
}
