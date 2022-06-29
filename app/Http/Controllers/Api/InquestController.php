<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\InquestRequest;
use App\Repositories\InquestRepository;
use Illuminate\Http\Request;



class InquestController extends Controller
{

    public function show(Request $request, InquestRepository $rInquest)
    {
        $inquests = $rInquest->getAllInquest($request);
        return ApiResponses::okObject($inquests);
    }

    public function create(InquestRequest $request, InquestRepository $rInquest)
    {
        try {
            $inquest = $rInquest->createInquest($request);
            return ApiResponses::okObject($inquest);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function edit($id, InquestRepository $rInquest)
    {
        $inquest = $rInquest->getInquest($id);
        if ($inquest) {
            return ApiResponses::okObject($inquest);
        }
        return ApiResponses::notFound();
    }

    public function update($id, Request $request, InquestRepository $rInquest)
    {
        try {
            $inquest = $rInquest->updateInquest($id, $request);
            return ApiResponses::okObject($inquest);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function destroy($id, InquestRepository $rInquest)
    {
        try {
            $inquest = $rInquest->destroyInquest($id);
            return ApiResponses::okObject($inquest);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function restore($id, InquestRepository $rInquest)
    {
        try {
            $inquest = $rInquest->restoreInquest($id);
            return ApiResponses::okObject($inquest);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function verify($code, InquestRepository $rInquest)
    {
        try {
            $response = $rInquest->verifyInquest($code);
            return ApiResponses::okObject($response);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }

    }

    public function changeStatusInquest($id, InquestRepository $rInquest)
    {
        try {
            $response = $rInquest->expireInquest($id);
            return ApiResponses::okObject($response);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }

    }

    public function getInquestCsv($id, InquestRepository $rInquest)
    {
        try {
            $response = $rInquest->getExcel($id);
            return ApiResponses::okObject($response);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }

    }
}
