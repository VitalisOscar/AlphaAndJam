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
                The password reset link is either invalid or has already expired.
                Please request for a new one <a href="{{ route('web.auth.forgot_password') }}">here</a>. Alternatively, visit the <a href="{{ route('web.auth.login') }}">login page</a>.
            </p>
        </div>
    </section>

@endsection
