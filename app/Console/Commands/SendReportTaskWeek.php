<?php

namespace App\Console\Commands;

use App\Repositories\MailsRepository;
use App\Repositories\ReportRepository;
use Illuminate\Console\Command;

class SendReportTaskWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosko:sendTaskWeek';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se envian las actividades de la semana';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $report = new ReportRepository();
        $file = $report->getWeekTaskExcel(true);
        $rMails = new MailsRepository();
        $rMails->sendTaskWeek($file);
        if (!$file) {
            return false;
        }
        return true;
    }
}
