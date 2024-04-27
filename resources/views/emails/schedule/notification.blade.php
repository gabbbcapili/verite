@component('mail::message')
# Hi {{ $user->fullName }}!

You were assigned as {{ $eventUser->role }} to an audit for {{ $event->titleComputed }}

Start Date: {{ $eventUser->start_date }}

End Date: {{ $eventUser->end_date }}

To accept or reject the schedule kindly click the button below.

@component('mail::button', ['url' => route('schedule.editNew', $event)])
View Schedule Details
@endcomponent

Attached is the calendar invite to add it to your calendar.

{!! Helper::settings()->email_footer !!}
@endcomponent
