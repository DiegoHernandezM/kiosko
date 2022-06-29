<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubareaRequest;
use App\Repositories\SubareaRepository;
use Illuminate\Http\Request;

class SubareaController extends Controller
{

    public function show(Request $request, SubareaRepository $rSubarea)
    {
        $subareas = $rSubarea->getAllSubareas($request);
        return ApiResponses::okObject($subareas);
    }

    public function create(SubareaRequest $request, SubareaRepository $rSubarea)
    {
        try {
            var_dump($request->all());
            $subarea = $rSubarea->createSubarea($request);
            return ApiResponses::okObject($subarea);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function edit($id, SubareaRepository $rSubarea)
    {
        $subarea = $rSubarea->getSubarea($id);
        if ($subarea) {
            return ApiResponses::okObject($subarea);
        }
        return ApiResponses::notFound();
    }

    public function update($id, Request $request, SubareaRepository $rSubarea)
    {
        try {
            $subarea = $rSubarea->updateSubarea($id, $request);
            return ApiResponses::okObject($subarea);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function destroy($id, SubareaRepository $rSubarea)
    {
        try {
            $subarea = $rSubarea->destroySubarea($id);
            return ApiResponses::okObject($subarea);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function restore($id, SubareaRepository $rSubarea)
    {
        try {
            $subarea = $rSubarea->restoreSubarea($id);
            return ApiResponses::okObject($subarea);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function showByArea($area, SubareaRepository $rSubarea)
    {
        try {
            $subareas = $rSubarea->getByArea($area);
            return ApiResponses::okObject($subareas);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}

