<?php

namespace App\Mail\Audit;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AuditFormHeader;

class FormReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AuditFormHeader $auditFormHeader)
    {
        $this->auditFormHeader = $auditFormHeader;
        $this->auditForm = $auditFormHeader->form;
        $this->user = $auditFormHeader->created_by_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.audit.form_reminder', ['auditFormHeader' => $this->auditFormHeader, 'user' => $this->user, 'auditForm' => $this->auditForm])
                    ->subject('Audit Form Completion Reminder');
    }
}
