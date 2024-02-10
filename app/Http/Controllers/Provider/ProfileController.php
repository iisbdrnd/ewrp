<?php

namespace App\Http\Controllers\Provider;

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
use App\Model\EnProviderUser_provider;
use App\Model\EmployeeBasicInfo_user;
use App\Model\EmployeeDesignation_provider;
use App\Model\CrmJobArea_user;
use App\Model\EnProviderUserInfo_provider;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    //CORPORATE PROFILE UPDATE VIEW PAGE
    public function userProfile()
    {
        $data['user_id'] = $user_id = Auth::guard('provider')->user()->id;
        $project_id = Auth::guard('provider')->user()->project_id;

        $data['userProfileDetails'] = EnProviderUser_provider::join('en_provider_user_info', 'en_provider_user.id', '=', 'en_provider_user_info.user_id')
            ->join('employee_designation', 'en_provider_user_info.designation', '=', 'employee_designation.id')
            ->select('en_provider_user_info.*', 'en_provider_user.email','employee_designation.name as designationName', 'en_provider_user.timezone_id')
            ->where('en_provider_user.project_id', $project_id)
            ->where('en_provider_user.id', $user_id)
            ->where('en_provider_user.email_verified', 1)
            ->where('en_provider_user.valid', 1)
            ->first();

        // echo "<pre>";
        // print_r($data['userProfileDetails']);
        // exit();

        $data['projectInfo'] = DB::table('project_info')->where('valid', 1)->where('project_id', $project_id)->first();
        $data['timezones'] = DB::table('timezones')->where('valid', 1)->get();

        return view('provider.userProfile', $data);
    }

    //Update Profile Action
    public function userProfileUpdate(Request $request)
    {
        $user_id = Auth::guard('provider')->user()->id;
        // DB::beginTransaction();
        $output = array();
        $validator = Validator::make($request->all(), [
            "name"              => "required",
            "mobile"            => "required"
        ]);

        if ($validator->passes()) {

            $user_info = array(
                "name" => $request->name
            );

            EnProviderUser_provider::where('valid', 1)->find($user_id)->update($user_info);

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

            EnProviderUserInfo_provider::valid()->where('user_id', $user_id)->update($employee_details);

            $output['messege'] = 'User profile has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
        // DB::commit();
    }

    //Change password
    public function changePassword(Request $request)
    {
        $user_id = Auth::guard('provider')->user()->id;
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
            $oldPassword = EnProviderUser_provider::find($user_id)->password;

            if(Hash::check($currentPassword, $oldPassword)){
                EnProviderUser_provider::valid()->where('id', $user_id)->update($user_input);
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
    
    //CHANGE TIMEZONE
    public function changeTimeZone(Request $request) {
        $user_id = Auth::guard('provider')->user()->id;
        $output = array();
        $timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();
        $input["timezone_id"] = $timezone->id;
        $input["timezone"] = $timezone->value;
        EnProviderUser_provider::valid()->where('id', $user_id)->update($input);
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

        $data['employeeDesignation'] = EmployeeDesignation_provider::valid()->orderBy('grade', 'asc')->get();
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
