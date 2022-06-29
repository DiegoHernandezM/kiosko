<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function show(Request $request, ProjectRepository $rProject)
    {
        $projects = $rProject->getAllProjects($request);
        return ApiResponses::okObject($projects);
    }

    public function create(ProjectRequest $request, ProjectRepository $rProject)
    {
        try {
            $project = $rProject->createProject($request);
            return ApiResponses::okObject($project);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function edit($id, ProjectRepository $rProject)
    {
        $project = $rProject->getProject($id);
        if ($project) {
            return ApiResponses::okObject($project);
        }
        return ApiResponses::notFound();
    }

    public function update($id, Request $request, ProjectRepository $rProject)
    {
        try {
            $project = $rProject->updateProject($id, $request);
            return ApiResponses::okObject($project);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function destroy($id, ProjectRepository $rProject)
    {
        try {
            $rProject->destroyProject($id);
            return ApiResponses::ok('Recurso eliminado');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function restore($id, ProjectRepository $rProject)
    {
        try {
            $rProject->restoreProject($id);
            return ApiResponses::ok('Recurso recuperado');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
