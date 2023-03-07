<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

<<<<<<< HEAD
use App\Console\Commands\ResetViewDaily;
use App\Console\Commands\CrawlCategories;
=======
use App\Console\Commands\ResetViewHour;
use App\Console\Commands\ResetViewDaily;
use App\Console\Commands\CrawlCategories;
use App\Console\Commands\CrawlNews;
>>>>>>> ac5b240 (update)

class Kernel extends ConsoleKernel
{
   
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(CrawlCategories::class)->dailyAt('00:00');
<<<<<<< HEAD
        // $schedule->command(ResetViewDaily::class)->dailyAt('00:00');
=======
        $schedule->command(CrawlNews::class)->everyFiveMinutes();
        $schedule->command(ResetViewDaily::class)->dailyAt('00:00');
        $schedule->command(ResetViewHour::class)->hourly();
>>>>>>> ac5b240 (update)
    }

    
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
