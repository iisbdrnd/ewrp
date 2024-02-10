<?php

namespace App\Http\Middleware;

use App\Model\SoftwareModules;
use App\Model\SoftwareMenuAccess;
use App\Model\SoftwareInternalLinkAccess;
use Closure;
use Auth;
use DB;

class ProviderUserAccess
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
        $user_id = Auth::guard('provider')->id();
        $route = $request->route()->getName();
        $actions = $request->route()->getAction();

        if(!empty($route)) {
            $access = DB::table('software_access')->where('user_id', $user_id)->where('route', $route)->first();

            if(empty($access)) {
                if(array_key_exists('access', $actions)) {
                    $routeArray = $actions['access'];

                    $selfUrlPrefix = '';
                    $dbUrlPrefix = '';
                    if(array_key_exists('prefix', $actions)) {
                        $dbUrlPrefix = $actions['prefix'];
                        $selfUrlPrefix = explode("/", $actions['prefix']);
                        $selfUrlPrefix = implode(".", $selfUrlPrefix);
                    }

                    $selfRoutePrefix = '';
                    if(!empty($dbUrlPrefix)) {
                        $module = SoftwareModules::where('url_prefix', $dbUrlPrefix)->where('valid', 1)->first();
                        $selfRoutePrefix = (empty($module)) ? '' : $module->route_prefix;
                    }

                    $i = 0;
                    foreach($routeArray as $key => $route) {
                        if($key==$i) {
                            $urlPrefix = $selfUrlPrefix;
                            $routePrefix = $selfRoutePrefix;
                            $i++;
                        } else {
                            $urlPrefix = $key;
                            $routePrefix = '';
                            if(!empty($urlPrefix)) {
                                $module = SoftwareModules::where('url_prefix', $urlPrefix)->where('valid', 1)->first();
                                $routePrefix = (empty($module)) ? '' : $module->route_prefix;
                            }
                        }

                        $route = explode("|", $route);
                        $routeType = (count($route)>1) ? array_shift($route) : "";
                        $route = implode("|", $route);

                        $holePrefix = (!empty($routePrefix)) ? $routePrefix : "";
                        if($routeType=="resource") {
                            $holePrefix = (!empty($urlPrefix)) ? $holePrefix.$urlPrefix.'.' : $holePrefix;
                        }
                        $route = $holePrefix.$route;
                        $access = DB::table('software_access')->where('user_id', $user_id)->where('route', $route)->first();

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

                    $access = DB::table('software_access')->where('user_id', $user_id)->where('route', $accessRoute)->first();
                }
            }
        } else {
            $access = false;
        }

        if(!empty($access)) {
            $request->accessMenuId = $access->menu_id;
            return $next($request);
        } else {
            $msg = '<div class="login-panel panel panel-default plain animated bounceIn"><div class="panel-body"><div class="row"><div class="col-lg-12 mt10 mb10 text-center"><strong>';
            $msg .= 'You have no Access';
            $msg .= '</strong></div></div></div></div>';
            echo $msg;
            //return redirect()->guest('login');
        }
    }
}
