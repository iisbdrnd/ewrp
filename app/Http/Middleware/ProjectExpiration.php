<?php

namespace App\Http\Middleware;

use App\Model\Project;
use Closure;
use Auth;
use DB;

class ProjectExpiration
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
        $project_id = Auth::user()->get()->project_id;
        $project = Project::valid()->find($project_id);

        if(!empty($project)) {
            //Day Left Calculation
            $current_date = date_create(date('Y-m-d'));
            $expire_date = date_create($project->crm_expire_date);
            $diff = date_diff($current_date, $expire_date);
            $diff = intval($diff->format("%R%a"));
            if($diff>=0) {
                return $next($request);
            } else {
                $msg = '<div class="login-panel panel panel-default plain animated bounceIn"><div class="panel-body"><div class="row"><div class="col-lg-12 mt10 mb10 text-center"><p style="color: #ff3232; font-size: 16px; font-weight: 600;">';
                $msg .= 'Your Package has already expired, Please communicate with your administrator and renew your subscription.';
                $msg .= '</p></div></div></div></div>';
                echo $msg;
            }
        } else {
            $msg = '<div class="login-panel panel panel-default plain animated bounceIn"><div class="panel-body"><div class="row"><div class="col-lg-12 mt10 mb10 text-center"><strong>';
            $msg .= 'Project Not Exist';
            $msg .= '</strong></div></div></div></div>';
            echo $msg;
        }
    }
}
