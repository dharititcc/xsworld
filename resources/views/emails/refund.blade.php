@component('mail::message')

Hello {{ $userDetails->first_name }} {{ $userDetails->last_name }}!

<p>Your money has been refunded successfully.</p>

<p>Refund Amount is <b>{{$order->restaurant->country->symbol}}{{$order->amount}}</b></p>

<p>Your Order Id is <b>#{{$order->id}}</b>.</p>

<p>Your Refund Id is <b>{{ $refundData }}</b>.</p>

Regards,
<br />
{{ config('app.name') }}

@endcomponent