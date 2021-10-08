@extends('web.base')

@section('title', config('app.name').' | Ad Overview')

@section('section_heading', 'Ad Overview')

@section('content')

    <div class="row">

        <div class="col-md-5 col-lg-5 pr-lg-5">
            <div class="info">
                @switch($advert->status)
                    @case(\App\Models\Advert::STATUS_APPROVED)
                        This ad has been approved. It will be aired on the booked dates and times

                        @if($advert->invoice->isUnpaid())
                        <form action="{{ route('web.user.invoices.single.payment', $advert->invoice->number) }}" method="get" class="d-block mt-2 text-right">
                            <button class="btn btn-default btn-sm"><i class="ni ni-money-coins mr-1"></i>Complete Payment</button>
                        </form>
                        @endif

                        @break
                    @case(\App\Models\Advert::STATUS_DECLINED)
                        This ad does not meet our set standards. You can still modify it and resubmit<br>
                        <a href="{{ route('web.adverts.edit', $advert->id) }}" class="btn btn-link px-0 py-1"><i class="fa fa-edit mr-1"></i>Edit Ad</a>
                        @break

                    @case(\App\Models\Advert::STATUS_PENDING_APPROVAL)
                    @case(\App\Models\Advert::STATUS_PENDING_REAPPROVAL)
                        This advert is pending approval from our staff. You'll be notified when this is done
                        @break

                @endswitch
            </div>

            <div class="embed-responsive embed-responsive-16by9 media-bg mb-4">
                <div class="embed-responsive-item">

                    @if($advert->content['media_path'] != null)
                    <div class="mb-2 rounded-lg w-100" style="background: #ececec">
                        <?php
                            $media_path = asset('storage/'.$advert->content['media_path']);
                        ?>
                        @if($advert->content['media_type'] == 'image')
                        <img src="{{ $media_path }}" class="img-fluid">
                        @else
                        <video controls muted src="{{ $media_path }}" class="img-fluid" type="video/*"></video>
                        @endif
                    </div>

                    @else
                    <div class="px-4">
                        <div class="mb-2">Edit this ad by uploading a video or an image</div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('web.adverts.edit', $advert->id) }}" class="btn btn-link p-0"><i class="fa fa-edit mr-2"></i>Edit Ad</a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <h5 class="font-weight-600">Description</h5>

            <p>
                {{ $advert->description }}
            </p>

            <div class="mb-4">
                <span class="mr-3 font-weight-600" style="">
                    <i class="fa fa-bullhorn text-muted mr-1"></i>{{ $advert->category_name }}
                </span>

                <span class="font-weight-bold" style="">
                    <i class="fa fa-calendar-o text-muted font-weight-bold mr-1"></i>{{ $advert->time }}
                </span>
            </div>
        </div>

        <div class="col-md-4 col-lg-4">

            <div class="mb-4">
                <h5 class="font-weight-600">Booked Slots</h5>

                <p>You booked the following slots:</p>

                @php
                        $total_price = 0;
                    @endphp

                    @foreach($advert->slot_groups as $group)
                    <div class="border mb-3 pt-3 pb-2 rounded-lg px-3 bg-white">
                        <div class="d-flex align-items-center mb-2">
                            <span class="rounded-circle bg-warning text-white d-none mr-3 align-items-center justify-content-center" style="min-width: 35px; height: 35px">
                                <i class="fa fa-video-camera"></i>
                            </span>
                            <h6 class="mb-0"><strong>{{ $group->screen->title.' - '.$group->package->name.' ('.$group->package->summary.') - KSh'.number_format($group->price) }}</strong></h6>
                        </div>

                        <div>
                            @if(count($group->slots) == 1)
                                <div class="mb-2">
                                    <span class="d-inline-flex rounded-circle small text-white mr-1 align-items-center justify-content-center" style="min-width: 30px; height: 30px; background: #ff7f5090">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    1 Slot - {{ $group->slots[0]->date }}
                                </div>
                            @else
                            <div class="mt-2" style="white-space: nowrap; overflow-x: auto; scrollbar-width: thin">
                                @foreach($group->slots as $slot)
                                <span class="d-inline-block px-3 py-2 mb-2 mr-1 small font-weight-600" style="border-radius: 20px; background: #eaeaea">
                                    {{ $slot->date }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    @php
                        $total_price += $group->price;
                    @endphp
                    @endforeach

                    <h4 class="mb-0">Total Price: {{ 'KSh '.number_format($total_price) }}</h4>
            </div>

        </div>

        <div class="col-md-3 col-lg-3">

            {{-- <div class="mb-3">
                <div>
                    <strong class="font-weight-600"><i class="fa fa-plus text-muted mr-1"></i>Recreate Ad</strong>
                </div>
                Create a new ad from this ad's content<br>
                <a href="{{ route('web.adverts.recreate', $advert->id) }}" class="btn btn-link px-0 py-1">Recreate</a>
            </div> --}}

            @if($advert->isRejected())
            <div class="mb-3">
                <div>
                    <strong class="font-weight-600"><i class="fa fa-edit text-muted mr-1"></i>Edit Content</strong>
                </div>
                Edit this ad's content<br>
                <a href="{{ route('web.adverts.edit', $advert->id) }}" class="btn btn-link px-0 py-1">Edit</a>
            </div>

            <div class="mb-3">
                <div>
                    <strong class="font-weight-600"><i class="fa fa-trash text-muted mr-1"></i>Delete Ad</strong>
                </div>
                Delete this ad from your history<br>
                <form id="del-form" action="{{ route('web.adverts.delete', $advert->id) }}" method="POST">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); $('#del-form').submit()" class="btn btn-link px-0 py-1">Delete Advert</a>
                </form>
            </div>
            @endif

            @if($advert->invoice != null)
            <div class="mb-3">
                <div>
                    <strong class="font-weight-600"><i class="fa fa-money text-muted mr-1"></i>View Invoice</strong>
                </div>
                View your charges for slots and payment status for this ad<br>
                <a href="{{ route('web.user.invoices.single', $advert->invoice->number) }}" class="btn btn-link px-0 py-1">View Invoice</a>
            </div>
            @endif
        </div>

    </div>



@endsection
