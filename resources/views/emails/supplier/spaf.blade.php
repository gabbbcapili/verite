@component('mail::message')
# Welcome to Verite {{ $user->fullName }}!

Please take time to answer this supplier pre-assesment form, kindly click the button below.
@component('mail::panel')
<p>Your Email: {{ $user->email }}</p>
<p>Your Password: {{ $password }}</p>
@endcomponent

@component('mail::button', ['url' => route('spaf.edit', $spaf)])
Go to Site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
