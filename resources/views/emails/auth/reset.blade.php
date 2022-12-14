@component('mail::message')
# Welcome to {{ env('APP_NAME') }} {{ $user->fullName }}!

{!! Helper::settings()->user_reset !!}
@component('mail::panel')
<p>Your Email: {{ $user->email }}</p>
<p>You will be prompted to change your password immediately</p>
@endcomponent

@component('mail::button', ['url' => route('password.reset', $token)])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
