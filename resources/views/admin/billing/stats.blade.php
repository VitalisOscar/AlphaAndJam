<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Billing Stats')

@section('page_heading')
<i class="fa fa-bar-chart text-success mr-3" style="font-size: .8em"></i>
Billing Stats
@endsection

@section('content')

<style>
    .nice-select, .form-control, .btn{
        height: 42px !important;
    }

    .nice-select, .form-control{
        border-color: #ddd;
    }
</style>

<div>

    <!-- Summary -->

    <div>

        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
            <h4 class="font-weight-600 mb-0">Payments Summary</h4>

            <form class="d-flex ml-auto" method="GET">
                <?php $request = request(); ?>

                <input style="border-radius: 0 .25rem .25rem .25rem;" class="mr-3 form-control flatpickr flatpickr-input" type="text" readonly name="summary_range" id="summary_dates" placeholder="Select Date Range..">
                <button class="btn btn-default shadow-none">Refresh</button>
            </form>
        </div>

        <div class="row mb-5">

            <div class="col-xl-3">
                <div class="card h-100 shadow-sm bg-warning rounded-lg" style="">
                    <div class="card-body p-3">
                        <h5 class="card-title text-uppercase text-white font-weight-bold small mb-0">Invoices Generated</h5>
                        <span class="h2 text-white mb-0">{{ $summary->all_invoices }}</span>
                        @if($summary->paid_invoices != 0)
                        <h5 class="text-white font-weight-bold small mb-0"><i class="fa fa-bar-chart mr-2"></i>{!! $summary->paid_invoices.' paid' !!}</h5>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card h-100 shadow-sm bg-default rounded-lg" style="">
                    <div class="card-body p-3">
                        <h5 class="card-title text-uppercase text-white font-weight-bold small mb-0">Total Amount (KSh)</h5>
                        <span class="h2 text-white mb-0">{{ number_format($summary->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card h-100 shadow-sm bg-indigo rounded-lg" style="">
                    <div class="card-body p-3">
                        <h5 class="card-title text-uppercase text-white font-weight-bold small mb-0">Amount Paid (KSh)</h5>
                        <span class="h2 text-white mb-0">{{ number_format($summary->paid_amount, 2) }}</span>
                        @if($summary->total_amount != 0 && $summary->paid_amount != 0)
                        <h5 class="text-white font-weight-bold small mb-0"><i class="fa fa-bar-chart mr-2"></i>{{ round((($summary->paid_amount/$summary->total_amount) * 100), 0).'%' }}</h5>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-3">
                <div class="card h-100 shadow-sm bg-gradient-success rounded-lg" style="">
                    <div class="card-body p-3">
                        <h5 class="card-title text-uppercase text-white font-weight-bold small mb-0">Amount Unpaid (KSh)</h5>
                        <span class="h2 text-white mb-0">{{ number_format($summary->unpaid_amount, 2) }}</span>
                        @if($summary->total_amount != 0 && $summary->unpaid_amount != 0)
                        <h5 class="text-white font-weight-bold small mb-0"><i class="fa fa-bar-chart mr-2"></i>
                            {{ round((($summary->unpaid_amount/$summary->total_amount) * 100), 0).'%' }}
                        </h5>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Charts -->

    <div class="row">

        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-3">
                <h4 class="mb-0 font-weight-600">Invoice Payments Trend</h4>
            </div>

            {{-- @dump($payments) --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <canvas id="chart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <h4 class="font-weight-600">Invoice Generation</h4>
            <canvas id="invoice_chart" height="320"></canvas>
        </div>

    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr('#summary_dates',{
        mode: 'range',
        maxDate: 'today',
        defaultDate: '{!! $summary->range !!}',
        disable: [{
            from: 'today'
        }]
    });

    var amount_chart = new Chart(document.querySelector('#chart'), {
        type: 'line',
        data: {
            labels: [@foreach($payments as $payment)
            @php
                $date = \Carbon\Carbon::createFromDate($payment->date);
                $d = substr($date->monthName, 0, 3).' '.$date->day;
            @endphp
            {!! "'".$d."'," !!}
            @endforeach],
            datasets: [
                {
                    label: 'Paid',
                    data: [
                        @foreach($payments as $payment){{ $payment->paid_amount.',' }}@endforeach
                    ],
                    borderWidth: 1,
                    borderColor: 'dodgerblue',
                },
                {
                    label: 'Unpaid',
                    data: [@foreach($payments as $payment){{ $payment->unpaid_amount.',' }}@endforeach],
                    borderWidth: 2,
                    borderColor: 'coral',
                    type: 'line',
                }
            ]
        },
        options: {
            spanGaps: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

var invoices_volume = new Chart(document.querySelector('#invoice_chart'), {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($invoices as $stat){!! "'".$stat['label']."'," !!}@endforeach
        ],
        datasets: [{
            label: 'Invoice Volume',
            data: [
                @foreach($invoices as $stat){{ $stat['value'].',' }}@endforeach
            ],
            borderWidth: 2,
            backgroundColor: [
                @foreach($invoices as $stat){!! "'".$stat['color']."'," !!}@endforeach
            ]
        }]
    },
    options: {}
});

</script>
@endsection
