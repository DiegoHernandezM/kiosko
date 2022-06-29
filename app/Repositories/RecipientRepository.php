<?php

namespace App\Repositories;

use App\Models\Recipient;


class RecipientRepository
{
    protected $mRecipient;

    public function __construct()
    {
        $this->mRecipient = new Recipient();
    }

    public function getRecipient($id)
    {
        return $this->mRecipient->find($id);

    }

    public function getRecipientsByInquest($id)
    {
        return $this->mRecipient->where('inquest_id',$id)->get();

    }

    public function getRecipientByUuid($code)
    {
        return $this->mRecipient->where('uuid', $code)->get()->toArray();
    }

    public function createRecipient($request)
    {
        return $this->mRecipient->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'inquest_id' => $request['inquest_id'],
            'uuid' => $request['uuid']
        ]);
    }
}
