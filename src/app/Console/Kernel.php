<?php

namespace App\Console;

use App\Jobs\PruneEventPageUpdatesTable;
use App\Jobs\PruneQueryserviceBatchesTable;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\EnsureStoragePoolsPopulatedJob;
use App\Jobs\ExpireOldUserVerificationTokensJob;
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
        // TODO it would be good if some of these could be opportunist & triggered rather than crons..
        $schedule->job(new EnsureStoragePoolsPopulatedJob)->everyMinute();
        $schedule->job(new ExpireOldUserVerificationTokensJob)->hourly();
        $schedule->job(new PruneEventPageUpdatesTable)->everyFifteenMinutes();
        $schedule->job(new PruneQueryserviceBatchesTable)->everyFifteenMinutes();
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
