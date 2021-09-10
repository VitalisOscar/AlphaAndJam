<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Package - '.$package->name)

@section('page_heading')
<i class="ni ni-box-2 text-success mr-3" style="font-size: .8em"></i>
Manage Package - {{ $package->name }}
@endsection

@section('content')

<style>
    .form-control, .nice-select{
        border-color: #eee;
        background: #eee;
        color: rgba(0,0,1,.7)
    }
</style>

@if(!$user->isAdmin())
<p class="lead mt-0 mb-4 info">
    Please note that making changes to packages or adding new ones is disabled for non administrative accounts. You will only be able to view the package in read only mode
</p>
@endif

<div class="row">

    <div class="col-md-7 col-lg-6">
        <form class="card border" method="POST" action="{{ route('admin.packages.edit', $package->id) }}">
            @csrf
            <div class="card-header bg-default py-3 rounded-top">
                <h4 class="text-white card-title mb-0 font-weight-600">Package Overview</h4>
            </div>

            <div class="card-body">

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-3 d-flex align-items-center">
                            <label><strong>Name:</strong></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" @if($user->isAdmin()) name="name" @else disabled @endif value="{{ old('name') != null ? old('name') : $package->name }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-3 d-flex align-items-center">
                            <label><strong>Category:</strong></label>
                        </div>

                        <div class="col-md-9">
                            @if($user->isAdmin())
                            <div class="clearfix">
                                <select name="type" class="nice-select w-100">
                                    <option value="peak" @if($package->type == 'peak' || old('type') == 'peak') selected @endif>Peak</option>
                                    <option value="off_peak" @if($package->type == 'off_peak' || old('type') == 'off_peak') selected @endif>Off Peak</option>
                                </select>
                            </div>
                            @else
                            <input type="text" value="{{ $package->category }}" class="form-control" disabled="disabled">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-3 d-flex align-items-">
                            <label><strong>Clients:</strong></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" @if($user->isAdmin()) name="clients" @else disabled @endif value="{{ old('clients') != null ? old('clients') : $package->clients }}">
                            <small>This is the maximum number of clients to be served per screen for this package</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-3 d-flex align-items-">
                            <label><strong>Loops:</strong></label>
                        </div>

                        <div class="col-md-9">
                            <input type="text" class="form-control" @if($user->isAdmin()) name="loops" @else disabled @endif value="{{ old('loops') != null ? old('loops') : $package->loops }}">
                            <small>This is the approximate number of times a single ad will be played</small>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <div class="form-row">
                        <div class="col-md-3 d-flex align-items-">
                            <label><strong>Airing Time:</strong></label>
                        </div>

                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-5">
                                    <input type="text" placeholder="e.g 6" class="form-control" @if($user->isAdmin()) name="from" @else disabled @endif value="{{ old('from') != null ? old('from') : $package->plays_from }}">
                                </div>

                                <div class="col-2 d-flex align-items-center justify-content-center">
                                    to
                                </div>

                                <div class="col-5">
                                    <input type="text" placeholder="e.g 9" class="form-control" @if($user->isAdmin()) name="to" @else disabled @endif value="{{ old('to') != null ? old('to') : $package->plays_to }}">
                                </div>
                            </div>
                            <small>This is the time of day when ads in this package will be aired</small>
                        </div>
                    </div>
                </div>

            </div>

            @if($user->isAdmin())
            <div class="card-footer text-center bg-white">
                <button class="btn btn-dark shadow-none">Update Package Details</button>
            </div>
            @endif
        </form>
    </div>

    <div class="col-md-5 col-lg-6">
        <form method="POST" action="{{ route('admin.packages.pricing', $package->id) }}" class="pricing card border">
            @csrf
            <div class="card-header border-bottom-0 bg-white">
                <h4 class="font-weight-600 card-title mb-0">Package Pricing</h4>
            </div>

            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr class="bg-dark text-white">
                        <th><i class="fa fa-tv mr-2"></i>Screen</th>
                        <th><i class="ni ni-money-coins mr-2"></i>Price (KSh)</th>
                    </tr>

                    <?php $i = 0; ?>
                    @foreach ($screens as $screen)
                        <?php $prefix = 'prices['.$i.']'; ?>
                        <input type="hidden" name="{{ $prefix.'[screen_id]' }}" value="{{ $screen->id }}">
                        <tr>
                            <td style="vertical-align: middle">
                                <div>
                                    {{ $screen->title }}
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control" @if($user->isAdmin()) name="{{ $prefix.'[price]' }}" @else disabled @endif placeholder="Set New Price" style="" value="{{ isset(old('prices')[$i]['price']) ? old('prices')[$i]['price'] : $screen->price }}">
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach

                    @if(count($screens) == 0)
                    <tr>
                        <td colspan="2">
                            <p class="lead my-0">
                                There are no screens that have been added to the system. Go to <a href="{{ route('admin.screens') }}">screens</a> to add a new screen and come back to set prices for this package
                            </p>
                        </td>
                    </tr>
                    @endif

                </table>
            </div>

            @if(count($screens) > 0 && $user->isAdmin())
            <div class="card-footer text-center bg-white py-0">
                <button class="btn btn-link px-0 py-3 btn-block">Save Pricing Data</button>
            </div>
            @endif
        </form>
    </div>

</div>

@endsection
