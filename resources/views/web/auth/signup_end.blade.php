@extends('web.master')

@section('body')

<style>
    body{background: #fafafa}

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

                <div class="text-center mb-3">
                    <img src="{{ asset('img/alphalogo.svg') }}" class="d-block mx-auto mb-4" height="60px" alt="">
                    <div class="mb-2 text-left">Hello <strong>{{ auth()->user()->operator_name }}</strong>, finish up with registration and start using our services</div>
                    <div>
                        <a href="{{ route('logout') }}" class="btn btn-block btn-outline-danger shadow-none py-2"><i class="fa fa-power-off mr-1"></i>Log Out</a>
                    </div>
                </div>

                <div class="px-sm-4 mb-3 py-4 border bg-white rounded auth-card">

                    <form class="form-horizontal" method="post" action="{{ route('web.user.account.complete') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Business/Company Name</label>
                                    <input type="text" class="form-control" placeholder="e.g Jamrock Ltd" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Business Registration Certificate</label>
                                    <input type="file" name="document_certificate" class="form-control-file" required>
                                    <span class="d-block">Select pdf, jpeg, jpg or png</span>
                                    @error('document_certificate')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Your Position in Company</label>
                                    <input type="text" class="form-control" placeholder="e.g VP Sales" name="position" value="{{ old('position') }}" required>
                                    @error('position')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>Company KRA Pin</label>
                                    <input type="text" class="form-control" placeholder="e.g A012345678C" name="kra_pin" value="{{ old('kra_pin') }}" required>
                                    @if(!$errors->has('kra_pin'))
                                    <span>If your business KRA returns are filed under your personal PIN, use that</span>
                                    @endif
                                    @error('kra_pin')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group form-group-custom mb-4">
                                    <label>KRA Pin Document</label>
                                    <input type="file" name="document_kra_pin" class="form-control-file" required>
                                    <span class="d-block">Select pdf, jpeg, jpg or png</span>
                                    @error('document_kra_pin')
                                    <span class="text-danger small" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="col-12"></div> --}}

                            <div class="col-12">
                                We collect this informtion to help us in verification of your account
                            </div>

                            <div class="col-12 text-center">
                                <div class="mt-4">
                                    <button class="btn btn-dark btn-block shadow-none" type="submit">Get Onboard</button>
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
