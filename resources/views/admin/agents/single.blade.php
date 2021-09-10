<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Agent - '.$agent->name)

@section('page_heading')
<i class="fa fa-user-circle text-success mr-3" style="font-size: .8em"></i>
Manage Client - {{ $agent->name }}
@endsection

@section('content')

<style>
    i.circle{
        width: 35px;
        height: 35px;
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #eee;
    }

</style>

<div class="row">

    <div class="col-lg-4">
        <div class="border rounded bg-white">
            <div class="border-bottom bg-light p-3">
                Agent
            </div>

            <div class="p-3">John Doe</div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="card">
            <div class="card-header py-3 bg-primary">
                <h4 class="card-title text-white mb-0 text-primary">Account Information</h4>

            </div>
            <div>
                <table class="table mb-0 border">
                    <tr>
                        <td>
                            <i class="ni ni-building circle text-primary mr-2"></i>
                            <strong>Name:</strong>
                        </td>

                        <td>
                            {{ $agent->name }}<br>
                            <a href="{{ route('admin.clients.single.certificate', $agent->email) }}">View Certificate</a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa circle text-success fa-phone mr-2"></i>
                            <strong>Phone:</strong>
                        </td>

                        <td>
                            {{ $agent->business['phone'] }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa circle text-warning fa-envelope mr-2"></i>
                            <strong>Email:</strong>
                        </td>

                        <td>
                            {{ $agent->business['email'] }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="circle mr-3">
                                    <img style="height: 24px; width: 24px; border-radius: 50%" src="{{ asset('img/kra_logo.png') }}" alt="">
                                </i>
                                <strong>KRA Pin:</strong>
                            </div>
                        </td>

                        <td>
                            {{ $agent->business['kra_pin'] }}<br>
                            <a href="{{ route('admin.clients.single.kra_pin', $agent->email) }}">View KRA Pin</a>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa circle text-info fa-link mr-2"></i>
                            <strong>Website:</strong>
                        </td>

                        <td>
                            {{ isset($agent->business['website']) ? $agent->business['website'] : 'No website provided' }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa circle text-default fa-user-circle mr-2"></i>
                            <strong>User:</strong>
                        </td>

                        <td>
                            {{ $agent->user['name'].' - '.$agent->user['phone'] }}
                        </td>
                    </tr>
                </table>

            </div>
        </div>

    </div>

    <div class="col-md-6">

        <div class="mb-4">
            <h4 class="font-weight-600">Verification Status</h4>

            <p class="lead mt-0">
                @if($agent->isPending())
                This account is pending approval to submit ads through the {{ config('app.name') }} website or app
                @elseif($agent->isVerified())
                This account is verified and can submit ads through the {{ config('app.name') }} website or app
                @else
                This account has not been approved. Ads cannot be submitted through the {{ config('app.name') }} website or app
                @endif
            </p>

            <div>
                @if($agent->isRejected() || $agent->isPending())
                <form class="d-inline-block mr-3 mb-3" method="POST" action="{{ route('admin.clients.approve', $agent->email) }}">
                    @csrf
                    <button class="btn btn-outline-success py-2 shadow-none d-inline-flex align-items-center">
                        Verify Account
                    </button>
                </form>
                @endif

                @if($agent->isVerified() || $agent->isPending())
                <form class="d-inline-block mb-3" method="POST" action="{{ route('admin.clients.reject', $agent->email) }}">
                    @csrf
                    <button class="btn btn-outline-danger py-2 shadow-none">Reject Account</button>
                </form>
                @endif
            </div>
        </div>

        @if($agent->isVerified())
        <hr class="mb-3 mt-0">

        <div>
            <h4 class="font-weight-600">Payment Options</h4>

            @if($agent->canPayLater())
            <p class="lead mt-0">
                This client is a post paying client, with a limit of <strong>{{ $agent->post_pay_limit }} days</strong>. This means ads submitted by the client will be available for airing even before payment for their invoices is processed
            </p>

            <div>
                <form method="POST" action="{{ route('admin.clients.remove_post_pay', $agent->email) }}">
                    @csrf
                    <button class="btn btn-success shadow-none">
                        Remove from Post Pay Clients
                    </button>
                </form>
            </div>
            @else
            <p class="lead mt-0">
                Adding this client to post pay means their ads will be available for airing even before they pay for their invoices
            </p>

            <div>
                <button class="btn btn-success shadow-none" data-toggle="modal" data-target="#add-post-pay">
                    Add to Post Pay Clients
                </button>
            </div>
            @endif
        </div>
        @endif

    </div>

    <div class="col-12 mt-4 d-none">
        <h4>Activity</h4>
    </div>
</div>

@if(!$agent->canPayLater() && $agent->isVerified())
<div class="modal fade" id="add-post-pay">
    <div class="modal-dialog modal-dialog-centered modal-sm">

        <form action="{{ route('admin.clients.add_post_pay', $agent->email) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-body">

                <div class="text-center">
                    <div>
                        <span class="mb-4 bg-success d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 60px; width:60px">
                            <i class="ni ni-money-coins text-white" style="font-size: 2em"></i>
                        </span>
                    </div>
                    <h4 class="mb-3 modal-title font-weight-600">Add to Post Pay Clients</h4>
                </div>

                <p class="text-justify">
                    Add <strong class="font-weight-600">{{ $agent->name }}</strong> to post pay clients. Specify the limit number of days for payments from this client
                </p>

                <div>
                    <div class="form-group">
                        <input class="form-control" value="{{ old('limit') }}" name="limit" placeholder="e.g 30..." required>
                    </div>

                    <div class="text-center mb-3">
                        <button class="btn btn-block btn-primary shadow-none">Add Client</button>
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
