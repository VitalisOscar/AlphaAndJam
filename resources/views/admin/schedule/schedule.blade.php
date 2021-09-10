<?php
$user = auth('staff')->user();
?>

@extends('admin.base')

@section('title', 'Airing Schedule')

@section('page_heading')
<i class="fa fa-calendar text-success mr-3" style="font-size: .8em"></i>
Schedule
@endsection

@section('content')

@php
    $packages = \App\Models\Package::all();
    $screens = \App\Models\Screen::all();
@endphp

<div class="d-flex align-items-center mb-3 pb-3 border-bottom">
    <div class="mr-3">
        <h4 class="mb-0">
            Slots
        </h4>
    </div>

    <form class="d-flex align-items-center mr-3 ml-auto" method="GET">
        <?php $request = request(); ?>

        <div class="ml-3 d-flex align-items-center ml-auto">
            <div class="clearfix mr-3">
                <select name="screen" class="nice-select" onchange="document.querySelector('#screen_post').selectedIndex = this.selectedIndex">
                    <option value="">Screen</option>
                    @foreach($screens as $s)
                    <option value="{{ $s->id }}" @if($request->get('screen') == $s->id) selected @endif>{{ $s->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="clearfix mr-3">
                <select name="package" class="nice-select" onchange="document.querySelector('#package_post').selectedIndex = this.selectedIndex">
                    <option value="">Package</option>
                    @foreach($packages as $p)
                    <option value="{{ $p->id }}" @if($request->get('package') == $p->id) selected @endif>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mr-3">
                <input type="date" oninput="document.querySelector('#date_post').value = this.value" name="date" value="{{ $request->get('date') }}" class="form-control">
            </div>

            <div class="">
                <button class="btn btn-success shadow-none"><i class="fa fa-video mr-1"></i>View Schedule</button>
            </div>
        </div>

    </form>

    <form action="{{ route('admin.schedule.download') }}" method="GET" class="">
        <div class="d-none">
            <select name="screen" id="screen_post">
                <option value=""></option>
                @foreach($screens as $s)
                <option value="{{ $s->id }}" @if($request->get('screen') == $s->id) selected @endif>{{ $s->title }}</option>
                @endforeach
            </select>

            <select name="package" id="package_post">
                <option value=""></option>
                @foreach($packages as $p)
                <option value="{{ $p->id }}" @if($request->get('package') == $p->id) selected @endif>{{ $p->name }}</option>
                @endforeach
            </select>

            <input type="text" name="date" id="date_post" value="{{ $request->get('date') }}">
        </div>

        <button class="btn btn-default shadow-none"><i class="fa fa-download mr-1"></i>Download</button>
    </form>

</div>

@if(count($adverts) > 0)
<div class="table-responsive">
    <table class="table bg-white border">
        <tr class="bg-default text-white">
            <th class="text-center border-right px-4">#</th>
            <td>Media</td>
            <th>About</th>
            <th>Client</th>
            <th>Status</th>
            <th></th>
        </tr>

        <?php $i = 1; ?>
        @foreach($adverts as $advert)
        <tr>
            <td class="text-center border-right">{{ $i++ }}</td>
            <td>
                @if($advert->hasImage())
                Image
                @elseif($advert->hasVideo())
                Video
                @else
                None
                @endif
            </td>
            <td>{{ $advert->description }}</td>
            <td>{{ $advert->user->name }}</td>
            @php
                $downloaded = (isset($advert->scheduled_slot->status['downloaded']) && $advert->scheduled_slot->status['downloaded']);
                $aired = (isset($advert->scheduled_slot->status['aired']) && $advert->scheduled_slot->status['aired']);
            @endphp
            <td>{{ ($downloaded ? 'Downloaded':'Not Downloaded') }}</td>

            <td>
                <a class="mr-3" href="{{ route('admin.adverts.single', $advert->id) }}">View Ad</a>
                @if(!$advert->hasNoMedia())
                <a href="{{ route('admin.schedule.download.single', ['slot'=>$advert->scheduled_slot->id]) }}">Download</a>
                @endif
            </td>
        </tr>
        @endforeach

    </table>
</div>

@php
    $now = \Carbon\Carbon::now();

    // End of airing for current package
    $ending = \Carbon\Carbon::createFromFormat('Y-m-d',$request->get('date'));
@endphp

<div class="mt-3">
    <h4><strong>Playback Comments</strong></h4>

    @if($now->isAfter($ending))
        @if($playback_comment == null)
        <p class="lead mt-0">
            Specify your comments about how media for these slots was played on screen <strong class="font-weight-600">{{ $screen->title }}</strong> on <strong>{{ $date }}</strong> for package <strong>{{ $package->name }}</strong>
        </p>

        <form class="d-flex align-items-center" method="POST" action="{{ route('admin.schedule.comments.add') }}">
            @csrf
            <input type="hidden" name="screen" value="{{ $request->get('screen') }}">
            <input type="hidden" name="package" value="{{ $request->get('package') }}">
            <input type="hidden" name="date" value="{{ $request->get('date') }}">

            <div class="mr-3">
                <select name="comment" class="custom-select" required>
                    <option value="">Select a comment</option>
                    @foreach(\App\Models\PlaybackComment::COMMENTS as $k=>$v)
                    <option value="{{ $k }}" @if(old('comment') == $k){{ __('selected') }}@endif>{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success shadow-none">Save</button>
        </form>
        @else
        <p class="lead mt-0">
            {{ (isset(\App\Models\PlaybackComment::COMMENTS[$playback_comment->comment]) ? \App\Models\PlaybackComment::COMMENTS[$playback_comment->comment] : $playback_comment->comment).' - Comment added on '.$playback_comment->time.' by '.$playback_comment->staff->name }}
        </p>
        @endif
    @else
    <p class="lead mt-0">
        You will be able to add playback comments after the package time has passed on the selected date.
    </p>
    @endif
</div>
@elseif($fetched)
<p class="lead">
    There are no ads that have been booked through {{ config('app.name') }} for {{ \App\Models\Screen::where('id', $request->get('screen'))->first()->title }}, under package {{ \App\Models\Package::where('id', $request->get('package'))->first()->name }} on {{ $request->get('date') }}
</p>
@else
<p class="lead">
    Select a screen, package and set a date to view ads to be played
</p>
@endif

@endsection
