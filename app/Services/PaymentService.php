<?php

namespace App\Services;

use App\Models\Advert;
use App\Models\MpesaPayment;
use App\Models\Slot;

class PaymentService{
    use GeneratesInvoices;

    /**
     * @param Slot $slot
     * @return int
     */
    function getSlotPrice($slot){
        $screen = $slot->screen;
        $package = $screen->packages()->where('package_id', $slot->package_id)->first();

        return $package->pivot->price;
    }

    function getToken($invoice){
        return hash_hmac('SHA256', $invoice->number, \config('app.key'));
    }

    function verifyToken($invoice, $token){
        $verify_token = hash_hmac('SHA256', $invoice->number, \config('app.key'));
        return \hash_equals($verify_token, $token);
    }

    /**
     * @param $id Advert id
     * @return null|Advert|string
     */
    function getAd($id){
        $ad = Advert::where('user_id', auth()->id())
            ->where('id', $id)
            ->with('slots')
            ->first();

        if($ad == null) return null;

        // Check status
        if($ad->status != Advert::STATUS_PAYMENT_FAILED && $ad->status != Advert::STATUS_PENDING_PAYMENT) return null;

        // Check for previous pending payment attempts
        $payments = $ad->payments()->whereIn('status', [
            MpesaPayment::STATUS_PENDING,
            MpesaPayment::STATUS_SUCCESSFUL,
        ])->count();

        if($payments > 0){
            return 'Ad has already been paid for or has a pending payment that has not been completed or cancelled';
        }

        return $ad;
    }

    function getAdvertPrice($ad){
        $price = 0;

        foreach($ad->slots as $slot) $price += $slot->price;

        return $price;
    }

    /**
     * Save an initiated payment
     * @param Advert $ad
     * @param $response M-Pesa response
     */
    function savePayment($ad, $response){
        $payment = MpesaPayment::create([
            'merchant_request_id' => $response->MerchantRequestID,
            'checkout_request_id' => $response->CheckoutRequestID,
            'advert_id' => $ad->id,
            'status' => MpesaPayment::STATUS_PENDING
        ]);

        $payment->save();
    }
}
