<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Http\Requests;

class SoftAdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if((Auth::guard('softAdmin')->check()))
        {
            return $next($request);
        }
        else
        {
            if($request->ajax()) {
                if($request->wantsJson()) {
                    return json_encode(array('auth' => 0));
                } else {
                    return 0;
                }
            } else {
                return redirect()->guest(route('softAdmin.login'));
            }
        }
    }
}