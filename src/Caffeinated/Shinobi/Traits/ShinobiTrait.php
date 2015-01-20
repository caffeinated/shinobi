<?php
namespace Caffeinated\Shinobi\Traits;

trait ShinobiTrait
{
	/**
	 * Users can have many roles.
	 *
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function roles()
	{
		return $this->belongsToMany('Caffeinated\Shinobi\Models\Role')->withTimestamps();
	}

	/**
	 * Checks if the user is under the given role.
	 *
	 * @param  string $slug
	 * @return bool
	 */
	public function hasRole($slug)
	{
		foreach ($this->roles as $role) {
			if ($role->slug == $slug) return true;
		}

		return false;
	}

	/**
	 * Assigns the given role to the user.
	 *
	 * @param  integer|object $role
	 * @return bool
	 */
	public function assignRole($role)
	{
		return $this->roles()->attach($role);
	}

	/**
	 * Revokes the given role from the user.
	 *
	 * @param  integer|object $role
	 * @return bool
	 */
	public function revokeRole($role)
	{
		return $this->roles()->detach($role);
	}
}
