@component('mail::message')

Hello {{ $name }}!

Here is a Giftcard code for redeem.

<b>{{ $code }}</b>

Regards,
<br />
{{ config('app.name') }}

@endcomponent