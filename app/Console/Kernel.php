<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Library\Services\Commands\PrecacheImage;
use App\Library\Services\Commands\CrawlerNews;
use App\Library\Services\Commands\ESIndexMapping;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        PrecacheImage::class,
        CrawlerNews::class,
        ESIndexMapping::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //delete log file
        $schedule->exec('find ' . storage_path('logs') . ' -mindepth 1 -mtime +7 -delete')->runInBackground()->weeklyOn(1, '23:59');

        //delete all files in folder tmp
        $schedule->exec('rm -rf ' . storage_path('app') . '/tmp/*')->runInBackground()->weeklyOn(1, '0:0');

        //pre-cache image
        //$schedule->command('image:pre-cache')->runInBackground()->hourly();

        //crawler data
        $schedule->command('crawler:news')->runInBackground()->dailyAt('0:0');

        //retry job failed
        $schedule->command('queue:retry all')->runInBackground()->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
