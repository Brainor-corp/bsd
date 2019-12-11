<?php

namespace App\Console;

use App\Jobs\OrdersSyncTo1c;
use App\Jobs\SendOrdersPaymentStatusTo1c;
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

        $schedule->job(new UsersSyncTo1c())->everyMinute();
//        $schedule->job(new OrdersSyncTo1c())->everyMinute();
        $schedule->job(new UsersOrdersSyncFrom1c())->everyMinute();
        $schedule->job(new SendOrdersPaymentStatusTo1c())->everyMinute();
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
