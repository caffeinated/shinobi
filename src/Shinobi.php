<?php
namespace Caffeinated\Shinobi;

use Illuminate\Contracts\Auth\Guard;

class Shinobi
{
    /**
     * @var Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new UserHasPermission instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Checks if user has the given permissions.
     *
     * @param array|string $permissions
     *
     * @return bool
     */
    public function can($permissions)
    {
        if ($this->auth->check()) {
            return $this->auth->user()->can($permissions);
        }

        return false;
    }

    /**
     * Checks if user has at least one of the given permissions.
     *
     * @param array $permissions
     *
     * @return bool
     */
    public function canAtLeast($permissions)
    {
        if ($this->auth->check()) {
            return $this->auth->user()->canAtLeast($permissions);
        }

        return false;
    }

    /**
	 * Checks if user is assigned the given role.
	 *
	 * @param  string $slug
	 * @return bool
	 */
    public function is($role)
    {
        if ($this->auth->check()) {
            return $this->auth->user()->is($role);
        }

        return false;
    }
}
