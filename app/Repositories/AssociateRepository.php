<?php

namespace App\Repositories;

use App\Models\Associate;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function Symfony\Component\String\b;

class AssociateRepository
{
    protected $mAssociate;
    protected $rUser;
    protected $mUser;
    protected $rEvent;

    public function __construct()
    {
        $this->mAssociate = new Associate();
        $this->rUser = new UserRepository();
        $this->mUser = new User();
        $this->rEvent = new EventRepository();
    }

    public function showAssociates()
    {
        return $this->mAssociate
            ->join('users', 'users.id', '=', 'associates.user_id')
            ->join('areas', 'areas.id', '=', 'associates.area_id')
            ->join('subareas', 'subareas.id', '=', 'associates.subarea_id')
            ->select('associates.*', 'users.email', 'areas.name as area', 'subareas.name as subarea')
            ->withTrashed()
            ->get();
    }

    public function findAssociate($id)
    {
        $associate = $this->mAssociate->where('associates.id', $id)
            ->with(['user' => function ($query) {
                $query->with(['permissions' => function ($q) {
                    return $q->select('name');
                }]);
                $query->select('id', 'email');
            }])
            ->withTrashed()
            ->select('associates.*')
            ->first();
        return $associate;
    }

    public function createAssociate($request)
    {
        $type = $request->type !== 'associate' ? 'manager' : 'associate';
        request()->merge([ 'authority' => ['permissions' => [$type]]]);
        $password = rand(100, 999).str_pad(Str::random(8), 3, STR_PAD_LEFT);
        request()->merge([ 'password' => $password]);
        $user = $this->rUser->createUser($request->all());

        if ($user && $request->type !== 'manager') {
            $associate = $this->mAssociate->create([
                'name' => $request->name,
                'lastnames' => $request->lastnames,
                'employee_number' => $request->employee_number,
                'entry_date' => $request->entry_date,
                'birthday' => $request->birthday,
                'vacations_available' => (int)$request->vacations_available,
                'area_id' => $request->area_id,
                'subarea_id' => $request->subarea_id,
                'user_id' => $user->id,
            ]);

            if ($associate) {
                $year = Carbon::now();
                $birthday = Carbon::parse($associate->birthday);
                $entry = Carbon::parse($associate->entry_date);
                $this->rEvent->createCustomEvent(
                    true,
                    'Cumpleaños '.$associate->name,
                    'Cumpleaños de '.$associate->name.' '.$associate->lastnames,
                    $year->year.'-'.$birthday->month.'-'.$birthday->day.' 00:00:01',
                    $year->year.'-'.$birthday->month.'-'.$birthday->day.' 23:59:59'
                );
                $this->rEvent->createCustomEvent(
                    true,
                    'Aniversario '.$associate->name,
                    'Aniversario de '.$associate->name.' '.$associate->lastnames,
                    $year->year.'-'.$entry->month.'-'.$entry->day.' 00:00:01',
                    $year->year.'-'.$entry->month.'-'.$entry->day.' 23:59:59'
                );
            }
            return $associate;
        }
    }

    public function updateAssociate($id, $request)
    {
        $associate = $this->findAssociate($id);
        if ($associate) {
            $eAnniversary = $this->rEvent->findWhere('title', 'Aniversario '.$associate->name);
            if ($eAnniversary) {
                $this->rEvent->deleteEvent($eAnniversary->id);
            }
            $eBirthday = $this->rEvent->findWhere('title', 'Cumpleaños '.$associate->name);
            if ($eBirthday) {
                $this->rEvent->deleteEvent($eBirthday->id);
            }

            $type = $request->type !== 'associate' ? 'manager' : 'associate';
            $user = $this->mUser->find($associate->user_id);
            $this->rUser->setPermission($user, [$type]);
            $associate->name = $request->name;
            $associate->lastnames = $request->lastnames;
            $associate->birthday = $request->birthday;
            $associate->entry_date = $request->entry_date;
            $associate->area_id = $request->area_id;
            $associate->subarea_id = $request->subarea_id;
            $associate->vacations_available = $request->vacations_available;
            $associate->save();

            $year = Carbon::now();
            $birthday = Carbon::parse($associate->birthday);
            $entry = Carbon::parse($associate->entry_date);

            $this->rEvent->createCustomEvent(
                true,
                'Cumpleaños '.$associate->name,
                'Cumpleaños de '.$associate->name.' '.$associate->lastnames,
                $year->year.'-'.$birthday->month.'-'.$birthday->day.' 00:00:01',
                $year->year.'-'.$birthday->month.'-'.$birthday->day.' 23:59:59'
            );
            $this->rEvent->createCustomEvent(
                true,
                'Aniversario '.$associate->name,
                'Aniversario de '.$associate->name.' '.$associate->lastnames,
                $year->year.'-'.$entry->month.'-'.$entry->day.' 00:00:01',
                $year->year.'-'.$entry->month.'-'.$entry->day.' 23:59:59'
            );

            return $associate;
        }
    }

    public function deleteAssociate($id)
    {
        $this->mAssociate->destroy($id);
    }

    public function restoreAssociate($id)
    {
        $this->mAssociate->withTrashed()->find($id)->restore();
    }

    public function counts()
    {
        return $this->mAssociate->count();
    }

    public function checkVacations()
    {
        $associates = $this->mAssociate
            ->where('entry_date', 'like', '%'.Carbon::now()->format('m-d').'%')
            ->get();
        if (count($associates) > 0) {
            foreach ($associates as $associate) {
                $years = Carbon::now()->diffInYears(Carbon::parse($associate->entry_date));
                $associate->vacations_available = $associate->vacations_available + Associate::VACATIONS[$years];
                $associate->save();
            }
        }
        return true;
    }

    private function randColor() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
