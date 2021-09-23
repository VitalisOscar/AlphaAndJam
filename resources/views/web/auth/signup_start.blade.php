@extends('web.master')

@section('body')

<style>
    body{background: #fff}

    .input-group{
        border-color: #cad1d7 !important;
        transition: .25s all !important;
    }

    .input-group:focus-within {
        border-color: #44a4fc !important;
        box-shadow: 0 0 8px rgba(102,175,233,.75);
    }

    @media(max-width: 450px){
        .auth-card{
            border: none !important;
            box-shadow: none !important;
        }

        body{background: #fff}
    }
</style>

<section class="py-5 section-shaped">
    <div class="container">

        <div class="d-flex align-items-center justify-content-center">
            <div class="mx-auto" style="width: 400px; max-width: 100%">
                <div class="px-sm-4 mb-3 py-4 border bg-white rounded auth-card shadow-s">
                    <div class="text-center mb-3">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('img/logo.png') }}" class="d-inline-block mx-auto mb-4" height="60px" alt="">
                        </a>
                        <p class="mb-2">Create your basic client profile</p>
                        <span class="d-inline-block px-5 bg-dark rounded" style="height: 3px"></span>
                    </div>

                    <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Your Name</label>
                                    <div class="form-row">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <input type="text" class="form-control" placeholder="First" name="first_name" value="{{ old('first_name') }}" required>
                                            @if($errors->has('first_name'))
                                            <span class="text-danger small" role="alert">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" placeholder="Last" name="last_name" value="{{ old('last_name') }}" required>
                                            @if($errors->has('last_name'))
                                            <span class="text-danger small" role="alert">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Your Phone Number</label>
                                    <input type="tel" class="form-control" placeholder="e.g 0700123456" name="phone" value="{{ old('phone') ?? null }}" required>
                                    @if($errors->has('phone'))
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Email Address</label>
                                    <input type="text" class="form-control" placeholder="e.g info@jamrock.com" name="email" value="{{ old('email') }}" required>
                                    @if($errors->has('email'))
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="col-12"></div> --}}

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Set a Password</label>
                                    <div class="input-group input-group-alternative shadow-none border" id="password">
                                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span style="cursor: pointer" class="small text-primary toggle font-weight-600" onclick="toggle(event)">SHOW</span>
                                            </span>
                                        </div>
                                    </div>
                                    @if($errors->has('password'))
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <div class="mt-4">
                                    <button class="btn btn-danger shadow-none btn-block mb-4" type="submit">Submit</button>

                                    <div class="position-relative">
                                        <div style="z-index: 0" class="position-absolute left-0 right-0 top-0 bottom-0 d-flex align-items-center">
                                            <hr class="my-0 w-100">
                                        </div>
                                        <strong style="z-index: 1" class="position-relative d-inline-block bg-white px-2">Already registered?</strong>
                                    </div>

                                    <a href="{{ route('web.auth.login') }}" class="btn btn-block mt-4 btn-outline-primary shadow-none">Log in</a>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="mt-4">
                        Powered by <a href="https://oriscop.com">Oriscop Solutions</a>. All accounts will be verified before any content is submitted.
                        {{-- By signing up, you agree to our digital advertising <a class="text-primary">Terms and Conditions</a> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@section('scripts')

<script>
    $('.input-group').click(function(){
        document.querySelector('.input-group input').focus();
    });

    function toggle(e){
        var p = $('#password input');
        if(p.attr('type') == 'text'){
            $('#password input').attr('type', 'password');
            $('#password .input-group-append .toggle').text('SHOW');
        }else{
            $('#password input').attr('type', 'text');
            $('#password .input-group-append .toggle').text('HIDE');
        }
    }
</script>

@endsection
