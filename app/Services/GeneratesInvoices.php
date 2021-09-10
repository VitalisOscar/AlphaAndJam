<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

trait GeneratesInvoices{
    use ManagesSlots;

    function createInvoice($advert, $amount){
        $invoice = new Invoice();

        $invoice->number = $this->generateInvoiceNumber();

        $invoice->created_at = Carbon::now();
        $invoice->advert_id = $advert->id;

        // TODO from config
        $tax_rate = 16;
        $tax = ($amount * $tax_rate) / 100;

        $invoice->totals = [
            'sub_total' => $amount,
            'tax_rate' => $tax_rate,
            'tax' => $tax,
            'total' => ($amount + $tax)
        ];

        // Due date
        $client = $advert->user;
        $earliest = Carbon::createFromTimeString($this->getEarliestSlotPlayingTime($advert->slots));

        if($client->canPayLater()){
            // Get the limit
            $limit = $client->payment['limit'];
            $earliest->addDays($limit)->toDateTimeString();
        }

        $invoice->due = $earliest;

        return $invoice;
    }

    function generateInvoiceNumber(){
        $generated_today = Invoice::query()
            ->count();

        $suffix = $generated_today + 1;
        if($suffix < 10) $suffix = '00'.$suffix;
        else if($suffix < 100) $suffix = '0'.$suffix;

        // $date = Carbon::today()->format('Y-m-d');
        // $date = str_replace('-', '', $date);

        return 'MV'.$suffix;
    }
}
