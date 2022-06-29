<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\ObjectiveRequest;
use App\Repositories\ObjectiveRepository;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function show(Request $request, ObjectiveRepository $rObjective)
    {
        try {
            return ApiResponses::okObject($rObjective->all($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function edit($id, ObjectiveRepository $rObjective)
    {
        try {
            $objective = $rObjective->findObjective($id);
            if (!$objective) {
                return ApiResponses::notFound();
            }
            return ApiResponses::okObject($objective);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function create(ObjectiveRequest $request, ObjectiveRepository $rObjective)
    {
        try {
            return ApiResponses::okObject($rObjective->createObjective($request));
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function addFiles(Request $request, ObjectiveRepository $rObjective)
    {
        try {
            $objectives = $rObjective->addFiles($request);
            return ApiResponses::okObject($objectives);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function dropFiles(Request $request, ObjectiveRepository $rObjective)
    {
        try {
            $objectives = $rObjective->deleteFile($request);
            return ApiResponses::okObject($objectives);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function downloadFile(Request $request, ObjectiveRepository $rObjective)
    {
        try {
            return $rObjective->getFile($request);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function update($id, Request $request, ObjectiveRepository $rObjective)
    {
        try {
            return $rObjective->updateObjective($id, $request);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function delete($id, ObjectiveRepository $rObjective)
    {
        try {
            $rObjective->deleteObjective($id);
            return ApiResponses::ok('Recurso eliminado');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function allAssociates(Request $request, ObjectiveRepository $rObjective)
    {
        try {
            $associates = $rObjective->getByAssociates($request);
            return ApiResponses::okObject($associates);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

}
