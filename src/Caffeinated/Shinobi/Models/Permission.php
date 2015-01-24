<?php
namespace Caffeinated\Shinobi\Models;

use Config;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
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
	protected $table = 'permissions';

	/**
	 * Permissions can belong to many roles.
	 *
	 * @return Model
	 */
	public function roles()
	{
		return $this->belongsToMany('\Caffeinated\Shinobi\Models\Role')->withTimestamps();
	}
}
