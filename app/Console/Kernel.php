<?php

namespace App\Console;

use App\Console\Commands\DataMigrateToEsCommand;
use App\Console\Commands\ElasticImportCommand;
use App\Console\Commands\ElasticIndex;
use App\Console\Commands\ElasticMigrate;
use App\Console\Commands\ElasticUpdateMapping;
use App\Console\Commands\JoydataComposerPostScriptsRun;
use App\Console\Commands\JoydataPackageUpdate;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        JoydataPackageUpdate::class,
        JoydataComposerPostScriptsRun::class,
        ElasticIndex::class,
        ElasticMigrate::class,
        ElasticUpdateMapping::class,
        DataMigrateToEsCommand::class,
        ElasticImportCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('youtube-dl:channel-sync')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
