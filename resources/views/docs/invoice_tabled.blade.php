<?php $client = $invoice->advert->user; ?>

{{-- <link rel="preconnect" href="https://fonts.gstatic.com"> --}}
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
<link href="{{ asset('css/argon-design-system.min.css') }}" rel="stylesheet">
<link href= "{{ asset('css/main.css') }}" rel="stylesheet">

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

<div class="bg-white p-4 shadow-sm invoice">

    <table class="mb-4">

        <tr>
            <td>
                <div class="mr-4">
                    <img src="{{ asset('img/alphalogo.png') }}" style="height: 110px">
                </div>
            </td>

            <td>
                <div>
                    <h3 class="font-weight-600" style="line-height: 1.2">Alpha and Jam Limited</h3>
                    <div>The Mirage, Chiromo road, Nairobi</div>
                    <div><strong>Email:&nbsp;</strong>info@alphaandjam.net</div>
                    <div><strong>Tel:&nbsp;</strong>0704 607 893</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="mb-5">
        <h5>BILLED TO</h5>

        <table>
            <tr>
                <td>
                    <div>
                        <table>
                            <tr>
                                <td class="pr-5"><strong>Client Name:</strong></td>
                                <td>{{ $client->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $client->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $client->operator_phone }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="mb-2">
        <div class="">

            <div>
                <h3 class="font-weight-600" style="line-height: 1.2">Proforma Invoice</h3>

                <table style="width: 100%">
                    <tr>
                        <td style="width: 33.33%; vertical-align: top">
                            <h5 class="mb-0">Invoice Number</h5>
                            <div><strong>{{ $invoice->number }}</strong></div>
                        </td>

                        <td style="width: 33.33%; vertical-align: top">
                            <h5 class="mb-0">Date Generated</h5>
                            <div><strong>{{ $invoice->time }}</strong></div>
                        </td>

                        <td style="width: 33.33%; vertical-align: top">
                            <h5 class="mb-0">Invoice Status</h5>
                            <div>
                                <strong>
                                @if($invoice->isPaid())
                                <span class="text-success">PAID</span> - {{ \App\Models\Payment::METHODS[strtolower($invoice->payment->method)].($invoice->payment->code != null ? ' ('.$invoice->payment->code.')':'') }}
                                @elseif($invoice->isUnpaid())
                                <span class="text-danger">UNPAID</span>
                                @else
                                <span class="text-primary">PAYMENT UNDERWAY</span>
                                @endif
                                </strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="my-4 d-inline-block px-5 bg-success sep" style="height: 5px; background: #373737; border-radius: 0 3px 3px 0"></div>

            <div>
                <h4 class="font-weight-600">Booking Information</h4>
                <p class="lead my-0">
                    {{ $invoice->advert->description }}
                </p>
            </div>

            <div class="mt-4 table-responsive">

                <table style="border-top: 1px solid #111" class="table table-sm">
                    <tr style="border-bottom: 1px solid #111">
                        <th class="border text-center">#</th>
                        <th class="border">Screen</th>
                        <th class="border">Package</th>
                        <th class="border">Slots</th>
                        <th>Price (Total)</th>
                    </tr>

                    <?php $i = 1; ?>

                    @foreach($invoice->advert->slot_groups as $slot)
                    <tr style="border-bottom: 1px solid #111">
                        <td class="text-center">{{ $i }}</td>
                        <td class="">{{ $slot->screen->title }}</td>
                        <td class="">{{ $slot->package->name.' - '.$slot->package->summary }}</td>
                        <td class="text-center">{{ number_format(count($slot->slots)) }}</td>
                        <td>{{ 'KSh '.number_format($slot->price) }}</td>
                    </tr>
                    <?php $i++; ?>
                    @endforeach
                </table>

            </div>

        </div>
    </div>

    <div class="d-flex align-items-center">
        <div class="text-right ml-auto">
            <table style="font-size: 1.15em">
                <tr>
                    <th class="pr-5">Sub-Total:</th>
                    <td class="text-left"><span>{{ 'KSh '.number_format($invoice->totals['sub_total'], 2) }}</span></td>
                </tr>

                <tr>
                    <th class="pr-5">{{ $invoice->totals['tax_rate'].'% VAT' }}:</th>
                    <td class="text-left"><span>{{ 'KSh '.number_format($invoice->totals['tax'], 2) }}</span></td>
                </tr>

                <tr>
                    <th class="pr-5">Total:</th>
                    <td class="text-left"><span>{{ 'KSh '.number_format($invoice->totals['total'], 2) }}</span></td>
                </tr>

                <tr>
                    <th class="pr-5">Due Date:</th>
                    <td class="text-left"><span>{{ $invoice->due_date }}</span></td>
                </tr>
            </table>
        </div>
    </div>

</div>
