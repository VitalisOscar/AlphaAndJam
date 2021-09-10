<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Staff Activity')

@section('page_heading')
<i class="fa fa-history text-success mr-3" style="font-size: .8em"></i>
Staff Activity
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

<form class="d-flex align-items-center mb-3 pb-3 border-bottom" method="GET">
    <?php $request = request(); ?>

    <div class="mr-3">
        <h4 class="mb-0">Logs</h4>
    </div>

    <div class="ml-3 d-flex align-items-center ml-auto">
        <div class="clearfix mr-3">
            <select name="staff" class="nice-select">
                <option value="">All Accounts</option>
                @foreach(\App\Models\Staff::all() as $staff)
                <option value="{{ $staff->id }}" @if($request->get('staff') == $staff->id) selected @endif>{{ $staff->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="clearfix mr-3">
            <select name="category" class="nice-select">
                <option value="">Any Category</option>
                @foreach(\App\Models\StaffLog::CATEGORIES as $k=>$c)
                <option value="{{ $k }}" @if($request->get('category') == $k){{ __('selected') }}@endif >{{ $c }}</option>
                @endforeach
            </select>
        </div>

        <div class="clearfix mr-3">
            <select name="order" class="nice-select">
                <option value="recent">Most Recent</option>
                <option value="oldest" @if($request->get('order') == 'oldest') selected @endif>Oldest first</option>
            </select>
        </div>

        <div class="">
            <button class="btn btn-success shadow-none"><i class="fa fa-refresh mr-1"></i>Refresh</button>
        </div>
    </div>

</form>

<div class="table-responsive">
    <table class="table bg-white border">
        <tr class="bg-default text-white">
            <th class="text-center border-right px-4">#</th>
            <th>Account</th>
            <th>Activity</th>
            <th>Time</th>
            <th></th>
        </tr>

        <?php $i = 1; ?>
        @foreach($result->items as $log)
        <tr>
            <td class="text-center border-right">{{ $i++ }}</td>
            <td>{{ $log->account->name }}</td>
            <td style="max-width: 300px">{{ $log->activity }}</td>
            <td>{{ $log->time }}</td>
            <td>
                <a href="{{ route('admin.staff.activity.redirect', ['item' => $log->item, 'id' => $log->item_id]) }}">{{ $log->link_text }}</a>
            </td>
        </tr>
        @endforeach

        @if($result->total == 0)
        <tr>
            <td colspan="6">
                <p class="lead my-0">
                    All activity from existing staff accounts will be on this page
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
                    @if($result->prev_page != null)
                        <a href="{{ route($current->getName(), array_merge($current->parameters(), [
                            'page' => $result->prev_page
                        ], $request->except('page'))) }}" class="mr-auto btn btn-link py-0 shadow-none py-2"><i class="fa fa-angle-double-left mr-1"></i>Prev</a>
                    @endif

                    <span>Page {{ $result->page }} of {{ $result->max_pages }}</span>

                    @if($result->next_page != null)
                        <a href="{{ route($current->getName(), array_merge($current->parameters(), [
                            'page' => $result->next_page
                        ], $request->except('page'))) }}" class="ml-auto btn btn-link py-0 shadow-none py-2">Next<i class="fa fa-angle-double-right ml-1"></i></a>
                    @endif
                </div>
            </td>
        </tr>
        @endif

    </table>
</div>

@endsection
