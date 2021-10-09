<?php

namespace App\Http\Controllers\User\Payments;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Bryceandy\Laravel_Pesapal\Facades\Pesapal;
use Bryceandy\Laravel_Pesapal\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PesapalPaymentController extends Controller
{
    function __invoke($invoice_number){


        // // Create a pending payment
        // $payment = new Payment([
        //     'invoice_id' => $invoice->id,
        //     'method' => 'Pesapal',
        //     'generated' => 'system',
        //     'status' => Payment::STATUS_PENDING
        // ]);

        // if(!$payment->save()){
        //     return back()->withErrors('Unable to initiate payment. Something went wrong');
        // }

        // get iframe


        // $url = 'pesapal/iframe?p=';

        // foreach($details as $key=>$value){
        //     $url .= "&{$key}={$value}";
        // }

        // $iframe = Http::post(config('app.url').'/pesapal/iframe/', $details);

        // return view('payments.pesapal.iframe', compact('iframe'));





        // $validator = validator($request->all(), [
        //     'amount' => 'required|numeric',
        //     'currency' => 'required|in:TZS,KES,UGX,USD',
        //     'description' => 'required|min:5',
        //     'type' => 'required|in:MERCHANT,ORDER',
        //     'reference' => 'required',
        //     'first_name' => 'sometimes|required|min:3',
        //     'last_name' => 'sometimes|required|min:3',
        //     'email' => 'required_without:phone_number|email',
        //     'phone_number' => 'required_without:email|numeric',
        // ])->validate();

        $invoice = Invoice::where('number', $invoice_number)->first();
        if($invoice == null){
            return back()->withErrors(['status' => 'The invoice does not exist']);
        }

        if($invoice->isPaid()){
            return $this->json->error('The invoice has already been paid for');
        }

        if($invoice->isPending()){
            // return $this->json->error("There's already another pending payment for this invoice. Please wait a while and try again");
        }

        $user = auth('web')->user();

        $details = array(
            // 'amount' => number_format($invoice->totals['total'], 2),
            'amount' => number_format(1, 2),
            'description' => 'Invoice Payment',
            'type' => 'MERCHANT',
            'first_name' => $user->name,
            'last_name' => '',
            'email' => $user->email,
            'phone_number' => $user->phone,
            'reference' => $invoice->id,
            'currency' => 'KES'
        );


        Payment::create($details);

        $iframe_src = Pesapal::getIframeSource($details);

        return view('payments.pesapal.iframe', compact('iframe_src'));

    }

    function callback(Request $request){
        // $tracking_id = $request->get('tracking_id');
        // $reference = $request->get('merchant_reference');

        // // reference is payment id
        // $pesapal_payment = PesapalPayment::where('id', $reference)->first();
        // $pesapal_payment->tracking_id = $tracking_id;

        // $pesapal_payment->save();

        // return view('payments.pesapal.received');

        $transaction = Pesapal::getTransactionDetails(
            request('pesapal_merchant_reference'), request('pesapal_transaction_tracking_id')
        );

        // Store the paymentMethod, trackingId and status in the database
        Payment::modify($transaction);

        $status = $transaction['status'];
        // also $status = Pesapal::statusByTrackingIdAndMerchantRef(request('pesapal_merchant_reference'), request('pesapal_transaction_tracking_id'));
        // also $status = Pesapal::statusByMerchantRef(request('pesapal_merchant_reference'));

        return view('payments.pesapal.received', compact('status')); // Display this status to the user. Values are (PENDING, COMPLETED, INVALID, or FAILED)
    }

}
