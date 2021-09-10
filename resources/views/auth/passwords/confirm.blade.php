@extends('web.master')

@section('body')

<section class="d-flex align-items-center justify-content-center h-100vh" style="background: #f2f4f6">

    <div class="border rounded px-4 pt-4 pb-4 bg-white" style="width: 350px; max-width: 100%">
        <div class="text-center mb-3">
            <h4 class="font-weight-600">{{ config('app.name') }}</h4>
            <p class="mb-2">Please confirm your password and redo the previous action</p>
            <span class="d-inline-block px-5 bg-success rounded" style="height: 3px"></span>
        </div>
        <form method="POST" action="{{ route('password.confirm') }}" autocomplete="off">
            @csrf

            @if(count($errors->all()) > 0)
            <div class="text-danger mb-2">{{ $errors->all()[0] }}</div>
            @endif

            <div class="form-group">
                <label><strong>Your Password:</strong></label>
                <input type="password" class="form-control" name="password" value="{{ old('password') }}" required>
            </div>

            <div class="mb-3">
                <button class="btn btn-success shadow-none btn-block">Continue</button>
            </div>

            <p class="mb-0 text-justify">You'll only need to confirm your password for such actions once for the next 2 hours</p>
        </form>
    </div>

</section>

@endsection
