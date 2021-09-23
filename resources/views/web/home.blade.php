@extends('web.master')

@section('title', config('app.name').' | Simple digital outdoor advertising')

@section('links')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('body')

<style>
    body{
        background: #fafafa !important;
    }
</style>

<header class="py-3 bg-white shadow-sm sticky-top">
    <nav class="navbar py-0">
        <div class="container d-block d-sm-flex">

            <div class="d-flex align-items-center">
                <a href="{{ route('home') }}" class="navbar-brand mr-auto">
                    <img style="height: 50px" src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}">
                </a>

                <button class="navbar-toggler border border-danger d-sm-none" onclick="$('#menu').toggleClass('d-none'); $('.navbar-toggler i').toggleClass('fa-bars fa-times');">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <div class="py-3 py-sm-0 float-sm-right d-none d-sm-flex align-items-center" id="menu">
                <div class="links pt-3 pb-2 pt-sm-0 pb-sm-0">
                    <a href="{{ route('web.auth.login') }}" class="active">Home</a>
                    <a href="{{ route('web.auth.login') }}">Log in</a>
                </div>

                <a class="btn btn-outline-danger shadow-none btn-rounded cfa-btn" href="{{ route('web.auth.signup') }}">Get Started</a>
            </div>
        </div>
    </nav>
</header>

<section class="section-shaped" style="">

    <div class="shape shape-style-2" style="background: linear-gradient(225deg, #87ceeb, #87ceeb95, #0000, #0000, #0000, #87ceeb20)">
        <span>
            <svg class="clouds cloud1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="128" height="128" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path fill="#eee" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
            </svg>
        </span>

        <span>
            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="192" height="192" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path fill="#eee" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
            </svg>
        </span>

        <span>
            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="192" height="192" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path fill="#eee" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
            </svg>
        </span>

        <span>
            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="128" height="128" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path fill="#eee" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
            </svg>
        </span>

        <span>
            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="128" height="128" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                <path fill="#eee" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
            </svg>
        </span>
    </div>

    <div class="hero-content">
        <div class="container">

            <div class="row">

                <div class="col-lg-6 d-flex align-items-center">

                     <div>
                        <h1 class="hero-title"><span class="text-danger">Simple</span> Digital Outdoor <span class="text-dar">Advertising</span></h1>

                        <p class="lead">
                            {{ config('app.name') }} has been built to enable your business and products gain more visibility through digital outdoor advertising.
                            From a single dashboard on your phone or computer, submit and manage adverts in a few clicks
                        </p>

                        <div>
                            <a href="#about" class="mb-3 btn btn-lg btn-outline-dark shadow-none">Learn More</a>
                            <a href="{{ route('web.auth.signup') }}" class="mb-3 btn btn-lg btn-danger shadow-none">Get Started</a>
                        </div>
                     </div>

                </div>

                <div class="col-lg-6">
                    <img src="{{ asset('img/hero_vector.png') }}" class="img-fluid">
                </div>
            </div>

        </div>
    </div>
</section>

<section id="about" class="py-5 bg-white">
    <div class="container py-lg-3">

        <div class="row">



            <div class="col-lg-8">
                <h1 class="section-heading">What is {{ config('app.name') }}?</h1>

                <p class="lead">
                    {{ config('app.name') }} is a platform designed to help your business have it's digital adverts on our outdoor screens, in a simple fashion, eliminating the need for you to leave your office to do the same
                </p>

                <div>
                    <a href="{{ route('web.auth.signup') }}" class="px-4 btn btn-lg btn-primary shadow-none">Get Started</a>
                </div>
            </div>

            <div class="col-lg-4">

            </div>

        </div>

    </div>
</section>


