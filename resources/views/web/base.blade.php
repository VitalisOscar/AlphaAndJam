<?php
$route = \Illuminate\Support\Facades\Route::current();
$current_name = $route->getName();

$user = auth()->user();
// $user = $user ? $user:auth('agent')->user();
?>

@extends('web.master')

@section('body')
    <div class="bg-white sticky-top">

        <header class="navbar py-2 px-0 px-sm-3 section-shaped position-relative">

            <div class="shape shape-light position-absolute top-0 bottom-0 right-0 left-0 bg-dark shape-style-1">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="container-fluid">
                <button class="navbar-toggler text-white d-none d-md-inline-block d-lg-none mr-3" onclick="$('#sidenav').toggleClass('open')">
                    <i class="fa fa-bars"></i>
                </button>

                <a href="{{ route('home') }}" class="navbar-brand mr-auto">
                    {{ config('app.name') }}
                </a>

                <div class="ml-auto d-flex align-items-center">
                    <div class="d-none d-md-flex align-items-center">
                        <div class="links mr-3">
                            <a href="{{ route('web.user.dashboard') }}">Dashboard</a>
                            <a href="{{ route('web.presence') }}">View Presence</a>
                        </div>
                        <a href="{{ route('web.user.invoices') }}" class="btn btn-outline-white shadow-none py-2"><i class="fa fa-dollar mr-1"></i>Your Invoices</a>
                        <a href="{{ route('web.adverts.create') }}" class="btn btn-warning shadow-none py-2"><i class="fa fa-upload mr-1"></i>Upload an Ad</a>
                    </div>

                    <button class="navbar-toggler text-white d-md-none" onclick="$('#sidenav').toggleClass('open')">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>
    </div>

    <div class="container-fluid px-0 px-sm-3">
        <aside class="sidenav d-lg-block d-none" id="sidenav" onclick="if(event.target == this){ this.classList.remove('open') }">
            <div class="px-3 py-4">
                <div class="mb-3">
                    <div><strong>{{ $user->operator_name }}&nbsp;({{ $user->name }})</strong>
                    </div>
                    <div class="mb-2">{{ $user->email }}</div>
                    <div>
                        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-block shadow-none py-2"><i class="fa fa-power-off"></i> Sign Out</a>
                    </div>
                </div>

                <div class="sidenav-items mb-4">
                    <a href="{{ route('web.user.dashboard') }}" @if($current_name == 'web.user.dashboard') class="active"@endif ><i class="fa fa-user bg-default"></i>Dashboard</a>
                    <a href="{{ route('web.adverts.create') }}" @if($current_name == 'web.adverts.create') class="active"@endif ><i class="fa fa-upload bg-primary"></i>Upload Advert</a>
                    <a href="{{ route('web.adverts.drafts') }}" class="d-none @if($current_name == 'web.adverts.drafts') active @endif" >
                        <i class="fa fa-clock-o bg-warning"></i>Drafts
                        <?php
                            $drafts = \App\Models\Advert::where('user_id', auth()->id())->whereIn('status', [
                                \App\Models\Advert::STATUS_PENDING_PAYMENT,
                                \App\Models\Advert::STATUS_PAYMENT_FAILED
                                ])->count();
                        ?>
                        @if($drafts > 0)
                        <span class="float-right ml-auto badge badge-danger badge-pill">{{ $drafts }}</span>
                        @endif
                    </a>
                    <a href="{{ route('web.adverts.pending') }}" @if($current_name == 'web.adverts.pending') class="active"@endif ><i class="fa fa-clock-o bg-info"></i>Pending Approval</a>
                    <a href="{{ route('web.adverts.approved') }}" @if($current_name == 'web.adverts.approved') class="active"@endif ><i class="fa fa-check bg-success"></i>Approved Ads</a>
                    <a href="{{ route('web.adverts.declined') }}" @if($current_name == 'web.adverts.declined') class="active"@endif ><i class="fa fa-times bg-danger"></i>Declined Ads</a>
                    <a href="{{ route('web.user.account') }}" @if($current_name == 'web.user.account') class="active"@endif ><i class="fa fa-user bg-indigo"></i>My Account</a>
                    <a href="{{ route('web.user.invoices') }}" @if($current_name == 'web.user.invoices') class="active"@endif ><i class="fa fa-money bg-warning"></i>My Invoices</a>
                </div>
            </div>
        </aside>

        <section class="py-4 py-sm-5 px-3 px-lg-4 pl-xl-4 main">
            @hasSection ('section_heading')
            <h4 class="heading-title mb-3">@yield('section_heading')</h4>
            @endif

            <div>

                @yield('content')

            </div>
        </section>
    </div>

@endsection
