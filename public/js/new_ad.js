function valid(){
    if(!contentValid()){
        return false;
    }

    if(!slotsValid()){
        return false;
    }

    return true;
}

function contentValid(){
    if($('#category').val() == ''){
        $('#category_error').text('Select a category!');
        goToContent();

        $('html, body').animate({
            scrollTop: $("#category").offset().top
        }, 500);

        return false;
    }
    $('#category_error').text('');

    if($('#description').val() == ''){
        $('#description_error').text('Please enter a description!');
        goToContent();

        $('html, body').animate({
            scrollTop: $("#description").offset().top
        }, 500);

        return false;
    }
    $('#description_error').text('');

    if(document.querySelector('#media').files.length == 0){
        $('#media_error').text('Please select a video or an image!');
        goToContent();

        $('html, body').animate({
            scrollTop: $("#media").offset().top
        }, 500);

        return false;
    }
    $('#media_error').text('');

    return true;
}

function slotsValid(){
    if(document.querySelector('#slots').childElementCount == 0){
        $('#slots_error').text('You need to book at least one slot!');
        goToSlots();

        $('html, body').animate({
            scrollTop: $("#slots").offset().top
        }, 500);

        return false;
    }

    $('#slots_error').text('');
    return true;
}

function goToSlots(){
    if(contentValid()){
        $('#slots_tab').addClass('active');
        $('#content_tab').removeClass('active');
    }
}

function goToContent(){
    $('#content_tab').addClass('active');
    $('#slots_tab').removeClass('active');
}

function showTerms(){
    if(valid()){
        $('#tac').modal({
            backdrop: 'static'
        });
    }
}

function submit_data(){
    if(valid()){
        if(!document.querySelector('#agree_terms').checked){
            $('#terms_error').removeClass('d-none');
            return;
        }

        $('#terms_error').addClass('d-none');

        $('#tac').modal('hide');

        ad_form.addClass('loading');
        bar.css('width: 0');
        progress.text('0%');

        $.ajax({
            url: ad_url,
            type: 'post',
            data: new FormData(document.querySelector('#ad_form')),
            contentType: false,
            processData: false,
            xhr: function(){
                //upload Progress
                var xhr = $.ajaxSettings.xhr();

                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;

                        if (event.lengthComputable)
                        {
                            percent = Math.ceil((position / total) * 100);
                        }
                        //update progressbar
                        bar.css('width', + percent +'%');
                        progress.text(percent +'%');
                    }, true);
                }
                return xhr;
            },
            success: function(response){
                ad_form.removeClass('loading');

                // Ad created
                if(response.success){
                    window.location.replace(exit_url);
                }else{
                    showAlert(response.errors[0], 'Error');
                }
            },
            error: function(error){
                console.log(error);
                ad_form.removeClass('loading');
                showAlert('Something went wrong. Please try again', 'Oops');
            }
        });
    }

}
