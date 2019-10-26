<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class User1CRegister extends Mailable
{
    use Queueable, SerializesModels;

    private $user, $password;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $password
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Регистрация на сайте БСД')
            ->view('emails.1c-user-register')
            ->with([
                'user' => $this->user,
                'password' => $this->password
            ]);
    }
}
