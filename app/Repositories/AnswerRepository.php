<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Recipient;

class AnswerRepository
{
    protected $mAnswer;
    protected $rRecipient;
    protected $mRecipient;


    public function __construct()
    {
        $this->mAnswer = new Answer();
        $this->rRecipient = new RecipientRepository();
        $this->mRecipient = new Recipient();

    }

    public function createAnswer($request)
    {
        $answer = $this->mAnswer->create([
            'content' => json_encode($request['content']),
            'comments' => $request['comments']
        ]);

        if ($answer) {
            $this->mRecipient->where('uuid', $request['uuid'])
                ->update([
                'answer_id' => $answer->id,
                'status' => 1
            ]);
        }
        return $answer;
    }
}
