<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Screen - '.$screen->title)

@section('page_heading')
<i class="fa fa-tv text-success mr-3" style="font-size: .8em"></i>
Manage Screen - {{ $screen->title }}
@endsection

@section('content')

<style>
    .form-control, .nice-select{
        border-color: #eee;
        background: #eee;
        color: rgba(0,0,1,.7)
    }

    .pricing .form-control{
        text-align: right
    }
</style>

@if(!$user->isAdmin())
<p class="lead mt-0 mb-4 info">
    Please note that making changes to screens or adding new ones is disabled for non administrative accounts. You will only be able to view the screen in read only mode
</p>
@endif

<div class="row">
    <div class="col-md-7 col-lg-6">

        <form action="{{ route('admin.screens.edit', $screen->slug) }}" method="POST" class="card border">
            @csrf

            <div class="card-header py-3 bg-default rounded-top">
                <h4 class="font-weight-600 text-white card-title mb-0">Screen Overview</h4>
            </div>

            <div class="card-body">

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-3 d-flex align-items-">
                            <label><strong>Title:</strong></label>
                        </div>

                        <div class="col-md-9">
                            @if($user->isAdmin())
                            <input type="text" class="form-control" name="title" value="{{ old('title') != null ? old('title') : $screen->title }}">
                            @else
                            <input type="text" class="form-control" value="{{ $screen->title }}" disabled>
                            @endif
                            <small>Clients will see this title when booking slots</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="form-group mb-4">
                    <div class="form-row">
                        <div class="col-12 d-flex align-items-center">
                            <label class="custom-toggle mr-3">
                                <input type="checkbox" name="online" id="online" @if($screen->online) checked @endif @if(!$user->isAdmin()) disabled @endif>
                                <span class="custom-toggle-slider rounded-circle"></span>
                            </label>

                            <label for="online"><strong>Online Status</strong></label>
                        </div>
                    </div>

                    <small>Clients will not be able to book slots on this screen if you switch the toggle to off. Should only be done if screen is down or under maintenance</small>
                </div>

                @if($user->isAdmin())
                <div class="text-center">
                    <button class="btn btn-dark shadow-none">Save Changes</button>
                </div>
                @endif

            </div>
        </form>

        @if($user->isAdmin())
        <form class="cardborder mt-4" action="{{ route('admin.screens.delete', $screen->slug) }}" method="POST">
            @csrf
            <div class="card-bod">

                <h4 class="card-title font-weight-600 mb-2">Delete Screen</h4>

                <p class="mt-0">
                    Delete this screen from available screens. If deleted, it will not be possible to view this screen again or for clients to book slots on it.
                </p>

                <div>
                    <button class="btn btn-link p-0"><i class="fa fa-trash mr-1"></i>Delete Screen</button>
                </div>

            </div>
        </form>
        @endif

    </div>

    <div class="col-md-5 col-lg-6">
        <form action="{{ route('admin.screens.pricing', $screen->slug) }}" method="POST" class="card border">
            @csrf
            <div class="card-header bg-white rounded-top py-3">
                <h4 class="font-weight-600 text-whit card-title mb-0">Pricing per Package</h4>
            </div>

            <div class="py-3 px-4 border-bottom" style="background: #f6f9fc">
                <div class="form-row">
                    <div class="col-md-3">
                        <strong>Package</strong>
                    </div>
                    <div class="col-md-9">
                        <strong>Price</strong>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <?php $i = 0; ?>
                @foreach ($packages as $package)
                    <?php $prefix = 'prices['.$i.']'; ?>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-3 d-flex align-items-center">
                                <label><strong>{{ $package->name }}</strong></label>
                            </div>

                            <div class="col-md-9">
                                <input type="text" class="form-control" @if($user->isAdmin()) name="{{ $prefix.'[price]' }}" @else disabled @endif placeholder="Set New Price" value="{{ isset(old('prices')[$i]['price']) ? old('prices')[$i]['price'] : $package->price }}">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ $prefix.'[package_id]' }}" value="{{ $package->id }}">
                    <?php $i++; ?>
                @endforeach

            </div>

            @if($user->isAdmin())
            <div class="card-footer py-0 bg-white">
                <button class="btn btn-link btn-block py-3">Save Prices</button>
            </div>
            @endif
        </form>
    </div>
</div>

@endsection
