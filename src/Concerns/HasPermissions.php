<?php

namespace Caffeinated\Shinobi\Concerns;

use Caffeinated\Shinobi\Facades\Shinobi;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{
    /**
     * Users can have many permissions
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(config('shinobi.models.permission'))->withTimestamps();
    }

    /**
     * The mothergoose check. Runs through each scenario provided
     * by Shinobi - checking for special flags, role permissions, and
     * individual user permissions; in that order.
     * 
     * @param  Permission  $permission
     * @return boolean
     */
    public function hasPermissionTo($permission): bool
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

    public function givePermissionTo(...$permissions): self
    {
        $permissions = array_flatten($permissions);
        $permissions = $this->getPermissions($permissions);

        if (! $permissions) {
            return $this;
        }

        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
    }

    public function revokePermissionTo(...$permissions): self
    {
        $permissions = array_flatten($permissions);
        $permissions = $this->getPermissions($permissions);

        $this->permissions()->detach($permissions);

        return $this;
    }

    public function syncPermissions(...$permissions): self
    {
        $permissions = array_flatten($permissions);
        $permissions = $this->getPermissions($permissions);

        $this->permissions()->sync($permissions);

        return $this;
    }

    /**
     * Get specified permissions.
     * 
     * @param  array  $permissions
     * @return Permission
     */
    protected function getPermissions(array $permissions)
    {
        return Shinobi::permission()->whereIn('slug', $permissions)->get();
    }

    /**
     * Checks if the user has the given permission assigned.
     * 
     * @param  \Caffeinated\Shinobi\Models\Permission  $permission
     * @return boolean
     */
    protected function hasPermission($permission): bool
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }
}