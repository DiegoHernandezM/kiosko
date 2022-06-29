<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Repositories\AdminTaskRepository;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function all(Request $request, AdminTaskRepository $rAdminTask)
    {
        try {
            $tasks = $rAdminTask->getAll($request);
            return ApiResponses::okObject($tasks);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function find($id, AdminTaskRepository $rAdminTask)
    {
        try {
            $task = $rAdminTask->getTask($id);
            return ApiResponses::okObject($task);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function update($id, Request $request, AdminTaskRepository $rAdminTask)
    {
        try {
            $task = $rAdminTask->updateTask($id, $request);
            return ApiResponses::okObject($task);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function getReport(Request $request, ReportRepository $rReport){
        try {
            return $rReport->getWeekTaskExcel(false, $request->init);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
