<?php

namespace App\Http\Controllers\SoftAdmin;

use App\Http\Controllers\Provider\Admin\EmployeeController;
use App\Http\Controllers\Provider\AdapterController;
use Illuminate\Http\Request;
use Helper;
use App\Model\EnProviderUser_admin;
use App\Model\Project;
use App\Model\EmployeeDesignation_admin;
use App\Model\EnProviderUserInfo_admin;
use App\Model\User_config_admin;
use App\Model\Web_RegistrationInfo;
use App\Model\EnProviderDesignation_admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Validator;
use DateTime;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('softAdmin.user.index', $data);
    }

    public function userList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['emp_id', 'name', 'designation', 'email', 'project_identity', 'report_to_name', 'email_verified', 'status', 'regDate']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;
        $data['projectId'] = $projectId = $request->project;
        $data['email_verified'] = $email_verified = (!empty($request->email_verified)) ? $request->email_verified : 3;

        $data['projectIdData'] = Project::valid()->orderBy('project_id', 'asc')->get();

        $data['users'] = EnProviderUserInfo_admin::join('en_provider_user as a', 'a.id', '=', 'en_provider_user_info.user_id')
            ->leftJoin('en_provider_user as b', 'b.id', '=', 'en_provider_user_info.report_to')
            ->leftJoin('project', 'en_provider_user_info.project_id', '=', 'project.id')
            ->leftJoin('en_provider_designation', 'en_provider_user_info.designation', '=', 'en_provider_designation.id')
            ->select('en_provider_user_info.*', 'project.project_id as project_identity', 'project.name as project_name', 'en_provider_designation.name as designation', 'a.id as userId', 'a.project_id as projectId', 'a.email', 'a.created_at as regDate', 'a.email_verified', 'b.name as report_to_name', 'a.status')
            ->where(function($query) use ($search)
            {
                $query->where('en_provider_user_info.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('en_provider_user_info.emp_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('en_provider_designation.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('a.email', 'LIKE', '%'.$search.'%')
                    ->orWhere('project.project_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('b.name', 'LIKE', '%'.$search.'%');
            })
            ->where(function($query) use ($projectId)
            {
                if(!empty($projectId)) {
                    $query->where('project.id', $projectId);
                }
            })
            ->where(function($query) use ($email_verified)
            {
                switch($email_verified) {
                    case 1:
                        $query->where('a.email_verified', 1);
                        break;
                    case 2:
                        $query->where('a.email_verified', 0);
                        break;
                    default:
                        $query->where('a.email_verified', 1)
                            ->orWhere('a.email_verified', 0);
                }

            })
            ->where('en_provider_user_info.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.user.userList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['employeeDesignation'] = EnProviderDesignation_admin::valid()->orderBy('grade', 'asc')->get();
        $data['projects'] = Project::where('valid', 1)->orderBy('project_id', 'asc')->get();
        $data['timezones'] = DB::table('timezones')->where('valid', 1)->get();
        return view('softAdmin.user.createUser', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $output = array();

        $validator = Validator::make($request->all(), [
            "project_id"        => "required",
            "name"              => "required",
            "designation"       => "required",
            "report_to"         => "required",
            "email"             => "required|email"
        ]);

        if ($validator->passes()) {
            $project_id = $request->project_id;
            $project = Project::valid()->find($project_id);
            $emailCheck = EnProviderUser_admin::where('email', $request->email)->where('valid', 1)->first();

            if(empty($emailCheck)){
                    $timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();
                    $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
                    $original_string = implode("", $original_string);
                    $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);
                    //Action
                    EnProviderUser_admin::create([
                        "project_id"        => $project_id,
                        "name"              => $request->name,
                        "email"             => $request->email,
                        "auth_key"          => str_replace('.', '', uniqid('', true)),
                        "secret_key"        => md5(time()),
                        "timezone_id"       => $timezone->id,
                        "timezone"          => $timezone->value,
                        "verification_code" => $verification_code,
                        "status"            => "Active"

                    ]);

                    $user_id = EnProviderUser_admin::orderBy('id', 'desc')->first()->id;
                    EnProviderUserInfo_admin::create([
                        "user_id"       => $user_id,
                        "emp_id"        => EmployeeController::getEmpId($project_id),
                        "name"          => $request->name,
                        "surname"       => $request->surname,
                        "designation"   => $request->designation,
                        "job_area"      => $request->job_area,
                        "report_to"     => $request->report_to,
                        "project_id"    => $project_id
                    ]);


                    //END EMAIL
					// Helper::mailConfig();
                    // $email_data['data'] = [
                    //     'name'              =>  $request->name,
                    //     'email'             =>  $request->email
                    // ];
                    // $email_data['link'] = url('provider/pro_email_verification?token=' . $verification_code);;
                    // Mail::send('emails.email_verification', $email_data, function($message) use ($request)
                    // {
                    //     $message->subject('Verify Sudoksho Account');
                    //     $message->to($request->email, $request->name);
                    // });

                    $output['messege'] = 'Provider user has been created';
                    $output['msgType'] = 'success';
                }else {
                    $output['messege'] = 'Email already exist.';
                    $output['msgType'] = 'danger';
                }
            } else {
                $output = Helper::vError($validator);
            }

        echo json_encode($output);
        DB::commit();
    }


    public function userEmailResend(Request $request)
    {
        $output = array();

        $project_id = $request->project_id;
        $user_id = $request->user_id;

        $user_info = EnProviderUser_admin::where('project_id', $project_id)->find($user_id);

        if($user_info->email_verified==1){
                $output['msg_title'] = 'Sorry !!!';
                $output['messege'] = 'Email already verified.';
                $output['messege_icon'] = 'icomoon-icon-close gritter-icon';
                $output['msgType'] = 'error-notice';
        }else {
            $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
            $original_string = implode("", $original_string);
            $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);
            $user_info->update(["verification_code"=> $verification_code]);

            //send mail
            Helper::mailConfig();
            $email_data['link'] = url('provider/pro_email_verification?token=' . $verification_code);
            Mail::send('emails.email_verification', $email_data, function($message) use ($user_info)
            {
                $message->subject('Verify Sudoksho Account');
                $message->to($user_info->email, $user_info->name);
            });
                $output['msg_title'] = 'Done !!!';
                $output['messege'] = 'Email has been sent';
                $output['messege_icon'] = 'icomoon-icon-checkmark-3';
                $output['msgType'] = 'success-notice';
        }

        echo json_encode($output);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $data['users'] = EnProviderUser_admin::where('valid', 1)->find($id);



        return view('softAdmin.user.userDetails', $data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['user'] = $user = EmployeeBasicInfo_admin::join('users', 'users.id', '=', 'employee_basic_info.user_id')
            ->leftJoin('project', 'employee_basic_info.project_id', '=', 'project.id')
            ->select('employee_basic_info.*', 'users.email', 'project.name as project_name', 'users.timezone_id')
            ->where('employee_basic_info.user_id', $id)
            ->where('employee_basic_info.valid', 1)
            ->where('users.valid', 1)
            ->first();

        $data['projects'] = Project::where('valid', 1)->orderBy('project_id', 'asc')->get();
        $data['employeeDesignation'] = EmployeeDesignation_admin::valid()->where('project_id', $user->project_id)->orderBy('grade', 'asc')->get();
        $data['reportTo'] = EnProviderUser_admin::valid()->where('id', '!=', $id)->where('project_id', $user->project_id)->orderBy('name', 'asc')->get();
        $data['timezones'] = DB::table('timezones')->where('valid', 1)->get();

        return view('softAdmin.user.updateUser', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $output = array();

        $validator = Validator::make($request->all(), [
            "project_id"            => "required",
            "name"                  => "required",
            "designation"           => "required",
            "report_to"             => "required",
            "email"                 => "required|email"
        ]);

        if ($validator->passes()) {
            $emailChk = EnProviderUser_admin::where('email', $request->email)->where('id', '!=', $id)->first();

            if(empty($emailChk)) {
                $timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();
                //Action
                $user_input = array(
                    "project_id"    => $request->project_id,
                    "name"          => $request->name,
                    "email"         => $request->email,
                    "timezone_id"   => $timezone->id,
                    "timezone"      => $timezone->value,
                    "status"        => $request->status
                );
                $userChk = EnProviderUser_admin::find($id);
                if($userChk->email != $request->email) {

                    //upsate user_info table in web database
                    Web_UserInfo::where('valid', 1)->where('user_id', $id)->where('db_status', 2)->update(["valid"=> 0]);
                    Web_RegistrationInfo::where('valid', 1)->where('user_id', $id)->where('package', 2)->update(["valid"=> 0]);

                    $user_input["email_verified"] = 0;
                    //insert registraion_info into web database
                    $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
                    $original_string = implode("", $original_string);
                    $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);
                    Web_RegistrationInfo::create([
                        "name"              => $request->name,
                        "email"             => $request->email,
                        "timezone_id"       => $timezone->id,
                        "timezone"          => $timezone->value,
                        "verification_code" => $verification_code,
                        "package"           => 2,
                        "reason"            => 3,
                        "user_id"           => $id,
                        "valid"             => 1
                    ]);
                    //send mail
					Helper::mailConfig();
                    $email_data['link'] = AdapterController::$webUrl.'/user/email_verification?token=' . $verification_code;
                    Mail::send('emails.email_verification', $email_data, function($message) use ($request)
                    {
                        $message->from('no_reply@leadvas.com', 'Leadvas');
                        $message->subject('Verify leadvas Account');
                        $message->to($request->email, $request->name);
                    });
                }

                $emp_input = array(
                    "name"          => $request->name,
                    "surname"       => $request->surname,
                    "designation"   => $request->designation,
                    "job_area"      => $request->job_area,
                    "report_to"     => $request->report_to
                );

                $emp_data = EmployeeBasicInfo_admin::valid()->where('user_id', $id)->first();

                //Compilation Status
                if($request->report_to!=$emp_data->report_to) {
                    $project_id = $emp_data->project_id;
                    $project = Project::valid()->find($project_id);
                    $project->update(['compile_status' => 0]);
                }

                $emp_data->update($emp_input);
                EnProviderUser_admin::valid()->where('id', $id)->update($user_input);

                $output['messege'] = 'Employee has been updated';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'Email already exist.';
                $output['msgType'] = 'danger';
            }
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
        DB::commit();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        EnProviderUser_admin::valid()->find($id)->delete();
        EmployeeBasicInfo_admin::valid()->where('user_id', $id)->first()->delete();
		Web_UserInfo::where('valid', 1)->where('user_id', $id)->where('db_status', 2)->update(["valid"=> 0]);
		Web_RegistrationInfo::where('valid', 1)->where('user_id', $id)->where('package', 2)->update(["valid"=> 0]);
        DB::commit();
    }


    public function userLogin(Request $request)
    {
        Auth::corporate()->loginUsingId(1403);
        // $id = $request->id;
        // $data = array(
        //     'id' => $id,
        //     'email_verified' => 1,
        //     'status'   => 'Active',
        //     'valid'    => 1
        // );
        // $user = EnProviderUser_admin::valid()->where('email_verified', 1)->where('status', 'Active')->find($id);
        
        // $output = array();
        
        // if (!empty($user)) {
        //     Auth::provider()->loginUsingId($id);
        //     $output["result"] = true;
        // } else {
        //     $output["result"] = false;
        //     $output["msg"] = "Id is not valid or verified.";
        // }
        // return json_encode($output);
    }

}
