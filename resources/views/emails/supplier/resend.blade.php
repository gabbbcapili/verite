@component('mail::message')
# Hello {{ $user->fullName }}

Thank you for your time answering spaf however {{ env('APP_NAME') }} needs additional info,
@component('mail::panel')
<p>Your Email: {{ $user->email }}</p>
<p>{{ env('APP_NAME') }} Notes: {{ $spaf->notes }}</p>
@endcomponent

If you wish to answer it now kindly click the button below. Thank you
@component('mail::button', ['url' => route('spaf.edit', $spaf)])
Go to Site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
