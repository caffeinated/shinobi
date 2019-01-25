<?php

namespace Caffeinated\Shinobi\Concerns;

use Caffeinated\Shinobi\Models\Permission;

trait HasPermissions
{
    /**
     * Users can have many permissions
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * The mothergoose check. Runs through each scenario provided
     * by Shinobi - checking for special flags, role permissions, and
     * individual user permissions; in that order.
     * 
     * @param  \Caffeinated\Shinobi\Models\Permission  $permission
     * @return boolean
     */
    public function hasPermissionTo($permission)
    {
        // Check role flags
        if ($this->hasPermissionFlags()) {
            return $this->hasPermissionThroughFlag();
        }

        // Check role permissions
        if ($this->hasPermissionThroughRole($permission)) {
            return true;
        }

        // Check user permission
        if ($this->hasPermission($permission)) {
            return true;
        }

        return false;
    }

    public function givePermissionTo(...$permissions)
    {
        $permissions = array_flatten($permissions);
        $permissions = $this->getPermissions($permissions);

        if (! $permissions) {
            return $this;
        }

        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
    }

    public function revokePermissionTo(...$permissions)
    {
        $permissions = array_flatten($permissions);
        $permissions = $this->getPermissions($permissions);

        $this->permissions()->detach($permissions);

        return $this;
    }

    public function syncPermissions(...$permissions)
    {
        $permissions = array_flatten($permissions);
        $permissions = $this->getPermissions($permissions);

        $this->permissions()->sync($permissions);

        return $this;
    }

    protected function getPermissions(array $permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }

    /**
     * Checks if the user has the given permission assigned.
     * 
     * @param  \Caffeinated\Shinobi\Models\Permission  $permission
     * @return boolean
     */
    protected function hasPermission($permission)
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }
}