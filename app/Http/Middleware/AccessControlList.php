<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AccessControlList
{
    protected $ignore = [];
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*if (Auth::user()->hasPermissionTo('Administer roles & permissions')) //If user has this //permission
        {
            return $next($request);
        }*/

        /*
         * Do not forget to set exception for route name missing
         * */

        $routeName = $request->route()->action['as'];

        if (!Auth::user()->hasPermissionTo($routeName)) {
            if (!$request->expectsJson()) {
                abort('401');
            } else {
                return response()->json(['error' => 'Sorry! You are not permitted to access this page'], 401);
            }
        }

        return $next($request);
    }
}
