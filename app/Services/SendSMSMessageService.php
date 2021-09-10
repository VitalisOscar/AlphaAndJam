<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use App\Traits\SendsSMS;
use Illuminate\Support\Facades\Storage;

class SendSMSMessageService{

    use SendsSMS;

    function send($message, $to){
        return $this->sendSMS($to, $message);
    }
}
