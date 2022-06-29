<?php

namespace App\Managers;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Log;

class MailsManager
{
    public function buildMail($to, $subject, $body)
    {
        try {
            Mail::send([], [], function (Message $message) use ($to, $subject, $body) {
                $message
                    ->to($to)
                    ->from('amartinezw@agarcia.com.mx', 'Abraham Martinez')
                    ->subject($subject)
                    ->setBody($body, 'text/html');
            });
        } catch (\Exception $e) {
            Log::error('Error en '.__METHOD__.' línea '.$e->getLine().':'.$e->getMessage());
            return $e->getMessage();
        }
    }
}
