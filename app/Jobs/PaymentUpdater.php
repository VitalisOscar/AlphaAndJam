<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Payment;
use Bryceandy\Laravel_Pesapal\Facades\Pesapal;
use Bryceandy\Laravel_Pesapal\Payment as Laravel_PesapalPayment;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentUpdater implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payments = Payment::pending()->with('latest_pesapal_payment')->get();

        foreach($payments as $payment){
            try{
                $pp = DB::table('pesapal_payments')
                    ->where('reference', $payment->invoice_id)
                    ->first();

                $transaction = Pesapal::getTransactionDetails(
                    $payment->invoice_id, $pp->tracking_id
                );

                Laravel_PesapalPayment::modify($transaction);
                $status = $transaction['status'];

                // $status = Pesapal::statusByMerchantRef($payment->id);

                $payment->status = $status;

                $payment->save();
            }catch(Exception $e){
                Storage::put('pesapal/pesapal_'.$payment->id, $e->getMessage());
            }
        }
    }
}
