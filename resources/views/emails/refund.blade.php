@component('mail::message')

Hello {{ $userDetails->first_name }} {{ $userDetails->last_name }}!
<p>Your money has been refunded successfully.</p>
Here is the Refund ID.

<b>{{ $refundData }}</b>

Regards,
<br />
{{ config('app.name') }}

@endcomponent