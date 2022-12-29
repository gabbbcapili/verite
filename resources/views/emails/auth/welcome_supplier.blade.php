@component('mail::message')
# Welcome to {{ env('APP_NAME') }}

{!! Helper::settings()->welcome_supplier !!}
@component('mail::button', ['url' => route('home'), 'color' => 'success'])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
