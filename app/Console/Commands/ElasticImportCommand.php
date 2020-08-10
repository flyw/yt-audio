<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\ElasticSeachable;
use App\Console\Commands\Traits\JoydataPackages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ElasticImportCommand extends Command
{
    use ElasticSeachable,JoydataPackages;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'joydata:elastic:single:import {model} {start} {end}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mysql\'s records import to Es';


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
        //
        set_time_limit(0);
        $model = $this->argument('model');
        $start = $this->argument('start');
        $end = $this->argument('end');

        $packages = collect($this->getAllJoydataPackages());
        $packages->prepend($this->getBasePath(), 'app');

        if ($model) {
            foreach ($packages as $packageName => $packageBasePath) {
                $modelObjects =$this->getAllSearchableModels(
                    $this->getModelPath($packageBasePath),
                    $this->getNamespaceFromComposer($packageBasePath));
                foreach ($modelObjects as $modelObject) {
                    if(class_basename($modelObject) == basename($model)) {
                        $this->runIndexCommand($modelObject, $start, $end);
                    }
                }
            }
        }
    }


    protected function runIndexCommand($modelObject, $start, $end) {
        try {
            $dbmodel = $modelObject;
            $dbmodel->whereBetween("id", [$start, $end])->chunk(2000, function ($records) {
                foreach ($records as $item) {
                    $item->searchable();
                }
                Log::info("ElasticImportCommand memory usages: ".$this->convert(memory_get_usage(true)));
                unset($records);
                sleep(1);
            });

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    function convert($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
}
