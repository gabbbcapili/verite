@component('mail::message')
# Hello {{ $user->fullName }}!

{!! Helper::settings()->spaf_completed !!}

@component('mail::panel')
<p>Client Name: {{ $spaf->client->fullName }}</p>
@if($spaf->supplier)
<p>Supplier Name: {{ $spaf->supplier->fullName }}</p>
@endif
<p>Form: {{ $spaf->template->name }}</p>
<p>Assessment Type: {{ $spaf->template->typeDisplay }}</p>
<p>Status: Completed</p>
<p>{{ env('APP_NAME') }} Notes: {{ $spaf->notes }}</p>
@endcomponent



{!! Helper::settings()->email_footer !!}
@endcomponent
