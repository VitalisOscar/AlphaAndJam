Payment status is {{ $status }}

@extends('web.base')

@section('title', 'Payment Received')

@section('section_heading')
Payment Received
@endsection

@section('content')

<p class="lead text-justify" style="">
    Your payment is being processed. This can take upto 5 minutes depending on the payment method.
    Your ad willl be ready for approval once payment processing has been completed
</p>

<div>
    <a href="{{ route('web.user.dashboard') }}" class="btn btn-default shadow-none">Go to Dashboard</a>
</div>

@endsection
