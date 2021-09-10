@extends('web.base')

@section('title', 'Advert submitted')

@section('section_heading')
Advert Submitted
@endsection

@section('content')

<p class="lead text-justify" style="">
    Your advert has been submitted. We will go through the content shortly and you'll be notified if we approved your ad, or you need to make some changes regarding the content
</p>

<div>
    <a href="{{ route('web.adverts.pending') }}" class="btn btn-default shadow-none">View in Pending</a>
</div>

@endsection
