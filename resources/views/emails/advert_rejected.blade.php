@extends('emails.base')

@section('message')

Your advert, <strong>{{ $advert->description }}</strong> has been declined and cannot be aired because of the following:<br>
{{ $reason }}<br>
Open the advert by clicking <a href="{{ route('web.adverts.single', $advert->id) }}">here</a><br> and correct the content as guided, then submit again

@endsection
