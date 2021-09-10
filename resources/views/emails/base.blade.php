Hello {{ $user->name }},<br>
@yield('message')
<br><br>
With regards,<br>
{{ config('app.name') }}
