<?php
    $ad_service = resolve(\App\Services\AdService::class);
    $date = $ad_service->getEarliestSlotBookingDate();
?>

@extends('web.base')

@section('title', config('app.name').' | '.$title)

@section('section_heading', $heading)

@section('content')

<style>
    .form-control:not(:focus),
    .nice-select:not(:focus)
    {
        background-color: #eaeaea;
        border-color: #eaeaea;
    }

    .form-control,
    .nice-select{
        color: #333;
    }
</style>

<form method="POST" enctype="multipart/form-data" id="ad_form" class="with-loader shadow-sm bg-white rounded-lg mx--3 mx-sm-0 px-4 py-4">
    @csrf
    <div class="loader">
        <div>

            <div class="progress-wrapper">
                <div class="progress-success">
                    <div class="progress-label">
                        <strong style="font-size: 1.2em">Uploading Content...</strong>
                    </div>

                    <div class="progress-percentage">
                        <span id="upload_progress_value">0%</span>
                    </div>

                </div>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="upload_progress_bar"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="row no-gutters">
        <div class="col-md-6 col-lg-6 pr-sm-3 pr-lg-4">
            @if($mode == 'create')
            <p class="info">
                When recreating ad, all the original advert's info except slots will be copied to this page. You can simply add slots and submit or edit some of the content if you wish
            </p>
            @endif

            <p>
                Enter a description and category of your advert. This will help us understand your content
            </p>

            <div class="form-group">
                <label><strong>Category:</strong></label>
                <div class="clearfix">
                    <select class="nice-select w-100" name="category_id" id="category">
                        <option value="">Select Category</option>
                        @foreach (\App\Models\Category::all() as $category)
                        <option @if($advert->category_id == $category->id){{ __('selected') }}@endif value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <small class="text-danger" id="category_error"></small>
            </div>

            <div class="form-group">
                <label><strong>Description:</strong></label><br>
                <span style="font-size: .9em">Should be brief and to the point, telling us about the advert</span>
                <textarea name="description" rows="1" id="description" class="form-control" rows="2" placeholder="e.g. Furnished apartments to let" required>{{ $advert->description }}</textarea>
                <small class="text-danger" id="description_error"></small>
            </div>

            <h5 class="font-weight-600">Content</h5>

            <div class="form-group">

                @if($advert->content['media_path'] != null)
                Previously Selected:
                <div class="mb-2 rounded-lg w-100" style="max-width: 300px; background: #ececec">
                    <div class="embed-responsive embed-responsive-16by9">
                        <div class="embed-responsive-item">
                            <?php
                                $media_path = asset('storage/'.$advert->content['media_path']);
                            ?>
                            @if($advert->content['media_type'] == 'image')
                            <img src="{{ $media_path }}" class="img-fluid">
                            @else
                            <video controls src="{{ $media_path }}" class="img-fluid" type="video/*"></video>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <div class="mb-1">
                    <div class="mb-2">
                        Select an image or video for your ad to upload. Please ensure that what you select meets these guidelines
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-sm border-bottom">
                            <tr class="bg-purple text-white">
                                <th></th>
                                <th>Videos</th>
                                <th>Images</th>
                            </tr>

                            <tr>
                                <th>Orientation:</th>
                                <td>Landscape</td>
                                <td>Landscape</td>
                            </tr>

                            <tr>
                                <th>Dimensions:</th>
                                <td>1920x1080 (Full HD)</td>
                                <td>1920x1080 (Full HD)</td>
                            </tr>

                            <tr>
                                <th>File Size:</th>
                                <td>5Mb to 100Mb</td>
                                <td>1Mb to 10Mb</td>
                            </tr>

                            <tr>
                                <th>File Types:</th>
                                <td>mp4</td>
                                <td>jpg, jpeg and png</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <input type="file" name="media" class="form-control-file" accept="video/*, image/*">
            </div>

            <div class="form-group">
                <label><strong>Text Content:</strong></label>
                <div class="mb-1">
                    If you do not wish to send an image or video, you can send text, which will be played instead
                </div>
                <textarea name="text" rows="2" class="form-control">{{ $advert->content['text'] }}</textarea>
            </div>

        </div>

        <div class="col-md-6 col-lg-6 pl-sm-3">
            <h5 class="font-weight-600">Contacts</h5>

            <div class="mb-1">
                If you need to have contacts shown alongside your ad during airing, e.g. for the clients to use to reach out to your business, you can add them  below
            </div>

            <div class="form-group">
                <label class="mb-0"><strong>Phone Number:</strong></label>
                <input type="tel" class="form-control" name="phone" value="{{ $advert->content['phone'] }}">
            </div>

            <div class="form-group">
                <label class="mb-0"><strong>Email Address:</strong></label>
                <input type="email" class="form-control" name="email" value="{{ $advert->content['email'] }}">
            </div>

            <h5 class="font-weight-600">Slots</h5>

            @if($mode == 'create')
                <p class="mb-0">
                    You can book more than one slot on multiple screens
                </p>

                <div class="slots" id="slots" data-index="0">
                    <small class="text-danger" id="slots_error"></small>
                </div>
                <button class="btn btn-primary py-2" type="button" onclick="initSlotDialog(); $('#add_slot').modal({backdrop: 'static'});">
                    <i class="fa fa-plus mr-1"></i>Book A Slot
                </button>

                <div class="mt-2">
                    <span class="d-inline-block mb-2">Note that your advert is subject to approval by our admins before being aired</span>

                    <button type="button" onclick="showTerms()" class="btn-submit btn btn-default btn-block shadow-none py-2">Read Terms and Submit</button>
                </div>

                @include('web.ads._terms_dialog')
            @else
                <p class="mb-0">
                    Booked slots
                </p>

                <div class="slots mb-3" id="slots" data-index="0">

                    @foreach($advert->slot_groups as $group)
                    <div class="border mb-3 pt-3 pb-2 rounded-lg px-3 bg-white">
                        <div class="d-flex align-items-center mb-2">
                            <span class="rounded-circle bg-warning text-white d-none mr-3 align-items-center justify-content-center" style="min-width: 35px; height: 35px">
                                <i class="fa fa-video-camera"></i>
                            </span>
                            <h6 class="mb-0"><strong>{{ $group->screen->title.' - '.$group->package->name.' ('.$group->package->summary.') - KSh'.number_format($group->price) }}</strong></h6>
                        </div>

                        <div>
                            @if(count($group->slots) == 1)
                                <div class="mb-2">
                                    <span class="d-inline-flex rounded-circle small text-white mr-1 align-items-center justify-content-center" style="min-width: 30px; height: 30px; background: #ff7f5090">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    1 Slot - {{ $group->slots[0]->date }}
                                </div>
                            @else
                            <div class="mt-2" style="white-space: nowrap; overflow-x: auto; scrollbar-width: thin">
                                @foreach($group->slots as $slot)
                                <span class="d-inline-block px-3 py-2 mb-2 mr-1 small font-weight-600" style="border-radius: 20px; background: #eaeaea">
                                    {{ $slot->date }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-2">
                    <button class="btn btn-default btn-block shadow-none py-2">Save Changes</button>
                </div>
            @endif
        </div>
    </div>

    @if($mode == 'create')
    <input type="hidden" name="advert_id" value="{{ $advert->id }}">
    @endif
</form>

<style>
    @media(max-width: 500px){
        #ad_form{
            box-shadow: none !important;
            border-radius: 0 !important;
            border-top: 1px solid #dedede;
            border-bottom: 1px solid #dedede;
        }
    }
