<?php
$user = auth('staff')->user();
$owner = $advert->user;
?>

@extends('admin.base')

@section('title', 'View advert')

@section('extra_links')
<link href="{{ asset('vendor/video-js/video-js.css') }}" rel="stylesheet" />
@endsection

@section('scripts')
<script src="{{ asset('vendor/video-js/video-js.min.js') }}"></script>

<script>
    videojs(document.querySelector('.video-js'));
</script>
@endsection

@section('page_heading')
<i class="fa fa-bullhorn text-success mr-3" style="font-size: .8em"></i>
Advert Info
@endsection

@section('content')

<div class="row">

    <div class="col-md-5">

        <div class="embed-responsive embed-responsive-16by9 bg-dark rounded mb-3">
            <div class="embed-responsive-item d-flex align-items-center">

                @if($advert->content['media_path'] != null)
                <div class="mb-2 rounded-lg w-100" style="background: #ececec">
                    <?php
                        $media_path = asset('storage/'.$advert->content['media_path']);
                    ?>
                    @if($advert->content['media_type'] == 'image')
                    <img src="{{ $media_path }}" class="img-fluid">
                    @else
                    <video controls class="video-j" src="{{ $media_path }}" type="video/*">

                    </video>
                    @endif

                    {{-- <video-js>
                        <source src="{{ $media_path }}" type="{{ $advert->content['media_type'].'/*' }}">
                    </video-js> --}}
                </div>

                @else
                <div class="px-4 w-100">
                    <div class="text-center">
                        <p class="lead text-white my-0">
                            No Media Content Uploaded
                        </p>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <div class="card border">
            <div class="card-body">

                <div class="row">

                    <div class="col-12">

                        <div class="mb-4">
                            <h5 class="font-weight-600">More Content</h5>

                            <p>
                                {{ $advert->content['text'] != null ? $advert->content['text']:__('No text content provided') }}
                            </p>
                        </div>

                        <div>
                            <h5 class="font-weight-600">Advert Owner</h5>

                            @if($owner->isClient())
                            <table class="table table-sm mb-0 border-bottom">
                                <tr>
                                    <th>
                                        Client Name:
                                    </th>

                                    <td>{{ $owner->name }}</td>
                                </tr>

                                <tr>
                                    <th>
                                        Official Contacts:
                                    </th>

                                    <td>{{ $owner->email }}</td>
                                </tr>

                                <tr>
                                    <th>
                                        Personnel Contact:
                                    </th>

                                    <td>{{ $owner->operator_name.' ('.$owner->operator_position.') - '.$owner->operator_phone }}</td>
                                </tr>
                            </table>
                            @else
                            <table class="table table-sm mb-0 border-bottom">
                                <tr>
                                    <th>
                                        Client Name:
                                    </th>

                                    <td>{{ $owner->name.' (Agent)' }}</td>
                                </tr>

                                <tr>
                                    <th>
                                        Agent Contacts:
                                    </th>

                                    <td>{{ $owner->email.', '.$owner->phone }}</td>
                                </tr>

                                <tr>
                                    <th>
                                        Client:
                                    </th>

                                    <td>{{ '' }}</td>
                                </tr>
                            </table>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="col-md-4">

        <div class="card border mb-3">
            <div class="card-body bg-dark py-3 rounded-top">
                <h5 class="font-weight-600 card-title mb-0 text-white">About Advert</h5>
            </div>
            <div class="card-body py-3">

                <p>
                    {{ $advert->description }}
                </p>

                <div class="mb-4">
                    <table class="table table-sm border-bottom mb-0">
                        <tr>
                            <th>Category:</th>
                            <td>{{ $advert->category_name }}</td>
                        </tr>

                        <tr>
                            <th>Time:</th>
                            <td>{{ $advert->time }}</td>
                        </tr>
                    </table>
                </div>

                <div class="">
                    <h5 class="font-weight-600">Booked Slots</h5>

                    @php
                        $total_price = 0;
                    @endphp

                    @foreach($advert->slot_groups as $group)
                    <div class="mb-3 pt-3 pb-2 border rounded-lg px-3 bg-white">
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
        </div>

    </div>

    <div class="col-md-3">
        <div class="" style="bordertop: 5px solid #09a53085; border-radius: 8px 8px .25rem .25rem">
            <div class="card-bodpy-3">

                <div>
                    <h4 class="font-weight-600">Content Approval</h4>
                    @if($advert->isApproved())
                    <div class="mb-3">
                        This advert has been approved. After payment, it will be available for airing
                    </div>

                    <div>
                        <h4 class="font-weight-600 mb-2">Payment Status</h4>
                        <p class="mt-0 mb-2">
                            View the payment status for this advert
                        </p>
                        <a href="{{ route('admin.clients.invoices.single', $advert->invoice->number) }}">View Invoice</a>
                    </div>
                    @elseif($advert->isRejected())
                    This advert has been declined. Client has already been notified
                    @else
                    <p class="text-justify">
                        Content has not been approved. Hit the approve button if the content satisfies all requirements. The system will notify the client to pay for what they booked. Click the decline button to decline content and specify a reason we can send to the client
                    </p>

                    <div>
                        <form method="post" class="mb-3" action="{{ route('admin.adverts.approve', $advert->id) }}">
                            @csrf
                            <button class="btn btn-block btn-success shadow-none py-2"><i class="fa fa-check-circle mr-1"></i>Approve Ad</button>
                        </form>
                        <button data-toggle="modal" data-target="#reject_ad" class="btn btn-block btn-outline-danger shadow-none py-2"><i class="fa fa-times mr-1"></i>Decline Ad</button>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>

<style>
    textarea[name=reason]{
        background-color: #eee;
        border-color: #eee;
    }
</style>

<div class="modal fade" id="reject_ad" data-backdrop="static">
    <form action="{{ route('admin.adverts.reject', $advert->id) }}" method="POST" class="modal-dialog modal-dialog-centered modal-sm">
        @csrf
        <div class="modal-content">

            <div class="modal-header d-flex align-items-center bg-success py-3">
                <h4 class="modal-title mb-0 text-white">Reject Ad Content</h4>
                <span class="close text-white" data-dismiss="modal"><i class="fa fa-times"></i></span>
            </div>

            <div class="modal-body">

                <p>
                    Please specify a reason why the submitted content cannot be aired
                </p>

                <div class="mb-4">
                    <textarea name="reason" rows="3" class="form-control">{{ old('reason') }}</textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-warning btn-block mb-3 shadow-none">Continue</button>
                    <button type="button" class="btn btn-white btn-block shadow-none" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection
