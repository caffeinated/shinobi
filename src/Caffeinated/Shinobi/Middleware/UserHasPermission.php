<?php namespace Caffeinated\Shinobi\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserHasPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (!Auth::user() || !Auth::user()->can($permission)) {
            // User doesn't have admin access

            if ($request->ajax()) {
                // And the request was AJAX, so just die with a simple response.
                return response('Unauthorized.', 401);
            }

            // Full stop.
            return abort(401);
        }

        return $next($request);
    }
}
