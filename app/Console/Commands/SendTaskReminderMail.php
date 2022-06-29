<?php

namespace App\Console\Commands;

use App\Repositories\MailsRepository;
use Illuminate\Console\Command;


class SendTaskReminderMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosko:taskreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia correo a las personas que no han registrado sus actividades en la semana';
    protected $mailrepo;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->mailrepo = new MailsRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->mailrepo->sendTaskReminder();
    }
}
