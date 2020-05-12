<?php

namespace App\Console;

use App\Console\Commands\CancelOldOrdersCommand;
use App\Console\Commands\RevokeTokensCommand;
use App\Jobs\CreateAndSaveEskizToken;
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
        CancelOldOrdersCommand::class,
        RevokeTokensCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('clear:tokens')->weekly();
        $schedule->command('credits:cancel')->daily();
        $schedule->job(new CreateAndSaveEskizToken())->weekly();
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
