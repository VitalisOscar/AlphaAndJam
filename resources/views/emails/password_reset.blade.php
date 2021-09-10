@extends('emails.base')

@section('message')

you are receiving this email because you requested to reset your password for your {{ config('app.name') }} account.<br>
To proceed, click this link:<br><br>
<a href="{{ route('web.auth.reset_password', $token) }}">{{ route('web.auth.reset_password', $token) }}</a>
<br><br>
Note that the link will be unusable after {{ config('auth.passwords.users.expire') }} minutes.<br>
If you did not request for this link, you can ignore this email.

@endsection
