<?php


namespace App\Repositories;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Associate;
use App\Models\Task;
use Auth;
use Illuminate\Support\Facades\DB;
use Storage;

class TaskRepository
{
    protected $mTask;
    protected $mAssociate;

    public function __construct()
    {
        $this->mTask = new Task();
        $this->mAssociate = new Associate();
    }

    public function getByAssociate($request)
    {
        $dateInit = ($request->init) ? Carbon::parse($request->init)->format('Y-m-d').' 00:00:00' : null;
        $dateEnd = ($request->end) ? Carbon::parse($request->end)->format('Y-m-d').' 23:59:59' : Carbon::now()->format('Y-m-d').' 23:59:59';
        $status = ($request->status) ? $request->status : null;
        $associate = $this->mAssociate->where('user_id', Auth::user()->id)->first();
        if ($associate) {
            return $this->mTask
                ->where('associate_id', $associate->id)
                ->where(function ($q) use ($status, $dateInit, $dateEnd) {
                    if ($status !== null) {
                        $q->where('status', $status);
                    }
                    if ($dateInit !== null) {
                        $q->wherebetween('created_at', [$dateInit, $dateEnd]);
                    }
                    return $q;
                })
                ->orderBy('task_day', 'desc')
                ->get();
        } else {
            return false;
        }
    }

    public function findTask($id)
    {
        return $this->mTask->find($id);
    }

    public function createTask($request)
    {
        $names = [];
        $day = Carbon::now()->format('Y-m-d H:i');
        $limit = Carbon::parse(Carbon::now()->endOfWeek()->format('Y-m-d'.' 23:59'))->subDay(1);
        if ($day <= $limit) {
            if ($request->hasFile('files')) {
                $files =  $request->file('files');
                $names = $this->uploadFiles($files);
            }
            $associate = $this->mAssociate->where('user_id', Auth::user()->id)->first();
            if ($associate) {
                return $this->mTask->create([
                    'name' => $request->name,
                    'task_description' => $request->description ?? null,
                    'files' => count($names) > 0  ? json_encode($names) : null,
                    'task_day' =>  Carbon::parse($request->task_day)->format('Y-m-d'),
                    'hours' => $request->hours,
                    'associate_id' => $associate->id,
                    'status' => $day <= Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d'.' 15:00') ? Task::INTIME : Task::DELAYED
                ]);
            }
        }
        return false;
    }

    public function addFilesTask($request)
    {
        $task = $this->mTask->find((int)$request->id);
        if ($request->hasFile('files')) {
            $files =  $request->file('files');
            $names = $this->uploadFiles($files);
            $newFiles = json_decode(json_encode($names));
            $oldFiles =  $task->files;
            $aFiles = array_merge($oldFiles ?? [], $newFiles);
            $task->files = json_encode($aFiles);
            $task->save();
        }
        return $task;
    }

    public function deleteFileTask($request)
    {
        $task = $this->mTask->find($request->id);
        Storage::disk('s3')->delete('activities/'.$request->name);
        $task->files = json_encode($request->input('files'));
        $task->save();
        return $task;
    }

    public function deleteTask($id)
    {
        $task = $this->mTask->find($id);
        $files = $task->files;
        if ($files !== null) {
            $nameS3 = 'activities/';
            foreach ($files as $file) {
                Storage::disk('s3')->delete($nameS3.$file->name);
            }
        }
        $task->delete();
    }

    public function getFile($request)
    {
        $headers = [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'. $request->name .'"',
        ];
        return \Response::make(Storage::disk('s3')->get('activities/'.$request->name), 200, $headers);
    }

    public function updateTask($id, $request)
    {
        $task = $this->findTask($id);
        $task->name = $request->name;
        $task->task_description = $request->description;
        $task->task_day = Carbon::parse($request->day)->format('Y-m-d');
        $task->hours = $request->hours;
        $task->save();
        return $task;
    }

    private function uploadFiles($files)
    {
        $names = [];
        foreach ($files as $key => $file) {
            $name = Uuid::uuid1().'.'.$file->extension();
            $filePath =  '/activities/';
            $names[] = [
                'original' => $file->getClientOriginalName(),
                'name' => $name,
                'id' => $key
            ];
            Storage::disk('s3')->putFileAs($filePath, $file, $name);
        }
        return $names;
    }
}
