@extends('admin.admin')

@section('title', 'Registered Users')

@section('content')

<?php $current_route = \Illuminate\Support\Facades\Route::currentRouteName(); ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Registered Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a>Users</a></li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
    

    <div class="card">
        <div class="card-header bg-primary">
        <h3 class="card-title text-white">App Registered users</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="dataTables_wrapper dt-bootstrap4">
            
                <div class="row">

                <div class="col-12 col-md-12 col-lg-8 mb-4 mt-2">
                    <form method="get" class="d-flex">
                        <div class="input-group">
                            <input class="form-control" value="{{ $search }}" placeholder="Search..." name="s" required>
                            <select class="form-control" name="s_by">
                                <option value="phone" @if($search_by == 'phone'){{ __('selected') }}@endif >Phone No</option>
                                <option value="email" @if($search_by == 'email'){{ __('selected') }}@endif >Email</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary">Search</button>
                            </div>
                        </div>

                        @if(isset($_GET['s']) || isset($_GET['s_by']))
                        <a href="{{ route('app_users') }}" class="btn btn-danger ml-3">Clear</a>
                        @endif
                    </form>
                </div>

                <div class="col-sm-12 table-responsive">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline collapsed" role="grid" aria-describedby="example2_info">
                        <thead>
                        <tr role="row">
                        <th style="width: 35px">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Total Ads</th>
                        <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = $from; ?>
                        @foreach($users as $user)
                        <tr role="row" class="odd parent">
                        <td>{{ $i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->ads }}</td>
                        <td class="text-center"><a href="{{ route('single_user', $user->email) }}" class="btn btn-sm btn-primary"><i class="mr-2 fa fa-bullhorn"></i>View Ads</a></td>
                        </tr>
                        <?php $i++; ?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="float-left">
                        <div class="dataTables_info">Showing {{ $from }} to {{ $to }} of {{ $total }} entries</div>
                    </div>

                    <div class="float-right ml-auto">
                        <div class="dataTables_paginate paging_simple_numbers">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous">
                                    <a href="{{ route($current_route, ['s'=>$search, 's_by'=>$search_by, 'page'=>$prev]) }}" class="page-link">Prev</a>
                                </li>
                                <li class="paginate_button page-item next">
                                    <a href="{{ route($current_route, ['s'=>$search, 's_by'=>$search_by, 'page'=>$next]) }}" class="page-link">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
        <!-- /.card-body -->
    </div>


    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->


@endsection