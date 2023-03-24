@component('mail::message')
# Hello {{ $user->fullName }}!

{!! Helper::settings()->spaf_resend !!}
@component('mail::panel')
<p>Client Name: {{ $spaf->client->fullName }}</p>
@if($spaf->supplier)
<p>Supplier Name: {{ $spaf->supplier->fullName }}</p>
@endif
<p>Form: {{ $spaf->template->name }}</p>
<p>{{ env('APP_NAME') }} Notes: {{ $spaf->notes }}</p>
@endcomponent

If you wish to answer it now kindly click the button below. Thank you
@component('mail::button', ['url' => route('spaf.edit', $spaf)])
Go to Site
@endcomponent

{!! Helper::settings()->email_footer !!}
@endcomponent
