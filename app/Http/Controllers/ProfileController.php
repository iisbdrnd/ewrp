<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Hash;
use Validator;
use DateTime;
use DateInterval;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\User_user;
use App\Model\EmployeeBasicInfo_user;
use App\Model\EmployeeDesignation_user;
use App\Model\CrmJobArea_user;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function userProfile()
    {
    	$data['user_id'] = $user_id = Auth::user()->get()->id;
    	$project_id = Auth::user()->get()->project_id;

        $data['employeeDesignation'] = EmployeeDesignation_user::valid()->orderBy('grade', 'asc')->get();
    	$data['userProfileDetails'] = EmployeeBasicInfo_user::join('users', 'employee_basic_info.user_id', '=', 'users.id')
                ->leftJoin('employee_designation', 'employee_basic_info.designation', '=', 'employee_designation.id')
    			->leftJoin('project', 'employee_basic_info.project_id', '=', 'project.id')
    			->select('employee_basic_info.*', 'users.email','users.password','users.timezone', 'users.timezone_id', 'employee_designation.name as designationName', 'project.name as projectName')
		    	->where('employee_basic_info.valid', 1)
                ->where('employee_basic_info.project_id', $project_id)
		    	->where('employee_basic_info.user_id', $user_id)
		    	->first();
		$data['timezones'] = DB::table('timezones')->where('valid', 1)->get();
        
        return view('userProfile', $data);
    }

    //Update Profile Action
    public function userProfileUpdate(Request $request)
    {
        $user_id = Auth::user()->get()->id;
        DB::beginTransaction();
        $output = array();

        $validator = Validator::make($request->all(), [
            "name"              => "required"
        ]);

        if ($validator->passes()) {

            $user_info = array(
                "name" => $request->name
            );
            User_user::valid()->find($user_id)->update($user_info);

            $employee_details = array(
                "name"          => $request->name,
                "about"         => $request->about,
                "address"       => $request->address,
                "mobile"        => $request->mobile,
                "office_phone"  => $request->office_phone,
                "fax"           => $request->fax,
                "age"           => $request->age,
                "gender"        => $request->gender,
                "image"         => $request->image
            );

            EmployeeBasicInfo_user::valid()->where('user_id', $user_id)->update($employee_details);

            $output['messege'] = 'User profile has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
        DB::commit();
    }

    //Change password
    public function changePassword(Request $request)
    {
        $user_id = Auth::user()->get()->id;
        $output = array();

        $validator = Validator::make($request->all(), [
            "old_password"          => "required",
            "password"              => "required|confirmed"
        ]);

        if ($validator->passes()) {  
            if(!empty($request->password)) {
                $user_input["password"] = bcrypt($request->password);
            }

            $currentPassword = $request->old_password;
            $oldPassword = User_user::find($user_id)->password;

            if(Hash::check($currentPassword, $oldPassword)){
                User_user::valid()->where('id', $user_id)->update($user_input);
                    $output['messege'] = 'Password has been changed';
                    $output['msgType'] = 'success';
                } else {
                    $output['messege'] = 'Your old password is incurrect';
                    $output['msgType'] = 'danger';
                }
        }else{
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }
	
	//Change timezone
    public function changeTimeZone(Request $request) {
        $user_id = Auth::user()->get()->id;
        $output = array();
		$timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();
		$input["timezone_id"] = $timezone->id;
		$input["timezone"] = $timezone->value;
		User_user::valid()->where('id', $user_id)->update($input);
		Auth::loginUsingId($user_id); //update authentication
		$output['messege'] = 'Timezone has been changed';
		$output['msgType'] = 'success';
        echo json_encode($output);
    }

    //Owner Profile
    public function ownerProfile(Request $request, $id)    
    {
        $data['user_id'] = $user_id = Auth::user()->get()->id;
        $project_id = Auth::user()->get()->project_id;
        $ownerId = $request->id;

        $data['employeeDesignation'] = EmployeeDesignation_user::valid()->orderBy('grade', 'asc')->get();
        $data['userProfileDetails'] = $userProfileDetails = EmployeeBasicInfo_user::join('users', 'employee_basic_info.user_id', '=', 'users.id')
                ->leftJoin('employee_designation', 'employee_basic_info.designation', '=', 'employee_designation.id')
                ->leftJoin('project', 'employee_basic_info.project_id', '=', 'project.id')
                ->select('employee_basic_info.*', 'users.email', 'users.password', 'users.timezone_id', 'employee_designation.name as designationName', 'project.name as projectName')
                ->where('employee_basic_info.valid', 1)
                ->where('employee_basic_info.project_id', $project_id)
                ->where('employee_basic_info.user_id', $id)
                ->first();

        $data['userJobArea'] = CrmJobArea_user::valid()->find($userProfileDetails->job_area);
        $data['userTimezones'] = DB::table('timezones')->find($userProfileDetails->timezone_id);
        $data['userReportTo'] = User_user::find($userProfileDetails->report_to);
        
        
        return view('ownerProfile', $data);
    }
}