</style>

@include('web.ads._new_ad_slot')

@endsection

@section('scripts')
<script>
    var min_date = '{{ $date }}';

    @if($mode == 'create')
    var ad_url = "{{ route('web.adverts.recreate', \Illuminate\Support\Facades\Route::current()->parameters()) }}";
    @else
    var ad_url = "{{ route('web.adverts.edit', \Illuminate\Support\Facades\Route::current()->parameters()) }}";
    var to_url = "{{ route('web.adverts.single', \Illuminate\Support\Facades\Route::current()->parameters()) }}";
    @endif
    var exit_url = "{{ route('web.adverts.submitted') }}";
    var availability_url = "{{ route('web.adverts.slots.availability') }}";
</script>

@if($mode == 'edit')

<script>
    var ad_form = $('#ad_form');
    $('#ad_form .btn-submit').on('click', function(e){
        e.preventDefault();

        showTerms(function(){
            ad_form.addClass('loading');

            $.ajax({
                url: ad_url,
                type: 'post',
                data: new FormData(document.querySelector('#ad_form')),
                contentType: false,
                processData: false,
                success: function(response){
                    ad_form.removeClass('loading');

                    // Ad created
                    if(response.success){
                        window.location.href = to_url;
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
        });
    });
</script>
@else
@section('links')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('js/date_pick.js') }}"></script>

@endif
<script src="{{ asset('js/new_ad.js') }}"></script>
<script src="{{ asset('js/slots.js') }}"></script>
@endsection
