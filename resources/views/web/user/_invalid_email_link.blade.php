@extends('web.unauthenticated')

@section('title', 'Link unusable')

@section('content')

    <section class="d-flex align-items-center py-5" style="min-height: 80vh">
        <div class="container text-center">
            <div class="mb-5 text-center">
                <img src="{{ asset('img/icons/broken-link.svg') }}" style="height: 120px; width: 120px" alt="">
            </div>

            <h2><strong>Link Unusable</strong></h2>

            <p class="lead text-justify mx-auto" style="max-width: 500px">
                The email verification link is either invalid or has already expired.
                Please request for a new one by visiting <a href="{{ route('web.user.account') }}">your account</a> under the verification status section
            </p>
        </div>
    </section>

@endsection
