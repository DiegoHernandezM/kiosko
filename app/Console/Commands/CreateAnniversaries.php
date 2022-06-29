<?php

namespace App\Console\Commands;

use App\Repositories\EventRepository;
use Illuminate\Console\Command;

class CreateAnniversaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosko:setAnniversary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza los aniversarios por año';

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
        $rEvent = new EventRepository();
        return  $rEvent->setAnniversaries();
    }
}
