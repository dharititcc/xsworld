@component('mail::message')

Hello {{ $name }}!

Here is a invoice for your order #{{ $order->id }}.

Regards,
<br />
{{ config('app.name') }}

@endcomponent