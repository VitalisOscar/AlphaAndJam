<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Invoices')

@section('page_heading')
<i class="ni ni-money-coins text-success mr-3" style="font-size: .8em"></i>
Client Invoices
@endsection

@section('content')

<style>
    .nice-select, .form-control, .btn:not(.btn-sm){
        height: 42px !important;
    }

    .nice-select, .form-control{
        border-color: #ddd;
    }
</style>

<div class="d-flex align-items-center mb-3 pb-3 border-bottom">
    <?php $request = request(); ?>
    <div class="mr-auto">
        <input type="search" style="" class="form-control" name="number" value="{{ $request->get('number') }}" placeholder="Enter Invoice Number">
    </div>

    <form class="d-flex align-items-center ml-3 mr-3" method="GET">
        <div class="clearfix mr-3">
            <select name="status" class="nice-select">
                <option value="">Any Status</option>
                <option value="paid" @if($request->get('status') == 'paid') selected @endif>Paid</option>
                <option value="unpaid" @if($request->get('status') == 'unpaid') selected @endif>Overdue (Nothing aired)</option>
                <option value="post_pay" @if($request->get('status') == 'post_pay') selected @endif>Post Pay</option>
                <option value="pending" @if($request->get('status') == 'pending') selected @endif>Pending Payment</option>
            </select>
        </div>

        <div class="clearfix mr-3">
            <select name="order" class="nice-select">
                <option value="recent">Most Recent</option>
                <option value="oldest" @if($request->get('order') == 'oldest') selected @endif>Oldest first</option>
                <option value="highest" @if($request->get('order') == 'highest') selected @endif>Highest Amount</option>
                <option value="lowest" @if($request->get('order') == 'lowest') selected @endif>Lowest Amount</option>
            </select>
        </div>

        <div class="input-group input-group-alternative mr-3" style="max-width: 200px">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-calendar"></i>
                </span>
            </div>
            <input style="border-radius: 0 .25rem .25rem .25rem;" class="form-control flatpickr flatpickr-input" type="text" readonly name="date_range" id="date_range" placeholder="Select Date Range..">
        </div>

        <div class="float-right ml-auto">
            <button class="btn btn-success shadow-none">Refresh</button>
        </div>

        <div class="clearfix"></div>
    </form>

    <form action="{{ route('admin.clients.invoices.export') }}" class="" method="get">
        @foreach($request->all() as $k=>$d)
        <input type="hidden" name="{{ $k }}" value="{{ $d }}">
        @endforeach
        <button class="btn btn-default shadow-none"><i class="fa fa-download"></i>&nbsp;Export</button>
    </form>

</div>

<div class="table-responsive">
    <table class="table bg-white border">
        <tr class="bg-default text-white">
            <th class="text-center border-right">#</th>
            <th>Invoice No</th>
            <th>Client Name</th>
            <th>Time</th>
            <th>Amount (KSh)</th>
            <th>Status</th>
            <th></th>
        </tr>

        <?php $i = 1; ?>
        @foreach($invoices as $invoice)
        <tr>
            <td class="text-center border-right">{{ $i++ }}</td>
            <td>{{ $invoice->number }}</td>
            <td>{{ $invoice->user->name }}</td>
            <td>{{ $invoice->time }}</td>
            <td>{{ number_format($invoice->totals['total'], 2) }}</td>
            <td>
                @if($invoice->isPaid())
                <span class="d-inline-flex align-items-center text-success">
                    Paid
                </span> ({{ \App\Models\Payment::METHODS[$invoice->payment->method] }})
                @elseif($invoice->user->canPayLater())
                <span class="d-inline-flex align-items-center text-default">
                    Post Pay
                </span>
                @elseif($invoice->isPending())
                <span class="d-inline-flex align-items-center text-indigo">
                    Payment Underway
                </span>
                @elseif($invoice->isOverDue())
                <span class="d-inline-flex align-items-center text-danger">
                    Overdue (Ad Not Aired)
                </span>
                @else
                <span class="d-inline-flex align-items-center text-default">
                    Pending
                </span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.clients.invoices.single', $invoice->number) }}" class="btn btn-sm btn-outline-primary shadow-none">View Invoice</a>
            </td>
        </tr>
        @endforeach

        @if(count($invoices) == 0)
        <tr>
            <td colspan="7">
                <p class="lead my-0">
                    No invoices found for the given filters
                </p>
            </td>
        </tr>
        @else
        <tr>
            <td colspan="7">
                <div class="d-flex align-items-center">
                    <?php
                        $current = \Illuminate\Support\Facades\Route::current();
                    ?>
                    @if($prev_page != null)
                        <a href="{{ route($current->getName(), array_merge($current->parameters(), [
                            'page' => $prev_page
                        ], $request->except('page'))) }}" class="mr-auto btn btn-link py-0 shadow-none py-2"><i class="fa fa-angle-double-left mr-1"></i>Prev</a>
                    @endif

                    <span>Page {{ $page }} of {{ $total_pages }}</span>

                    @if($next_page != null)
                        <a href="{{ route($current->getName(), array_merge($current->parameters(), [
                            'page' => $next_page
                        ], $request->except('page'))) }}" class="ml-auto btn btn-link py-0 shadow-none py-2">Next<i class="fa fa-angle-double-right ml-1"></i></a>
                    @endif
                </div>
            </td>
        </tr>
        @endif

    </table>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr('#date_range',{
        mode: 'range',
        maxDate: 'today',
        defaultDate: "{!! $request->get('date_range') !!}",
        disable: [{
            from: 'today'
        }]
    });
</script>
@endsection
