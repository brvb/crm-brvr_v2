<?php

namespace App\Console;

use App\Events\Alerts\AlertEvent;
use Illuminate\Support\Facades\DB;
use App\Console\Commands\AlertsEmailConclusion;
use App\Console\Commands\EmailNotify;
use App\Console\Commands\AlertsEmails;
use App\Models\Tenant\CustomerServices;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\AlertsEmails::class,
        Commands\EmailNotify::class,
        Commands\AlertsEmailConclusion::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('alerts:cron')->dailyAt('08:30');

        $schedule->command('alerts:notify')->dailyAt('15:30');

        $schedule->command('alerts:mail_conclusion_day')->dailyAt('22:00');

        $schedule->command('alerts:check_finalizados')->dailyAt('07:00');

        $schedule->command('alerts:check_stamps')->everyMinute();

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
