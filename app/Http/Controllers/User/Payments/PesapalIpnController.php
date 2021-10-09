<?php
namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Bryceandy\Laravel_Pesapal\Facades\Pesapal;
use Bryceandy\Laravel_Pesapal\Payment;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PesapalIpnController extends Controller
{
    public function index()
    {
        $transaction = Pesapal::getTransactionDetails(
            request('pesapal_merchant_reference'), request('pesapal_transaction_tracking_id')
        );

        // Store the paymentMethod, trackingId and status in the database
        Payment::modify($transaction);

        // If there was a status change and the status is not 'PENDING'
        if(request('pesapal_notification_type') == "CHANGE" && request('pesapal_transaction_tracking_id') != ''){

            //Here you can do multiple things to notify your user that the changed status of their payment
            // 1. Send an email or SMS (if your user doesnt have an email)to your user
            $payment = Payment::whereReference(request('pesapal_merchant_reference'))->first();
            // Mail::to($payment->email)->send(new PaymentProcessed(request('pesapal_transaction_tracking_id'), $transaction['status']));
            // PaymentProcessed is an example of a mailable email, it does not come with the package

            try{
                Storage::put("pesapal", "IPN");
            }catch(Exception $e){

            }

            // 2. You may also create a Laravel Event & Listener to process a Notification to the user
            // 3. You can also create a Laravel Notification or dispatch a Laravel Job. Possibilities are endless!

            // Finally output a response to Pesapal
            $response = 'pesapal_notification_type=' . request('pesapal_notification_type').
                    '&pesapal_transaction_tracking_id=' . request('pesapal_transaction_tracking_id').
                    '&pesapal_merchant_reference=' . request('pesapal_merchant_reference');

            ob_start();
            echo $response;
            ob_flush();
            exit; // This is mandatory. If you dont exit, Pesapal will not get your response.
        }
    }
}
