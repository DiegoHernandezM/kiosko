<?php

namespace App\Mail;

use App\Models\Associate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthdayMail extends Mailable
{
    use Queueable, SerializesModels;

    public $associate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Associate $associate)
    {
        $this->associate = $associate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Mañana es cumpleaños de ' . $this->associate->name . ' ' . $this->associate->lastnames)
            ->view('emails.birthdayreminder')
            ->with(['associate' => $this->associate]);
    }
}
