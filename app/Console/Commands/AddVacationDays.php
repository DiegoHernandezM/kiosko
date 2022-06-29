<?php

namespace App\Console\Commands;

use App\Repositories\AssociateRepository;
use Illuminate\Console\Command;

class AddVacationDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kiosko:addVacationDay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica fecha de ingreso y agrega los dias correspondientes de vacaciones';

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
        $rAssociates = new AssociateRepository();
        $check = $rAssociates->checkVacations();
        return $check;
    }
}
