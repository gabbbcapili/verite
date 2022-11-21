@component('mail::message')
# Welcome to Verite {{ $user->fullName }}!

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
