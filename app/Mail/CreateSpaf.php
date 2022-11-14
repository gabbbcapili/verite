<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Spaf;
use App\Models\User;

class CreateSpaf extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Spaf $spaf, $token)
    {
        $this->user = $user;
        $this->spaf = $spaf;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.supplier.spaf', ['user' => $this->user, 'spaf' => $this->spaf, 'token' => $this->token])
                    ->subject('Supplier Pre-assessment Form');
    }
}
