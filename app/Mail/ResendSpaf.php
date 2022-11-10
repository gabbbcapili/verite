<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Spaf;
use App\Models\User;

class ResendSpaf extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Spaf $spaf)
    {
        $this->user = $user;
        $this->spaf = $spaf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.supplier.resend', ['user' => $this->user, 'spaf' => $this->spaf])
                    ->subject('Supplier Pre-assessment Form');
    }
}
