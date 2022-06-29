<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Repositories\AreaRepository;
use Illuminate\Http\Request;

class AreaController extends Controller
{

    public function show(Request $request, AreaRepository $rArea)
    {
        $areas = $rArea->getAllAreas($request);
        return ApiResponses::okObject($areas);
    }

    public function create(AreaRequest $request, AreaRepository $rArea)
    {
        try {
            $area = $rArea->createArea($request);
            return ApiResponses::okObject($area);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function edit($id, AreaRepository $rArea)
    {
        $area = $rArea->getArea($id);
        if ($area) {
            return ApiResponses::okObject($area);
        }
        return ApiResponses::notFound();
    }

    public function update($id, Request $request, AreaRepository $rArea)
    {
        try {
            $area = $rArea->updateArea($id, $request);
            return ApiResponses::okObject($area);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function destroy($id, AreaRepository $rArea)
    {
        try {
            $area = $rArea->destroyArea($id);
            return ApiResponses::okObject($area);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function restore($id, AreaRepository $rArea)
    {
        try {
            $area = $rArea->restoreArea($id);
            return ApiResponses::okObject($area);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}

