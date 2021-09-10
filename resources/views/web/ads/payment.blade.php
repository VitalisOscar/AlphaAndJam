@extends('web.base')

@section('title', config('app.name').' | Payment')

{{-- @section('section_heading', 'Complete Payment') --}}

@section('content')

<div class="row">
    {{-- <div class="col-md-6">

        <div class="row">

            <div class="col-6 col-md-4 col-lg-3">
                <a href="" class="btn btn-white btn-block">
                    <h4>Pay with</h4>
                    <img src="{{ asset('img/mpesa_logo.png') }}" style="height: 60px" alt="M-Pesa">
                </a>
            </div>

        </div>
    </div> --}}

    <div class="col-md-6">
        <div class="card rounded-lg border-none shadow-sm" id="phone_verification">
            <div class="modal-dialogmodal-dialog-centeredmodal-sm" style="">
                <div class="card-body">
                    <form action="{{ route('web.mpesa.checkout', $invoice->number) }}" method="POST" class="with-loader" id="verify_phone">
                        @csrf
                        <div class="loader">
                            <div class="text-center">
                                <span class="spinner spinner-border text-primary d-inline-block mb-2"></span><br>
                                <strong>Please wait...</strong>
                            </div>
                        </div>

                        <div class="success d-none">
                            <div class="text-center">
                                <div>
                                    <span class="mb-4 bg-success d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 50px; width:50px">
                                        <i class="fa fa-check-circle text-white fa-3x"></i>
                                    </span>
                                </div>
                                <h4 class="mb-3 modal-title font-weight-600">Phone Verified</h4>
                            </div>

                            <p class="text-justify">
                                Done. Your phone number has been successfully verified
                            </p>

                            <div class="text-center mb-3">
                                <button data-dismiss="modal" class="btn btn-primary shadow-none">Ok</button>
                            </div>
                        </div>

                        <div class="error d-none">
                            <div class="text-center">
                                <div>
                                    <span class="mb-4 bg-danger d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 50px; width:50px">
                                        <i class="fa fa-times text-white fa-2x"></i>
                                    </span>
                                </div>
                                <h4 class="mb-3 modal-title font-weight-600">Unable to Verify</h4>
                            </div>

                            <p class="text-justify">
                                We are unable to verify your phone number. Please check the code and retry
                            </p>

                            <div class="text-center mb-3">
                                <button data-dismiss="modal" class="btn btn-primary shadow-none">Ok</button>
                            </div>
                        </div>

                        <div class="form">
                            <div class="text-center">
                                <div>
                                    <img src="{{ asset('img/mpesa_logo.png') }}" style="height: 100px" alt="M-Pesa">
                                </div>
                                <h4 class="mb-3 modal-title font-weight-600">Initiate Payment</h4>
                            </div>

                            <div class="text-justify mb-3">
                                Enter the phone number you wish to pay with. We will initiate an M-Pesa payment of <strong>{{ 'KSh '.number_format($invoice->totals['total']) }}</strong> for you. All you will do is to authorize by entering your PIN on your mobile phone.
                            </div>

                            <div>

                                <div class="form-group">
                                    <label><strong>Phone Number</strong></label>
                                    <input class="form-control" value="{{ old('phone') }}" name="phone" placeholder="Enter phone number..." required>
                                    <small>Use the format 0700123456</small>
                                </div>

                                <div class="form-group">
                                    <label><strong>Amount</strong></label>
                                    <input class="form-control" disabled value="{{ 'KSh '.number_format($invoice->totals['total']) }}" required>
                                    <small>This cannot be changed</small>
                                </div>

                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="text-center mb-3">
                                    <button class="btn btn-block btn-primary shadow-none">Submit</button>
                                </div>

                                <div class="text-center">
                                    <button data-dismiss="modal" type="button" class="btn btn-block btn-white shadow-none">Cancel</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('common_scripts')
    @if(session()->has('status'))
    <script>
        showAlert("{{ session()->get('status') }}", 'Success');
    </script>
    @endif

    @if($errors->has('status'))
    <script>
        showAlert("{{ $errors->get('status')[0] }}", 'Error');
    </script>
    @endif
@endsection
