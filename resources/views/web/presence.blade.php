
@extends('web.base')

@section('title', config('app.name').' | Presence')

{{-- @section('section_heading', 'Our Screens') --}}

@section('content')

<div class="mb-4">
    <h4 class="font-weight-600">1. Kimathi Street</h4>

    <div class="row">
        <div class="mb-3 col-md-4">
            <div class="embed-responsive embed-responsive-16by9">
                <div class="embed-responsive-item">
                    <img src="{{ asset('img/undraw_mobile_app.svg') }}" class="img-fluid rounded-lg" alt="">
                </div>
            </div>
        </div>

        <div class="mb-3 col-md-4">
            <div class="embed-responsive embed-responsive-16by9">
                <div class="embed-responsive-item">
                    <img src="{{ asset('img/banner.jpeg') }}" class="img-fluid rounded-lg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <h4 class="font-weight-600">2. Haile Sellasie</h4>

    <div class="row">
        <div class="mb-3 col-md-4">
            <div class="embed-responsive embed-responsive-16by9">
                <div class="embed-responsive-item">
                    <img src="{{ asset('img/undraw_mobile_app.svg') }}" class="img-fluid rounded-lg" alt="">
                </div>
            </div>
        </div>

        <div class="mb-3 col-md-4">
            <div class="embed-responsive embed-responsive-16by9">
                <div class="embed-responsive-item">
                    <img src="{{ asset('img/banner.jpeg') }}" class="img-fluid rounded-lg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
