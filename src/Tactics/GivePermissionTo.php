<?php

namespace Caffeinated\Shinobi\Tactics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Caffeinated\Shinobi\Facades\Shinobi;

class GivePermissionTo
{
    /**
     * @var array
     */
    protected $permissions;

    /**
     * Create a new GivePermissionTo instance.
     * 
     * @param  array  $permissions
     */
    public function __construct(...$permissions)
    {
        $this->permissions = Arr::flatten($permissions);
    }

    /**
     * Give the permissions to the given user or role.
     * 
     * @param  Role|User  $roleOrUser
     */
    public function to($roleOrUser)
    {
        if ($roleOrUser instanceof Model) {
            $instance = $roleOrUser;
        } else {
            $instance = Shinobi::role()->where('slug', $roleOrUser)->firstOrFail();
        }

        $instance->givePermissionTo($this->permissions);
    }
}