<?php

namespace Caffeinated\Shinobi\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Caffeinated\Shinobi\Contracts\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * Users can have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(config('shinobi.models.role'))->withTimestamps();
    }

    /**
     * Checks if the model has the given role assigned.
     * 
     * @param  string  $role
     * @return boolean
     */
    public function hasRole($role): bool
    {
        $slug = Str::slug($role);

        return (bool) $this->roles->where('slug', $slug)->count();
    }

    /**
     * Checks if the model has any of the given roles assigned.
     * 
     * @param  array  $roles
     * @return bool
     */
    public function hasAnyRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the model has all of the given roles assigned.
     * 
     * @param  array  $roles
     * @return bool
     */
    public function hasAllRoles(...$roles): bool
    {
        foreach ($roles as $role) {
            if (! $this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    public function hasRoles(): bool
    {
        return (bool) $this->roles->count();
    }

    /**
     * Assign the specified roles to the model.
     * 
     * @param  mixed  $roles,...
     * @return self
     */
    public function assignRoles(...$roles): self
    {
        $roles = Arr::flatten($roles);
        $roles = $this->getRoles($roles);

        if (! $roles) {
            return $this;
        }

        $this->roles()->syncWithoutDetaching($roles);

        return $this;
    }

    /**
     * Remove the specified roles from the model.
     * 
     * @param  mixed  $roles,...
     * @return self
     */
    public function removeRoles(...$roles): self
    {
        $roles = Arr::flatten($roles);
        $roles = $this->getRoles($roles);

        $this->roles()->detach($roles);

        return $this;
    }

    /**
     * Sync the specified roles to the model.
     * 
     * @param  mixed  $roles,...
     * @return self
     */
    public function syncRoles(...$roles): self
    {
        $roles = Arr::flatten($roles);
        $roles = $this->getRoles($roles);

        $this->roles()->sync($roles);

        return $this;
    }

    /**
     * Get the specified roles.
     * 
     * @param  array  $roles
     * @return Role
     */
    protected function getRoles(array $roles)
    {
        return array_map(function($role) {
            $model = $this->getRoleModel();

            if ($role instanceof $model) {
                return $role->id;
            }

            $role = $model->where('slug', $role)->first();

            return $role->id;
        }, $roles);
    }

    public function hasPermissionRoleFlags()
    {
        if ($this->hasRoles()) {
            return ($this->roles
                ->filter(function($role) {
                    return ! is_null($role->special);
                })->count()) >= 1;
        }

        return false;
    }

    /**
     * Get the model instance responsible for permissions.
     * 
     * @return \Caffeinated\Shinobi\Contracts\Role
     */
    protected function getRoleModel(): Role
    {
        return app()->make(config('shinobi.models.role'));
    }
}