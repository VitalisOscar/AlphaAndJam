<?php

namespace App\Mail;

use App\Models\Advert;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdvertRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user, $reason, $advert;

    /**
     * Create a new message instance.
     *
     * @param User $user User being notified
     * @param Advert $advert Advert that is rejected
     * @param string $reason Reason for rejecting ad
     * @return void
     */
    public function __construct($user, $advert, $reason)
    {
        $this->user = $user;
        $this->advert = $advert;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('emails.advert_rejected')
            ->with([
                'user' => $this->user,
                'advert' => $this->advert,
                'reason' => $this->reason,
            ])
            ->to($this->user->email)
            ->subject('Advert declined');
    }
}
