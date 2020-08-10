<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApplyPermissionTableSeederInPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joydata:seed:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply Permission Table Seed in Packages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $composerPackageList = [];
        $composer = file_get_contents(base_path('composer.json'));
        $composerObject = json_decode($composer);
        foreach ($composerObject->require as $key => $item) {
            if (preg_match('/joydata/', $key)) {
                $package = preg_replace('/joydata\//', '', $key);
                $composerPackageList[] = $package;
                $this->info('Package Found: '. $package );
            }
        }
        $dev = 'require-dev';
        foreach ($composerObject->$dev as $key => $item) {
            if (preg_match('/joydata/', $key)) {
                $package = preg_replace('/joydata\//', '', $key);
                $composerPackageList[] = $package;
                $this->info('Package Found: '. $package );
            }
        }
        foreach ($composerPackageList as $packageName) {
            $dirPath = base_path('vendor').DS.'joydata'.DS.$packageName;
            if (is_file($dirPath.DS.'database'.DS.'seeds'.DS.'PermissionTableSeeder.php') ) {
                $this->applySeed($dirPath , $packageName);
            }
        }
        $this->info('permission from package done.');
    }

    public function applySeed($dirPath , $packageName) {
        $namespace = $this->getNamespace($dirPath , $packageName);
        $command = ' php artisan db:seed --class="\\'.$namespace . 'Database\\Seeds\\PermissionTableSeeder"';
        if( !$this->isWindows() )
            $command= str_replace("\\" , "\\\\" , $command);

        $this->warn('==== Permission ==== '.$packageName);
        exec($command , $output);
        foreach ($output as $item) {
            $this->info($item);
        }
    }

    public function getNamespace($dirPath , $packageName) {
        $composer = file_get_contents($dirPath.DS.'composer.json');
        $composerObject = json_decode($composer);
        $composerAutoloadArray = collect($composerObject->autoload)->toArray();
        $namespaceArray = collect($composerAutoloadArray['psr-4'])->toArray();
        foreach ($namespaceArray as $key => $item) {
            return $key;
        }
        throw new \Exception('no psr-4 found in '.$packageName.'\'s composer.json');

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
