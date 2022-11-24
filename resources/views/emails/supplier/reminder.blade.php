@component('mail::message')
# Hello {{ $user->fullName }}!

{!! Helper::settings()->spaf_reminder !!}

@component('mail::button', ['url' => route('spaf.edit', $spaf)])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
