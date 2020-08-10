<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\ElasticSeachable;
use App\Console\Commands\Traits\JoydataPackages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;

class ElasticIndex extends Command
{
    use ElasticSeachable, JoydataPackages;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'joydata:elastic:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elastic index management device.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $method = $this->argument('method');
        if (!preg_match('/^create$|^update$|^drop$/', $method, $output)) {
            $this->warn('Method argument must be one of those values in the list: [create, update, drop].');
            return;
        }

        $model = $this->argument('model');

        $packages = collect($this->getAllJoydataPackages());
        $packages->prepend($this->getBasePath(), 'app');

        if ($model == 'all') {
            foreach ($packages as $packageName => $packageBasePath) {
                $modelObjects =$this->getAllSearchableModels(
                    $this->getModelPath($packageBasePath),
                    $this->getNamespaceFromComposer($packageBasePath));
                foreach ($modelObjects as $modelObject) {
                    $this->runIndexCommand($method, $modelObject);
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
                        $this->runIndexCommand($method, $modelObject);
                    }
                }
            }
        }
    }

    protected function runIndexCommand($method, $modelObject) {
        try {
            $className = get_class($modelObject);
            $this->warn('Elastic Index ' . $method . ' for Model: ' . $className);
            Artisan::call('elastic:'.$method.'-index', ['index-configurator' => get_class($modelObject->getIndexConfigurator())]);
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
        return array_merge([
            [
                'method',
                InputArgument::REQUIRED,
                '可选模式 create / update / drop',
            ]
        ], $this->__requiresElasticArgument());
    }
}
