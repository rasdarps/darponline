<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Send daily transaction email report at configured time (6pm-12am range)
        $emailHour = (int) config('pos.daily_email_report_time', 18);
        $schedule->command('email:send-daily-transaction')
            ->dailyAt(str_pad($emailHour, 2, '0', STR_PAD_LEFT) . ':00')
            ->description('Send daily transaction email report')
            ->withoutOverlapping();
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
