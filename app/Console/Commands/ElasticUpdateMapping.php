<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\ElasticSeachable;
use App\Console\Commands\Traits\JoydataPackages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ElasticUpdateMapping extends Command
{
    use JoydataPackages, ElasticSeachable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'joydata:elastic:update-mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates a model mapping to Elasticsearch.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = $this->argument('model');

        $packages = collect($this->getAllJoydataPackages());
        $packages->prepend($this->getBasePath(), 'app');

        if ($model == 'all') {
            foreach ($packages as $packageName => $packageBasePath) {
                $modelObjects =$this->getAllSearchableModels(
                    $this->getModelPath($packageBasePath),
                    $this->getNamespaceFromComposer($packageBasePath));
                foreach ($modelObjects as $modelObject) {
                    $this->runElasticUpdateMappingCommand($modelObject);
                }
            }
        }
        else {
            foreach ($packages as $packageName => $packageBasePath) {
                $modelObjects =$this->getAllSearchableModels(
                    $this->getModelPath($packageBasePath),
                    $this->getNamespaceFromComposer($packageBasePath));
                foreach ($modelObjects as $modelObject) {
                    if(class_basename($modelObject) == $model) {
                        $this->runElasticUpdateMappingCommand($modelObject);
                    }
                }
            }
        }
    }

    private function runElasticUpdateMappingCommand($modelObject) {
        try {
            Artisan::call('elastic:update-mapping',
                [
                    'model' => get_class($modelObject),
                ]);
            $this->info(Artisan::output());

        }
        catch (\Throwable $throwable) {
            $this->info(' ');
            $this->error($throwable->getMessage());
        }
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return $this->__requiresElasticArgument();
    }
}
