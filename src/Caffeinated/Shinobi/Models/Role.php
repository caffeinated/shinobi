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
	protected $fillable = ['name', 'slug', 'description'];

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
		return $this->belongsToMany(Config::get('auth.model'))->withTimestamps();
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
	 * Checks if the role has the given permission.
	 *
	 * @param  string $slug
	 * @return bool
	 */
	public function can($slug)
	{
		$slug = strtolower($slug);

		foreach ($this->permissions as $permission) {
			if ($permission->slug == $slug) return true;
		}

		return false;
	}

	/**
	 * Assigns the given permission to the role.
	 *
	 * @param  int $permissionId
	 * @return bool
	 */
	public function assignPermission($permissionId = null)
	{
		$permissions = $this->permissions;

		if (! $permissions->contains($permissionId)) {
			return $this->permissions()->attach($permissionId);
		}

		return false;
	}

	/**
	 * Revokes the given permission from the role.
	 *
	 * @param  int $permissionId
	 * @return bool
	 */
	public function revokePermission($permissionId = '')
	{
		return $this->permissions()->detach($permissionId);
	}

	/**
	 * Syncs the given permission(s) with the role.
	 *
	 * @param  array $permissionIds
	 * @return bool
	 */
	public function syncPermissions(array $permissionIds)
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

	/**
	 * Magic __call method to handle dynamic methods.
	 *
	 * @param  string $method
	 * @param  array  $arguments
	 * @return mixed
	 */
	public function __call($method, $arguments = array())
	{
		// Handle canPermissionSlug() methods
		if (starts_with($method, 'can') and $method !== 'can') {
			$permission = substr($method, 3);

			return $this->can($permission);
		}

		return parent::__call($method, $arguments);
	}
}
