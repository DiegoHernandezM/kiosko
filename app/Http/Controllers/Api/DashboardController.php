<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Repositories\AdminTaskRepository;
use App\Repositories\EventRepository;
use App\Repositories\ObjectiveRepository;
use App\Repositories\PetitionRepository;
use Illuminate\Http\Request;
use App\Repositories\AssociateRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;

class DashboardController extends Controller
{
    public function getData(
        AssociateRepository $rAssociate,
        ObjectiveRepository $rObjective,
        PetitionRepository $rPetition,
        AdminTaskRepository $rTask,
        EventRepository $rEvent
    )
    {
        try {
            $associates = $rAssociate->counts();
            $objectives = $rObjective->pending();
            $petitions = $rPetition->pending();
            $tasks = $rTask->counts();
            $events = $rEvent->thisWeek();
            $projects = 0;

            $data = [
                'associates' => $associates,
                'objectives' => $objectives,
                'petitions' => $petitions,
                'events' => $events,
                'tasks' => $tasks
            ];
            return ApiResponses::okObject($data);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
