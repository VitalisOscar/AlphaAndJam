@extends('web.base')

@section('title', config('app.name').' | Client Invoice')

@section('section_heading', 'Client Invoice')

@section('content')

<div class="row">
    <div class="col-12 d-sm-none actions mb-4">
        <div>
            <a href="{{ route('web.user.invoices.single.download', $invoice->number) }}" class="btn btn-default shadow-none mb-3 btn-block"><i class="fa fa-download mr-1"></i>Download Invoice</a>
            @if($invoice->isUnpaid())
            <form action="{{ route('web.user.invoices.single.payment.mpesa', $invoice->number) }}" method="get" class="d-block mb-3">
                <button class="btn btn-primary shadow-none btn-block"><i class="fa fa-credit-card mr-1"></i>Pay Online</button>
            </form>
            {{-- <button class="btn btn-primary shadow-none btn-block" data-toggle="modal" data-target="#payment_instructions"><i class="fa fa-credit-card mr-1"></i>Payment</button> --}}
            @endif
            <a href="{{ route('web.adverts.single', $invoice->advert->id) }}" class="btn btn-outline-primary shadow-none btn-block"><i class="fa fa-bullhorn mr-1"></i>View Advert</a>

        </div>
        @if($invoice->isPending())
        <div>
            <p class="lead mb-0">
                Payment is underway for this invoice. We will notify you once Pesapal confirms the payment status
            </p>
        </div>
        @endif
    </div>

    <div class="col-md-9 mb-3">
        @include('docs.invoice')
    </div>


    <div class="col-md-3 actions d-none d-sm-block">
        <div>
            <h4 class="font-weight-600">Actions</h4>
            <a href="{{ route('web.user.invoices.single.download', $invoice->number) }}" class="btn btn-default shadow-none mb-3 btn-block"><i class="fa fa-print mr-1"></i>Download/Print</a>
            @if($invoice->isUnpaid())
            <form action="{{ route('web.user.invoices.single.payment.mpesa', $invoice->number) }}" method="get" class="d-block mb-3">
                <button class="btn btn-primary shadow-none btn-block"><i class="fa fa-credit-card mr-1"></i>Pay Online</button>
            </form>
            {{-- <button class="btn btn-primary shadow-none btn-block" data-toggle="modal" data-target="#payment_instructions"><i class="fa fa-credit-card mr-1"></i>Payment</button> --}}
            @endif
            <a href="{{ route('web.adverts.single', $invoice->advert->id) }}" class="btn btn-outline-primary shadow-none btn-block"><i class="fa fa-bullhorn mr-1"></i>View Advert</a>
        </div>

        @if($invoice->isPending())
        <div>
            <p class="lead mb-0">
                Payment is underway for this invoice. We will notify you once Pesapal confirms the payment status
            </p>
        </div>
        @endif
    </div>

    @if($invoice->isUnpaid())

    <div class="modal fade" id="payment_instructions" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="text-center">
                        <div>
                            <img src="{{ asset('img/mpesa_logo.png') }}" style="height: 100px" alt="M-Pesa">
                        </div>
                        <h4 class="mb-3 modal-title font-weight-600">Payment Instructions</h4>
                    </div>

                    <p class="text-justify">
                        Complete payment for this invoice via M-Pesa.
                        <ul>
                            <li>Open M-Pesa and go to pay bill option</li>
                            <li>Enter the business number as <strong class="font-weight-800">339 300</strong></li>
                            <li>Under account number, enter <strong class="font-weight-800">{{ $invoice->number }}</strong></li>
                            <li>Enter amount upto {{ 'KSh '.number_format($invoice->totals['total']) }}</li>
                            <li>Enter M-Pesa pin and submit</li>
                        </ul>
                        Once your full payment is confirmed, your advert will be lined for airing on selected slots
                    </p>

                    <div class="text-center">
                        <button data-dismiss="modal" type="button" class="btn btn-block btn-white shadow-none">Ok</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @endif

    <style>
        @media print{
            .logo-sm{
                display: none !important;
            }

            .actions{
                display: none;
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

@include('web.user._pay_dialog')

@endsection

@section('scripts')
@if(request()->get('pay'))
<script>
    $('#payment_instructions').modal('show');
</script>
@endif
@if($errors->has('status'))
<script>showAlert("{{ $errors->get('status')[0] }}", 'Oops')</script>
@endif
{{-- <script src="{{ asset('js/pay.js') }}"></script>

<script>
    var mpesa_url = "{{ route('web.user.invoices.single.payment.mpesa', $invoice->number) }}";
</script> --}}
@endsection
