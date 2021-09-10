<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Screens')

@section('page_heading')
<i class="fa fa-tv text-success mr-3" style="font-size: .8em"></i>
Screens
@endsection

@section('content')

<h4 class="font-weight-500">Advertising Screens</h4>

<div class="row">
    <div class="col-lg-8">
        <div class="table-responsive">
            <table class="table bg-white border">
                <tr class="bg-default text-white">
                    <th class="text-center border-right">#</th>
                    <th>Screen</th>
                    <th>Status</th>
                    <th></th>
                </tr>

                @if(count($screens) == 0)
                <tr>
                    <td colspan="4">
                        There are no screens added. Start adding them and they'll appear here
                    </td>
                </tr>
                @endif

                <?php $i = 1; ?>
                @foreach($screens as $screen)
                <tr>
                    <td class="text-center border-right">{{ $i++ }}</td>
                    <td>{{ $screen->title }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($screen->online)
                            <i class="fa fa-circle text-success mr-1" style="font-size: .6em"></i>Online
                            @else
                            <i class="fa fa-circle text-danger mr-1" style="font-size: .6em"></i>Offline
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($user->isAdmin())
                        <a href="{{ route('admin.screens.single', $screen->slug) }}" class="btn btn-link p-0 small mr-3"><i class="fa fa-tv small mr-1"></i>Manage Screen</a>
                        @else
                        <a href="{{ route('admin.screens.single', $screen->slug) }}" class="btn btn-link p-0 small mr-3"><i class="fa fa-tv small mr-1"></i>View Screen</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        @if($user->isAdmin())
        <form action="{{ route('admin.screens.add') }}" class="border rounded-lg p-4 bg-white" method="POST">
            @csrf
            <h4 class="mb-4">Add Screen</h4>

            <div class="form-group">
                <label class="mb-0" for="title"><strong>Title:</strong></label>
                <input type="text" name="title" class="form-control" placeholder="e.g Kimathi Street" required="required" @if($errors->has('status')) value="{{ old('title') }}" @endif>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="online" id="online" class="custom-control-input" @if($errors->has('status') && old('online') == 'on'){{ __('checked') }} @endif>
                    <label class="mb-0 custom-control-label" for="online">
                        <span>Mark as online</span>
                    </label>
                </div>
            </div>

            <hr class="my-3">

            <div>
                <button class="btn btn-dark shadow-none btn-block">Save Screen</button>
            </div>
        </form>
        @else
        <p class="lead my-0">
            You do not have full administrative privilleges hence you cannot add or manage screens
        </p>
        @endif
    </div>
</div>

@endsection
