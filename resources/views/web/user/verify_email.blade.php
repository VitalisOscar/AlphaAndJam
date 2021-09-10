@extends('web.unauthenticated')

@section('title', 'Email verification')

@section('content')

<section class="d-flex align-items-center py-5" style="min-height: 80vh">
    <div class="container text-center">

        @if($status))
        <div class="mb-5 text-center">
            <img src="{{ asset('img/icons/email-verified.svg') }}" style="height: 120px; width: 120px" alt="">
        </div>

        <h2><strong>Verified</strong></h2>
        <p class="lead text-justify mx-auto" style="max-width: 500px">
            Your account email is now been verified. You can confirm this by checking verification status in <a href="{{ route('web.user.account') }}">your account</a>.
        </p>
        @else
        <div class="mb-5 text-center">
            <img src="{{ asset('img/icons/failure.svg') }}" style="height: 120px; width: 120px" alt="">
        </div>

        <h2><strong>Unable to Verify</strong></h2>
        <p class="lead text-justify mx-auto" style="max-width: 500px">
            Something went wrong and we are unable to verify your email at the moment. Please try again
        </p>
        @endif

        <div>
            <a href="{{ route('web.user.dashboard') }}" class="btn btn-primary py-2 shadow-none">Go to Dashboard</a>
        </div>
    </div>
</section>

@endsection
