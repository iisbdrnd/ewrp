<?php

namespace App\Http\Controllers\softAdmin;

use Illuminate\Http\Request;
use Auth;
use DB;
use Helper;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Project;
use App\Model\EnProviderUser_admin;

class DashboardController extends Controller {
    //dashboard
    public function index() {
    	$data['activeProjects'] = Project::valid()->get();
    	$data['todayExpireProjects'] = Project::valid()->get();
    	$data['totalExpiredProjects'] = Project::valid()->get();
    	$data['totalProjects'] = Project::get();

    	$data['activeUsers'] = EnProviderUser_admin::join('project', 'en_provider_user.project_id', '=', 'project.id')
    			->where('project.valid', 1)
    			->where('en_provider_user.valid', 1)
    			->count();
    	$data['todayExpireUsers'] = EnProviderUser_admin::join('project', 'en_provider_user.project_id', '=', 'project.id')
    			->where('project.valid', 1)
    			->where('en_provider_user.valid', 1)
    			->count();
		$data['totalExpiredUsers'] = EnProviderUser_admin::join('project', 'en_provider_user.project_id', '=', 'project.id')
    			->where('project.valid', 1)
    			->where('en_provider_user.valid', 1)
    			->count();
    	$data['totalUsers'] = EnProviderUser_admin::get();


        return view('softAdmin.dashboard', $data);
    }

    public function activeProjectList(Request $request) {   	
    	$data['inputData'] = $request->all();

        return view('softAdmin.projectSummary.list', $data); 
    }

	public function activeProjectsListData(Request $request) {
    	$today = date("Y-m-d");
        return view('softAdmin.projectSummary.listData');

    }

}