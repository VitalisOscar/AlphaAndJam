@extends('web.base')

@section('title', config('app.name').' | '.$title)

@section('section_heading', $header)

@section('content')

    <div class="row">
        <div class="col-md-8">

            <p class="info">
                {{ $description }}
            </p>

            <div class="d-md-none mb-3">
                <div class="text-center">
                    <button type="button" onclick="$('.filters-sm').toggleClass('show')" class="btn btn-outline-success shadow-none btn-block py-2 mb-3"><i class="fa fa-filter mr-1"></i>Filter Results<i class="fa fa-caret-down ml-3"></i></button>
                </div>

                <div class="collapse border bg-white p-4 filters-sm">

                    @include('web.ads._ad_filters_form')

                </div>
            </div>

            @if($result['total'] == 0)
            <div>
                <p class="lead">
                    You do not have any ads in this section. Change some of your filters or create an ad to see it here
                </p>

                <div>
                    <a href="{{ route('web.adverts.create') }}" class="btn btn-link p-0"><i class="fa fa-plus mr-1"></i>Create An Ad</a>
                </div>
            </div>
            @endif

            <div class="row">
                @foreach($result['adverts'] as $ad)

                <article class="col-12 col-sm-6 mb-3">
                    <div class="bg-white rounded ad">
                        <div class="px-0">

                            @if($ad->content['media_path'] != null)
                            <div class="w-100 d-flex align-items-center h-100 bg-lighter">
                                <div class="embed-responsive embed-responsive-16by9 rounded-top">
                                    <div class="embed-responsive-item">
                                        <?php
                                            $media_path = asset('storage/'.$ad->content['media_path']);
                                        ?>
                                        @if($ad->content['media_type'] == 'image')
                                        <img src="{{ $media_path }}" class="img-fluid">
                                        @else
                                        <video controls muted src="{{ $media_path }}" class="img-fluid" type="video/*"></video>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                <i class="fa fa-video-camera text-muted" style="font-size: 1.3em"></i><br>
                                No media selected
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="px-3 py-3">
                            <h6><strong>{{ $ad->time }}</strong></h6>

                            @if($ad->isApproved())
                            <p class="description mb-1">
                                {{ $ad->description }}
                            </p>

                            <div class="mb-3 font-weight-700">
                                {{ 'KSh '.number_format($ad->invoice->totals['total']) }}
                            </div>
                            @else
                            <p class="description">
                                {{ $ad->description }}
                            </p>
                            @endif

                            <div class="mb-3">
                                <span class="mr-3">
                                    <i class="fa fa-bullhorn text-muted font-weight-bold mr-1"></i>{{ $ad->category_name }}
                                </span>

                                <span>
                                    <i class="fa fa-video-camera text-muted font-weight-bold mr-1"></i>{{ $ad->slots_count }} @if($ad->slots_count != 1){{ __('Slots') }}@else{{ __('Slot') }}@endif
                                </span>
                            </div>

                            <div class="d-flex">
                                @if($ad->isApproved())
                                @if($ad->invoice->isUnpaid())
                                <form action="{{ route('web.pesapal.make', $ad->invoice->number) }}" method="get" class="w-50">
                                    <input type="hidden" name="pay" value="1">
                                    <button class="btn btn-block btn-outline-dark btn-sm shadow-none mr-1"><i class="ni ni-money-coins mr-1"></i>Pay Now</button>
                                </form>
                                @else
                                <span class="w-50">
                                    <i class="ni ni-money-coins text-success"></i>&nbsp;Paid
                                </span>
                                @endif
                                @endif
                                <a href="{{ route('web.adverts.single', $ad->id) }}" class="ml-1 w-50 btn btn-default shadow-none btn-sm">View Details&nbsp;<i class="fa fa-share"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            @if($result['total'] > 0)
            <div class="pt-2 d-flex align-items-center">
                <?php
                    $current = \Illuminate\Support\Facades\Route::current();
                    $request = request();
                ?>
                @if($result['prev_page'] != null)
                    <a href="{{ route($current->getName(), array_merge($current->parameters(), [
                        'page' => $result['prev_page']
                    ], $request->except('page'))) }}" class="mr-auto btn btn-primary shadow-none py-2"><i class="fa fa-angle-double-left mr-1"></i>Prev</a>
                @endif

                <span>Page {{ $result['current_page'] }} of {{ $result['pages'] }}</span>

                @if($result['next_page'] != null)
                    <a href="{{ route($current->getName(), array_merge($current->parameters(), [
                        'page' => $result['next_page']
                    ], $request->except('page'))) }}" class="ml-auto btn btn-primary shadow-none py-2">Next<i class="fa fa-angle-double-right ml-1"></i></a>
                @endif
            </div>
            @endif

        </div>

        <div class="col-md-4 d-none d-md-block">
            <div class="border bg-white rounded p-4">

                <h4 class="font-weight-600"><i class="fa fa-filter mr-3"></i>Filters</h4>

                @include('web.ads._ad_filters_form')

            </div>
        </div>
    </div>

@endsection
