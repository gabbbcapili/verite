@component('mail::message')
# Hello {{ $user->fullName }}!

{!! Helper::settings()->user_changed_role !!}
@component('mail::panel')
<p>New Role: {{ $user->getRoleNames()->first() }}</p>
@endcomponent
@component('mail::button', ['url' => route('home'), 'color' => 'success'])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
