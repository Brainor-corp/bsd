<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLandingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $phone;

    /**
     * Create a new message instance.
     *
     * @param $phone
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Заявка с посадочной страницы');

        return $this->view('emails.landing');
    }
}
