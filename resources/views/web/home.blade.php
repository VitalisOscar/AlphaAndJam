@extends('web.master')

@section('title', config('app.name').' | Simple digital outdoor advertising')

@section('links')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('body')

<header class="py-3 bg-white sticky-top">
    <nav class="navbar py-0">
        <div class="container">

            <a href="" class="navbar-brand">{{ config('app.name') }}</a>

            <div class="float-right d-flex align-items-center">
                <div class="links">
                    <a href="{{ route('web.auth.login') }}" class="active">Home</a>
                    <a href="{{ route('web.auth.login') }}">Log in</a>
                </div>

                <a class="btn btn-success shadow-none btn-rounded" href="{{ route('web.auth.signup') }}">Get Started</a>
            </div>
        </div>
    </nav>
</header>

<section class="section-shaped bg">

    <div class="hero-content">
        <div class="container">

            <div class="row">
                <div class="col-lg-6 d-flex align-items-center">

                    <div class="shape shape-style-1">
                        <span>
                            <svg class="clouds cloud1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="128" height="128" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                                <path fill="#ddd" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
                            </svg>
                        </span>

                        <span>
                            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="192" height="192" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                                <path fill="#ddd" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
                            </svg>
                        </span>

                        <span>
                            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="192" height="192" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                                <path fill="#ddd" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
                            </svg>
                        </span>

                        <span>
                            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="128" height="128" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                                <path fill="#ddd" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
                            </svg>
                        </span>

                        <span>
                            <svg class="clouds cloud2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" width="128" height="128" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                                <path fill="#ddd" id="cloud-icon" d="M406.1 227.63c-8.23-103.65-144.71-137.8-200.49-49.05 -36.18-20.46-82.33 3.61-85.22 45.9C80.73 229.34 50 263.12 50 304.1c0 44.32 35.93 80.25 80.25 80.25h251.51c44.32 0 80.25-35.93 80.25-80.25C462 268.28 438.52 237.94 406.1 227.63z"/>
                            </svg>
                        </span>
                     </div>

                     <div>
                        <h1 class="hero-title">Simple Digital Outdoor Advertising</h1>

                        <p class="lead">
                            {{ config('app.name') }} has been built to enable your business and products gain more visibility through digital outdoor advertising.
                            From a single dashboard on your phone or computer, submit and manage adverts in a few clicks
                        </p>

                        <div>
                            <a href="#about" class="mb-3 btn btn-lg btn-outline-primary shadow-none">Learn More</a>
                            <a href="{{ route('web.auth.signup') }}" class="mb-3 btn btn-lg btn-success shadow-none">Get Started</a>
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
    <div class="shape shape-light bg-gradient-success shape-style-1">
        <span class="span-200"></span>
        <span class="span-100"></span>
        <span class="span-150"></span>
        <span class="span-75"></span>
        <span class="span-100"></span>
        <span class="span-100"></span>
        <span class="span-100"></span>
        <span class="span-100"></span>
    </div>


    <div class="container py-lg-3">

        <div class="mb-5 text-white">
            <h1 class="section-heading text-white">How it Works</h1>

            <p class="lead my-0" style="font-size: 1.5em">{{ config('app.name') }} has been tailored to serve its purpose in simple easy steps, letting you dedicate more time on your business rather than running adverts</p>
        </div>

        <div class="row">

            <div class="col-lg-6 hiw-item mb-4">
                <div class="d-sm-flex align-items-cente">

                    <span class="hiw-circle">1</span>

                    <div class="ml-sm-4">
                        <h4 class="mb-2 text-white"><strong>Create an Account</strong></h4>

                        <p class="text-white lead my-0">Create a free account with your personal and business info, or log in if you are already registered</p>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 hiw-item mb-4">
                <div class="d-sm-flex align-items-cente">

                    <span class="hiw-circle">2</span>

                    <div class="ml-sm-4">
                        <h4 class="mb-2 text-white"><strong>Submit your Ads</strong></h4>

                        <p class="text-white lead my-0">Creating an ad will only take you a few minutes. Once you complete payment, your ad will be submitted immediately</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 hiw-item">
                <div class="d-sm-flex align-items-cente">

                    <span class="hiw-circle">3</span>

                    <div class="ml-sm-4">
                        <h4 class="mb-2 text-white"><strong>Increase your visibility</strong></h4>

                        <p class="text-white lead my-0">Once your advert is received and approved by us, it will be advertised on our screens to hundreds of thousands of potential clients</p>
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
                        <a href="{{ route('web.auth.signup') }}" class="btn btn-outline-success shadow-none btn-lg px-4 btn-rounded">Get Started</a>
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
                        <a href="{{ route('web.auth.signup') }}" class="btn btn-outline-success shadow-none btn-lg px-4 btn-rounded">Get Started</a>
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
                        We are a trusted, leading outdoor advertising company in the region, with thousands of other businesses already using our other services
                    </p>

                    <div class="position-absolute bottom-0 text-center left-0 right-0 pb-5">
                        <a href="{{ route('web.auth.signup') }}" class="btn btn-outline-success shadow-none btn-lg px-4 btn-rounded">Get Started</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-5 section-shaped">
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

<section class="py-5 bg-white">
    <div class="container">

        <div class="text-center">
            <img src="{{ asset('img/magnate_logo.png') }}" class="d-block mx-auto mb-3" style="height: 100px" alt="Magnate Logo">

            <div class="">
                <p class="lead mt-0 mb-3">{{ config('app.name') }} is powered by <a href="https://magnate-ventures.com" target="_blank">Magnate Ventures Limited</a></p>
                <div class="">
                    <a href="http://www.linkedin.com/company/magnate-ventures-ltd" target="_blank" class="btn btn-facebook px-3 shadow-none" title="LinkedIn"><i class="fa fa-linkedin" style="font-size: 1.4em"></i></a>
                    <a href="http://twitter.com/magnateventures" target="_blank" class="btn btn-twitter px-3 shadow-none" title="Twitter"><i class="fa fa-twitter" style="font-size: 1.4em"></i></a>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
