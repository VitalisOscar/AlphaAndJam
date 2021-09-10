<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Packages')

@section('page_heading')
<i class="ni ni-box-2 text-success mr-3" style="font-size: .8em"></i>
Packages
@endsection

@section('content')

<h4 class="font-weight-500">Available Packages</h4>

<div class="row">
    <div class="col-lg-8">
        <div class="table-responsive">
            <table class="table bg-white border">
                <tr class="bg-default text-white">
                    <th class="text-center border-right">#</th>
                    <th>Package Name</th>
                    <th>Category</th>
                    <th>Airing Time</th>
                    <th>Clients</th>
                    <th>Loops</th>
                    <th></th>
                </tr>

                @if(count($packages) == 0)
                <tr>
                    <td colspan="4">
                        There are no packages added. Start adding them and they'll appear here
                    </td>
                </tr>
                @endif

                <?php $i = 1; ?>
                @foreach($packages as $package)
                <tr>
                    <td class="text-center border-right">{{ $i++ }}</td>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->category }}</td>
                    <td>{{ $package->summary }}</td>
                    <td>{{ $package->clients }}</td>
                    <td>{{ $package->loops }}</td>
                    <td>
                        @if($user->isAdmin())
                        <a href="{{ route('admin.packages.manage', $package->id) }}">Manage Package</a><br>
                        @else
                        <a href="{{ route('admin.packages.manage', $package->id) }}">View Package</a><br>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="col-lg-4">

        <div class="mb-3">
            <h4 class="mb-0"><strong>Pricing</strong></h4>

            <p class="lead mb-2 mt-1">
                You can set or view the prices for an ad for each package per screen. You can also do so on a screen by screen basis
            </p>

            <div>
                <a href="{{ route('admin.screens') }}"><i class="fa fa-tv mr-1"></i>Go to Screens</a>
            </div>
        </div>

        @if($user->isAdmin())
        <div>
            <h4 class="mb-1"><strong>Create a Package</strong></h4>
            <p class="lead my-0">Add a new package to the existing ones</p>
            <a class="text-primary" style="cursor: pointer" onclick="$('#new_package').modal({backdrop: 'static'})"><i class="fa fa-plus mr-1"></i>Add Package</a>
        </div>
        @endif

    </div>
</div>

<div class="modal fade" id="new_package">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-header py-3 d-flex align-items-center">
                <h4 class="modal-title mb-0">Create a Package</h4>
                <span class="close" data-dismiss="modal"><i class="fa fa-times"></i></span>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('admin.packages.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Package Name:</strong></label>
                        <input type="text" name="name" placeholder="e.g Gold" class="form-control" @if($errors->has('status')) value="{{ old('name') }}" @endif>
                    </div>

                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Category:</strong></label>
                        <div class="clearfix">
                            <select name="type" class="nice-select w-100">
                                <option value="peak">Peak</option>
                                <option value="off_peak">Off Peak</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Clients:</strong></label>
                        <input type="number" name="clients" placeholder="e.g 15" class="form-control" min="1" @if($errors->has('status')) value="{{ old('clients') }}" @endif>
                        <small>Enter the maximum number of clients to serve for this package</small>
                    </div>

                    <div class="form-group">
                        <label for="" class="mb-0"><strong>Airing Time:</strong></label>
                        <div class="form-row">
                            <div class="col-6">
                                <input type="number" placeholder="From" name="from" class="form-control" min="0" max="23" @if($errors->has('status')) value="{{ old('from') }}" @endif>
                            </div>

                            <div class="col-6">
                                <input type="number" placeholder="To" name="to" class="form-control" min="0" max="23" @if($errors->has('status')) value="{{ old('to') }}" @endif>
                            </div>
                        </div>
                        <small>This is the time in between where ads in this package will be aired</small>
                    </div>

                    <div>
                        <button class="btn btn-dark btn-block shadow-none">Save Package</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection
