@extends('emails.base')

@section('message')

Your account for {{ config('app.name') }} has been verified and approved<br>
This means you can now log onto <a href="{{ config('app.url') }}">our website</a> and submit your digital content for advertising right from your phone, tablet or PC.<br>
All the best as you join us.

@endsection
