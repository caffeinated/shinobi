<?php

namespace Caffeinated\Shinobi\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserHasAllRoles
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
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $closure
     * @param string                   $role
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $roles      = call_user_func_array('array_merge', $roles);
        $authorized = call_user_func_array([$this->auth->user(), 'hasAllRoles'], $roles);

        if (! $authorized) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

            return abort(401);
        }

        return $next($request);
    }
}
