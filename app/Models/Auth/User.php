<?php

namespace App\Models\Auth;

use App\Repositories\Backend\Auth\PermissionRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\Traits\Scope\UserScope;
use App\Models\Auth\Traits\Method\UserMethod;
use App\Models\Auth\Traits\Attribute\UserAttribute;
use App\Models\Auth\Traits\Relationship\UserRelationship;
use Joydata\Settings\Models\Agent;


/**
 * Class User.
 */
class User extends BaseUser
{
    use UserAttribute,
        UserMethod,
        UserRelationship,
        UserScope;


    public function enabledPermissions(): Collection
    {
        $permissionRepository = app( PermissionRepository::class);
        return $permissionRepository->getEnabledPermissions($this->getDirectPermissions()->get());
    }

    /**
     * Determine if the entity has a given ability.
     *
     * @param  string $ability
     * @param  string|null $package
     * @param  array|mixed $arguments
     * @return bool
     */
    public function can($ability, $package = null, $arguments = [])
    {
        if ($package == null) $package = 'system-permissions';
        if (config('joydata.'.$package.'.permission-'.$ability.'-enabled')) {
            if (Auth::user()->isAdmin())
                return true;
            else
                return parent::can($ability , $arguments);
        }
        return false;
    }

    public function agent() {
        return $this->belongsTo(Agent::class , 'agent_id', 'id');
    }

}
