<?php

namespace Caffeinated\Shinobi\Traits;

trait PermissionTrait
{
    /**
     * The shinobi cache tag used by the model.
     * Should be implemented by Model using this trait
     *
     * @return string
     */
    public static function getShinobiTag()
    {
        return '';
    }

    /**
     * Users and Roles can have many permissions
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function permissions()
    {
        return $this->belongsToMany('\Caffeinated\Shinobi\Models\Permission')->withTimestamps();
    }

    /**
     * Get fresh permission slugs assigned to the user or role.
     * Internal method, should be implemented by Model using this trait
     *
     * @return array
     */
    protected function getFreshPermissions()
    {
    }

    /**
     * Wrapper for caching the permission slugs
     *
     * @return array
     */
    public function getPermissions()
    {
        $primaryKey = $this[$this->primaryKey];
        $cacheKey   = 'caffeinated.'.substr(static::getShinobiTag(), 0, -1).'.permissions.'.$primaryKey;

        if (method_exists(app()->make('cache')->getStore(), 'tags')) {
            return app()->make('cache')->tags(static::getShinobiTag())->remember($cacheKey, 60, function () {
                return $this->getFreshPermissions();
            });
        }

        return $this->getFreshPermissions();
    }

    /**
     * Assigns the given permission to the user or role.
     *
     * @param int $permissionId
     *
     * @return bool
     */
    public function assignPermission($permissionId = null)
    {
        $permissions = $this->permissions;

        if (!$permissions->contains($permissionId)) {
            $this->flushPermissionCache();

            return $this->permissions()->attach($permissionId);
        }

        return false;
    }

    /**
     * Revokes the given permission from the user or role.
     *
     * @param int $permissionId
     *
     * @return bool
     */
    public function revokePermission($permissionId = '')
    {
        $this->flushPermissionCache();

        return $this->permissions()->detach($permissionId);
    }

    /**
     * Syncs the given permission(s) with the user or role.
     *
     * @param array $permissionIds
     *
     * @return bool
     */
    public function syncPermissions(array $permissionIds = [])
    {
        $this->flushPermissionCache();

        return $this->permissions()->sync($permissionIds);
    }

    /**
     * Revokes all permissions from the user or role.
     *
     * @return bool
     */
    public function revokeAllPermissions()
    {
        $this->flushPermissionCache();

        return $this->permissions()->detach();
    }

    /**
     * Flush the permission cache repository.
     *
     * @return void
     */
    public function flushPermissionCache(array $tags = null)
    {
        if (method_exists(app()->make('cache')->getStore(), 'tags')) {
            if ($tags === null) {
                $tags = [ static::getShinobiTag() ];
            }

            foreach ($tags as $tag) {
                app()->make('cache')->tags($tag)->flush();
            }
        }
    }

    /**
     * Check if the or all requested permissions are satisfied
     *
     * @param mixed $permission
     * @param array $permissions
     *
     * @return bool
     */
    protected function hasAllPermissions($permission, array $permissions)
    {
        if (is_array($permission)) {
            $permissionCount   = count($permission);
            $intersection      = array_intersect($permissions, $permission);
            $intersectionCount = count($intersection);

            return ($permissionCount == $intersectionCount) ? true : false;
        } else {
            return in_array($permission, $permissions);
        }
    }

    /**
     * Check if one of the requested permissions are satisfied
     *
     * @param array $permission
     * @param array $permissions
     *
     * @return bool
     */
    protected function hasAnyPermission(array $permission, array $permissions)
    {
        $intersection      = array_intersect($permissions, $permission);
        $intersectionCount = count($intersection);

        return ($intersectionCount > 0) ? true : false;
    }
}
