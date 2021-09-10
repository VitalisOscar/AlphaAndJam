<?php

namespace App\Http\Controllers\Admin\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    function stats(Request $request){
        $payments = Payment::query();

        $summary_range = $request->get('summary_range');
        if($summary_range == null){
            $summary_range = Carbon::today()->subDays(60)->format('Y-m-d').' to '.Carbon::today()->format('Y-m-d');
        }

        $summary_range = explode(' to ', $summary_range);

        $from = $summary_range[0];

        $to = null;
        if(count($summary_range) > 1){
            $to = $summary_range[1];
        }

        if($from > $to){
            $x = $to;
            $to = $from;
            $from = $x;
        }

        // paid - all paid
        // overdue - regular only
        // post pay - for post pay clients
        // pending - unpaid|regular|still due
        $stats = DB::select(
            "select ".
                "(".
                Invoice::whereRaw("date(created_at) >= '".$from."'")
                    ->whereRaw("date(created_at) <= '".$to."'")
                    ->selectRaw("count(*)")->toSql().") as all_invoices,".
                // all paid invoices
                "(".Invoice::paid()
                ->whereRaw("date(created_at) >= '".$from."'")
                ->whereRaw("date(created_at) <= '".$to."'")
                ->selectRaw('count(*)')
                ->toSql().") as paid_invoices,".
                // invoices which haven't been paid, but aren't due
                "(".Invoice::prePay()->pending()
                ->whereRaw("date(created_at) >= '".$from."'")
                ->whereRaw("date(created_at) <= '".$to."'")
                ->selectRaw('count(*)')
                ->toSql().") as pending_invoices,".
                // post pay invoices
                "(".Invoice::unpaid()->postPay()
                ->whereRaw("date(created_at) >= '".$from."'")
                ->whereRaw("date(created_at) <= '".$to."'")
                ->selectRaw('count(*)')
                ->toSql().") as post_pay_invoices,".
                // pre pay invoices which haven't been paid, and are due
                "(".Invoice::prePay()
                ->overDue()
                ->whereRaw("date(created_at) >= '".$from."'")
                ->whereRaw("date(created_at) <= '".$to."'")
                ->selectRaw('count(*)')
                ->toSql().") as overdue_invoices,".
                // all paid amounts
                "(".Invoice::paid()
                ->whereRaw("date(created_at) >= '".$from."'")
                ->whereRaw("date(created_at) <= '".$to."'")
                ->selectRaw("sum(json_extract(`totals`, '$.total'))")
                ->toSql().") as paid_amount,".
                // all unpaid amounts
                "(".Invoice::unpaid()
                ->whereRaw("date(created_at) >= '".$from."'")
                ->whereRaw("date(created_at) <= '".$to."'")
                ->selectRaw("sum(json_extract(`totals`, '$.total'))")
                ->toSql().") as unpaid_amount"
        );

        $stats = $stats[0];
        $stats->range = $from.' to '.$to;

        $invoice_stats = [
            ['label' => 'Pending (Prepay)', 'value' => $stats->pending_invoices, 'color' => '#007bff'],
            ['label' => 'Post Pay', 'value' => $stats->post_pay_invoices, 'color' => 'purple'],
            ['label' => 'Paid (All)', 'value' => $stats->paid_invoices, 'color' => 'green'],
            ['label' => 'Overdue (Prepay)', 'value' => $stats->overdue_invoices, 'color' => 'orangered'],
        ];

        // trends
        $payments = DB::select(
            "select
                distinct(date(created_at)) as `date`,
                (".
                Invoice::paid()
                    ->selectRaw("sum(json_extract(totals, '$.total'))")
                    ->whereRaw('date(invoices.created_at) = date(i_main.created_at)')->toSql().
                ")as paid_amount,
                (".
                Invoice::unpaid()
                    ->selectRaw("sum(json_extract(totals, '$.total'))")
                    ->whereRaw('date(invoices.created_at) = date(i_main.created_at)')->toSql().
                ")as unpaid_amount
                from invoices as i_main
                where date(created_at) >= '".$from."' and date(created_at) <= '".$to."'
                order by date(created_at)
            "
        );

        $with_stats = [];
        $without_stats = [];

        foreach($payments as $payment){
            if($payment->paid_amount == null){
                $payment->paid_amount = 0;
            }

            if($payment->unpaid_amount == null){
                $payment->unpaid_amount = 0;
            }

            array_push($with_stats, $payment->date);
        }

        // include dates with no data
        $from = $summary_range[0];

        $from = Carbon::createFromFormat('Y-m-d', $from);
        $today = Carbon::today();

        while($from->isBefore($today)){
            if(!in_array($from->format('Y-m-d'), $with_stats)){
                array_push($without_stats, $from->format('Y-m-d'));
            }

            $from->addDay();
        }

        if(!in_array($today->format('Y-m-d'), $with_stats)){
            array_push($without_stats, $today->format('Y-m-d'));
        }

        foreach($without_stats as $date){
            array_push($payments, json_decode('{
                "date": "'.$date.'",
                "paid_amount": 0,
                "unpaid_amount": 0
            }'));
        }

        // sort by date
        array_multisort(array_column($payments, 'date'), SORT_ASC, $payments);


        $total_invoices = 0;

        foreach($invoice_stats as $stat){
            $total_invoices += $stat['value'];
        }

        $stats->total_invoices = $total_invoices;
        $stats->total_amount = $stats->paid_amount + $stats->unpaid_amount;

        return response()->view('admin.billing.stats', [
            'payments' => $payments,
            'summary' => $stats,
            'invoices' => $invoice_stats
        ]);
    }
}
