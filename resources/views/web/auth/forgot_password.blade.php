@extends('web.master')

@section('title', 'Reset password')

@section('body')

<style>
    @media(max-width: 576px){
        .auth-card{
            border: none !important;
        }

        body{background: #fff}
    }
</style>

<section style="min-height: 100vh" class="py-5 d-md-flex align-items-md-center justify-content-md-center">

    <div class="auth-card border rounded px-4 pt-4 pb-4 bg-white mx-auto" style="width: 350px; max-width: 100%">
        <div class="text-center mb-3">
            <img src="{{ asset('img/alphalogo.svg') }}" class="d-block mx-auto mb-4" height="60px" alt="">
            <p class="mb-2">Reset your Password</p>
            <span class="d-inline-block px-5 bg-dark rounded" style="height: 3px"></span>
        </div>
        <form method="POST">
            @csrf

            <p class="text-justify">
                We'll send you an email with a link you can follow to set a new password for your account
            </p>

            @if(count($errors->all()) > 0)
            <div class="text-danger mb-2">{{ $errors->all()[0] }}</div>
            @endif

            <div class="form-group">
                <label><strong>Account Email:</strong></label>
                <input class="form-control" placeholder="e.g info@companyx.com" name="email" type="email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <button class="btn btn-dark shadow-none btn-block">Send</button>
            </div>

            <div class="text-center" style="font-size: .9em">
                <div>
                    <a href="{{ route('web.auth.login') }}">Back to log in</a>
                </div>
            </div>
        </form>
    </div>

</section>

@endsection

@section('scripts')
@if(count($errors) > 0)
<script>showAlert('{{ $errors->first() }}', 'Error');</script>
@endif

@if(session()->has('status'))
<script>showAlert('{{ session()->get("status") }}', 'Alert');</script>
@endif

@endsection
