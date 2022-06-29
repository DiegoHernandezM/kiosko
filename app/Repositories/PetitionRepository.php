<?php
namespace App\Repositories;

use App\Models\Petition;
use App\Models\User;
use App\Models\Associate;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Storage;
use Ramsey\Uuid\Uuid;


class PetitionRepository
{
    protected $mPetition;
    protected $mUser;
    protected $rMails;

    public function __construct()
    {
        $this->mPetition = new Petition();
        $this->mUser = new User();
        $this->mAssociate = new Associate();
        $this->rMails = new MailsRepository();
    }

    public function getAllPetitions()
    {
        $petitions = $this->mUser
            ->select('petitions.*', DB::raw("CONCAT(associates.name, ' ' ,associates.lastnames) AS full_name"), 'areas.name as areaname', 'subareas.name as subname')
            ->join('associates', 'associates.user_id', '=', 'users.id')
            ->join('petitions','petitions.associate_id' ,'=', 'associates.id')
            ->join('areas', 'areas.id','=', 'associates.area_id')
            ->join('subareas', 'subareas.id','=', 'associates.subarea_id')
            ->withTrashed()
            ->get();

        return $petitions;
    }

    public function getAllPetitionsByUser()
    {
        $user = Auth::user();
        $associate = $this->mAssociate->where('user_id', $user->id)->get();
        if ($associate) {
            $petitions = $this->mUser
                ->select('p.*')
                ->join('associates as a', 'a.user_id', '=', 'users.id')
                ->join('petitions as p', 'p.associate_id', '=', 'a.id')
                ->where('users.id', $user->id)
                ->withTrashed()
                ->get();

            foreach ($petitions as $key => $petition) {
                if ($petition) {
                    $approvedBy = $this->mUser->where('id', $petition->approved_by)->first();
                    $petition['approvedBy'] = $approvedBy ? $approvedBy['name'] : '';
                }
            }
        }
        $response = [
            'petitions' => $petitions,
            'associate' => $associate[0]
        ];
        return $response;
    }

    public function createPetition($request)
    {
        $names = [];
        $user = Auth::user();
        if ($user) {
            $associate = $this->mAssociate->where('user_id', $user->id)->first();
            if ($associate) {
                if ($request->hasFile('files')) {
                    $files =  $request->file('files');
                    $names = $this->uploadFiles($files);
                }
                $petition = $this->mPetition->create([
                    'date' => $request->date,
                    'petition_description' => $request->petition_description,
                    'comment' => $request->comment,
                    'approved' => $request->approved,
                    'associate_id' => $associate->id,
                    'approved_by' => 0,
                    'period' => $request->period !== NULL ? $request->period : NULL,
                    'files' => count($names) > 0  ? json_encode($names) : NULL,

                ]);
                if ($petition) {
                    $this->rMails->sendNewPetition($associate);
                }
            }
        }
        return $petition;
    }

    public function getPetition($id)
    {
        return $this->mPetition->with('associate')->withTrashed()->find($id);
    }

    public function updatePetition($id, $request)
    {
        $petition = $this->getPetition($id);
        if ($petition) {
            $petition->date = Carbon::parse($request->date)->format('Y-m-d');
            $petition->petition_description = $request->petition_description;
            $petition->comment = $request->comment;
            $petition->period = $request->period !== null ? json_encode($request->period) : NULL;
            $petition->save();
            return $petition;
        }
    }

    public function changeStatusPetition($id, $request)
    {
        $start = '';
        $end = '';
        $selectedDay = 0;
        $user = Auth::user();
        if ($user) {
            $petition = $this->getPetition($id);
            $associate = $this->mAssociate->with('user')->findOrFail($petition->associate_id);
            if($petition->petition_description === Petition::PETITIONS[3]) {
                if($associate && $request->approved === 2){
                    foreach ($petition->period as $data) {
                        $start = $data->startDate;
                        $end = $data->endDate;
                        $selectedDay = $data->days;
                    }
                    $startDate = new Carbon($start);
                    $endDate = new Carbon($end);
                    $halfDay = $selectedDay - 0.5;
                    if($startDate->dayOfWeek === Carbon::FRIDAY || $endDate->dayOfWeek === Carbon::FRIDAY) {
                        $daysToDiscount = $halfDay;
                    } else {
                        $daysToDiscount = $selectedDay;
                    }
                    $associate->vacations_available = (float)$associate->vacations_available - (float)$daysToDiscount;
                    $associate->save();
                }
            }
            if ($petition) {
                $petition->comment = $request->comment;
                $petition->approved = $request->approved;
                $petition->approved_by = $request->approved === 0 ? 0 : $user->id;
                $petition->comment_by_admin = $request->comment_by_admin;
                $petition->save();

                if ($petition->approved === 2) {
                    $this->rMails->sendApprovedPetition($associate);
                } else {
                    $this->rMails->sendDisapprovedPetition($associate);
                }
                return $petition;
            }
        }
    }

    public function destroyPetition($id)
    {
        $petition = $this->mPetition->find($id);
        $files = $petition->files;
        if ($files !== null) {
            $nameS3 = 'petitions/';
            foreach ($files as $file) {
                Storage::disk('s3')->delete($nameS3.$file->name);
            }
        }
        $petition->delete();
    }

    public function restorePetition($id)
    {
        return $this->mPetition->withTrashed()->find($id)->restore();
    }

    public function pending()
    {
        return $this->mPetition->where('approved', 1)->count();
    }

    public function addFilesPetition($request)
    {
        $petition = $this->mPetition->find((int)$request->id);
        if ($request->hasFile('files')) {
            $files =  $request->file('files');
            $names = $this->uploadFiles($files);
            $newFiles = json_decode(json_encode($names));
            $oldFiles =  $petition->files;
            $aFiles = array_merge($oldFiles ?? [], $newFiles);
            $petition->files = json_encode($aFiles);
            $petition->save();
        }
        return $petition;
    }

    public function deleteFilePetition($request)
    {
        $petition = $this->mPetition->find($request->id);
        Storage::disk('s3')->delete('petitions/'.$request->name);
        $petition->files = json_encode($request->input('files'));
        $petition->save();
        return $petition;
    }

    public function getFile($request)
    {
        $headers = [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'. $request->name .'"',
        ];
        return \Response::make(Storage::disk('s3')->get('petitions/'.$request->name), 200, $headers);
    }

    private function uploadFiles($files)
    {
        $names = [];
        foreach ($files as $key => $file) {
            $name = Uuid::uuid1().'.'.$file->extension();
            $filePath =  '/petitions/';
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
