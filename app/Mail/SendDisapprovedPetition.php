<?php

namespace App\Mail;

use App\Models\Associate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDisapprovedPetition extends Mailable
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
        return $this->subject('Petición rechazada')->view('emails.petitionDisapproved')
            ->with([
                'associate' => $this->associate,
            ]);
    }
}
