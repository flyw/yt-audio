<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\JoydataPackages;
use Illuminate\Console\Command;

class JoydataPackageUpdate extends Command
{
    use JoydataPackages;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joydata:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $packages = $this->getAllJoydataPackages();
        foreach ($packages as $packageName => $dirPath) {
            $this->update($dirPath , $packageName);
        }
        $this->info('Restarting Supervisord ...');
        exec('systemctl restart supervisord');
        $this->info('Supervisord restarted.');

        $this->info('Done');
        exit(0);

    }

    public function update($dirPath , $packageName) {
        if (is_link($dirPath)) {
            $this->error($packageName .' is linked, skipd.');
            return;
        }
        $command = ' cd '.$dirPath . $this->commandSplit();
        $command .= ' git stash ' . $this->commandSplit();
        $command .= ' git pull ' . $this->commandSplit();
        $command .= ' git stash pop' ;

        $this->warn('==== Git Pull ==== '.$packageName);
        exec($command , $output);
        foreach ($output as $item) {
            $this->info($item);
        }
    }

    public function isWindows() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return true;
        } else {
            return false;
        }
    }

    public function commandSplit() {
        if ($this->isWindows()) {
            return ' && ';
        }
        else {
            return ' ; ';
        }
    }
}
