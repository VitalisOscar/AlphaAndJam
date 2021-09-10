@extends('admin.admin')

@section('title', 'Update your password')

@section('content')



<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Password Change</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a>Password</a></li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-default">
                <div class="card-header">
                <h3 class="card-title">Change your Password</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" method="post">
                @csrf

                <div class="card-body">
                    @if(isset($error))
                    <div class="alert alert-danger">
                    <strong>Error: </strong>{{ $error }}
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="exampleInputEmail1">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
                        <small>Enter the password that you currently use</small>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                        <small>Enter the new password</small>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-default">Save Changes</button>
                </div>
                </form>
            </div>
            <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@if(isset($message))
<script>
alert('{{ $message }}');
</script>
@endif

@endsection
