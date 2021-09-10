<div class="modal fade" id="email_verification">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body py-5">

                <div class="success d-none">
                    <div class="text-center">
                        <div>
                            <span class="mb-4 bg-success d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 50px; width:50px">
                                <i class="fa fa-check-circle text-white fa-3x"></i>
                            </span>
                        </div>
                        <h4 class="mb-3 modal-title font-weight-600">Email Sent</h4>
                    </div>

                    <p class="text-justify">
                        A verification email has been sent to <strong class="font-weight-600">{{ $user->business['email'] }}</strong> with a link. Check your inbox shortly and follow the link to have your email verified.
                    </p>

                    <div class="text-center">
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
                        <h4 class="mb-3 modal-title font-weight-600">Unable to Send Email</h4>
                    </div>

                    <p class="text-justify">
                        We are unable to send a verification email to <strong class="font-weight-600">{{ $user->business['email'] }}</strong>. If the email is correct, you can dismiss and try again later
                    </p>

                    <div class="text-center">
                        <button data-dismiss="modal" class="btn btn-primary shadow-none">Ok</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
