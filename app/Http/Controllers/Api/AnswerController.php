<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerRequest;
use App\Repositories\AnswerRepository;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function create(AnswerRequest $request, AnswerRepository $rAnswer)
    {
        try {
            $answer = $rAnswer->createAnswer($request);
            return ApiResponses::okObject($answer);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
