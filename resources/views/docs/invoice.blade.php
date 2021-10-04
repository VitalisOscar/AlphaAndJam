<?php $client = $invoice->advert->user; ?>

@if(\Illuminate\Support\Facades\Route::current()->getName() == 'web.user.invoices.single.download')
<link rel="stylesheet" href="{{ asset('css/main.css') }}" type="text/css">
@endif

<div class="bg-white p-4 shadow-sm invoice">

    <div class="mb-5">

        <div class="d-sm-flex align-items-center">
            <div class="mr-4">
                <img src="{{ asset('img/logo.png') }}" style="height: 110px">
            </div>

            <div>
                <h3 class="font-weight-600" style="line-height: 1.2">Oriscop Solutions</h3>
                <div>Imara Daima, Mombasa road, Nairobi</div>
                <div><strong>Email:&nbsp;</strong>info@oriscop.com</div>
                <div><strong>Tel:&nbsp;</strong>0710 338 211</div>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h5>BILLED TO</h5>

        <div class="">
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

            {{-- <div class="ml-auto">
                <img src="{{ asset('img/logo.png') }}" style="height: 80px" alt="">
            </div> --}}
        </div>
    </div>

    <div class="mb-2">
        <div class="">

            <div>
                <h3 class="font-weight-600" style="line-height: 1.2">Proforma Invoice</h3>

                <div class="">
                    <div class="form-row">
                        <div class="mb-3 col-sm-6 col-md-4">
                            <h5 class="mb-0">Invoice Number</h5>
                            <div><strong>{{ $invoice->number }}</strong></div>
                        </div>

                        <div class="mb-3 col-sm-6 col-md-4">
                            <h5 class="mb-0">Date Generated</h5>
                            <div><strong>{{ $invoice->time }}</strong></div>
                        </div>

                        <div class="mb-3 col-sm-6 col-md-4">
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-4 d-inline-block px-5 bg-success sep" style="border: 2px solid #373737; border-radius: 0 3px 3px 0"></div>

            <div>
                <h4 class="font-weight-600">Booking Information</h4>
                <p class="lead my-0">
                    {{ $invoice->advert->description }}
                </p>
            </div>

            <div class="mt-4 table-responsive">

                <table class="table border-bottom border">
                    <tr style="background: #dedede">
                        <th class="border text-center">#</th>
                        <th class="border">Screen</th>
                        <th class="border">Package</th>
                        <th class="border">Slots</th>
                        <th>Price (Total)</th>
                    </tr>

                    <?php $i = 1; ?>
                    @foreach($invoice->advert->slot_groups as $slot)
                    <tr>
                        <td class="border-right text-center">{{ $i }}</td>
                        <td class="border-right">{{ $slot->screen->title }}</td>
                        <td class="border-right">{{ $slot->package->name.' - '.$slot->package->summary }}</td>
                        <td class="border-right text-center">{{ number_format(count($slot->slots)) }}</td>
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
