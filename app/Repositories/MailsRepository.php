<?php


namespace App\Repositories;

use App\Mail\BirthdayMail;
use App\Mail\InquestMail;
use App\Mail\SendApprovedPetition;
use App\Mail\SendDisapprovedPetition;
use App\Mail\SendNewObjectiveMail;
use App\Mail\SendNewPetitionMail;
use App\Mail\SendReportTaskMail;
use App\Mail\TaskReminder;
use App\Mail\WelcomeMail;
use App\Models\Associate;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class MailsRepository
{
    protected $mUser;

    public function __construct()
    {
        $this->mUser = new User();
    }

    public function sendTaskReminder()
    {
        $associates = Associate::whereDoesntHave('tasks', function($q) {
            $q->whereBetween('task_day', [Carbon::now()->startOfWeek(), Carbon::now()]);
        })
        ->with('user')
        ->get();

        foreach ($associates as $associate) {
            if ($associate->user->email_verified_at) {
                Mail::to($associate->user)->send(new TaskReminder($associate->user));
            }
        }
    }

    public function sendTaskWeek($file)
    {
        $reminders = User::where('reports', true)->get();
        $tomorrow = Carbon::tomorrow();
        $nextSaturday = Carbon::tomorrow()->modify("next saturday");
        $events = Event::whereBetween('start', [$tomorrow, $nextSaturday])->orderBy('start')->get();
        foreach ($reminders as $reminder) {
            Mail::to($reminder->email)->send(new SendReportTaskMail($file.'.xlsx', $events));
        }
        unlink(public_path('files/'.$file.'.xlsx'));
    }

    public function sendNewObjective($associate)
    {
        $managers = $this->getManagers();
        foreach ($managers as $manager) {
            Mail::to($manager->email)->send(new SendNewObjectiveMail($manager, $associate));
        }
    }

    public function sendWelcome($associate, $body) {
        Mail::to($associate->email)->send(new WelcomeMail($body));
    }

    public function sendBirthdayReminder()
    {
        $associatesWhoseBirthdayIsTomorrow = Associate::whereMonth('birthday', Carbon::tomorrow()->month)
            ->whereDay('birthday', Carbon::tomorrow()->day)
            ->get();
        if (!empty($associatesWhoseBirthdayIsTomorrow)) {
            $dontSpoilIds = $associatesWhoseBirthdayIsTomorrow->pluck('id')->toArray();
            $users = User::whereNotNull('email_verified_at')
                ->whereDoesntHave('associate', function($q) use ($dontSpoilIds) {
                    $q->whereIn('id', $dontSpoilIds);
                })->get();
            foreach ($associatesWhoseBirthdayIsTomorrow as $associate) {
                foreach ($users as $user) {
                    Mail::to($user->email)->send(new BirthdayMail($associate));
                }
            }
        }
    }

    private function getManagers()
    {
        return $this->mUser->where('reports', true)->get();
    }

    public function getContacts()
    {
        return $this->mUser->where('reports',1)->whereNotNull('device_key')->with(['permissions' => function ($q) {
            $q->where('name', 'manager');
            return $q;
        }])->get();
    }

    public function sendInquest($body) {
        Mail::to($body['recipient']['email'])->send(new InquestMail($body));
    }

    public function sendNewPetition($associate)
    {
        $managers = $this->getContacts();
        foreach ($managers as $manager) {
            Mail::to($manager->email)->send(new SendNewPetitionMail($manager, $associate));
        }
    }

    public function sendApprovedPetition($associate)
    {
        Mail::to($associate->user->email)->send(new SendApprovedPetition($associate));
    }

    public function sendDisapprovedPetition($associate)
    {
        Mail::to($associate->user->email)->send(new SendDisapprovedPetition($associate));
    }


}
