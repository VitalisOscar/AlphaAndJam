<?php

namespace App\Repository;

use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;

class InvoiceRepository{

    /**
     * Get a single invoice
     * @param string $invoice_number Invoice number
     * @return Invoice|null
     */
    function getSingle($invoice_number){
        $invoice = auth()->user()
            ->invoices()
            ->where('number', $invoice_number)
            ->first();

        if($invoice == null) return null;

        $this->modify($invoice);

        // Add token, to guard ajax payment requests
        $paymentService = resolve(\App\Services\PaymentService::class);
        $invoice->token = $paymentService->getToken($invoice);

        return $invoice;
    }

    /**
     * Get a customer's invoices
     * @return array
     */
    function fetchAll(){
        $request = request();

        $invoices = auth()->user()->invoices();

        if($request->filled('status')){
            if($request->get('status') == 'paid'){
                $invoices->paid();
            }else if($request->get('status') == 'unpaid'){
                $invoices->unpaid();
            }
        }

        $from = $request->filled('date_from') ? $request->get('date_from') : null;
        $to = $request->filled('date_to') ? $request->get('date_to') : null;

        if(isset($from, $to)){
            $invoices->where(function($q) use($from, $to){
                $q->whereBetween('invoices.created_at', [$from, $to])
                    ->orWhereDate('invoices.created_at', $from)
                    ->orWhereDate('invoices.created_at', $to);
            });
        }

        if($request->filled('order')){
            $order = $request->get('order');
            if($order == 'oldest') $invoices->orderBy('created_at', 'asc');
            else if($order == 'highest') $invoices->orderBy('totals->total', 'desc');
            else if($order == 'lowest') $invoices->orderBy('totals->total', 'asc');
            else $invoices->orderBy('created_at', 'desc');
        }else{
            $invoices->orderBy('created_at', 'desc');
        }

        // total
        $total = $invoices->count();

        // paginate
        $page = $request->filled('page') ? intval($request->get('page')) : 1;
        if($page < 1) $page = 1;

        $limit = Invoice::FETCH_LIMIT;
        $offset = ($page - 1) * $limit;

        $invoices->limit($limit)->offset($offset);

        $invoices = $invoices->get()->each(function($invoice){
            $this->modify($invoice);
        });

        //meta
        $total_pages = ceil($total / $limit);
        $prev_page = $page > 1 ? ($page - 1) : null;
        $next_page = $page < $total_pages ? ($page + 1) : null;

        return [
            'invoices' => $invoices,
            'page' => $page,
            'prev_page' => $prev_page,
            'next_page' => $next_page,
            'total_pages' => $total_pages,
            'total' => $total
        ];
    }

    /**
     * Get all invoices, from admin
     * @return array
     */
    function adminFetch(){
        $request = request();

        $invoices = $this->getQuery();

        // total
        $total = $invoices->count();

        // paginate
        $page = $request->filled('page') ? intval($request->get('page')) : 1;
        if($page < 1) $page = 1;

        $limit = Invoice::FETCH_LIMIT;
        $offset = ($page - 1) * $limit;

        $invoices->limit($limit)->offset($offset);

        $invoices = $invoices->with(['advert', 'payment'])->get()->each(function($invoice){
            $this->modify($invoice);
        });

        //meta
        $total_pages = ceil($total / $limit);
        $prev_page = $page > 1 ? ($page - 1) : null;
        $next_page = $page < $total_pages ? ($page + 1) : null;

        return [
            'invoices' => $invoices,
            'page' => $page,
            'prev_page' => $prev_page,
            'next_page' => $next_page,
            'total_pages' => $total_pages,
            'total' => $total
        ];
    }

    /**
     * Get query, for current request (in admin mode)
     */
    function getQuery(){
        $request = request();

        $invoices = Invoice::query();

        if($request->filled('number')){
            $invoices->where('invoices.number', $request->get('number'));
        }

        if($request->filled('status')){

            if($request->get('status') == 'unpaid'){
                // overdue, regular clients
                $invoices->prepay()->overdue();
            }else if($request->get('status') == 'post_pay'){
                // Overdue, post pay clients
                // due date passed

                // $invoices->unpaid();
                $invoices->unpaid()->postpay();

            }else if($request->get('status') == 'pending'){
                // pending payment, regular clients
                // Due date has not passed
                $invoices->prepay()->unpaid()->pending();
            }else if($request->get('status') == 'paid'){
                $invoices->paid();
            }

        }

        $range = $request->get('date_range');
        if($range == null){
            $range = Carbon::today()->subDays(60)->format('Y-m-d').' to '.Carbon::today()->format('Y-m-d');
        }

        $range = explode(' to ', $range);

        $from = $range[0];
        $to = null;
        if(count($range) > 1){
            $to = $range[1];
        }

        if($from > $to){
            $x = $to;
            $to = $from;
            $from = $x;
        }

        if(isset($from, $to)){
            $invoices->where(function($q) use($from, $to){
                $q->whereBetween('invoices.created_at', [$from, $to])
                    ->orWhereDate('invoices.created_at', $from)
                    ->orWhereDate('invoices.created_at', $to);
            });
        }

        if($request->filled('order')){
            $order = $request->get('order');
            if($order == 'oldest') $invoices->orderBy('created_at', 'asc');
            else if($order == 'highest') $invoices->orderBy('totals->total', 'desc');
            else if($order == 'lowest') $invoices->orderBy('totals->total', 'asc');
            else $invoices->orderBy('created_at', 'desc');
        }else{
            $invoices->orderBy('created_at', 'desc');
        }

        return $invoices;
    }

    /**
     * Get a single invoice, by admin
     * @param string $invoice_number Invoice number
     * @return Invoice|null
     */
    function adminSingle($invoice_number){
        $invoice = Invoice::where('number', $invoice_number)
            ->first();

        if($invoice == null) return null;

        $this->modify($invoice);

        return $invoice;
    }

    private function modify($invoice){
        $time = Carbon::createFromTimeString($invoice->created_at);

        $tm = substr($time->monthName, 0, 3).' ';

        if($time->day < 10) $tm .= '0';
        $tm .= $time->day.', '.$time->year.' ';

        if($time->hour < 12) $tm .= $time->hour.':<min> AM';
        else if($time->hour > 12) $tm .= ($time->hour - 12).':<min> PM';
        else if($time->minute > 0) $tm .= '12:<min> PM';
        else $tm .= '12:<min> Noon';

        $mins = $time->minute;
        $invoice->time = str_replace('<min>', ($mins>9 ? $mins:'0'.$mins),$tm);

        // Due date
        $invoice->due_date = $invoice->due_date;

        // amount
        $invoice->amount = 'KSh '.number_format($invoice->amount);

        return $invoice;
    }
}
