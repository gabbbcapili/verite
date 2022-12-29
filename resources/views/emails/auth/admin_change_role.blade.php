@component('mail::message')
# Greetings Admin

{!! Helper::settings()->admin_change_role_of !!}
@component('mail::panel')
<p>ID: {{ $user->id }}</p>
<p>Email: {{ $user->email }}</p>
<p>Name: {{ $user->fullName }} </p>
@endcomponent

@component('mail::button', ['url' => route('home'), 'color' => 'success'])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
