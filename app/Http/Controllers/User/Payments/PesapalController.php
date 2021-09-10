<?php

namespace App\Http\Controllers\User\Payments;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PesapalPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Knox\Pesapal\Facades\Pesapal;

class PesapalController extends Controller
{
    function make($invoice_number){
        $invoice = Invoice::where('number', $invoice_number)->first();
        if($invoice == null){
            return back()->withErrors(['status' => 'The invoice does not exist']);
        }

        if($invoice->isPaid()){
            return $this->json->error('The invoice has already been paid for');
        }

        if($invoice->isPending()){
            return $this->json->error("There's already another pending payment for this invoice. Please wait a while and try again");
        }

        $user = auth('web')->user();

        // Create a pending payment
        $payment = new Payment([
            'invoice_id' => $invoice->id,
            'method' => 'Pesapal',
            'generated' => 'system',
            'status' => Payment::STATUS_PENDING
        ]);

        DB::beginTransaction();
        if(!$payment->save()){
            return back()->withErrors('Unable to initiate payment. Something went wrong');
        }

        $pesapal_payment = new PesapalPayment([
            'payment_id' => $payment->id,
            'status' => 'pending'
        ]);

        if(!$pesapal_payment->save()){
            DB::rollback();
            return back()->withErrors('Unable to initiate payment. Something went wrong');
        }

        DB::commit();

        // get iframe
        $details = array(
            'amount' => number_format($invoice->totals['total'], 2),
            // 'amount' => number_format(200, 2),
            'description' => 'Invoice Payment',
            'type' => 'MERCHANT',
            'first_name' => $user->name,
            'last_name' => '',
            'email' => $user->email,
            'phonenumber' => $user->phone,
            'reference' => $pesapal_payment->id,
            'currency' => 'KES'
        );

        $iframe = Pesapal::makePayment($details);

        return view('payments.pesapal.iframe', compact('iframe'));
    }

    function received(Request $request){
        $tracking_id = $request->input('tracking_id');
        $reference = $request->input('merchant_reference');

        // reference is payment id
        $pesapal_payment = PesapalPayment::where('payment_id', $reference)->first();
        $pesapal_payment->tracking_id = $tracking_id;

        $pesapal_payment->save();

        return view('payments.pesapal.received');
    }

    function ipn(Request $request){
        $trackingid = $request->input('pesapal_transaction_tracking_id');
        $merchant_reference = $request->input('pesapal_merchant_reference');

        // reference is payment id
        $pesapal_payment = PesapalPayment::where('payment_id', $merchant_reference)->first();

        // $pesapal_notification_type= $request->input('pesapal_notification_type');

        $status = Pesapal::getMerchantStatus($merchant_reference);

        $payment = Payment::where('id',$$merchant_reference)->first();
        $payment->status = $status;
        $payment->save();

        return "success";
    }

}
