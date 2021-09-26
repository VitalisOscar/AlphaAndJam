<?php
    $ad_service = resolve(\App\Services\AdService::class);
    $date = $ad_service->getEarliestSlotBookingDate();
?>

@extends('web.base')

@section('title', config('app.name').' | Create an advert')

@section('section_heading', 'Get Started')

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

<form method="POST" enctype="multipart/form-data" id="ad_form" class="with-loader shadow-sm bg-white rounded-lg mx--3 mx-sm-0 px-4 py-4 mb-4">
    @csrf

    <div class="loader position-fixed top-0 bottom-0 left-0 right-0">
        <div class="bg-white rounded p-3 shadow" style="z-index: 1000; width: 300px; max-width: 100%;">


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
                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="upload_progress_bar"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="tab-content">

        <div id="content_tab" class="tab-pane active">
            <div class="row no-gutters">
                <div class="col-md-5 col-lg-5 pr-sm-3 pr-lg-4">
                    <h5 class="font-weight-600">What are you advertising?</h5>

                    @if(!auth()->user()->profileComplete())
                    <p class="info danger">
                        Some information about your account is not complete. Your advert will not be submitted
                        <br><a href="{{ route('web.user.account') }}" class="btn btn-link px-0 py-1"><i class="fa fa-user mr-1"></i>Complete Profile</a>
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
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger" id="category_error"></small>
                    </div>

                    <div class="form-group">
                        <label><strong>Description:</strong></label><br>
                        <span style="font-size: .9em">Should be brief and to the point, telling us about the advert</span>
                        <textarea id="description" name="description" rows="1" placeholder="e.g. Furnished apartments to let" class="form-control" required></textarea>
                        <small class="text-danger" id="description_error"></small>
                    </div>

                </div>

                <div id="content_tab" class="col-md-7 col-lg-7 pl-sm-3 pl-lg-4">

                    <h5 class="font-weight-600">Content</h5>

                    <div class="form-group mb-4">
                        <div>
                            <div class="mb-1">
                                <div class="mb-2">
                                    Select an image or video for your ad to upload. Please ensure that what you select meets these guidelines
                                </div>

                                <div class="table-responsive mb-2">
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
                            <input type="file" id="media" name="media" class="form-control-file" accept="video/*, image/*">

                            <small class="text-danger" id="media_error"></small>
                        </div>
                    </div>

                </div>

                <div class="col-12 pt-3 border-top text-right">
                    <button type="button" onclick="goToSlots()" class="btn btn-default shadow-none">Next&nbsp;<i class="fa fa-angle-double-right"></i></button>
                </div>
            </div>
        </div>

        <div id="slots_tab" class="tab-pane">

            @include('web.ads._new_slot')

        </div>

    </div>

    @include('web.ads._terms_dialog')
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

@endsection

@section('links')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        var min_date = '{{ $date }}';
        var ad_url = "{{ route('web.adverts.create') }}";
        var exit_url = "{{ route('web.adverts.submitted') }}";
        var availability_url = "{{ route('web.adverts.slots.availability') }}";

        @php
        $prices = \App\Models\ScreenPrice::all();
        @endphp

        var prices = {
            @foreach ($prices as $price)
            {{ 's'.$price->screen_id.'_p'.$price->package_id.':'.$price->price.',' }}
            @endforeach
        };

    </script>

    <script src="{{ asset('js/slots.js') }}"></script>
    <script src="{{ asset('js/new_ad.js') }}"></script>

    <script>
        $('#agree_terms').attr('checked', '');
        function showTerms(){
            submit_data();
        }
    </script>
@endsection
