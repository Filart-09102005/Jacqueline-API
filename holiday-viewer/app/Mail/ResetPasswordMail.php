<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $actionUrl;

    public function __construct($name, $actionUrl)
    {
        $this->name = $name;
        $this->actionUrl = $actionUrl;
    }

    public function build()
    {
        return $this->subject('Reset Your Password')
                    ->view('emails.reset-password');
    }
}
