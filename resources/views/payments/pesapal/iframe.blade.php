<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | Pay securely with PesaPal</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> --}}

    <style>
        iframe{
            min-height: 100vh !important;
            width: 100% !important;
        }

        *{
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    {!! $iframe !!}
</body>
</html>
