@component('mail::message')

Hello {{ $name }}!

Please click the button below to verify your email.


<b>{{ $code }}</b>

If you did not create an account, no further action is required.

Regards,
<br />
{{ config('app.name') }}

@endcomponent