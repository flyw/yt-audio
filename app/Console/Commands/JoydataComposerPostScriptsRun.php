<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\JoydataPackages;
use Illuminate\Console\Command;

class JoydataComposerPostScriptsRun extends Command
{
    use JoydataPackages;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joydata:post-scripts:run';

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

        $this->info( "::--:: Run joydata:post-scripts:run done.");
        exit(0);

    }

    public function update($dirPath , $packageName) {
        $json = file_get_contents($dirPath.'/composer.json');
        $jsonObject = json_decode($json);
        $wapper = "post-install-cmd";
        if(isset($jsonObject->scripts->$wapper)) {
            $postInstallScripts = $jsonObject->scripts->$wapper;
            foreach ($postInstallScripts as $postInstallScript) {
                echo $postInstallScript."\n";
                exec($postInstallScript , $result);
                print_r($result);
            }
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
