var ad_form = $('#ad_form');
var bar = $("#upload_progress_bar");
var progress = $("#upload_progress_value");

window.slots_stored = {};

initFlatPickr([]);

function storeSlots(screen_id, package_id){
    if(screen_id == '' || package_id == ''){
        return;
    }

    var slots_stored = window.slots_stored;

    // Key for slots per screen per package
    var storing_key = 's' + screen_id + '_p' + package_id;
    var stored = slots_stored[storing_key] || [];

    // Add dates
    var dates = document.querySelector('#slot_datepicker').value.split(', ');
    if(dates.length == 1 && dates[0] == ""){
        dates = [];
    }

    if(slots_stored[storing_key] == undefined) {
        slots_stored[storing_key] = [];
    }

    slots_stored[storing_key]['dates'] = dates;

    // Add Data
    var data = {
        package_name: $('#package').attr('data-package'),
        screen_name: $('#screen_id').attr('data-screen'),
        price: prices[storing_key],
    };

    slots_stored[storing_key]['data'] = data;

    // Persist
    window.slots_stored = slots_stored;

    // Update on ui
    var slot = document.querySelector('#slots #' + storing_key);

    // Inputs
    var slot_inputs = `
        <input type="hidden" name="slots[`+storing_key+`][screen_id]" value="`+ screen_id +`">
        <input type="hidden" name="slots[`+storing_key+`][package]" value="`+ package_id +`">`;

    for(i = 0; i<dates.length; i++){
        slot_inputs += `<input type="hidden" name="slots[`+storing_key+`][play_date][`+ i +`]" value="`+ dates[i] +`">`;
    }

    // UI
    if(slot){
        var slot_ui = '%slot_ui%';
    }else{
        var slot_ui = `<div id="` + storing_key + `" class="slot rounded pr-3 pl-2 mb-3 py-3 d-flex align-items-center">%slot_ui%</div>`;
    }

    slot_ui = slot_ui.replace('%slot_ui%', `
                        `+ slot_inputs +`
                        <span class="mr-2 icon icon-shape">
                            <i class="fa fa-tv text-warning"></i>
                        </span>

                        <div class="mr-2">
                            <div><strong>` + data.screen_name + ' - ' + data.package_name + `</strong></div>
                            <div class="d-flex align-items-center">
                                <i style="font-size: .8em" class="fa fa-calendar mr-1"></i>
                                <span class="mr-3">` + dates.length + `</span>

                                <i style="font-size: .8em" class="ni ni-money-coins text-success mr-1"></i>
                                <span>KSh ` + (data.price * dates.length).toLocaleString() + `</span>
                            </div>
                        </div>

                        <span onclick="deleteSlot('` + storing_key + `')" class="ml-auto d-inline-flex" style="width: 20px; height: 20px; border-radius: 50%; cursor: pointer" title="Clear Booking">
                            <i class="fa fa-times"></i>
                        </span>`);

    if(dates.length > 0) {
        if(slot){
            slot.innerHTML = slot_ui;
        }else{
            $('#slots').html($('#slots').html() + slot_ui);
        }

        $('#no_slots').addClass('d-none');
    }else{
        slot.remove();

        if(document.querySelector('#slots').childElementCount == 0){
            $('#no_slots').removeClass('d-none');
        }
    }
}

function deleteSlot(key){
    // from ui
    var slot = $('#' + key);
    slot.remove();

    // from data
    window.slots_stored[key] = [];

    if(document.querySelector('#slots').childElementCount == 0){
        $('#no_slots').removeClass('d-none');
    }
}

function updateWithStoredSlots(){
    var screen_id = $('#screen_id').val();
    var package_id = $('#package').val();

    if(!(screen_id != '' && package_id != '')){
        return;
    }

    // Key for slots per screen per package
    var storing_key = 's' + screen_id + '_p' + package_id;

    var dates = [];

    if(window.slots_stored[storing_key] != undefined &&
    window.slots_stored[storing_key]['dates'] != undefined){
        dates = window.slots_stored[storing_key]['dates'];
    }

    // Update date picker
    initFlatPickr(dates);
}

function checkAvailability(){
    var slot = getSlot();
    if(!slot) return;

    var slot_form = $('#slot_form');
    slot_form.addClass('loading');

    $.ajax({
        url: availability_url,
        type: 'post',
        data: new FormData(document.querySelector(('#slot_form'))),
        contentType: false,
        processData: false,
        success: function(response){
            console.log(response);
            slot_form.removeClass('loading');

            if(response.success){
                var data = response.data;

                var available = data.available;
                var unavailable = data.unavailable;

                if(unavailable.length == 0 && available.length > 0){
                    // Available
                    $('#slot_form_input').addClass('d-none');
                    $('#slot_form_error').addClass('d-none');
                    $('#slot_form_success').removeClass('d-none');

                    $('#selected_play_dates').val(available.join(','));
                }else{
                    if(available.length == 0){
                        var txt = 'None of the slots you booked is available. Please select different dates or another screen or package and try again';
                    }else{
                        var txt = 'The following slots are not available: <br>';
                        for(i=0; i<unavailable.length; i++){
                            if(i != 0) txt = ', ' + txt;
                            txt += '<strong>' + data.unavailable[i] + '</strong>'
                        }

                        txt += '<br>Please select different screens and packages for these dates';
                    }

                    $('#slot_form_error .error-text').html(txt);

                    $('#slot_form_input').addClass('d-none');
                    $('#slot_form_success').addClass('d-none');
                    $('#slot_form_error').removeClass('d-none');

                    $('#date_error').text('').addClass('d-none');
                    $('#date_info').removeClass('d-none');
                }
            }else{
                $('#date_error').text(response.errors[0]).removeClass('d-none');
                $('#date_info').addClass('d-none');
            }
        },
        error: function(error){
            slot_form.removeClass('loading');
            $('#pay_form_input').addClass('d-none');
            $('#pay_form_error').removeClass('d-none');
            $('#payment_err_msg').text('Something went wrong. Please try again');
            console.log(error);
        }
    });
}

function initFlatPickr(dates){
    $('#slot_datepicker').val(dates.join(','));

    flatpickr('#slot_datepicker',{
        mode: 'multiple',
        inline: true,
        defaultDate: dates,
        monthSelectorType: "static",
        minDate: min_date,
        onChange: function(d){
            storeSlots($('#screen_id').val(), $('#package').val());
        }
        // disable: getPickedDates()
    });

    $('.flatpickr-calendar')
        .css('box-shadow', 'none')
        .css('margin', 'auto')
        .css('max-width', '100%')
        .css('border', '1px solid #ededed');
}
