<?php
namespace Caffeinated\Shinobi\Models;

use Config;
use App\Http\Models\Base\Model;

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
}
