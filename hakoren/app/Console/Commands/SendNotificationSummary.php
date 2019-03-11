<?php

namespace App\Console\Commands;

use App\Logic\Activation\ActivationRepository;
use App\Http\Controllers\DashBoardController;
use Illuminate\Console\Command;

class SendNotificationSummary extends Command
{
    protected $signature = 'send:automailsummary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send summary for price and mount of yesterday.';

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
     * @return mixed
     */
    public function handle()
    {
        \App\Http\Controllers\DashBoardController::PriceMountYesterday();
    }
}
