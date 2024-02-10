<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;

class SoftAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $admin_id = Auth::guard('softAdmin')->id();
        $route = $request->route()->getName();
        $actions = $request->route()->getAction();
        if($route != '') {
            $access = DB::table('admin_access')->where('admin_id', $admin_id)->where('route', $route)->first();
            if(empty($access)) {
                if(array_key_exists('access', $actions)) {
                    $routeArray = $actions['access'];

                    $selfUrlPrefix = '';
                    if(array_key_exists('prefix', $actions)) {
                        $selfUrlPrefix = explode("/", $actions['prefix']);
                        if(count($selfUrlPrefix)>1) { array_shift($selfUrlPrefix); }
                        $selfUrlPrefix = implode("/", $selfUrlPrefix);
                    }

                    $selfRoutePrefix = 'softAdmin.';

                    $i = 0;
                    foreach($routeArray as $key => $route) {
                        if($key==$i) {
                            $urlPrefix = $selfUrlPrefix;
                            $i++;
                        } else {
                            $urlPrefix = $key;
                        }
                        $routePrefix = $selfRoutePrefix;

                        $route = explode("|", $route);
                        $routeType = (count($route)>1) ? array_shift($route) : "";
                        $route = implode("|", $route);

                        $holePrefix = (!empty($routePrefix)) ? $routePrefix : "";
                        if($routeType=="resource") {
                            $holePrefix = (!empty($urlPrefix)) ? $holePrefix.$urlPrefix.'.' : $holePrefix;
                        }
                        $route = $holePrefix.$route;

                        $access = DB::table('admin_access')->where('admin_id', $admin_id)->where('route', $route)->first();

                        if(!empty($access)) { break; }
                    }
                } else {
                    $routeName = explode(".", $route);
                    $lastIndex = count($routeName)-1;
                    if($routeName[$lastIndex]=='store') {
                        $routeName[$lastIndex] = 'create';
                    } else if($routeName[$lastIndex]=='update') {
                        $routeName[$lastIndex] = 'edit';
                    }
                    $accessRoute = implode(".", $routeName);

                    $access = DB::table('admin_access')->where('admin_id', $admin_id)->where('route', $accessRoute)->first();
                }
            }
        } else {
            $access = false;
        }

        if(!empty($access)) {
            return $next($request);
        } else {
            echo "You have no Access";
        }
    }
}