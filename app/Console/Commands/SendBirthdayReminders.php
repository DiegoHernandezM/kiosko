<?php

namespace App\Console\Commands;

use App\Repositories\MailsRepository;
use Illuminate\Console\Command;

class SendBirthdayReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosko:sendbirthdayreminders';
    protected $mailrepo;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->mailrepo->sendBirthdayReminder();
    }
}
