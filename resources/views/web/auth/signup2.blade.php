@extends('web.master')

@section('body')

<style>
    body{background: #f1f5f9}

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
        }


    }
</style>

<section class="py-5 section-shaped">

    <div class="shape shape-style-1" style="background: #f1f5f9">
        <span class="span-100"></span>
        <span class="span-150"></span>
        <span class="span-200"></span>
        <span class="span-150"></span>
        <span class="span-100"></span>
        <span class="span-200"></span>
    </div>

    <div class="container">


        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-9 col-sm-10">
                <div class="card shadow">
                    <div class="card-body p-4">

                        <div class="row">
                            <div class="col-md-6">

                            </div>

                            <div class="col-md-12">
                                <div class="text-center mb-3">
                                    <img src="{{ asset('img/magnate_logo.png') }}" class="d-block mx-auto mb-4" height="60px" alt="">
                                    <h4 class="font-weight-600">{{ config('app.name') }}</h4>
                                    <p class="mb-2">Fill the form to create a client account</p>
                                    <span class="d-inline-block px-5 bg-success rounded" style="height: 3px"></span>
                                </div>

                                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">

                                        <div class="col-12">
                                            <h4><strong>Business Information</strong></h4>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Business/Company Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Official Email</label>
                                                <input type="text" class="form-control" name="email" value="{{ old('email') }}" required>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Upload Incorporation/Business Certificate</label>
                                                <input type="file" name="documents[certificate]" class="form-control-file" required>
                                                @error('documents.certificate')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>KRA Pin</label>
                                                <input type="text" class="form-control" name="kra_pin" value="{{ old('kra_pin') }}" required>

                                                @error('kra_pin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Upload your KRA Pin</label>
                                                <input type="file" name="documents[kra_pin]" class="form-control-file" required>
                                                @error('documents.kra_pin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <h4><strong>Personal Details</strong></h4>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Your Name</label>
                                                <input type="text" class="form-control" name="operator[name]" value="{{ old('operator')['name'] ?? null }}" required>
                                                @error('operator.name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Your Phone No</label>
                                                <input type="text" class="form-control" name="operator[phone]" value="{{ old('operator')['phone'] ?? null }}" required>
                                                @error('operator.phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Your Position in Business</label>
                                                <input type="text" class="form-control" name="operator[position]" value="{{ old('operator')['position'] ?? null }}" required>
                                                @error('operator.position')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="col-12"></div> --}}

                                        <div class="col-sm-6">
                                            <div class="form-group form-group-custom mb-4">
                                                <label>Set a Password</label>
                                                <div class="input-group input-group-alternative shadow-none border" id="password">
                                                    <input type="password" class="form-control" name="password" value="{{ old('password') }}" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span style="cursor: pointer" class="text-primary toggle font-weight-600" onclick="toggle(event)">SHOW</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            {{ config('app.name') }} is a service provided by <a href="https://magnate-ventures.com">Magnate Ventures</a>. All accounts will be verified before any content is submitted. By signing up, you agree to our digital advertising <a class="text-primary">Terms and Conditions</a>
                                        </div>

                                        <div class="col-12 text-center">
                                            <div class="mt-4">
                                                <button class="btn btn-success" type="submit">Get Onboard</button>
                                            </div>

                                            <div class="mt-4 text-center">
                                                Already have account? <a href="{{ route('web.auth.login') }}">Log in</a>
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
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
