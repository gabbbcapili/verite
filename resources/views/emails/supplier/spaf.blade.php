@component('mail::message')
# Welcome to Verite {{ $user->fullName }}!

Please take time to answer this supplier pre-assesment form, kindly click the button below.
@component('mail::panel')
<p>Your Email: {{ $user->email }}</p>
<p>You will be prompted to change your password immediately</p>
@endcomponent

@component('mail::button', ['url' => route('password.reset', $token)])
Go to Site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
