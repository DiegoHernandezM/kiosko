<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssociateRequest;
use App\Repositories\AssociateRepository;
use Illuminate\Http\Request;
use Log;

class AssociateController extends Controller
{
    public function show(AssociateRepository $rAssociate)
    {
        try {
            $associates = $rAssociate->showAssociates();
            return ApiResponses::okObject($associates);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function find($id, AssociateRepository $rAssociate)
    {
        try {
            $associate = $rAssociate->findAssociate($id);
            if ($associate) {
                return ApiResponses::okObject($associate);
            }
            return ApiResponses::notFound();
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function create(AssociateRequest $request, AssociateRepository $rAssociate)
    {
        try {
            $associate = $rAssociate->createAssociate($request);
            return ApiResponses::okObject($associate);
        } catch (\Exception $e) {
            Log::error('Error en '.__METHOD__.' línea '.$e->getLine().':'.$e->getMessage());
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function update($id, Request $request, AssociateRepository $rAssociate)
    {
        try {
            $associate = $rAssociate->updateAssociate($id, $request);
            return ApiResponses::okObject($associate);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function destroy($id, AssociateRepository $rAssociate)
    {
        try {
            $rAssociate->deleteAssociate($id);
            return ApiResponses::ok('Recurso eliminado');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function restore($id, AssociateRepository $rAssociate)
    {
        try {
            $rAssociate->restoreAssociate($id);
            return ApiResponses::ok('Recurso recuperado');
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}
