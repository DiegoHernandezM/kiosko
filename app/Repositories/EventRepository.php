<?php


namespace App\Repositories;

use App\Models\Associate;
use App\Models\Event;
use Carbon\Carbon;

class EventRepository
{
    protected $mEvents;
    protected $mAssociate;

    public function __construct()
    {
        $this->mEvents = new Event();
        $this->mAssociate = new Associate();
    }

    public function getAllEvents()
    {
        return $this->mEvents->all();
    }

    public function createEvent($request)
    {
        return $this->mEvents->create([
           'title' => $request->title,
           'description' => $request->description,
           'all_day' => $request->all_day,
           'start' => $request->all_day ? Carbon::parse($request->start)->format('Y-m-d'). ' 00:00:01' : Carbon::parse($request->start)->format('Y-m-d H:i:s'),
           'end' => $request->all_day ? Carbon::parse($request->end)->format('Y-m-d'). ' 23:59:59' : Carbon::parse($request->end)->format('Y-m-d H:i:s'),
           'textColor' => $this->randColor()
        ]);
    }

    public function updateEvent($id, $request)
    {
        $event = $this->mEvents->find($id);
        if ($event) {
            $event->title = $request->title;
            $event->description = $request->description;
            $event->all_day = $request->all_day;
            $event->start = $request->all_day ? Carbon::parse($request->start)->format('Y-m-d'). ' 00:00:01' : Carbon::parse($request->start)->format('Y-m-d H:i:s');
            $event->end = $request->all_day ? Carbon::parse($request->end)->format('Y-m-d'). ' 23:59:59' : Carbon::parse($request->end)->format('Y-m-d H:i:s');
            $event->save();
            return $event;
        }
    }

    public function deleteEvent($id)
    {
        $event = $this->mEvents->find($id);
        $event->delete();
    }

    public function createCustomEvent($allDay, $title, $description, $start, $end)
    {
        $this->mEvents->create([
            'all_day' => $allDay,
            'textColor' => $this->randColor(),
            'title' => $title,
            'description' => $description,
            'start' => $start,
            'end' => $end
        ]);
    }

    public function setBirthdaysYearly()
    {
        $year = Carbon::now();
        $associates = $this->mAssociate->all();
        foreach($associates as $associate) {
            $birthday = Carbon::parse($associate->birthday);
            $this->createCustomEvent(
                true,
                'Cumpleaños '.$associate->name,
                'Cumpleaños de '.$associate->name.' '.$associate->lastnames,
                $year->year.'-'.$birthday->month.'-'.$birthday->day.' 00:00:01',
                $year->year.'-'.$birthday->month.'-'.$birthday->day.' 23:59:59'
            );
        }
        return true;
    }

    public function setAnniversaries()
    {
        $year = Carbon::now();
        $associates = $this->mAssociate->all();
        foreach($associates as $associate) {
            $entry = Carbon::parse($associate->entry_date);
            $this->createCustomEvent(
                true,
                'Aniversario '.$associate->name,
                'Aniversario de '.$associate->name.' '.$associate->lastnames,
                $year->year.'-'.$entry->month.'-'.$entry->day.' 00:00:01',
                $year->year.'-'.$entry->month.'-'.$entry->day.' 23:59:59'
            );
        }
        return true;
    }

    public function thisWeek()
    {
        $start = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
        return $this->mEvents->whereBetween('start', [$start, $end])->orderBy('start')->get();
    }

    public function findWhere($column, $value)
    {
        return $this->mEvents->where('title', 'like', "%$value%")->first();
    }

    private function randColor() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

}
