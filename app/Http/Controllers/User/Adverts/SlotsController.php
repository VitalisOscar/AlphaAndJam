<?php

namespace App\Http\Controllers\User\Adverts;

use App\Http\Controllers\Controller;
use App\Models\ScreenPrice;
use App\Services\AdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SlotsController extends Controller
{
    function checkAvailability(Request $request, AdService $adService){
        $valid = $adService->validateDates();
        if(!is_bool($valid)){
            return $this->json->error($valid);
        }

        $validator = Validator::make($request->post(), [
            'package_id' => ['required'],
            'screen_id' => ['required'],
        ]);

        if($validator->fails()) return $this->json->errors($validator->errors()->all());

        $dates = $adService->getDates();

        if(count($dates) == 0) return $this->json->error('Please select at least one date');

        $result = $adService->checkAvailability([
            'package_id' => $request->get('package_id'),
            'screen_id' => $request->get('screen_id'),
            'play_date' => $dates,
        ]);

        if(is_array($result)){
            return $this->json->data($result);
        }

        return $this->json->error('Some data is invalid. Please use the provided form to submit booking data');
    }
}
