<?php


namespace App\Repositories;

use App\Models\Associate;
use App\Models\Task;
use Carbon\Carbon;

class AdminTaskRepository
{
    protected $mTask;
    protected $mAssociate;

    public function __construct()
    {
        $this->mTask = new Task();
        $this->mAssociate = new Associate();
    }

    public function getAll($request)
    {
        $dateInit = ($request->init) ?
            Carbon::parse($request->init)->startOfWeek()->addDay()->format('Y-m-d') :
            Carbon::now()->startOfWeek()->addDay()->format('Y-m-d');
        $dateEnd = Carbon::parse($dateInit)->addDays(4)->format('Y-m-d');
        $week = [
            Carbon::parse($dateInit)->format('Y-m-d'),
            Carbon::parse($dateInit)->addDay()->format('Y-m-d'),
            Carbon::parse($dateInit)->addDays(2)->format('Y-m-d'),
            Carbon::parse($dateInit)->addDay(3)->format('Y-m-d'),
            Carbon::parse($dateInit)->addDay(4)->format('Y-m-d')
        ];
        $associates = $this->mAssociate
            ->with(['tasks' => function ($q) use ($dateInit, $dateEnd) {
                $q->whereBetween('task_day', [$dateInit, $dateEnd]);
                $q->orderBy('task_day');
                return $q;
            }])
            ->get();
        $taskWeek = [];
        foreach ($associates as $k => $associate) {
            if (count($associate->tasks) > 0) {
                foreach ($associate->tasks as $key => $item) {
                    $day = strtolower(Carbon::parse($week[$this->checkDay($week, $item)])->format('l'));
                    $taskWeek[$day] = $item->status;
                    $associates[$k]['week'] = $taskWeek;
                }
                $taskWeek = [];
            } else {
                $associates[$k]['week'] = [];
            }
        }

        return $associates;
    }

    public function getTask($id)
    {
        return $this->mTask
            ->where('id', $id)
            ->with(['associate' => function ($query) {
                $query->select('id', 'name as associate', 'lastnames');
            }])
            ->with(['project' => function ($query) {
                $query->select('id', 'name as project');
            }])
            ->first();
    }

    public function updateTask($id, $request)
    {
        $task = $this->mTask->find($id);
        $task->observation = $request->observation ?? null;
        $task->percent = $request->percent;
        $task->status = $request->status;
        $task->save();
        return $task;
    }

    public function counts()
    {
        $request = new \Illuminate\Http\Request();
        $request->replace([
            'init' => Carbon::now()->startOfWeek()->format('Y-m-d' )
        ]);
        return $this->getAll($request);
    }

    private function checkDay($week, $task)
    {
        return array_search($task->task_day, $week);
    }
}
