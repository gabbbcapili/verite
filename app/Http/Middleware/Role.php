<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
        foreach($roles as $role) {
        // Check if user has the role This check will depend on how your roles are set up
            if($request->user()->hasRole($role))
                return $next($request);
        }
        abort(403, 'Unauthorized Access');
    }
}
