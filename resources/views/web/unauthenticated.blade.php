@extends('web.master')

@section('links')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    <style>
        body{background: #f2f5f8}
    </style>
@endsection

@section('body')

<header class="py-2 py-sm-3 bg-white border-bottom shadow-sm sticky-top" style="box-shadownone">
    <nav class="navbar py-0">
        <div class="container">

            <a href="{{ route('home') }}" class="navbar-brand">{{ config('app.name') }}</a>

            <div class="float-right d-flex align-items-center">
                <div class="links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('web.auth.login') }}">Log in</a>
                </div>

                <a class="btn btn-success shadow-none btn-rounded" href="{{ route('web.auth.signup') }}">Get Started</a>
            </div>
        </div>
    </nav>
</header>

@yield('content')

@endsection
