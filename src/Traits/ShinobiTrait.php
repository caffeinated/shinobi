<?php

namespace Caffeinated\Shinobi\Traits;

trait ShinobiTrait
{
    /*
    |----------------------------------------------------------------------
    | Role Trait Methods
    |----------------------------------------------------------------------
    |
    */

    /**
     * Users can have many roles.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function roles()
    {
        return $this->belongsToMany('\Caffeinated\Shinobi\Models\Role')->withTimestamps();
    }

    /**
     * Get all user roles.
     *
     * @return array|null
     */
    public function getRoles()
    {
        if (!is_null($this->roles)) {
            return $this->roles->pluck('slug')->all();
        }
    }

    /**
     * Checks if the user has the given role.
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isRole($slug)
    {
        $slug = strtolower($slug);

        foreach ($this->roles()->get() as $role) {
            if ($role->slug == $slug) {
                return true;
            }
        }

        return false;
    }

    /**
     * Assigns the given role to the user.
     *
     * @param int $roleId
     *
     * @return bool
     */
    public function assignRole($roleId = null)
    {
        $roles = $this->roles;

        if (!$roles->contains($roleId)) {
            return $this->roles()->attach($roleId);
        }

        return false;
    }

    /**
     * Revokes the given role from the user.
     *
     * @param int $roleId
     *
     * @return bool
     */
    public function revokeRole($roleId = '')
    {
        return $this->roles()->detach($roleId);
    }

    /**
     * Syncs the given role(s) with the user.
     *
     * @param array $roleIds
     *
     * @return bool
     */
    public function syncRoles(array $roleIds)
    {
        return $this->roles()->sync($roleIds);
    }

    /**
     * Revokes all roles from the user.
     *
     * @return bool
     */
    public function revokeAllRoles()
    {
        return $this->roles()->detach();
    }

    /*
    |----------------------------------------------------------------------
    | Permission Trait Methods
    |----------------------------------------------------------------------
    |
    */

    /**
     * Get all user role permissions.
     *
     * @return array|null
     */
    public function getPermissions()
    {
        $permissions = [[], []];

        foreach ($this->roles()->get() as $role) {
            $permissions[] = $role->getPermissions();
        }

        return call_user_func_array('array_merge', $permissions);
    }

    /**
     * Check if user has the given permission.
     *
     * @param string $permission
     * @param array  $arguments
     *
     * @return bool
     */
    public function can($permission, $arguments = [])
    {
        $can = false;

        foreach ($this->roles()->get() as $role) {
            if ($role->special === 'no-access') {
                return false;
            }

            if ($role->special === 'all-access') {
                return true;
            }

            if ($role->can($permission)) {
                $can = true;
            }
        }

        return $can;
    }

    /**
     * Check if user has at least one of the given permissions.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function canAtLeast(array $permissions)
    {
        $can = false;

        foreach ($this->roles()->get() as $role) {
            if ($role->special === 'no-access') {
                return false;
            }

            if ($role->special === 'all-access') {
                return true;
            }

            if ($role->canAtLeast($permissions)) {
                $can = true;
            }
        }

        return $can;
    }

    /*
    |----------------------------------------------------------------------
    | Magic Methods
    |----------------------------------------------------------------------
    |
    */

    /**
     * Magic __call method to handle dynamic methods.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        // Handle isRoleslug() methods
        if (starts_with($method, 'is') and $method !== 'is') {
            $role = substr($method, 2);

            return $this->isRole($role);
        }

        // Handle canDoSomething() methods
        if (starts_with($method, 'can') and $method !== 'can') {
            $permission = substr($method, 3);

            return $this->can($permission);
        }

        return parent::__call($method, $arguments);
    }
}
