<?php

namespace Caffeinated\Shinobi\Models;

use Config;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'special'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Roles can belong to many users.
     *
     * @return Model
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.model') ?: config('auth.providers.users.model'))->withTimestamps();
    }

    /**
     * Roles can have many permissions.
     *
     * @return Model
     */
    public function permissions()
    {
        return $this->belongsToMany('\Caffeinated\Shinobi\Models\Permission')->withTimestamps();
    }

    /**
     * Get permission slugs assigned to role.
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions->pluck('slug')->all();
    }

    /**
     * Checks if the role has the given permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function can($permission)
    {
        if ($this->special === 'no-access') {
            return false;
        }

        if ($this->special === 'all-access') {
            return true;
        }

        $permissions = $this->getPermissions();

        if (is_array($permission)) {
            $permissionCount = count($permission);
            $intersection = array_intersect($permissions, $permission);
            $intersectionCount = count($intersection);

            return ($permissionCount == $intersectionCount) ? true : false;
        } else {
            return in_array($permission, $permissions);
        }
    }

    /**
     * Check if the role has at least one of the given permissions.
     *
     * @param array $permission
     *
     * @return bool
     */
    public function canAtLeast(array $permission = [])
    {
        if ($this->special === 'no-access') {
            return false;
        }

        if ($this->special === 'all-access') {
            return true;
        }

        $permissions = $this->getPermissions();

        $intersection = array_intersect($permissions, $permission);
        $intersectionCount = count($intersection);

        return ($intersectionCount > 0) ? true : false;
    }

    /**
     * Assigns the given permission to the role.
     *
     * @param int $permissionId
     *
     * @return bool
     */
    public function assignPermission($permissionId = null)
    {
        $permissions = $this->permissions;

        if (!$permissions->contains($permissionId)) {
            return $this->permissions()->attach($permissionId);
        }

        return false;
    }

    /**
     * Revokes the given permission from the role.
     *
     * @param int $permissionId
     *
     * @return bool
     */
    public function revokePermission($permissionId = '')
    {
        return $this->permissions()->detach($permissionId);
    }

    /**
     * Syncs the given permission(s) with the role.
     *
     * @param array $permissionIds
     *
     * @return bool
     */
    public function syncPermissions(array $permissionIds = [])
    {
        return $this->permissions()->sync($permissionIds);
    }

    /**
     * Revokes all permissions from the role.
     *
     * @return bool
     */
    public function revokeAllPermissions()
    {
        return $this->permissions()->detach();
    }
}
