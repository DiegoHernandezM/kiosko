<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Repositories\EventRepository;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function all(EventRepository $rEvent)
    {
        try {
            return ApiResponses::okObject($rEvent->getAllEvents());
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function create(EventRequest $request, EventRepository $rEvent)
    {
        try {
            return ApiResponses::okObject($rEvent->createEvent($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function update($id, Request $request, EventRepository $rEvent)
    {
        try {
            return ApiResponses::okObject($rEvent->updateEvent($id, $request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function delete($id, EventRepository $rEvent)
    {
        try {
            $rEvent->deleteEvent($id);
            return ApiResponses::ok();
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
