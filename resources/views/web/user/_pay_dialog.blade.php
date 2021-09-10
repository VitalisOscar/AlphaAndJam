<div class="modal fade" id="payment">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form class="modal-content with-loader" style="background: #fcfcfc" method="POST" id="pay_form">
            @csrf
            <div class="loader">
                <div class="text-center">
                    <span class="spinner spinner-border text-primary d-inline-block mb-2"></span><br>
                    <strong>Please wait...</strong>
                </div>
            </div>

            <div class="modal-body">
                <div>
                    <input type="hidden" name="invoice_number" value="{{ $invoice->number }}" id="invoice_number">
                    <input type="hidden" name="token" value="{{ $invoice->token }}" id="token">

                    <div class="d-flex align-items-center">
                        <h4 class="font-weight-500">Pay with</h4>
                        <img class="float-right ml-auto" style="height: 80px" src="{{ asset('img/mpesa_logo.png') }}">
                    </div>

                    <div id="pay_form_input" class="d-none">
                        <div class="mb-3">
                            Enter your M-Pesa phone number to initiate a payment. You'll be prompted by M-Pesa to confirm the payment once you submit your phone number
                        </div>

                        <div>
                            <h5>Total Amount: <strong>{{ $invoice->amount }}</strong></h5>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-mobile" style="font-size: 1.3em"></i></span></div>
                                <input type="tel" placeholder="e.g 0700123456" class="form-control" name="phone" required>
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-default btn-block py-2 d-block mb-2 shadow-none">Continue with Payment</button>
                            <button type="button" data-dismiss="modal" class="btn btn-white btn-block py-2 shadow-none">Cancel</button>
                        </div>
                    </div>

                    <!-- Shown when payment cannot be initiated -->

                    <div id="pay_form_error" class="d-none">
                        <p class="lead mt-0 text-justify">
                            Error: <span id="payment_err_msg"></span>
                        </p>

                        <div class="text-right">
                            <button type="button" onclick="initPayment()" class="btn btn-link py-1 px-0 mr-3"><i class="fa fa-refresh mr-1"></i>Retry</button>
                            <button type="button" data-dismiss="modal" class="btn btn-link py-1 px-0"><i class="fa fa-times mr-1"></i>Cancel</button>
                        </div>
                    </div>

                    <div id="pay_form_success" class="d-non">
                        <p class="lead mt-0 text-justify">
                            An M-Pesa payment has been initiated. Wait for a prompt on your phone to confirm and complete the payment
                        </p>

                        <div class="text-right">
                            <button type="button" data-dismiss="modal" class="btn btn-link py-1 px-0">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
