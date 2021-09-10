<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Registered clients')

@section('page_heading')
<i class="fa fa-user-circle text-success mr-3" style="font-size: .8em"></i>
Client Accounts
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

<form class="d-flex mb-3 pb-3 border-bottom" method="GET">
    <?php $request = request(); ?>

    <div class="mr-3">
        <input type="search" style="width: 350px" class="form-control" name="search" value="{{ $request->get('search') }}" placeholder="Official email, phone or company name..">
    </div>

    <div class="clearfix mr-3">
        <select name="status" class="nice-select">
            <option value="">Any Verification</option>
            <option value="verified" @if($request->get('status') == 'verified') selected @endif>Verified</option>
            <option value="pending" @if($request->get('status') == 'pending') selected @endif>Pending Verification</option>
            <option value="rejected" @if($request->get('status') == 'rejected') selected @endif>Rejected</option>
        </select>
    </div>

    <div class="clearfix mr-3">
        <select name="order" class="nice-select">
            <option value="0">Most Recent</option>
            <option value="1" @if($request->get('order') == 1) selected @endif>Oldest first</option>
            <option value="2" @if($request->get('order') == 2) selected @endif>Company name (A-Z)</option>
            <option value="3" @if($request->get('order') == 3) selected @endif>Company name (Z-A)</option>
        </select>
    </div>

    <div>
        <button class="btn btn-success shadow-none"><i class="fa fa-refresh mr-1"></i>Refresh</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table bg-white border">
        <tr class="bg-default text-white">
            <th class="text-center border-right">#</th>
            <th>Company Name</th>
            <th>Date Registered</th>
            <th>Total Ads</th>
            <th>Verification</th>
            <th></th>
        </tr>

        <?php $i = 1; ?>
        @foreach($clients as $client)
        <tr>
            <td class="text-center border-right">{{ $i++ }}</td>
            <td>{{ $client->name }}</td>
            <td>{{ $client->date }}</td>
            <td>{{ $client->adverts_count }}</td>
            <td>
                @if($client->isVerified())
                <span class="d-inline-flex align-items-center text-success">
                    <img class="mr-1" src="{{ asset('img/icons/verified_green.svg') }}" style="height: 15px; width: 15px">Verified
                </span>
                @elseif($client->isRejected())
                <span class="d-inline-flex align-items-center text-danger">
                    <i class="mr-1 fa fa-times" style="font-size: .9em"></i>Rejected
                </span>
                @else
                <span class="d-inline-flex align-items-center text-primary">
                    <i class="mr-1 fa fa-clock-o" style="font-size: .9em"></i>Pending
                </span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.clients.single', $client->email) }}" class="mr-3"><i class="fa fa-user-circle mr-1"></i>View Client</a>
                <a href="{{ route('admin.adverts', ['client' => $client->id]) }}" class="mr-3"><i class="fa fa-bullhorn mr-1"></i>View Adverts</a>
            </td>
        </tr>
        @endforeach

        @if(count($clients) == 0)
        <tr>
            <td colspan="6">
                <p class="lead my-0">
                    There are no users registered on the website or app yet. Once clients begin creating accounts, they will appear on this page
                </p>
            </td>
        </tr>
        @else
        <tr>
            <td colspan="6">
                <div class="d-flex align-items-center">
                    <?php
                        $current = \Illuminate\Support\Facades\Route::current();
                    ?>
                    @if($prev_page != null)
                        <a href="{{ route($current->getName(), array_merge($current->parameters(), [
                            'page' => $prev_page
                        ], $request->except('page'))) }}" class="mr-auto btn btn-link py-0 shadow-none py-2"><i class="fa fa-angle-double-left mr-1"></i>Prev</a>
                    @endif

                    <span>Page {{ $current_page }} of {{ $pages }}</span>

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
