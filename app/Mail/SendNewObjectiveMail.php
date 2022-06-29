<?php

namespace App\Mail;

use App\Models\Associate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNewObjectiveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $associate;
    public $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $manager, Associate $associate)
    {
        $this->associate = $associate;
        $this->manager = $manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nuevo Objetivo creado por colaborador')->view('emails.newObjective')
            ->with([
                'associate' => $this->associate,
                'manager' => $this->manager,
            ]);
    }
}
