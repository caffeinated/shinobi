<?php

namespace Caffeinated\Shinobi\Concerns;

use Caffeinated\Shinobi\Facades\Shinobi;
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
        $slug = str_slug($role);

        return (bool) $this->roles->where('slug', $slug)->count();
    }

    /**
     * Assign the specified roles to the model.
     * 
     * @param  mixed  $roles,...
     * @return self
     */
    public function assignRoles(...$roles): self
    {
        $roles = array_flatten($roles);
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
        $roles = array_flatten($roles);
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
        $roles = array_flatten($roles);
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
        return Shinobi::role()->whereIn('slug', $roles)->get();
    }
}