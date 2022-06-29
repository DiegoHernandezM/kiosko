<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Support\Facades\DB;


class ProjectRepository
{
    protected $mProject;
    public function __construct()
    {
        $this->mProject = new Project();
    }

    public function getAllProjects($request)
    {
        return $this->mProject->withTrashed()->get();
    }

    public function createProject($request)
    {
        return $this->mProject->create($request->all());
    }

    public function getProject($id)
    {
        return $this->mProject->find($id);
    }

    public function updateProject($id, $request)
    {
        $project = $this->getProject($id);
        if ($project) {
            $project->name = $request->name;
            $project->description = $request->description;
            $project->project_number = $request->project_number;
            $project->save();
            return $project;
        }
    }

    public function destroyProject($id)
    {
        $this->mProject->destroy($id);
    }

    public function restoreProject($id)
    {
        $this->mProject->withTrashed()->find($id)->restore();
    }

    public function projectStats()
    {
        $projects = $this->mProject->with(['tasks' => function($q) {
            $q->select('project_id', DB::raw('COUNT(*) as count'))->groupBy('project_id');
        }])->get();
        return $projects;
    }
}
