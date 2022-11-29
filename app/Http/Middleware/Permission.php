<?php

namespace App\Http\Middleware;

use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $permissions)
    {
        foreach($permissions as $permission) {
            if($request->user()->can($permission))
                return $next($request);
        }
        abort(403, 'Unauthorized Access');
    }
}
