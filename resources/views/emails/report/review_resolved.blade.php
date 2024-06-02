@component('mail::message')
# Hi {{ $user->fullName }}!

{{ $reportReview->updated_by_user->fullName }} marked your review as resolved.

@component('mail::panel')
<p>Target Group: {{ $reportReview->target_group_display }}</p>
<p>Message: {{ $reportReview->message }}</p>
<p>File Link: {!! $reportReview->file_display !!}</p>
<p>Status: {{ $reportReview->status }}</p>
<p>Resolved Notes: {{ $reportReview->resolve_notes }}</p>
@endcomponent

To view the details of the review please click the button below.

@component('mail::button', ['url' => route('report.edit', $report)])
	Report Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
