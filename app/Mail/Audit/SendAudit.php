<?php

namespace App\Mail\Audit;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Audit;

class SendAudit extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Audit $audit)
    {
        $this->user = $user;
        $this->audit = $audit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.audit.sendAudit', ['user' => $this->user, 'audit' => $this->audit])
                    ->subject('New Audit');
    }
}
