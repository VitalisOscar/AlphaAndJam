@extends('web.master')

@section('title', 'Set a new password')

@section('body')

<style>
    @media(max-width: 576px){
        .auth-card{
            border: none !important;
        }

        body{background: #fff;}
    }
</style>

<section class="d-flex align-items-center justify-content-center" style="min-height: 100vh">

    <div class="auth-card rounded border px-4 pt-4 pb-4 bg-white" style="width: 350px; max-width: 100%">
        <div class="text-center mb-3">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" class="d-inline-block mx-auto mb-4" height="60px" alt="">
            </a>
            <p class="mb-2">Reset your password, {{ $user->name }}</p>
            <span class="d-inline-block px-5 bg-dark rounded" style="height: 3px"></span>
        </div>
        <form method="POST" autocomplete="off">
            @csrf

            @if(count($errors->all()) > 0)
            <div class="text-danger mb-2">{{ $errors->all()[0] }}</div>
            @endif

            <div class="form-group">
                <label class="mb-0"><strong>New Password:</strong></label><br>
                <small class="mb-2 d-inline-block">You'll be using this to log in to your account</small>
                <input class="form-control" name="new_password" type="password" value="{{ old('new_password') }}" required>
            </div>

            <div class="form-group">
                <label class="mb-0"><strong>Confirm Password:</strong></label><br>
                <small class="mb-2 d-inline-block">Re-type the new password here</small>
                <input class="form-control" name="confirm_password" type="password" value="{{ old('confirm_password') }}" required>
            </div>

            <div class="mb-4">
                <button class="btn btn-danger shadow-none btn-block">Save Password</button>
            </div>

            <div class="text-center" style="font-size: .9em">
                <div class="mb-2">
                    Go to <a href="{{ route('web.auth.login') }}">Log in</a>
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
