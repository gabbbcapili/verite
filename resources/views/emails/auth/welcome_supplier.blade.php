@component('mail::message')
# Welcome to {{ env('APP_NAME') }}

{!! Helper::settings()->welcome_supplier !!}

@component('mail::panel')
<p>Your Email: {{ $user->email }}</p>
<p>You will be prompted to change your password immediately</p>
@endcomponent
@component('mail::button', ['url' => route('password.reset', $token)])
Reset Password
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
