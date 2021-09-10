<?php

namespace App\Traits;

use AfricasTalking\SDK\AfricasTalking;
use App\Services\DebugService;
use Exception;

trait SendsSMS{

    /**
     * Send an SMS message
     * @param array|string $to Recepient(s) - phone numbers in form 0700...
     * @param string $message Message to be sent
     * @return bool|string
     */
    function sendSMS($to, $message){
        if(config('sms.fake')) return true;

        try{
            // change to +254
            $to = substr_replace($to, '+254', 0, 1);

            $carrier = config('sms.carrier');

            if($carrier == 'at'){
                return $this->viaAfricasTalking($to, $message);
            }

            return false;

        }catch(Exception $e){
            // DebugService::catch($e);
            return false;
        }
    }

    /**
     * Send SMS message through Africa's talking
     * @param array|string $to Recepient(s) - phone numbers in form 0700...
     * @param string $message Message to be sent
     * @return bool
     */
    function viaAfricasTalking($to, $message){
        try{
            $at = new AfricasTalking(
                config('sms.at_username'),
                config('sms.at_api_key')
            );

            $sms = $at->sms();

            $result = $sms->send([
                'to' => $to,
                'message' => $message,
            ]);

            return (isset($result['status']) && strtolower($result['status']) == 'success');
        }catch(Exception $e){
            // DebugService::catch($e);
            return false;
        }
    }
}
