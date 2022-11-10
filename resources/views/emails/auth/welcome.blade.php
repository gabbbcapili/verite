@component('mail::message')
# Welcome to Verite

@component('mail::panel')
    Hello {{ $user->fullName }}!
@endcomponent
@component('mail::button', ['url' => route('home'), 'color' => 'success'])
Go to Site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
