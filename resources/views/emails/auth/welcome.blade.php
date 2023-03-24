@component('mail::message')
# Welcome to {{ env('APP_NAME') }} {{ $user->fullName }}

{!! Helper::settings()->user_welcome !!}
@component('mail::button', ['url' => route('home'), 'color' => 'success'])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
