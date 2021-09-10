@extends('web.unauthenticated')

@section('title', 'Create a new account')

@section('content')
<style>

    body{background: #fff}
    .form-control:not(:focus){
        /* background: #eaeaea;
        border-color: #eaeaea; */
    }
    .input-group-alternative{
        box-shadow: none;
        border: 1px solid #ccc;
    }
    label.required::after, strong.required::after{
        content: ' *';
        color: #ff4040;
    }
    @media(max-width: 576px){
        .auth-card{
            border: none !important;
        }
    }
</style>
<section class="py-5">

    <div class="container">

        <div class="row">
            <div class="col-lg-5">
                <p class="lead mt-0">
                    Please fill the form to create a new account. We will go through the details you provide after you sign up and notify you once you are approved to begin sending your content
                </p>
            </div>

            <div class="col-lg-5 px-0">

                <form enctype="multipart/form-data" action="" method="POST" id="signup_form" style="background: #efefef" class="p-4 rounded border mb-3">
                    @csrf

                    <div class="form-group">
                        <strong class="required">Business/Company Name:</strong>

                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-building"></i>
                                </span>
                            </div>
                            <input class="form-control" placeholder="e.g. CompanyX Ltd" name="company_name" value="{{ old('company_name') }}" required>
                        </div>

                        @if($errors->has('company_name'))
                        <strong class="small text-danger">@error('company_name'){{ $message }}@enderror</strong>
                        @else
                        <small>Use your legally registered company or business name</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for=""><strong class="required">Incorporation Certificate:</strong></label>
                        <input type="file" name="business_certificate" class="form-control-file" required>
                        @if($errors->has('business_certificate'))
                        <strong class="small text-danger">@error('business_certificate'){{ $message }}@enderror</strong>
                        @else
                        <small>Select your certificate of corporation. Accepted files are png, jpg, jpeg images and pdf documents</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong class="required">Official Company Email:</strong>

                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-envelope"></i>
                                </span>
                            </div>
                            <input class="form-control" type="email" placeholder="e.g. info@companyx.com" name="email" value="{{ old('email') }}" required>
                        </div>
                        @if($errors->has('email'))
                        <strong class="small text-danger">@error('email'){{ $message }}@enderror</strong>
                        @else
                        <small>You'll use this to sign in and receive notifications. Use the correct email since you will have to verify it</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong class="required">Official Phone Number:</strong>

                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-phone"></i>
                                </span>
                            </div>
                            <input class="form-control" type="tel" placeholder="e.g. 0700123456" name="official_phone" value="{{ old('official_phone') }}" required>
                        </div>
                        @if($errors->has('official_phone'))
                        <strong class="small text-danger">@error('official_phone'){{ $message }}@enderror</strong>
                        @else
                        <small>You'll need to verify this, use the official correct business line. Do not include the country code</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Website:</strong>

                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-link"></i>
                                </span>
                            </div>
                            <input class="form-control" type="text" placeholder="e.g. www.companyx.co.ke" name="website" value="{{ old('website') }}">
                        </div>
                        @if($errors->has('website'))
                        <strong class="small text-danger">@error('website'){{ $message }}@enderror</strong>
                        @else
                        <small>You can add your company website if you have one</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="mb-0" for=""><strong class="required">KRA Pin:</strong></label>
                        <div class="input-group input-group-alternative mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-pencil"></i>
                                </span>
                            </div>
                            <input class="form-control" type="text" placeholder="e.g. A123456789F" name="kra_pin" value="{{ old('kra_pin') }}" required>
                        </div>

                        <input type="file" name="kra_pin_document" class="form-control-file" required>
                        <br>
                        @if($errors->has('kra_pin'))
                        <strong class="small text-danger">@error('kra_pin_document'){{ $message }}@enderror</strong>
                        @elseif($errors->has('kra_pin_doc'))
                        <strong class="small text-danger">@error('kra_pin_document'){{ $message }}@enderror</strong>
                        @else
                        <small>Enter the company's KRA PIN number and select the matching document</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label><strong>Your Company Logo:</strong></label>
                        <input class="form-control-file" name="logo" type="file">
                        @if($errors->has('logo'))
                        <br>
                        <strong class="small text-danger">@error('logo'){{ $message }}@enderror</strong>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="required"><strong>Your Full Name:</strong></label>
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input class="form-control" placeholder="e.g John Doe" name="user_name" type="text" value="{{ old('user_name') }}" required>
                            @if($errors->has('user_name'))
                            <strong class="small text-danger">@error('user_name'){{ $message }}@enderror</strong>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required"><strong>Your Phone Number:</strong></label>
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                            </div>
                            <input class="form-control" name="phone" placeholder="e.g 0700123456" type="tel" value="{{ old('phone') }}" required>
                        </div>

                        @if($errors->has('phone'))
                        <strong class="small text-danger">@error('phone'){{ $message }}@enderror</strong>
                        @else
                        <small>Enter your own personal phone number, one we can call to reach you</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="required"><strong>Your Position in Company:</strong></label>
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-briefcase"></i>
                                </span>
                            </div>
                            <input class="form-control" name="position" type="text" placeholder="e.g Sales Manager" value="{{ old('position') }}" required>
                            @if($errors->has('position'))
                            <strong class="small text-danger">@error('position'){{ $message }}@enderror</strong>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required"><strong>Create a Password:</strong></label>
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" name="password" id="password" placeholder="e.g Wb45hj0@e9" value="{{ old('password') }}" required>
                        </div>
                        @if($errors->has('password'))
                        <strong class="small text-danger">@error('password'){{ $message }}@enderror</strong>
                        @else
                        <small>Use a password at least 8 characters long, with both alphabetic and numerical characters</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="required"><strong>Confirm Password:</strong></label>
                        <div class="input-group input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="e.g Wb45hj0@e9" value="{{ old('confirm_password') }}" required>
                        </div>
                        @if($errors->has('confirm_password'))
                        <strong class="small text-danger">@error('confirm_password'){{ $message }}@enderror</strong>
                        @endif
                    </div>

                    <div class="text-center">
                        <button class="btn btn-success shadow-none">Create Your Account</button>
                    </div>

                </form>

                <div class="text-center">
                    Already registered? <a href="{{ route('web.auth.login') }}">Log in</a>
                </div>

            </div>

        </div>
    </div>

</section>

@endsection

@section('scripts')
@if($errors->any())
<script>
    showAlert('The form contains some errors. Go through the form and correct the highlighted errors and submit again', 'Oops');
</script>
@endif
<script>
    if($('#password') != null){
        $('#signup_form').on('submit', function(e){


            if($('#password').val() == $('#confirm_password').val()){
                // $('#signup_form').submit();
            }else{
                e.preventDefault();
                showAlert('Passwords do not match!', 'Error');
                $('#confirm_password').focus();
            }
        });
    }
</script>
@endsection
