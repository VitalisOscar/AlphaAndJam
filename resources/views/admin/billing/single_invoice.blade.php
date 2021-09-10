<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Invoice')

@section('page_heading')
<i class="ni ni-money-coins text-success mr-3" style="font-size: .8em"></i>
Client Invoice
@endsection

@section('content')

<div class="row">
    <div class="col-12 d-sm-none actions mb-4">
        <a href="{{ route('web.user.invoices.single.download', $invoice->number) }}" class="btn btn-default shadow-none mb-3 btn-block"><i class="fa fa-download mr-1"></i>Download Invoice</a>
        @if($user->isAdmin() && $invoice->isUnpaid())
        <button data-toggle="modal" data-target="#confirm_payment" class="mb-3 btn btn-success shadow-none btn-block"><i class="fa fa-credit-card mr-1"></i>Confirm Payment</button>
        @endif
        <a href="{{ route('admin.adverts.single', $invoice->advert->id) }}" class="btn btn-outline-primary shadow-none btn-block"><i class="fa fa-bullhorn mr-1"></i>View Advert</a>
    </div>

    <div class="col-md-9 mb-3">
        @include('docs.invoice')
    </div>


    <div class="col-md-3 actions d-none d-sm-block">
        <h4 class="font-weight-600">Actions</h4>
        <a href="{{ route('admin.clients.invoices.single.download', $invoice->number) }}" class="btn btn-default shadow-none mb-3 btn-block"><i class="fa fa-print mr-1"></i>Print</a>
        @if($user->isAdmin() && $invoice->isUnpaid())
        <button data-toggle="modal" data-target="#confirm_payment" class="mb-3 btn btn-success shadow-none btn-block"><i class="fa fa-credit-card mr-1"></i>Confirm Payment</button>
        @endif
        <a href="{{ route('admin.adverts.single', $invoice->advert->id) }}" class="btn btn-outline-primary shadow-none btn-block"><i class="fa fa-bullhorn mr-1"></i>View Advert</a>
    </div>

    <style>
        @media print{

            .actions, .sidebar, .header{
                display: none !important;
            }

            .main-content{
                margin-left: 0;
            }

            .invoice{
                box-shadow: none !important;
                margin: 1cm !important;
            }

            @page { size: auto;  margin: 0mm; }

            .heading-title{
                display: none;
            }

            .table{
                border-color: #111 !important;
            }

            .table *{
                border-color: #111 !important;
            }
        }
    </style>

</div>

@if($user->isAdmin() && $invoice->isUnpaid())
<div class="modal fade" id="confirm_payment">
    <div class="modal-dialog modal-dialog-centered modal-sm">

        <form action="{{ route('admin.clients.invoices.confirm_payment', $invoice->number) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-body">

                <div class="text-center">
                    <div>
                        <span class="mb-4 bg-success d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 60px; width:60px">
                            <i class="ni ni-money-coins text-white" style="font-size: 2em"></i>
                        </span>
                    </div>
                    <h4 class="mb-3 modal-title font-weight-600">Confirm Invoice Payment</h4>
                </div>

                <p class="text-justify">
                    This action will mark the invoice you are viewing as fully paid. Please specify the payment method used to pay for for this invoice
                </p>

                <div>
                    <div class="form-group">
                        <select class="custom-select" id="p_method" name="method" required onchange="if(this.value == 'mpesa'){$('#transaction_code').attr('required', 'required'); $('#code_wrap').removeClass('d-none');}else{$('#code_wrap').addClass('d-none'); $('#transaction_code').removeAttr('required');}">
                            <option value="">Payment Method</option>
                            @foreach(\App\Models\Invoice::PAYMENT_METHODS as $k=>$m)
                            <option value="{{ $k }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group d-none" id="code_wrap">
                        <input class="form-control" value="{{ old('method') }}" name="code" id="transaction_code" placeholder="Transaction Code">
                    </div>

                    <div class="text-center mb-3">
                        <button class="btn btn-block btn-primary shadow-none">Confirm Payment</button>
                    </div>

                    <div class="text-center">
                        <button data-dismiss="modal" type="button" class="btn btn-block btn-white shadow-none">Cancel</button>
                    </div>
                </div>

            </div>

        </form>

    </div>
</div>
@endif

@endsection

@section('scripts')
    <script>
        $('#confirm_payment').on('show.bs.modal', function(){
            document.querySelector('#p_method').selectedIndex = 0;
        });
    </script>
@endsection
