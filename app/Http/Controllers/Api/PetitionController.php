<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\PetitionRequest;
use App\Repositories\PetitionRepository;
use Illuminate\Http\Request;

class PetitionController extends Controller
{

    public function show(Request $request, PetitionRepository $rPetition)
    {
        $petitions = $rPetition->getAllPetitions($request);
        return ApiResponses::okObject($petitions);
    }

    public function allByAssociate(Request $request, PetitionRepository $rPetition)
    {
        $petitions = $rPetition->getAllPetitionsByUser($request);
        return ApiResponses::okObject($petitions);
    }

    public function create(PetitionRequest $request, PetitionRepository $rPetition)
    {
        try {
            $petition = $rPetition->createPetition($request);
            return ApiResponses::okObject($petition);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function edit($id, PetitionRepository $rPetition)
    {
        $petition = $rPetition->getPetition($id);
        if ($petition) {
            return ApiResponses::okObject($petition);
        }
        return ApiResponses::notFound();
    }

    public function update($id, Request $request, PetitionRepository $rPetition)
    {
        try {
            $petition = $rPetition->updatePetition($id, $request);
            return ApiResponses::okObject($petition);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function approvedPetition($id, Request $request, PetitionRepository $rPetition)
    {
        try {
            $rPetition->changeStatusPetition($id, $request);
            return ApiResponses::ok();
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function destroy($id, PetitionRepository $rPetition)
    {
        try {
            $petition = $rPetition->destroyPetition($id);
            return ApiResponses::okObject($petition);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function restore($id, PetitionRepository $rPetition)
    {
        try {
            $petition = $rPetition->restorePetition($id);
            return ApiResponses::okObject($petition);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function addFiles(Request $request, PetitionRepository $rPetition)
    {
        try {
            $petition = $rPetition->addFilesPetition($request);
            return ApiResponses::okObject($petition);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function dropFiles(Request $request, PetitionRepository $rPetition)
    {
        try {
            $petition = $rPetition->deleteFilePetition($request);
            return ApiResponses::okObject($petition);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }

    public function downloadFile(Request $request, PetitionRepository $rPetition)
    {
        try {
            return $rPetition->getFile($request);
        } catch (\Exception $e) {
            return ApiResponses::internalServerError($e->getMessage());
        }
    }
}

