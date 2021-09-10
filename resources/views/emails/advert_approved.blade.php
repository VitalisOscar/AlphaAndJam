@extends('emails.base')

@section('message')

Your advert, <strong>{{ $advert->description }}</strong> has been approved.<br>
Additionally, a new invoice with a total amount of <strong>{{ 'KSh '.number_format($invoice->totals['total']) }}</strong> has been generated and is due <strong>{{ $invoice->due_date }}</strong>.
@if(!$user->canPayLater())
Please complete payment before the due date to have your content aired<br>
@endif
<br>
Attached is your generated invoice for this advert.<br>
You can still view the invoice from the portal by clicking <a href="{{ route('web.user.invoices.single', $invoice->number) }}">here</a><br>
To view the advert, click <a href="{{ route('web.adverts.single', $advert->id) }}">here</a><br>

@endsection
