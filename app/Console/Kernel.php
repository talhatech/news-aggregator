<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Enums\NewsType;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Fetch trending news every hour
        $schedule->command('news:fetch ' . NewsType::TRENDING->value)->hourly();

        // Fetch yesterday's news once a day at midnight
        $schedule->command('news:fetch ' . NewsType::YESTERDAY->value)->dailyAt('00:00');
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
