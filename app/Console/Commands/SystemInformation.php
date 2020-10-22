<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MetisController;

class SystemInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch system information of user';

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
        $metis= new MetisController();
        $data = array(
            'cpuRamUsage'=> $metis->totalRamCpuUsage(),
            'diskUsage'=> $metis->diskUsage(),
            'ipAddress' => $metis->getUserIp(),
            'macAddress' => $metis->macAddress(),
        );
        $metis->myinfo($data);

        $this->info('Data has been sent');
        // return 0;
    }
}
