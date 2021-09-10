<?php

$user = auth()->user();

?>

@extends('web.base')

@section('title', 'Your Account')

@section('section_heading', 'Account Management')

@section('content')

<style>
    .form-control:disabled{
        background: #eee;
        border: none;
    }
</style>

<div class="row">
    <div class="col-md-6 col-lg-7">

        @if($user->isClient())
        <div class="mb-4">
            <form method="POST" @if($user->profileComplete()) action="" @else action="{{ route('web.user.account.complete') }}" enctype="multipart/form-data" @endif>
                @csrf

                <p>View your personal and business information. This information is usually verified and hence cannot be freely updated</p>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Business Name:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input class="form-control" value="{{ $user->name }}" disabled>
                        </div>
                    </div>
                </div>

                @if(!$user->profileComplete())
                <div class="form-group form-group-custom mb-4">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Business Certificate:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input type="file" name="document_certificate" class="form-control-file" required>
                            <span class="d-block">Select pdf, jpeg, jpg or png</span>
                            @error('document_certificate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Official Email:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>KRA Pin:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            @if($user->profileComplete())
                            <input class="form-control" value="{{ $user->kra_pin }}" disabled>
                            @else
                            <input class="form-control" placeholder="e.g A012345678N" value="{{ old('kra_pin') }}" name="kra_pin" required>
                            @error('kra_pin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @endif
                        </div>
                    </div>
                </div>

                @if(!$user->profileComplete())
                <div class="form-group form-group-custom mb-4">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>KRA Pin Document:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input type="file" name="document_kra_pin" class="form-control-file" required>
                            <span class="d-block">Select pdf, jpeg, jpg or png</span>
                            @error('document_kra_pin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Your Name:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input class="form-control" value="{{ $user->operator_name }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Position:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            @if($user->profileComplete())
                            <input class="form-control" value="{{ $user->operator_position }}" disabled>
                            @else
                            <input class="form-control" placeholder="e.g VP Sales" value="{{ old('position') }}" name="position" required>
                            @error('position')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Your Phone No:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            @if($user->profileComplete())
                            <input class="form-control" value="{{ $user->operator_phone }}" name="phone" required>
                            @else
                            <input class="form-control" placeholder="e.g 0700123456" value="{{ old('phone') }}" name="phone" required>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <button class="btn btn-default py-2 shadow-none">Save Changes</button>
                </div>
            </form>
        </div>
        @else

        <div class="mb-4">
            <form method="POST">
                @csrf

                <p>Your account information is usually verified and hence cannot be freely updated</p>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Name:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input class="form-control" value="{{ $user->name }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Email:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Phone No:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input class="form-control" value="{{ $user->phone }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-sm-3 col-md-12 col-lg-4 d-flex align-items-center">
                            <strong>Account Type:</strong>
                        </div>

                        <div class="col-sm-9 col-md-12 col-lg-8">
                            <input class="form-control" value="Agent" disabled>
                        </div>
                    </div>
                </div>
            </form>

            <p>Your account was created {{ $user->time }}</p>
        </div>

        @endif

    </div>

    <div class="col-md-6 col-lg-5 pl-lg-5">
        @if($user->isClient())
        <div class="mb-4 with-loader" id="verification_status">

            <div class="loader">
                <div class="text-center">
                    <span class="spinner spinner-border text-primary d-inline-block mb-2"></span><br>
                    <strong>Please wait...</strong>
                </div>
            </div>

            <h5 class="font-weight-600">Verification Status</h5>

            <p>
                To ensure that we only advertise content from genuine businesses, we require your account to be verified before you can send us adverts
            </p>

            <div>
                <div>
                    <h6 class="mb-1"><strong>Business</strong></h6>
                    <p class="mb-0">
                        @if(isset($user->verification['business']))
                        {{ 'Verified on '.\Carbon\Carbon::createFromTimeString($user->verification['business'])->format('Y-m-d') }}
                        @else
                        Not Verified
                        @endif
                    </p>
                </div>

                <hr class="my-2">

                @if($user->profileComplete())
                <div>
                    <h6 class="mb-1"><strong>Phone Number</strong></h6>
                    <p class="mb-0">
                        @if(isset($user->verification['official_phone']))
                        {{ 'Verified on '.\Carbon\Carbon::createFromTimeString($user->verification['official_phone'])->format('Y-m-d') }}
                        @else
                        <form method="POST" action="{{ route('web.user.account.verify_phone.send') }}" id="email_verification_form" class="d-flex align-items-center">
                            @csrf
                            <span>Not Verified</span>
                            <button class="ml-auto float-right btn btn-link p-0">Verify Now</button>
                        </form>
                        @endif
                    </p>
                </div>

                <hr class="my-2">
                @endif

                <div>
                    <h6 class="mb-1"><strong>Email Address</strong></h6>
                    <p class="mb-0">
                        @if(isset($user->verification['email']))
                        {{ 'Verified on '.\Carbon\Carbon::createFromTimeString($user->verification['email'])->format('Y-m-d') }}
                        @else
                        <form method="POST" action="{{ route('web.user.account.verify_email.send') }}" id="email_verification_form" class="d-flex align-items-center">
                            @csrf
                            <span>Not Verified</span>
                            <button class="ml-auto float-right btn btn-link p-0">Verify Now</button>
                        </form>
                        @endif
                    </p>
                </div>

                <hr class="my-2">
            </div>
        </div>
        @endif

        <div>
            <h5 class="font-weight-600">Change your Password</h5>

            <form action="{{ route('web.user.account.password') }}" method="post">
                @csrf
                <div class="form-group">
                    <strong>Current Password:</strong>
                    <br><small>We need this to confirm that it is you making this change</small>
                    <input class="form-control" type="password" name="password" value="{{ old('password') }}" required>
                </div>

                <div class="form-group">
                    <strong>New Password:</strong>
                    <br><small>Enter the new password that you want to be using</small>
                    <input class="form-control" type="password" name="new_password" value="{{ old('new_password') }}" required>
                </div>

                <div class="form-group">
                    <strong>Confirm Password:</strong>
                    <br><small>Retype your new password</small>
                    <input class="form-control" type="password" name="confirm_password" value="{{ old('confirm_password') }}" required>
                </div>

                <div>
                    <button class="btn btn-default btn-block py-2 shadow-none"><i class="fa fa-lock mr-2"></i>Save Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session()->has('status_email'))
@include('web.user._email_verification_dialog')
@endif

@if(session()->has('get_code') || session()->has('status_phone'))
@include('web.user._phone_verification_dialog')
@endif

@endsection

@section('scripts')
@if(count($errors) > 0)
<script>showAlert('{{ $errors->first() }}', 'Error');</script>
@endif

@if(session()->has('status'))
<script>showAlert('{{ session()->get("status") }}', 'Alert');</script>
@endif

@if(session()->has('status_email'))
<script>
    @if(session()->get('status_email'))
    $('#email_verification .success').removeClass('d-none');
    $('#email_verification .error').addClass('d-none');
    @else
    $('#email_verification .success').addClass('d-none');
    $('#email_verification .error').removeClass('d-none');
    @endif

    $('#email_verification').modal({
        backdrop: 'static'
    });
</script>
@endif

@if(session()->has('get_code') || session()->has('status_phone'))
<script>
    @if(session()->has('status_phone'))
    $('#phone_verification .form').addClass('d-none');
    @if(session()->get('status_phone'))
    $('#phone_verification .success').removeClass('d-none');
    $('#phone_verification .error').addClass('d-none');
    @else
    $('#phone_verification .success').addClass('d-none');
    $('#phone_verification .error').removeClass('d-none');
    @endif
    @endif
    $('#phone_verification').modal({
        backdrop: 'static'
    });
</script>
@endif
@endsection
