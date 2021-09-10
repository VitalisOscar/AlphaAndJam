<div class="modal fade" id="tac">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0">Terms and Conditions</h4>

                <span class="close" data-dismiss="modal"><i class="fa fa-times"></i></span>
            </div>

            <div class="modal-body">

                @include('docs.terms')

                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" name="agree_terms" id="agree_terms" required>
                    <label class="custom-control-label" style="font-size: 1em" for="agree_terms">
                        <span>I agree to these Terms and Conditions</span>
                    </label>
                </div>

                <strong class="text-danger d-none" id="terms_error">You need to first accept our terms!</strong>

            </div>

            <div class="modal-footer">
                <button type="button" onclick="submit_data()" class="btn btn-dark btn-block shadow-none py-2">Submit Advert</button>
            </div>
        </div>
    </div>
</div>
