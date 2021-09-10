<?php $user = auth('staff')->user(); ?>

@extends('admin.base')

@section('title', 'Manage Categories')

@section('page_heading')
<i class="ni ni-bullet-list-67 text-success mr-3" style="font-size: .8em"></i>
Ad Categories
@endsection

@section('content')

<div class="row">
    <div class="col-lg-8">

        <div class="d-flex align-items-center mb-3">
            <h4 class="font-weight-500">Existing Categories</h4>

            <form action="{{ route('admin.categories.export') }}" method="get" class="ml-auto">
                <button class="btn btn-default shadow-none"><i class="fa fa-download mr-1"></i>Export to Excel</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table bg-white border">
                <tr class="bg-default text-white">
                    <th class="text-center border-right">#</th>
                    <th>Name</th>
                    <th class="text-center">Total Ads</th>
                    <th></th>
                </tr>

                @if(count($categories) == 0)
                <tr>
                    <td colspan="4">
                        There are no categories yet. Start adding them and they'll appear here
                    </td>
                </tr>
                @endif

                <?php $i = 1; ?>
                @foreach($categories as $category)
                <tr>
                    <td class="text-center border-right">{{ $i++ }}</td>
                    <td>{{ $category->name }}</td>
                    <td class="text-center">{{ $category->adverts_count }}</td>
                    <td>
                        <a href="{{ route('admin.adverts', ['category' => $category->id]) }}" class="btn btn-link p-0 small mr-3"><i class="fa fa-bullhorn small mr-1"></i>View Ads</a>

                        @if($user->isAdmin())
                        <form action="{{ route('admin.categories.delete', $category->slug) }}" class="d-inline-block" method="POST">
                            @csrf
                            <button class="btn btn-link small p-0"><i class="fa fa-trash small mr-1"></i>Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="col-lg-4">
        @if($user->isAdmin())
        <form action="{{ route('admin.categories.add') }}" class="border rounded-lg p-4 bg-white" method="POST">
            @csrf
            <h4 class="mb-4">Add New</h4>

            <div class="form-group">
                <label class="mb-0" for="title"><strong>Name:</strong></label>
                <input type="text" name="name" class="form-control" placeholder="e.g Fashion and Fitness" required="required" @if($errors->has('status')) value="{{ old('name') }}" @endif>
            </div>

            <div>
                <button class="btn btn-success shadow-none btn-block">Save Category</button>
            </div>
        </form>
        @else
        <p class="lead my-0">
            You do not have full administrative privilleges hence you cannot add or manage categories
        </p>
        @endif
    </div>
</div>

@endsection
