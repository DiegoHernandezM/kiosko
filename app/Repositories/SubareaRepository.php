<?php

namespace App\Repositories;

use App\Models\Subarea;

class SubareaRepository
{
    protected $mSubarea;
    public function __construct()
    {
        $this->mSubarea = new Subarea();
    }

    public function getAllSubareas($request)
    {
        $subareas = $this->mSubarea
            ->select('subareas.*', 'areas.name as area_name')
            ->join('areas', 'areas.id', '=', 'subareas.area_id')
            ->withTrashed()
            ->get();
        return $subareas;
    }

    public function createSubarea($request)
    {
        return $this->mSubarea->create($request->all());
    }

    public function getSubarea($id)
    {
        return $this->mSubarea->withTrashed()->find($id);
    }

    public function updateSubarea($id, $request)
    {
        $subarea = $this->getSubarea($id);
        if ($subarea) {
            $subarea->name = $request->name;
            $subarea->area_id = $request->areaId;
            $subarea->save();
            return $subarea;
        }
    }

    public function destroySubarea($id)
    {
        return $this->mSubarea->destroy($id);
    }

    public function restoreSubarea($id)
    {
        return $this->mSubarea->withTrashed()->find($id)->restore();
    }

    public function getByArea($area)
    {
        return $this->mSubarea->where('area_id', $area)->get();
    }
}
