@component('mail::message')
# Hello {{ $user->fullName }}

{!! Helper::settings()->user_reset !!}
@component('mail::button', ['url' => route('password.reset', $token)])
Reset Password
@endcomponent

This password reset link will expire in 60 minutes.

{!! Helper::settings()->email_footer !!}

@component('mail::subcopy')
If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <a href="route('password.reset', $token)">{{ route('password.reset', $token) }}</a>
@endcomponent
@endcomponent
