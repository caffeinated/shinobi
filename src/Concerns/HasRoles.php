<?php

namespace Caffeinated\Shinobi\Concerns;

use Caffeinated\Shinobi\Models\Role;

trait HasRoles
{
    /**
     * Users can have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Checks if the model has the given role assigned.
     * 
     * @param  string  $role
     * @return boolean
     */
    public function hasRole($role)
    {
        $slug = str_slug($role);

        return (bool) $this->roles->where('slug', $slug)->count();
    }

    public function assignRoles(...$roles)
    {
        $roles = array_flatten($roles);
        $roles = $this->getRoles($roles);

        if (! $roles) {
            return $this;
        }

        $this->roles()->syncWithoutDetaching($roles);

        return $this;
    }

    public function removeRoles(...$roles)
    {
        $roles = array_flatten($roles);
        $roles = $this->getRoles($roles);

        $this->roles()->detach($roles);

        return $this;
    }

    public function syncRoles(...$roles)
    {
        $roles = array_flatten($roles);
        $roles = $this->getRoles($roles);

        $this->roles()->sync($roles);

        return $this;
    }

    protected function getRoles(array $roles)
    {
        return Role::whereIn('slug', $roles)->get();
    }
}