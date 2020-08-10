<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\ElasticSeachable;
use App\Console\Commands\Traits\JoydataPackages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ScoutElastic\Facades\ElasticClient;
use ScoutElastic\Payloads\RawPayload;

class ElasticMigrate extends Command
{
    use JoydataPackages, ElasticSeachable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'joydata:elastic:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates model to another index.';

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
                    $this->runElasticMigrateCommand($modelObject);
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
                        $this->runElasticMigrateCommand($modelObject);
                    }
                }
            }
        }
    }

    protected function runElasticMigrateCommand($modelObject) {
        try {
            $className = get_class($modelObject);
            $this->warn('Elastic Migrate for Model: ' . $className);

            $payload = (new RawPayload())
                ->set('name', $modelObject->getIndexConfigurator()->getName())
                ->get();

            if (ElasticClient::indices()->existsAlias($payload)) {
                $currentAlias = array_keys(ElasticClient::indices()->getAlias($payload))[0];
                $version = str_replace($modelObject->getIndexConfigurator()->getName().'-v','', $currentAlias);
                $alias = $modelObject->getIndexConfigurator()->getName().'-v'.($version+1);
            }
            else {
                $alias = $modelObject->getIndexConfigurator()->getName().'-v1';
            }

            Artisan::call('elastic:migrate',
                [
                    'model' => get_class($modelObject),
                    'target-index' => $alias,
                ]);
            $this->info(Artisan::output());

        } catch (\Throwable $throwable) {
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
