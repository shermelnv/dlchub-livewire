<?php

namespace App\Mail;



namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {

        return $this->subject('Account Status Notification')
                    ->markdown('emails.verification')
                    ->with(['user' => $this->user]);
    }
}
