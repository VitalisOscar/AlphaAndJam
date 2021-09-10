<?php
$user = auth('staff')->user();

function capitalize($string){
    if($string == null || strlen($string) == 0) return null;

    $first = strtoupper(substr($string, 0, 1));

    if(strlen($string) == 1) return $first;

    $rest = substr_replace(strtolower($string), '', 0, 1);

    return $first.$rest;
}
?>

@extends('admin.base')

@section('title', 'Submitted Adverts')

@section('page_heading')
<i class="fa fa-bullhorn text-success mr-3" style="font-size: .8em"></i>
Adverts
@endsection

@section('content')

<style>
    .nice-select, .form-control, .btn:not(.btn-sm){
        height: 42px !important;
    }

    .nice-select, .form-control{
        border-color: #ddd;
    }
</style>

<div class="d-flex align-items-center mb-3 pb-3 border-bottom">
    <?php $request = request(); ?>

    <div class="mr-3">
        <h4 class="mb-0">
            {{ ($request->filled('status') ? capitalize($request->get('status')).' Ads' : 'All Ads').($request->filled('category') ? ' - '.$category->name : '') }}
        </h4>
    </div>

    <div class="ml-3 d-flex align-items-center ml-auto">
        <form method="GET" class="ml-3 d-flex align-items-center mr-3">
            @if($request->filled('client'))
            <input type="hidden" name="client" value="{{ $request->get('client') }}">
            @endif
            <div class="clearfix mr-3">
                <select name="status" class="nice-select">
                    <option value="">Any Status</option>
                    <option value="approved" @if($request->get('status') == 'approved') selected @endif>Approved</option>
                    <option value="rejected" @if($request->get('status') == 'rejected') selected @endif>Rejected</option>
                    <option value="pending" @if($request->get('status') == 'pending') selected @endif>Pending</option>
                </select>
            </div>

            <div class="clearfix mr-3">
                <select name="category" class="nice-select">
                    <option value="">Any Category</option>
                    @foreach(\App\Models\Category::all() as $c)
                    <option value="{{ $c->id }}" @if($request->get('category') == $c->id){{ __('selected') }}@endif >{{ $c->name }}</option>
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

        </form>

        <form action="{{ route('admin.adverts.export') }}" method="get">
            @foreach($request->all() as $k=>$d)
            <input type="hidden" name="{{ $k }}" value="{{ $d }}">
            @endforeach
            <button class="btn btn-default shadow-none"><i class="fa fa-download mr-1"></i>Export to Excel</button>
        </form>
    </div>

</div>

@if($request->filled('client'))
<div class="mb-3">
    @php
        $client = \App\Models\User::where('id', $request->get('client'))->first();
    @endphp

    @if($client)
    <h4 class="mb-0">{{ 'Client: '.$client->name }}</i>
    @endif
</div>
@endif

<div class="table-responsive">
    <table class="table bg-white border">
        <tr class="bg-default text-white">
            <th class="text-center border-right px-4">#</th>
            <th>Description</th>
            <th>Category</th>
            <th>Time</th>
            <th>Client</th>
            <th>Status</th>
            <th></th>
        </tr>

        <?php $i = 1; ?>
        @foreach($result->items as $advert)
        <tr>
            <td class="text-center border-right">{{ $i++ }}</td>
            <td>{{ $advert->description }}</td>
            <td>{{ $advert->category_name }}</td>
            <td>{{ $advert->time }}</td>
            <td>{{ $advert->user->name }}</td>
            <td>
                @if($advert->isRejected())
                Rejected
                @elseif($advert->isApproved())
                Approved
                @else
                Pending
                @endif
            </td>

            <td>
                <a href="{{ route('admin.adverts.single', $advert->id) }}" class="btn btn-sm btn-outline-primary shadow-none">View Advert</a>
            </td>
        </tr>
        @endforeach

        @if($result->total == 0)
        <tr>
            <td colspan="6">
                <p class="lead my-0">
                    No adverts found
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
