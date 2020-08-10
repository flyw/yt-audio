<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\ElasticSeachable;
use App\Console\Commands\Traits\JoydataPackages;
use Illuminate\Console\Command;

class DataMigrateToEsCommand extends Command
{
    use ElasticSeachable,JoydataPackages;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joydata:elastic:db-import {model} {count}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'db import to Es';


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
        set_time_limit(0);
        $model = $this->argument('model');
        $count = $this->argument('count');

        $packages = collect($this->getAllJoydataPackages());
        $packages->prepend($this->getBasePath(), 'app');

        if ($model) {
            foreach ($packages as $packageName => $packageBasePath) {
                $modelObjects =$this->getAllSearchableModels(
                    $this->getModelPath($packageBasePath),
                    $this->getNamespaceFromComposer($packageBasePath));
                foreach ($modelObjects as $modelObject) {
                    if(class_basename($modelObject) == basename($model)) {
                        $this->runIndexCommand($count, $modelObject);
                    }
                }
            }
        }
    }


    protected function runIndexCommand($count, $modelObject) {
        try {
            $dbmodel    = $modelObject;
            $allNum     = $dbmodel::max("id");
            $bucketNum = ceil($allNum/$count);

            for ($i = 0; $i < $count; $i++) {
                $start = $i * $bucketNum;
                $end = ($i + 1) * $bucketNum;
                @exec("php " . base_path() . "/artisan joydata:elastic:single:import " . '"' . get_class($dbmodel) . '"' . " {$start} {$end} > /dev/null &");
            }



        } catch (\Throwable $throwable) {
            $this->error($throwable->getMessage());
        }
    }

}
