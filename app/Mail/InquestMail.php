<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $body;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva encuesta del sistema KIOSKO')
            ->view('emails.inquest')->with(['body' => $this->body]);
    }
}
