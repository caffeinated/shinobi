<?php

namespace Caffeinated\Shinobi\Tactics;

use Illuminate\Support\Arr;

class AssignRoleTo
{
    /**
     * @var array
     */
    protected $roles;

    /**
     * Create a new AssignRoleTo instance.
     * 
     * @param  array  $roles
     */
    public function __construct(...$roles)
    {
        $this->roles = Arr::flatten($roles);
    }

    public function to($user)
    {
        $user->assignRoles($this->roles);
    }
}