<section id="hiw" class="py-5 section-shaped hiw">
    <div class="shape shape-light bg-gradient-danger shape-style-1">
        <span class="span-200"></span>
        <span class="span-100"></span>
        <span class="span-150"></span>
    </div>


    <div class="container py-lg-3">

        <div class="mb-5 text-white">
            <h1 class="section-heading text-white">How it Works</h1>

            <p class="lead my-0" style="font-size: 1.5em">{{ config('app.name') }} has been tailored to serve its purpose in simple easy steps, letting you dedicate more time on your business rather than running adverts</p>
        </div>

        <div class="row">

            <div class="col-lg-6 hiw-item mb-5">
                <div class="d-sm-flex align-items-cente">

                    <span class="hiw-circle">1</span>

                    <div class="ml-sm-4">
                        <h4 class="mb-2 text-white"><strong>Create an Account</strong></h4>

                        <p class="text-white my-0">Create a free account with your personal and business info, or log in if you are already registered</p>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 hiw-item mb-5">
                <div class="d-sm-flex align-items-cente">

                    <span class="hiw-circle">2</span>

                    <div class="ml-sm-4">
                        <h4 class="mb-2 text-white"><strong>Submit your Ads</strong></h4>

                        <p class="text-white my-0">Creating an ad will only take you a few minutes. Once approved, you can complete payment and wait for airing</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 hiw-item">
                <div class="d-sm-flex align-items-cente">

                    <span class="hiw-circle">3</span>

                    <div class="ml-sm-4">
                        <h4 class="mb-2 text-white"><strong>Increase your visibility</strong></h4>

                        <p class="text-white my-0">Once your advert is received and approved by us, it will be advertised on our screens to hundreds of thousands of potential clients</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<section class="py-5 bg-white">

    <div class="container">

        <h1 class="section-heading mb-4 d-none">Why Choose {{ config('app.name') }}?</h1>

        <div class="row">

            <div class="col-lg-4">
                <div class="pt-5 h-100 px-3 position-relative" style="padding-bottom: 100px">
                    <div class="text-center mb-3">
                        <span class="icon icon-shape" style="width: 70px; height: 70px">
                            <img src="{{ asset('img/icons/fast.svg') }}">
                        </span>
                    </div>

                    <h4 class="text-center"><strong>Fast and Simple</strong></h4>

                    <p class="lead text-justify">
                        Send your advert content in a few clicks, pay instantly and be done in a few minutes, and head back to your other tasks
                    </p>

                    <div class="position-absolute bottom-0 text-center left-0 right-0 pb-5">
                        <a href="{{ route('web.auth.signup') }}" class="btn btn-outline-danger shadow-none btn-lg px-4 btn-rounded">Get Started</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="pt-5 h-100 px-3 position-relative" style="padding-bottom: 100px">
                    <div class="text-center mb-3">
                        <span class="icon icon-shape" style="width: 70px; height: 70px">
                            <img src="{{ asset('img/icons/dollar-tag.svg') }}">
                        </span>
                    </div>

                    <h4 class="text-center"><strong>Flexible Pricing</strong></h4>

                    <p class="lead text-justify">
                        We have friendly pricing plans tailored for everyone, with different factors in mind, such as the duration of your ad and time of airing the ad
                    </p>

                    <div class="position-absolute bottom-0 text-center left-0 right-0 pb-5">
                        <a href="{{ route('web.auth.signup') }}" class="btn btn-outline-danger shadow-none btn-lg px-4 btn-rounded">Get Started</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="pt-5 h-100 px-3 position-relative" style="padding-bottom: 100px">
                    <div class="text-center mb-3">
                        <span class="icon icon-shape" style="width: 70px; height: 70px">
                            <img src="{{ asset('img/icons/handshake.svg') }}">
                        </span>
                    </div>

                    <h4 class="text-center"><strong>Trusted by Many</strong></h4>

                    <p class="lead text-justify">
                        We are a trusted, leading digital advertising company, with a presence in multiple countries and many businesses using our services
                    </p>

                    <div class="position-absolute bottom-0 text-center left-0 right-0 pb-5">
                        <a href="{{ route('web.auth.signup') }}" class="btn btn-outline-danger shadow-none btn-lg px-4 btn-rounded">Get Started</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-5 section-shaped d-none">
    <div class="shape shape-light bg-gradient-success shape-style-1">
        <span class="span-200"></span>
        <span class="span-100"></span>
        <span class="span-50"></span>
        <span class="span-75"></span>
        <span class="span-100"></span>
        <span class="span-200"></span>
        <span class="span-50"></span>
        <span class="span-75"></span>
    </div>

    <div class="container py-lg-3">
        <div class="row">

            <div class="col-lg-6">
                <div>
                    <h1 class="section-heading mb-4 text-white">We have an App!</h1>

                    <p class="lead mt-0 mb-2 text-white">It even gets better. Have {{ config('app.name') }} a tap away on your mobile phone. Get the app now for free on Google Play</p>

                    <div>
                        <a href="">
                            <img style="height:90px; left: -10px" class="position-relative" src="{{ asset('img/google_play.png') }}">
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <img src="{{ asset('img/undraw_mobile_app.svg') }}" style="height: 200px; width: 100%" class="img-fluid">
            </div>

        </div>
    </div>
</section>

<footer class="py-3" style="background: #f2f5f8">
    <div class="container d-sm-flex align-items-sm-center">
        <div class="mb-3 mb-sm-0">
            Powered by Oriscop Solutions. &copy;2021 All Rights Reserved
        </div>

        <div class="ml-sm-auto">
            <a href="https://linkedin.com/company/" target="_blank" class="btn btn-facebook px-2 py-1 shadow-none" title="LinkedIn"><i class="fa fa-linkedin" style="font-size: 1.4em"></i></a>
            <a href="https://facebook.com" target="_blank" class="btn btn-facebook px-2 py-1 shadow-none" title="Facebook"><i class="fa fa-facebook" style="font-size: 1.4em"></i></a>
        </div>
    </div>
</footer>

@endsection
