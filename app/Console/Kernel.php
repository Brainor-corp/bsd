<?php

namespace App\Console;

use App\Jobs\ClearPendingFiles;
use App\Jobs\UsersOrdersSyncFrom1c;
use App\Jobs\UsersSyncTo1c;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->job(new SendTestMail())->everyMinute();

        $schedule->job(new UsersSyncTo1c())->everyFiveMinutes();
        $schedule->job(new UsersOrdersSyncFrom1c())->everyFiveMinutes();

        $schedule->job(new ClearPendingFiles())->daily();

        $schedule->command('sitemap:generate')->daily();
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
