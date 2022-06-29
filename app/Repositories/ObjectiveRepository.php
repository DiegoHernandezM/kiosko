<?php

namespace App\Repositories;

use App\Models\Associate;
use App\Models\Objective;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Auth;
use Storage;

class ObjectiveRepository
{
    protected $mObjective;
    protected $mAssociate;
    protected $rMails;

    public function __construct()
    {
        $this->mObjective = new Objective();
        $this->mAssociate = new Associate();
        $this->rMails = new MailsRepository();
    }

    public function all($request)
    {
        $associate = $this->mAssociate->where('user_id', Auth::user()->id)->first();
        $year = ($request->year) ? $request->year : null;
        $quarter = ($request->quarter) ? $request->quarter : null;

        return $this->mObjective
            ->where(function ($q) use ($year, $quarter, $associate) {
                $q->where('associate_id', $associate->id);
                $q->where('year', $year);
                $q->where('quarter', $quarter);
                return $q;
        })->get();
    }

    public function findObjective($id)
    {
        return $this->mObjective->find($id);
    }

    public function createObjective($request)
    {
        $associate = $this->mAssociate->where('user_id', Auth::user()->id)->first();
        $names = [];
        if ($request->hasFile('evidence')) {
            $files =  $request->file('evidence');
            $names = $this->uploadFiles($files);
        }
        if ($associate) {
            $objective =  $this->mObjective->create([
                'associate_id' => $associate->id,
                'name' => $request->name,
                'description' => $request->description ?? null,
                'weighing' => $request->weighing,
                'evidence' => count($names) > 0  ? json_encode($names) : null,
                'year' => $request->year,
                'quarter' => $request->quarter,
                'approved' => false,
                'progress' => $request->progress
            ]);
            if ($objective) {
                $this->rMails->sendNewObjective($associate);
            }
        }
        return false;
    }

    public function deleteObjective($id)
    {
        $objective = $this->mObjective->find($id);
        if (!$objective->approved) {
            $files = $objective->evidence;
            if ($files !== null) {
                $nameS3 = 'objectives/';
                foreach ($files as $file) {
                    Storage::disk('s3')->delete($nameS3.$file->name);
                }
            }
            $objective->delete();
        }
    }

    public function addFiles($request)
    {
        $objective = $this->mObjective->find((int)$request->id);
        if ($request->hasFile('evidence')) {
            $files =  $request->file('evidence');
            $names = $this->uploadFiles($files);
            $newFiles = json_decode(json_encode($names));
            $oldFiles =  $objective->evidence;
            $aFiles = array_merge($oldFiles ?? [], $newFiles);
            $objective->evidence = json_encode($aFiles);
            $objective->save();
        }
        return $objective;
    }

    public function deleteFile($request)
    {
        $objective = $this->mObjective->find($request->id);
        Storage::disk('s3')->delete('objectives/'.$request->name);
        $objective->evidence = json_encode($request->input('evidence'));
        $objective->save();
        return $objective;
    }

    public function getFile($request)
    {
        $headers = [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'. $request->name .'"',
        ];
        return \Response::make(Storage::disk('s3')->get('objectives/'.$request->name), 200, $headers);
    }

    public function updateObjective($id, $request)
    {
        $objective = $this->findObjective($id);
        if ($request->isAssociate) {
            $objective->name = $request->name;
            $objective->description = $request->description;
            $objective->weighing = $request->weighing;
            $objective->year = $request->year;
            $objective->quarter = $request->quarter;
            $objective->progress = $request->progress;
        } else {
            $objective->approved = $request->approved;
            $objective->observation = $request->observation;
            $objective->real_weighing = $request->realWeighing;
        }
        $objective->save();
        return $objective;
    }

    private function uploadFiles($files)
    {
        $names = [];
        foreach ($files as $key => $file) {
            $name = Uuid::uuid1().'.'.$file->extension();
            $filePath =  '/objectives/';
            $names[] = [
                'original' => $file->getClientOriginalName(),
                'name' => $name,
                'id' => $key
            ];
            Storage::disk('s3')->putFileAs($filePath, $file, $name);
        }
        return $names;
    }

    public function getByAssociates($request)
    {
        $year = ($request->year) ? $request->year : null;
        $quarter = ($request->quarter) ? $request->quarter : null;
        return $this->mAssociate
            ->with(['objectives' => function ($q) use ($year, $quarter) {
                $q->where('year', $year);
                $q->where('quarter', $quarter);
                return $q;
            }])
            ->get();
    }

    public function pending()
    {
        return $this->mObjective->where('quarter', Carbon::now()->quarter)->where('approved', false)->count();
    }
}
