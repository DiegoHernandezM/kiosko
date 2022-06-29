<?php

namespace App\Repositories;

use App\Models\Inquest;
use App\Models\Answer;
use App\Mail\MailAmazonSes;
use Ramsey\Uuid\Uuid;


class InquestRepository
{
    protected $mInquest;
    protected $mAnswer;
    protected $rRecipient;
    protected $rMails;


    public function __construct()
    {
        $this->mInquest = new Inquest();
        $this->mAnswer = new Answer();
        $this->rRecipient = new RecipientRepository();
        $this->rMails = new MailsRepository();

    }

    public function getAllInquest()
    {
        return $this->mInquest->select('inquests.*')->get();
    }

    public function createInquest($request)
    {
        $content = [];
        foreach ($request['content'] as $key => $questions) {
            $questions['id'] = $key;
            $content[] = $questions;
        }
        $inquest = $this->mInquest->create([
            'name' => $request->name,
            'description' => $request->description,
            'content' => json_encode($content)
        ]);

        $recipients = $request->emails;

        if ($inquest) {
            foreach ($recipients as $recipient) {

                $recipient['inquest_id'] = $inquest->id;
                $recipient['uuid'] = Uuid::uuid1()->toString();

                $saveRecipients = $this->rRecipient->createRecipient($recipient);

                if($saveRecipients) {
                    $body = [
                        'recipient' => $saveRecipients,
                    ];
                    $this->rMails->sendInquest($body);
                }
            }
        }
        return $inquest;
    }

    public function getInquest($id)
    {
        return $this->mInquest->withTrashed()->find($id);
    }

    public function updateInquest($id, $request)
    {
        $inquest = $this->getInquest($id);
        if ($inquest) {
            $inquest->name = $request->name;
            $inquest->description = $request->description;
            $inquest->content = json_encode($request->content);
            $inquest->save();
            return $inquest;
        }
    }

    public function destroyInquest($id)
    {
        return $this->mInquest->destroy($id);
    }

    public function restoreInquest($id)
    {
        return $this->mInquest->withTrashed()->find($id)->restore();
    }

    public function verifyInquest($code)
    {
        $recipient = $this->rRecipient->getRecipientByUuid($code);

        if (count($recipient) > 0 ) {
            if ($recipient[0]['status'] === 0) {
                $inquest = $this->getInquest($recipient[0]['inquest_id']);
                $response = [
                    'recipient' => $recipient[0],
                    'inquest' => $inquest
                ];
            }else {
                $response = [
                    'recipient' =>  $recipient,
                    'inquest' => ['expired' => 0]];
            }
        } else {
            $response = [
                'recipient' => [],
                'inquest' => []
            ];
        }

        return $response;
    }

    public function expireInquest($id)
    {
        return $this->mInquest->findOrFail($id)->update(['expired' => 1]);
    }

    public function getExcel($id)
    {
        $results = $this->mInquest
            ->select('inquests.created_at', 'inquests.name as iname', 'r.name as rname', 'r.email', 'a.content', 'a.comments')
            ->join('recipients as r', 'inquests.id', '=', 'r.inquest_id')
            ->join('answers as a', 'a.id', '=', 'r.answer_id')
            ->where('r.inquest_id', $id)
            ->get()->toArray();

        if ($results) {
            foreach ($results as $key => $value) {
                foreach ($value['content'] as $item) {
                    $results[$key]['format_content'][] = 'Pregunta: '.$item->question. ' '. 'respuesta: '. $item->answers[0];
                }
                $results[$key]['new_content'] = implode(',', $results[$key]['format_content']);
                $results[$key]['new_content'] = str_replace (',', ' ', $results[$key]['new_content']);
                unset($results[$key]['content']);
                unset($results[$key]['format_content']);
            }
        }
        return $results;
    }
}
