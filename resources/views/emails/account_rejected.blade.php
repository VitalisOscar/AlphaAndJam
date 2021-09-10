@extends('emails.base')

@section('message')

Regretably, we are unable to verify the correctness of the details you provided for your new {{ config('app.name') }} account.<br>
Until then, you won't be able to submit any content to us through <a href="{{ config('app.url') }}">our website</a> or app for advertising.<br>
If you think we missed something, please contact us on the same.<br>
If you have no idea of what we are referring to, no need to worry, you can ignore this

@endsection
