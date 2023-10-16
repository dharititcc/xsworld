@component('mail::message')

Hello {{ $user->first_name }}!

Please click the button below to verify your email.

@component('mail::button', ['url' => $url, 'target' => '_blank'])
Verify Email Address
@endcomponent

If you did not create an account, no further action is required.

Regards,
<br />
{{ config('app.name') }}

@endcomponent