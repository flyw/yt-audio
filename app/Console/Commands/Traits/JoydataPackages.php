<?php


namespace App\Console\Commands\Traits;


trait JoydataPackages
{
    public function getAllJoydataPackages() {
        $composerPackageList = [];
        $composer = file_get_contents(base_path('composer.json'));
        $composerObject = json_decode($composer);
        foreach ($composerObject->require as $key => $item) {
            if (preg_match('/joydata/', $key)) {
                $package = preg_replace('/joydata\//', '', $key);
                $composerPackageList[] = $package;
            }
        }
        $dev = 'require-dev';
        foreach ($composerObject->$dev as $key => $item) {
            if (preg_match('/joydata/', $key)) {
                $package = preg_replace('/joydata\//', '', $key);
                $composerPackageList[] = $package;
            }
        }
        $list = [];
        foreach ($composerPackageList as $packageName) {
            if (is_dir( base_path('vendor').DS.'joydata'.DS.$packageName)) {
                $this->info('Package Found: '. $packageName );
                $list[$packageName] =  base_path('vendor').DS.'joydata'.DS.$packageName;
            }
            else {
                $this->error('Package Not Found: '. $packageName );
            }

        }
        return $list;
    }
}
