@component('mail::message')
# Welcome to {{ env('APP_NAME') }}

{!! Helper::settings()->user_welcome !!}
@component('mail::panel')
    Hello {{ $user->fullName }}!
@endcomponent
@component('mail::button', ['url' => route('home'), 'color' => 'success'])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
