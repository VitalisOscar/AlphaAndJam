<?php

namespace App\Http\Controllers\User\Payments;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use App\Models\Invoice;
use App\Models\MpesaPayment;
use App\Models\Payment;
use App\Repository\InvoiceRepository;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SmoDav\Mpesa\Laravel\Facades\STK;

class MpesaPaymentController extends Controller
{
    function initiate(Request $request, InvoiceRepository $invoiceRepository, PaymentService $paymentService, $invoice_number){
        $validator = Validator::make($request->post(), [
            'phone' => ['required', 'regex:/0([0-9]){9}/'],
        ], [
            'phone.required' => 'Enter your M-Pesa phone number',
            'phone.regex' => 'Enter a phone number in the format 0700123456'
        ]);

        if ($validator->fails()) return $this->json->errors($validator->errors()->all());

        $phone = $request->post('phone');
        $token = $request->post('token');

        // Get invoice
        $invoice = $invoiceRepository->getSingle($invoice_number);

        if($invoice == null){
            return $request->expectsJson() ?
                $this->json->error('The invoice does not exist, please go back to invoices page or check the url if you typed manually'):
                back()->withInput()->withErrors(['status' => 'The invoice does not exist, please go back to invoices page or check the url if you typed manually']);
        }

        // Validate the token
        if (!$paymentService->verifyToken($invoice, $token)) {
            return $request->expectsJson() ?
                $this->json->error('Invalid payment token'):
                back()->withInput()->withErrors(['status' => 'Invalid payment token']);
        }

        if($invoice->isPaid()){
            return $request->expectsJson() ?
                $this->json->error('The invoice has already been paid for'):
                back()->withInput()->withErrors(['status' => 'The invoice has already been paid for']);
        }

        // if($invoice->isPending()){
        //     return $request->expectsJson() ?
        //         $this->json->error("There's already another pending payment for this invoice. Please wait a while and try again"):
        //         back()->withInput()->withErrors(['status' => "There's already another pending payment for this invoice. Please wait a while and try again"]);
        // }

        // Phone number to 2547...
        $phone = substr_replace($phone, "254", 0, 1);

        // Initiate M-Pesa STK Push
        $price = $invoice->totals['total'];

        try{
            $response = STK::push(intval($price), $phone, $invoice_number, config('app.name'));

            if (!property_exists($response, 'ResponseCode') || intval($response->ResponseCode) != 0) {
                Storage::put('mpesa/errors/initiate', json_encode($response).' '.$price);
                return $request->expectsJson() ?
                    $this->json->error('Something went wrong. Please try again'):
                    back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
            }

            DB::beginTransaction();
            // Create a pending payment
            $payment = new Payment([
                'invoice_id' => $invoice->id,
                'method' => 'M-Pesa',
                'generated' => 'system',
                'status' => Payment::STATUS_PENDING
            ]);

            // create payment
            $mpesa_payment = new MpesaPayment();
            $mpesa_payment->merchant_request_id = $response->MerchantRequestID;
            $mpesa_payment->checkout_request_id = $response->CheckoutRequestID;
            $mpesa_payment->invoice_id = $invoice->id;
            $mpesa_payment->status = MpesaPayment::STATUS_PENDING;

            if(!($mpesa_payment->save() && $payment->save())){
                DB::rollback();
                return $request->expectsJson() ?
                    $this->json->error('Something went wrong. Please try again'):
                    back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
            }

            DB::commit();
            return $request->expectsJson() ?
                $this->json->success('Payment initiated. Please enter your M-Pesa PIN when prompted to authorize payment'):
                back()->withInput()->with(['status' => 'Payment initiated. Please enter your M-Pesa PIN when prompted to authorize payment']);

        }catch (Exception $e){
            return $request->expectsJson() ?
                $this->json->error('Something went wrong. Please try again'):
                back()->withInput()->withErrors(['status' => 'Something went wrong. Please try again']);
        }
    }

    function hook(Request $request){
        $response = $request->post('Body');
        $callback = $response['stkCallback'];

        $merchant_request_id = $callback['MerchantRequestID'] ?? null;
        $checkout_request_id = $callback['CheckoutRequestID'] ?? null;

        Storage::put('mpesa/responses/cri_'.$checkout_request_id, \json_encode($response));

        // If paid for already, payment will be rejected

        // Fetch the payment from db
        $mpesa_payment = MpesaPayment::where('merchant_request_id', $merchant_request_id)
                        ->where('checkout_request_id', $checkout_request_id)
                        ->first();

        // Get the invoice and last payment
        $invoice = $mpesa_payment->invoice;
        $last_payment = $invoice->payment;

        // set status as failed or successful
        if($callback['ResultCode'] == '0'){
            // For the payment
            $mpesa_payment->status = MpesaPayment::STATUS_SUCCESSFUL;
            $last_payment->status = Payment::STATUS_SUCCESSFUL;

            // Access payment metadata
            $mpesa_payment->metadata = $callback['CallbackMetadata']['Item'];

            // Mark payment as successful

            // Persist to database
            DB::beginTransaction();
            $last_payment->save();
            $mpesa_payment->save();
            DB::commit();

            // Notify mpesa
            return response()->json([
                "ResponseCode" => "00000000",
                "ResponseDesc" => "success"
            ]);

        }else{
            $mpesa_payment->status = MpesaPayment::STATUS_FAILED;
            $last_payment->status = Payment::STATUS_FAILED;

            // Persist to database
            $mpesa_payment->save();
            $last_payment->save();
        }

        // Notify mpesa
        return response()->json(['Oops']);
    }
}
