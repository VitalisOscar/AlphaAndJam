@extends('web.base')

@section('title', 'Business Unverified')

@section('section_heading', 'Business Unverified')

@section('content')

    <section class="d-flexalign-items-centerpy-5">
        <div class="">
            <div class="mb-5 text-center d-none">
                <img src="{{ asset('img/icons/verified.svg') }}" style="height: 120px; width: 120px" alt="">
            </div>

            <p class="lead text-justify">
                You need to verify your business email and official phone number from 'My Account' section.
                Additionally, we need to verify your business using the info you signed up with before we can let you submit ads to us or manage them.
                Check your verification status in your account
            </p>

            <div>
                <a href="{{ route('web.user.account') }}" class="btn btn-primary shadow-none">Go to Account</a>
            </div>
        </div>
    </section>

@endsection
