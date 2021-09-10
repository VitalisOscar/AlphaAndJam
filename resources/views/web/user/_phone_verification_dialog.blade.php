<div class="modal fade" id="phone_verification">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 350px">
        <div class="modal-content">
            <form action="{{ route('web.user.account.verify_phone') }}" method="POST" class="modal-body pt-5 with-loader" id="verify_phone">
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
                            <span class="mb-4 bg-success d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 60px; width:60px">
                                <i class="fa fa-mobile text-white fa-3x"></i>
                            </span>
                        </div>
                        <h4 class="mb-3 modal-title font-weight-600">SMS Code Sent</h4>
                    </div>

                    <p class="text-justify">
                        Enter the code we just sent to <strong class="font-weight-600">{{ $user->phone }}</strong> and submit to verify phone.
                        The code will become unusable after {{ config('auth.sms_code_expiry') }} minutes.
                    </p>

                    <div>
                        <div class="form-group">
                            <input class="form-control" value="{{ old('code') }}" name="code" placeholder="Enter code here..." required>
                        </div>

                        <div class="text-center mb-3">
                            <button class="btn btn-block btn-primary shadow-none">Verify Phone</button>
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
