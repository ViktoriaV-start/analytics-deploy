<?php

namespace App\Console;

use App\Http\Controllers\HomeController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\ApiService;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {

            // Get Data
            (new HomeController())->index(new ApiService(), '2023-09-22', date('Y-m-d'));

        })->twiceDailyAt(6, 20, 44)
          ->timezone('Europe/Moscow');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
