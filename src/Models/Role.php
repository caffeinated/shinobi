<?php

namespace Caffeinated\Shinobi\Models;

use Illuminate\Database\Eloquent\Model;
use Caffeinated\Shinobi\Concerns\HasPermissions;

class Role extends Model
{
    use HasPermissions;
    
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
}
