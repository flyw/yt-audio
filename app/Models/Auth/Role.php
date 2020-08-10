<?php

namespace App\Models\Auth;

use Altek\Accountant\Contracts\Recordable;
use Altek\Accountant\Recordable as RecordableTrait;
use App\Models\Auth\Traits\Method\RoleMethod;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Repositories\Backend\Auth\PermissionRepository;
use Illuminate\Support\Collection;

/**
 * Class Role.
 */
class Role extends SpatieRole implements Recordable
{
    use RecordableTrait,
        RoleMethod;
    public function enabledPermissions(): Collection
    {
        $permissionRepository = app( PermissionRepository::class);
        return $permissionRepository->getEnabledPermissions($this->permissions()->get());
    }

}
