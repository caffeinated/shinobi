<?php

namespace Caffeinated\Shinobi\Concerns;

trait HasRolesAndPermissions
{
    use HasRoles, HasPermissions;

    /**
     * Run through the roles assigned to the permission and
     * checks if the user has any of them assigned.
     * 
     * @param  \Caffeinated\Shinobi\Models\Permission  $permission
     * @return boolean
     */
    protected function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }

        return false;
    }
}