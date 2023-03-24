@component('mail::message')
# Welcome to {{ env('APP_NAME') }} {{ $user->fullName }}

{!! Helper::settings()->welcome_client !!}

@component('mail::panel')
<p>Your Email: {{ $user->email }}</p>
<p>This reset password link will expire in 60 minutes</p>
@endcomponent
@component('mail::button', ['url' => route('password.reset', $token)])
Reset Password
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
