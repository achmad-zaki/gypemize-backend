<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $messageContent
    ) {}

    public function build()
    {
        return $this->subject('Testing Mailpit')
                    ->html($this->messageContent);
    }
}