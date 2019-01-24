<?php

namespace Caffeinated\Shinobi;

use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;
use Caffeinated\Shinobi\Tactics\AssignRoleTo;
use Caffeinated\Shinobi\Tactics\GivePermissionTo;
use Caffeinated\Shinobi\Tactics\RevokePermissionFrom;

class Shinobi
{
    /**
     * Fetch an instance of the Role model.
     * 
     * @return \Caffeinated\Shinobi\Models\Role
     */
    public function role()
    {
        return app()->make(Role::class);
    }

    /**
     * Fetch an instance of the Permission model.
     * 
     * @return \Caffeinated\Shinobi\Models\Permission
     */
    public function permission()
    {
        return app()->make(Permission::class);
    }

    /**
     * Assign roles to a user.
     * 
     * @param  string|array  $roles
     * @return \Caffeinated\Shinobi\Tactic\AssignRoleTo
     */
    public function assign($roles)
    {
        return new AssignRoleTo($roles);
    }
    
    /**
     * Give permissions to a user or role
     * 
     * @param  string|array  $permissions
     * @return \Caffeinated\Shinobi\Tactic\GivePermissionTo
     */
    public function give($permissions)
    {
        return new GivePermissionTo($permissions);
    }
    
    /**
     * Revoke permissions from a user or role
     * 
     * @param  string|array  $permissions
     * @return \Caffeinated\Shinobi\Tactic\RevokePermissionFrom
     */
    public function revoke($permissions)
    {
        return new RevokePermissionFrom($permissions);
    }
}
