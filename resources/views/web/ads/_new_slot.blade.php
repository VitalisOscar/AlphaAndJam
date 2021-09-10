<div>
    @php
        $packages = \App\Models\Package::all();
        $screens = \App\Models\Screen::all();
    @endphp

    <form class="with-loader" id="slot_form">

        @csrf
        {{-- <div class="loader">
            <div class="text-center">
                <span class="spinner spinner-border text-primary d-inline-block mb-2"></span><br>
                <strong>Checking availability...</strong>
            </div>
        </div> --}}

        <div id="slot_form_input" class="">
            <div id="slot_step1">

                <div class="row">
                    <div class="col-md-6 col-lg-4">

                        <ul class="mt-0 mb-3 px-3">
                            <li>Select the screen and package</li>
                            <li>Select the dates to play the ad</li>
                            <li>The booking will be shown or updated in the 'Bookings' section once you select/deselect dates</li>
                        </ul>

                        <div class="form-group">
                            <h6 class="mb-0"><strong>Screen:</strong></h6>
                            <div class="mb-1" style="font-size: .9em">This is the preferred screen where the ad shall be shown</div>
                            <div class="clearfix">
                                <select class="nice-select w-100" onchange="updateWithStoredSlots(); $(this).attr('data-screen', ($('#screen_id').children().get(this.selectedIndex).getAttribute('data-screen')))" name="screen_id" data-screen="{{ isset($screens[0]) ? $screens[0]->title:null }}" id="screen_id">
                                    @foreach(\App\Models\Screen::where('online', true)->get() as $screen)
                                    <option value="{{ $screen->id }}" data-screen="{{ $screen->title }}">{{ $screen->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <small id="screen_error" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <h6 class="mb-0"><strong>Package:</strong></h6>
                            <div class="mb-1" style="font-size: .9em">Select the time of the day to air your ad. The package will also determine the pricing of your ad</div>
                            <div class="clearfix">
                                <select name="package_id" class="nice-select w-100" onchange="updateWithStoredSlots(); $(this).attr('data-package', ($('#package').children().get(this.selectedIndex).getAttribute('data-package')))" id="package" data-package="{{ isset($packages[0]) ? $packages[0]->name.' ('.$packages[0]->summary.')':null }}" data-init-package="{{ isset($packages[0]) ? $packages[0]->name.' ('.$packages[0]->summary:null }}">
                                    @foreach($packages as $package)
                                    <option data-package="{{ $package->name.' ('.$package->summary.')' }}" value="{{ $package->id }}">
                                    {{ $package->summary.' - '.$package->name.' ('.$package->category.')' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <small id="package_error" class="text-danger"></small>
                        </div>

                    </div>

                    <div class="col-md-6 col-lg-4">

                        <input type="hidden" style="display:none" id="slot_datepicker">

                    </div>

                    <div class="col-md-4">

                        <h5 class="font-weight-600">Bookings</h5>

                        <div id="no_slots">
                            You have not booked anything yet!
                        </div>

                        <div id="slots">

                        </div>

                        <div class="table-responsive mb-2 d-none">
                            <table class="table table-striped border-bottom" id="slots_table">
                                <tbody>
                                    <tr class="">
                                        <th>Screen</th>
                                        <th>Package</th>
                                        <th>Dates</th>
                                        <th>Price</th>
                                    </tr>

                                    <tr id="no_slots">
                                        <td colspan="4">
                                            Start booking slots to see them here
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <small class="text-danger" id="slots_error"></small>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-none modal-body" id="slot_form_success">
            <div class="text-center">
                <div>
                    <span class="mb-4 bg-success d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 50px; width:50px">
                        <i class="fa fa-check-circle text-white fa-3x"></i>
                    </span>
                </div>
                <h4 class="mb-3 modal-title font-weight-600">Slots Available</h4>
            </div>

            <p class="text-justify">
                All slots are available for booking for the selected dates. The ad will be played approximately <strong id="new_slot_loops">0</strong> times on each slot, and will cost you a total of <strong id="new_slot_price">KSh 0</strong> for the slots you just selected
            </p>

            <div class="text-center">
                <button class="book-btn btn btn-primary mb-3 btn-block shadow-none" onclick="addToMainForm()">Proceed to Book</button>
                <button type="button" onclick="$('#slot_form_success').addClass('d-none'); $('#slot_form_input').removeClass('d-none');" class="btn btn-white btn-block shadow-none">Make Changes</button>
            </div>
        </div>

        <div class="d-none modal-body" id="slot_form_error">
            <div class="text-center">
                <div>
                    <span class="mb-4 bg-danger d-inline-flex align-items-center justify-content-center rounded-circle" style="height: 50px; width:50px">
                        <i class="fa fa-times text-white fa-2x"></i>
                    </span>
                </div>
                <h4 class="mb-3 modal-title font-weight-600">Unavailable</h4>
            </div>

            <p class="error-text">

            </p>

            <div class="text-center">
                <button class="book-btn btn btn-primary mb-3 btn-block shadow-none" onclick="$('#slot_form_error').addClass('d-none'); $('#slot_form_input').removeClass('d-none');">Make Changes</button>
                <button data-dismiss="modal" type="button" class="btn btn-white btn-block shadow-none">Cancel</button>
            </div>
        </div>

    </form>


    <div class="mt-3">
        <span class="d-inline-block mb-3">Your advert is subject to approval by our admins before being aired</span>

        <div class="pt-3 border-top d-sm-flex align-items-center final-actions">
            <button type="button" onclick="goToContent()" class="btn btn-outline-dark shadow-none py-2 final-action"><i class="fa fa-angle-double-left"></i> Back to Content</button>
            <button type="button" onclick="showTerms()" class="ml-sm-auto btn btn-default shadow-none py-2 final-action">Submit Advert</button>
        </div>
    </div>
</div>

<style>

    @media(max-width: 600px){
        .final-action{
            width: 50%;
            display: inline-block;
        }

        .final-actions{
            display: flex;
        }

        .final-action:first-child{
            margin-right: .5rem;
        }

        .final-action:last-child{
            margin-left: .5rem;
        }
    }

    @media(max-width: 550px){
        .final-action{
            width: 100%;
            display: block;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .final-action:first-child{
            margin-bottom: 1rem;
        }

        .final-actions{
            display: block;
        }
    }
</style>
