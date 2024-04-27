@component('mail::message')
# Hi {{ $user->fullName }}!

{{ $auditReview->created_by_user->fullName }} added a review on your answered audit form titled <b>"{{ $auditFormHeader->name }}"</b>

@component('mail::panel')
<p>Group: {{ $auditReview->group->header }}</p>
<p>Message: {{ $auditReview->message }}</p>
<p>Status: {{ $auditReview->status }}</p>
@endcomponent

To view the details of the review please click the button below.

@component('mail::button', ['url' => route('auditForm.edit', [
									'auditFormHeader' => $auditFormHeader, 
									'template' => $auditForm->template ? $auditForm->template->slug : '', 
									'q' => 'p', 
									'assigned_name' => $auditFormHeader->name, 
									'single_multiple' => $auditForm->isMultiple ? 'Multiple' : 'Single',
									'type' => $auditForm->template ? $auditForm->template->audit_type : '',
								]
							)])
	Audit Form Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
