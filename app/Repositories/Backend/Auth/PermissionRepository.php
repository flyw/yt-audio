<?php

namespace App\Repositories\Backend\Auth;

use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionRepository.
 */
class PermissionRepository extends BaseRepository
{
    /**
     * PermissionRepository constructor.
     *
     * @param  Permission  $model
     */
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $permissions
     * @return PermissionRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getEnabledPermissions($permissions = null) {
        if (null === $permissions) {
            $permissions = $this->get();
        }
        return $permissions->reject(function ($permission) {
            if (null == $permission->package_name)
            {
                return false === config('joydata.system-permissions.permission-'.$permission->name.'-enabled');
            }
            else {
                return false === config('joydata.'.$permission->package_name.'.permission-'.$permission->name.'-enabled');
            }
        });
    }
}
