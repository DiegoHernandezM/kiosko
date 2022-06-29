<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function showAssociate(Request $request, TaskRepository $rTask)
    {
        try {
            $tasks = $rTask->getByAssociate($request);
            if (!$tasks) {
                return ApiResponses::notFound('No se encuentra ningun asociado en esta cuenta');
            }
            return ApiResponses::okObject($tasks);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function editAssociate($id, TaskRepository $rTask)
    {
        try {
            $task = $rTask->findTask($id);
            if (!$task) {
                return ApiResponses::notFound();
            }
            return ApiResponses::okObject($task);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function create(TaskRequest $request, TaskRepository $rTask)
    {
        try {
            $task = $rTask->createTask($request);
            return ApiResponses::okObject($task);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function addFiles(Request $request, TaskRepository $rTask)
    {
        try {
            $task = $rTask->addFilesTask($request);
            return ApiResponses::okObject($task);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function dropFiles(Request $request, TaskRepository $rTask)
    {
        try {
            $task = $rTask->deleteFileTask($request);
            return ApiResponses::okObject($task);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function downloadFile(Request $request, TaskRepository $rTask)
    {
        try {
            return $rTask->getFile($request);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function update($id, Request $request, TaskRepository $rTask)
    {
        try {
            return $rTask->updateTask($id, $request);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function delete($id, TaskRepository $rTask)
    {
        try {
            $rTask->deleteTask($id);
            return ApiResponses::ok('Recurso eliminado');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
