@component('mail::message')
# Hi {{ $user->fullName }}!

This is a reminder to complete the Audit Form you started <b>{{ $auditFormHeader->created_at->diffForHumans() }}</b> with assigned name of <b>{{ $auditFormHeader->name }}</b>.

To view or edit the submission please click the button below.

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
