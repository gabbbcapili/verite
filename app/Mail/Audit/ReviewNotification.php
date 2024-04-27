<?php

namespace App\Mail\Audit;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AuditReview;

class ReviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AuditReview $auditReview)
    {
        $this->auditReview = $auditReview;
        $this->auditFormHeader = $auditReview->header;
        $this->auditForm = $this->auditFormHeader->form;
        $this->template = $this->auditForm->template;
        $this->audit = $this->auditForm->audit;
        $this->user = $this->auditFormHeader->created_by_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.audit.review_notification', ['auditReview' => $this->auditReview, 'user' => $this->user, 'auditFormHeader' => $this->auditFormHeader, 'auditForm' => $this->auditForm])
                    ->subject('Audit Form Review ' . $this->audit->schedule->title . ' - ' . $this->template->name . ' - ' . $this->auditReview->group->header);
    }
}
