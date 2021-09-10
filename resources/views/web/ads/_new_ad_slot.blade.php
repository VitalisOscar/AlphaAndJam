<?php

$packages = \App\Models\Package::all();

?>

<div id="add_slot" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">

            <div id="slot_form_input" class="">
                <div class="modal-header bg-success py-3">
                    <h4 class="modal-title mb-0 text-white font-weight-600">Book a Slot</h4>
                    <span class="close text-white" data-dismiss="modal"><i class="fa fa-times"></i></span>
                </div>

                <form class="modal-body with-loader" id="slot_form">
                    @csrf
                    <div class="loader">
                        <div class="text-center">
                            <span class="spinner spinner-border text-primary d-inline-block mb-2"></span><br>
                            <strong>Checking availability...</strong>
                        </div>
                    </div>

                    <div id="slot_step1">

                        <div class="form-group">
                            <label class="mb-0"><strong>Screen:</strong></label>
                            <div class="mb-1">This is the preferred screen where the ad shall be shown</div>
                            <div class="clearfix">
                                <select class="nice-select w-100" name="screen_id" id="screen_id">
                                    <option value="">Select a Screen</option>
                                    @foreach(\App\Models\Screen::where('online', true)->get() as $screen)
                                    <option value="{{ $screen->id }}">{{ $screen->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <small id="screen_error" class="text-danger"></small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="mb-0"><strong>Time and Package:</strong></label>
                            <div class="mb-1">Select the time of the day to air your ad</div>
                            <div class="clearfix">
                                <select name="package_id" class="nice-select w-100" onchange="$(this).attr('data-package', ($('#package').children().get(this.selectedIndex).getAttribute('data-package')))" id="package" data-package="{{ isset($packages[0]) ? $packages[0]->name.' ('.$packages[0]->summary.')':null }}" data-init-package="{{ isset($packages[0]) ? $packages[0]->name.' ('.$packages[0]->summary:null }}">
                                    @foreach($packages as $package)
                                    <option data-package="{{ $package->name.' ('.$package->summary.')' }}" value="{{ $package->id }}">
                                    {{ $package->summary.' - '.$package->name.' ('.$package->category.')' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <small id="package_error" class="text-danger"></small>
                        </div>

                        <div>
                            <button type="button" onclick="if(validateStep()){$('#slot_step1').addClass('d-none'); $('#slot_step2').removeClass('d-none')}" class="btn btn-success btn-block shadow-none py-2">Select Dates<i class="fa fa-angle-right ml-1"></i></button>
                            <button type="button" data-dismiss="modal" class="btn btn-white btn-block shadow-none py-2">Cancel</button>
                        </div>

                    </div>

                    <div id="slot_step2" class="d-none">

                        <div class="form-group mb-3">
                            <div class="mb-1">We accept dates at least 3 days in advance to allow for moderation from our side. Select <strong>'Single/Multiple'</strong> to select dates one by one and <strong>'Date Range'</strong> to select a range of dates</div>

                            <ul class="nav nav-tabs border-bottom-0" style="">
                                <li class="nav-item">
                                    <a class="nav-link active" style="cursor: pointer" id="for_multi" onclick="multiMode()">Single/Multiple</a>
                                </li>
                                <li class="nav-item">
                                    <a onclick="rangeMode()" style="cursor: pointer" id="for_range" class="nav-link">Date Range</a>
                                </li>
                            </ul>

                            <input style="border-radius: 0 .25rem .25rem .25rem;" class="form-control flatpickr flatpickr-input" type="text" id="play_date" placeholder="Select Date..">

                            <input type="hidden" name="play_date_from" id="play_date_from">
                            <input type="hidden" name="play_date_to" id="play_date_to">
                            <input type="hidden" name="play_date_multi" id="play_date_multi">

                            <input type="hidden" id="selected_play_dates">

                            <small id="date_error" class="text-danger d-none">This date is already selected!</small>
                            <small id="date_info" class="">Select a date and click on 'Add Date'</small>
                        </div>

                        <div id="selected_dates" style="" class="mb-3" data-index="0">
                            <div class="info">
                                <small>Dates already selected for the same screen and package are not selectable on the date picker</small>
                            </div>

                            <div class="dates"></div>
                        </div>

                        <div>
                            <button type="button" class="btn btn-success btn-block shadow-none py-2" onclick="checkAvailability(addToMainForm)">Check Availability</button>
                            <button type="button" onclick="$('#slot_step2').addClass('d-none'); $('#slot_step1').removeClass('d-none')" class="btn btn-white btn-block shadow-none py-2" id="submit_slot_btn"><i class="fa fa-angle-left mr-1"></i>Go Back</button>
                        </div>

                    </div>

                </form>
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

        </div>
    </div>
</div>
