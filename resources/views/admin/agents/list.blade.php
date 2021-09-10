<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Agent Accounts')

@section('page_heading')
<i class="fa fa-handshake-o text-success mr-3" style="font-size: .8em"></i>
Agent Accounts
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

<div class="d-flex align-items-center mb-3 pb-3 border-bottom">
    <form class="d-flex" method="GET">
        <?php $request = request(); ?>

        <div class="mr-3">
            <input type="search" style="width: 350px" class="form-control" name="search" value="{{ $request->get('search') }}" placeholder="Email, phone or name...">
        </div>

        <div class="clearfix mr-3">
            <select name="status" class="nice-select">
                <option value="">Any Status</option>
                <option value="approved" @if($request->get('status') == 'verified') selected @endif>Active</option>
                <option value="rejected" @if($request->get('status') == 'rejected') selected @endif>Deactivated</option>
            </select>
        </div>

        <div class="clearfix mr-3">
            <select name="order" class="nice-select">
                <option value="default">Most Recent</option>
                <option value="past" @if($request->get('order') == 'past') selected @endif>Oldest first</option>
                <option value="az" @if($request->get('order') == 'az') selected @endif>Company name (A-Z)</option>
                <option value="za" @if($request->get('order') == 'za') selected @endif>Company name (Z-A)</option>
            </select>
        </div>

        <div>
            <button class="btn btn-success shadow-none"><i class="fa fa-refresh mr-1"></i>Refresh</button>
        </div>
    </form>

    <a href="{{ route('admin.agents.add') }}" class="btn btn-default ml-auto">
        <i class="fa fa-plus"></i>&nbsp;Add an Agent
    </a>
</div>

<div class="table-responsive">
    <table class="table bg-white border">
        <tr class="bg-primary text-white">
            <th class="text-center border-right">#</th>
            <th>Agent Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Added</th>
            <th>Total Ads</th>
            <th>Status</th>
            <th></th>
        </tr>

        <?php $i = 0; ?>
        @foreach($result->items as $agent)
        <tr>
            <td class="text-center border-right">{{ $result->from + $i }}</td>
            <td>{{ $agent->name }}</td>
            <td>{{ $agent->phone }}</td>
            <td>{{ $agent->email }}</td>
            <td>{{ $agent->time }}</td>
            <td>{{ $agent->adverts_count }}</td>
            <td>
                @if($agent->isVerified())
                <span class="badge badge-success">
                    Active
                </span>
                @else
                <span class="badge badge-danger">
                    Deactivated
                </span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.agents.single', $agent->id) }}" class="mr-3"><i class="fa fa-user-circle mr-1"></i>View Agent</a>
                <a href="{{ route('admin.adverts', ['client' => $agent->id]) }}" class="mr-3"><i class="fa fa-bullhorn mr-1"></i>View Adverts</a>
            </td>
        </tr>

        <?php $i++; ?>
        @endforeach

        @if($result->total == 0)
        <tr>
            <td colspan="8">
                <p class="lead my-0">
                    There are no agents that have been added matching the given options. Agents will appear on this page
                </p>
            </td>
        </tr>
        @else
        @php
            $route = \Illuminate\Support\Facades\Route::current();
            $prev = array_merge($route->parameters, $request->except('page'), ['page' => $result->prev_page]);
            $next = array_merge($route->parameters, $request->except('page'), ['page' => $result->next_page]);
        @endphp

        <tr>
            <td colspan="8">
                <div class="d-flex align-items-center">
                    <a href="{{ route($route->getName(), $prev) }}" class="@if(!$result->hasPreviousPage()){{ __('disabled') }}@endif mr-auto btn btn-link p-0"><i class="fa fa-angle-double-left"></i>&nbsp;Prev</a>
                    <span>{{ 'Page '.$result->page.' of '.$result->max_pages }}</span>
                    <a href="{{ route($route->getName(), $next) }}" class="@if(!$result->hasNextPage()){{ __('disabled') }}@endif ml-auto btn btn-link p-0">Next&nbsp;<i class="fa fa-angle-double-right"></i></a>
                </div>
            </td>
        </tr>
        @endif

    </table>
</div>

@endsection
