@component('mail::message')
## Holla {{ $user->name }}
Your product order with reference  {{  $productOrder->payment_reference }} has been completed.
Thanks,<br>
{{ config('app.name') }}
@endcomponent
