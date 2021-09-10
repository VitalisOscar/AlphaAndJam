@extends('emails.base')

@section('message')

you are receiving this email because you need to verify your {{ config('app.name') }} account email.<br>
To proceed, click this link:<br><br>
<a href="{{ route('web.user.account.verify_email', $token) }}">{{ route('web.user.account.verify_email', $token) }}</a>
<br><br>
Note that the link will be unusable after {{ config('auth.email_link_expiry') }} minutes.<br>
If you did not request for this link, you can ignore this email.

@endsection
