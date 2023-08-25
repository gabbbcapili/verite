@component('mail::message')
# Hello {{ $user->fullName }}!

{!! Helper::settings()->audit_send !!}

@component('mail::button', ['url' => route('audit.show', $audit->id)])
View Audit Details
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
