<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use Mail;


use App\Model\EmployeeBasicInfo_user;
use App\Model\EmployeeDesignation_user;
use App\Model\User_user;
use App\Model\User_config_user;
use App\Model\ProjectInfo_user;
use App\Model\Project_user;
use App\Model\User_admin;
use App\Model\CrmJobArea_user;
use App\Model\Web_RegistrationInfo;
use App\Model\Web_UserInfo;
use App\Model\CrmPackage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CrmProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function edit(Request $request)
    {
        $data['inputData'] = $request->all();

        $project_id = Auth::user()->get()->project_id;
        $data['countries'] = DB::table('crm_country')->orderBy('country', 'asc')->get();
        $data['currency'] = DB::table('crm_currency')->get();
        $data['projectInfo'] = $projectInfo = Project_user::where('valid', 1)->find($project_id);
        $data['projectDetails'] = ProjectInfo_user::where('valid', 1)->where('project_id', $projectInfo->id)->first();

        return view('admin.project.update', $data);
    }



    public function update(Request $request)
    {
        DB::beginTransaction();
        $output = array();
        $project_id = Auth::user()->get()->project_id;

        $validator = Validator::make($request->all(), [
            "company_name"          => "required",
            "country"               => "required",
            "mobile"                => "required|numeric",
            "email"                 => "required|email"
        ]);

        if ($validator->passes()) {

            $project = array(
                "name"              => $request->company_name
            );

            Project_user::where('valid', 1)->where('id', $project_id)->update($project);
            
            $project_info = array(
                "company_name"      => $request->company_name,
                "logo"              => $request->logo,
                "address"           => $request->address,
                "street"            => $request->street,
                "city"              => $request->city,
                "state"             => $request->state,
                "post_code"         => $request->post_code,
                "country"           => $request->country,
                "mobile"            => $request->mobile,
                "office_phone"      => $request->office_phone,
                "fax"               => $request->fax,
                "website"           => $request->website
            );

            ProjectInfo_user::where('valid', 1)->where('project_id', $project_id)->update($project_info);

            $output['messege'] = 'Company profile has been Updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);

        DB::commit();
    }
   
}

