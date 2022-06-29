<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('kiosko:setBirthdays')->yearlyOn(1,1, '00:01');
        $schedule->command('kiosko:setAnniversary')->yearlyOn(1,1, '00:02');
        $schedule->command('kiosko:taskReminder')->fridays()->at('09:02:00');
        $schedule->command('kiosko:sendTaskWeek')->fridays()->at('21:00:00');
        $schedule->command('kiosko:sendbirthdayreminders')->dailyAt('09:00:00');
        $schedule->command('kiosko:addVacationDay')->dailyAt('09:01:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
