<?php

namespace App\Console\Commands\Traits;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\ErrorHandler\Error\FatalError;

trait ElasticSeachable
{
    protected $exceptionModel = [
        '\Joydata\Settings\Models\User'
    ];

    /**
     * 从指定的Model 路径获取所有Searchable Model Object.
     * @param $modelPath
     * @param $baseNamespace
     * @return array
     */

    protected function getAllSearchableModels($modelPath, $baseNamespace) {
        $modelObjects = [];
        /** @var \DirectoryIterator $fileInfo */
        foreach (new \DirectoryIterator($modelPath) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            if (preg_match('/\.php$/', $fileInfo->getFilename(), $output)) {
                $modelName = preg_replace('/\.php$/', '',$fileInfo->getFilename());
                $modelObject = $this->getSearchableModel($modelName, $baseNamespace);
                if ($modelObject)
                    $modelObjects[] = $modelObject;
            }
        }
        return $modelObjects;
    }

    /**
     * Fetch Searchable Model Object
     *
     * @param $model
     * @param $baseNamespace
     * @return Eloquent | null
     */
    protected function getSearchableModel($modelName, $baseNamespace) {
        try {
            $modelClass = "\\".$baseNamespace."Models\\".$modelName;
            foreach ($this->exceptionModel as $item) {
                if ($modelClass == $item) return null;
            }
            $modelObject = new $modelClass;
        } catch (\Error $error) {
            return null;
        }

        if ( method_exists($modelObject, 'shouldBeSearchable')
            && $modelObject->shouldBeSearchable() ) {
            return $modelObject;
        }
        return null;
    }

    /**
     * 从 composer json 中 获取当前 package 的 namespace
     *
     * @param $packageBasePath
     * @return string
     */
    protected function getNamespaceFromComposer($packageBasePath = null) {
        if (null == $packageBasePath) {
            $packageBasePath = $this->getBasePath();
        }
        $composerJson = json_decode(file_get_contents($packageBasePath.'/composer.json'));
        return array_keys((array) collect($composerJson->autoload)->get('psr-4')
        )[0];

    }

    protected function getBasePath() {
        return realpath(__DIR__.'/../../../../');
    }

    protected function getModelPath($basePath = null) {
        if (null == $basePath) {
            $basePath = $this->getBasePath();
        }
        return realpath($basePath.'/app/Models/');
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    protected function __requiresElasticArgument()
    {
        return [
            [
                'model',
                InputArgument::REQUIRED,
                '操作的 Model 类名（必须在 app/Models 目录下），操作全部 Elastic 可用 Model 填入 all ',
            ],
        ];
    }
}
