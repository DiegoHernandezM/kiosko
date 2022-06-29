<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecipientRequest;
use App\Repositories\RecipientRepository;

class RecipientController extends Controller
{

    public function create(RecipientRequest $request, RecipientRepository $rRecipient)
    {
        try {
            $recipient = $rRecipient->createRecipient($request);
            return ApiResponses::okObject($recipient);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
