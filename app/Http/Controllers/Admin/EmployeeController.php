<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use Mail;


use App\Model\EmployeeBasicInfo_user;
use App\Model\EmployeeDesignation_user;
use App\Model\User_user;
use App\Model\User_config_user;
use App\Model\ProjectInfo;
use App\Model\Project;
use App\Model\User_admin;
use App\Model\JobArea_user;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('admin.employee.employeeList', $data);
    }

    public function employeeListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['emp_id', 'name', 'designation', 'area_name', 'email', 'report_to_name', 'status']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $project_id = Auth::user()->get()->project_id;
        $data['email_verified'] = $email_verified = (!empty($request->email_verified)) ? $request->email_verified : 3;

        $data['employeeBasicInfo'] = EmployeeBasicInfo_user::leftJoin('users as a', 'a.id', '=', 'employee_basic_info.user_id')
            ->leftJoin('users as b', 'b.id', '=', 'employee_basic_info.report_to')
            ->leftJoin('project', 'employee_basic_info.project_id', '=', 'project.id')
            ->leftJoin('employee_designation', 'employee_basic_info.designation', '=', 'employee_designation.id')
            ->leftJoin('job_area', 'employee_basic_info.job_area', '=', 'job_area.id')
            ->select('employee_basic_info.*', 'project.project_id as project_identity', 'employee_designation.name as designation','job_area.area_name', 'a.email', 'a.created_at as regDate', 'a.email_verified', 'a.status')
            ->where(function($query) use ($search)
            {
                $query->where('employee_basic_info.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('employee_basic_info.surname', 'LIKE', '%'.$search.'%')
                    ->orWhere('employee_basic_info.emp_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('employee_designation.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('job_area.area_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('a.email', 'LIKE', '%'.$search.'%')
                    ->orWhere('project.project_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('b.name', 'LIKE', '%'.$search.'%');
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
            ->where('employee_basic_info.project_id', $project_id)
            ->where('employee_basic_info.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('admin.employee.employeeListData', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
    */
    
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $project_id = Auth::user()->get()->project_id;
        $data['employeeDesignationAddAccess'] = Helper::userAccess('employeeDesignation.create', 'admin');
        $data['areaAddAccess'] = Helper::userAccess('area.create', 'admin');
        $data['empId'] = self::getEmpId(Auth::user()->get()->project_id);
        $data['employeeDesignation'] = EmployeeDesignation_user::valid()->orderBy('grade', 'asc')->get();
        $data['jobArea'] = JobArea_user::valid()->orderBy('area_name', 'asc')->get()->keyBy('id')->all();
        $data['timezones'] = DB::table('timezones')->where('valid', 1)->get();

        return view('admin.employee.createEmployee', $data);
    }

    public function employeeIdView() {
        echo json_encode(['empId' => self::getEmpId(Auth::user()->get()->project_id)]);
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
            "employee_id"       => "required",
            "name"              => "required",
            "designation"       => "required",
            "timezone"          => "required",
            "email"             => "required|email"
        ]);

        if ($validator->passes()) {
            $project_id = Auth::user()->get()->project_id;
            $project = Project::valid()->find($project_id);
            $emailCheck = User_user::where('email', $request->email)->where('valid', 1)->first();
            $empIdCheck = EmployeeBasicInfo_user::where('employee_id', $request->employee_id)->where('valid', 1)->first();

            

            if (empty($empIdCheck)) {
                if(empty($emailCheck)){
                    $timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();
                    $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
                    $original_string = implode("", $original_string);
                    $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);
                    
                    User_user::create([
                        "name"          => $request->name,
                        "email"         => $request->email,
                        "auth_key"      => str_replace('.', '', uniqid('', true)),
                        "secret_key"    => md5(time()),
                        "timezone_id"   => $timezone->id,
                        "timezone"      => $timezone->value,
                        "verification_code" => $verification_code,
                        "status"        => "Active"
                    ]);

                    $user_id = User_user::valid()->orderBy('id', 'desc')->first()->id;
                    EmployeeBasicInfo_user::create([
                        "emp_id"        => self::getEmpId($project_id),
                        "employee_id"   => $request->employee_id,
                        "user_id"       => $user_id,
                        "name"          => $request->name,
                        "surname"       => $request->surname,
                        "designation"   => $request->designation,
                        "job_area"      => $request->job_area
                    ]);
                    
                    $date = new DateTime();
                    $created_at = $date->format('Y-m-d H:i:s');
                    //send mail
                    Helper::mailConfig();
                    $email_data['link'] = url('email_verification?token=' . $verification_code);
                    Mail::send('emails.email_verification', $email_data, function($message) use ($request)
                    {
                        //$message->from('no-reply@delbd.com ', 'Digital Engravers Ltd.');
                        $message->subject('Verify East West Human Resource Center Ltd. Account');
                        $message->to($request->email, $request->name);
                    });

                    //Compilation Status
                    if($request->report_to>0) {
                        $project->update(['compile_status' => 0]);
                        $output['compile'] = true;
                    } else {
                        $output['compile'] = false;
                    }

                    $output['ttlUser'] = User_user::valid()->count();
                    $output['empId'] = self::getEmpId($project_id);

                    $output['messege'] = 'Employee has been created';
                    $output['msgType'] = 'success';
                
                }else {
                    $output['messege'] = 'Email already exist.';
                    $output['msgType'] = 'danger';
                }
            }else{
                $output['messege'] = 'Employee Id already exist.';
                $output['msgType'] = 'danger';
            }
            
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
        DB::commit();
    }


    public function employeeEmailResend(Request $request)
    {
        $output = array();

        $project_id = Auth::user()->get()->project_id;
        $project = Project::valid()->find($project_id);
        $user_id = $request->user_id;
        $user_info = User_admin::where('project_id', $project_id)->find($user_id);
        $emailCheck = User_user::where('email', $user_info->email)->where('valid', 1)->first();

        if(!empty($emailCheck) && $user_info->email_verified==1){
                $output['msg_title'] = 'Sorry !!!';
                $output['messege'] = 'Email already verified.';
                $output['messege_icon'] = 'icomoon-icon-close gritter-icon';
                $output['msgType'] = 'error-notice';
            }else {
                $verification_code = $user_info->verification_code;
                
                //send mail
    			Helper::mailConfig();
                $email_data['link'] = url('email_verification?token=' . $verification_code);
                Mail::send('emails.email_verification', $email_data, function($message) use ($user_info)
                {
                    //$message->from('no-reply@delbd.com', 'Digital Engravers Ltd.');
                    $message->subject('Verify East West Human Resource Center Ltd. Account');
                    $message->to($user_info->email, $user_info->name); 
                });

                $output['msg_title'] = 'Done !!!';
                $output['messege'] = 'Email has been sent';
                $output['messege_icon'] = 'icomoon-icon-checkmark-3';
                $output['msgType'] = 'success-notice';
            }

        echo json_encode($output);
    }


    public function edit($id)
    {   
        $project_id = Auth::user()->get()->project_id;
        $data['employee'] = EmployeeBasicInfo_user::valid()->where('user_id', $id)->first();
        $data['user'] = User_user::valid()->find($id);
        $data['employeeDesignation'] = EmployeeDesignation_user::valid()->orderBy('grade', 'asc')->get();
        $data['jobArea'] = JobArea_user::valid()->orderBy('area_name', 'asc')->get()->keyBy('id')->all();

        $data['timezones'] = DB::table('timezones')->where('valid', 1)->get();
        $data['employeeDesignationAddAccess'] = Helper::userAccess('employeeDesignation.create', 'admin');
        $data['areaAddAccess'] = Helper::userAccess('area.create', 'admin');

        return view('admin.employee.updateEmployeeBasicInfo', $data);
    }



    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $output = array();

        $validator = Validator::make($request->all(), [
            "name"              => "required",
            "designation"       => "required",
			"timezone"       	=> "required",
            "email"             => "required|email"
            // "password"          => "min:6|confirmed"
        ]);

        if ($validator->passes()) {
            $emailChk = User_user::where('email', $request->email)->where('id', '!=', $id)->where('valid', 1)->first();
            $empIdCheck = EmployeeBasicInfo_user::where('employee_id', $request->employee_id)->where('id', '!=', $id)->where('valid', 1)->first();

            if (empty($empIdCheck)) {
                if(empty($emailChk)) {
                    $timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();
                    //Action
                    $user_input = array(
                        "name"          => $request->name,
                        "email"         => $request->email,
                        "timezone_id"   => $timezone->id,
                        "timezone"      => $timezone->value
                    );
    				$userChk = User_user::find($id);
    				if($userChk->email != $request->email) {
    					$userInfo = User_user::where('id', $id)->where('valid', 1)->first();
    					$user_input["email_verified"] = 0;
    					$verification_code = $emailChk->verification_code;
    					
    					//Send mail
    					Helper::mailConfig();
    					$email_data['link'] = url('email_verification?token=' . $verification_code);
                        Mail::send('emails.email_verification', $email_data, function($message) use ($request)
                        {
    						//$message->from('no-reply@delbd.com', 'Digital Engravers Ltd.');
    						$message->subject('Verify Digital Engravers Ltd. Account');
    						$message->to($request->email, $request->name);
                        });
    				}

                    $emp_input = array(
                        "employee_id"   => $request->employee_id,
                        "name"          => $request->name,
                        "surname"       => $request->surname,
                        "designation"   => $request->designation,
                        "job_area"      => $request->job_area
                    );

                    $emp_data = EmployeeBasicInfo_user::valid()->where('user_id', $id)->first();

                    //Compilation Status
                    if($request->report_to!=$emp_data->report_to) {
                        $project_id = Auth::user()->get()->project_id;
                        $project = Project::valid()->find($project_id);
                        $project->update(['compile_status' => 0]);
    					
    					$output['compile'] = true;
                    } else {
                        $output['compile'] = false;
                    }

                    $emp_data->update($emp_input);
                    User_user::valid()->where('id', $id)->update($user_input);

    				$output['ttlUser'] = User_user::valid()->count();
                    $output['messege'] = 'Employee has been updated';
                    $output['msgType'] = 'success';
                } else {
                    $output['messege'] = 'Email already exist.';
                    $output['msgType'] = 'danger';
                }
                } else {
                    $output['messege'] = 'Employee Id already exist.';
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
        User_user::valid()->find($id)->delete();
        EmployeeBasicInfo_user::valid()->where('user_id', $id)->first()->delete();
        DB::commit();
    }

    //Organogram
    public function employeeOrganogram(Request $request) {
        $data['inputData'] = $request->all();
		$organogram = self::getEmployeeOrganogram();
		$data["highestStep"] = self::getHighestStep($organogram);
        $data["organogram"] = json_encode($organogram);
        
        return view('admin.employee.employeeOrganogram', $data);
    }
	
	private static function getHighestStep($organogram, $highestStep=0) {
		$highestStep = ($organogram["step"]>$highestStep) ? $organogram["step"] : $highestStep;
		$children = (isset($organogram["children"])) ? $organogram["children"] : array();
		foreach($children as $children) {
			$highestStep = self::getHighestStep($children, $highestStep);
		}
		return $highestStep;
	}

    //Compile Employee Tree
    public function compileEmployeeOrganogram(Request $request) {
        $data['inputData'] = $request->all();

        $project_id = Auth::user()->get()->project_id;
        $data["project"] = Project::valid()->find($project_id);
        $data["ttlUser"] = User_user::valid()->count();

        return view('admin.employee.compileEmployeeOrganogram', $data);
    }

    //Compile Employee Tree
    public function compileEmployeeOrganogramAction() {
        set_time_limit(5000);
        $project_id = Auth::user()->get()->project_id;

        $teamArray = self::getTeamArray();
        foreach($teamArray as $id => $team) {
            $team = (!empty($team)) ? implode(',', $team) : "";
            EmployeeBasicInfo_user::valid()->where('user_id', $id)->update(['team_members' => $team]);
        }
        Project::valid()->find($project_id)->update(["compile_status" => 1]);

        $output['messege'] = 'Compile has been completed';
        $output['msgType'] = 'success';

        echo json_encode($output);
    }

    public static function getEmpId($project_id) {
        $empId = EmployeeBasicInfo_user::orderBy('id', 'desc')->where('project_id', $project_id)->where('valid', 1)->first();
        $empId = (empty($empId)) ? 0 : $empId->emp_id;
        $empId = ((int) $empId)+1;
        $empIdLen = strlen($empId);
        if($empIdLen==1) { $empId = '0000'.$empId; }
        else if($empIdLen==2) { $empId = '000'.$empId; }
        else if($empIdLen==3) { $empId = '00'.$empId; }
        else if($empIdLen==4) { $empId = '0'.$empId; }
        return $empId;
    }

    private static function getEmployeeOrganogram($user_id=0, $name="", $userInfo="", $step=1) {
        $project_id = Auth::user()->get()->project_id;
        $childUser = EmployeeBasicInfo_user::join("users", "employee_basic_info.user_id", "=", "users.id")
			->leftJoin('employee_designation', 'employee_basic_info.designation', '=', 'employee_designation.id')
			->leftJoin('job_area', 'employee_basic_info.job_area', '=', 'job_area.id')
			->select("employee_basic_info.*", "users.email", "employee_designation.name as designation_name", "job_area.area_name", "job_area.area_details")
			->where('employee_basic_info.report_to', $user_id)
			->where('employee_basic_info.project_id', $project_id)
			->where('employee_basic_info.valid', 1)
			->get();

        $contentStart = '<div class="row"><div class="col-lg-12"><table width="100%" cellspacing="0" class="table table-striped table-bordered" id="basic-datatables"><tbody>';
        $contentEnd = '</tbody></table></div></div>'; $trStart = '<tr><td>'; $trMiddle = '</td><td>'; $trEnd = '</td></tr>';

        //Parent
        if($user_id==0) {
            if(count($childUser)==1) {
                $organogram["name"] = $childUser[0]->name;
				$organogram["meta"]["dataOriginalTitle"] = (empty($childUser[0]->image)) ? $childUser[0]->name : '<img class="pr10" src="'.url('public/uploads/user_profile_images/'.$childUser[0]->image).'" style="max-width: 32px;">'.$childUser[0]->name;
                $userInfo = $childUser[0];
				$childUser = EmployeeBasicInfo_user::join("users", "employee_basic_info.user_id", "=", "users.id")
					->leftJoin('employee_designation', 'employee_basic_info.designation', '=', 'employee_designation.id')
					->leftJoin('job_area', 'employee_basic_info.job_area', '=', 'job_area.id')
					->select("employee_basic_info.*", "users.email", "employee_designation.name as designation_name", "job_area.area_name", "job_area.area_details")
					->where('employee_basic_info.report_to', $childUser[0]->user_id)
					->where('employee_basic_info.project_id', $project_id)
					->where('employee_basic_info.valid', 1)
					->get();
            } else {
                $companyInfo = ProjectInfo::where('project_id', $project_id)->first();
                $organogram["name"] = $companyInfo->company_name;
				$organogram["meta"]["dataOriginalTitle"] = (empty($companyInfo->logo)) ? $companyInfo->company_name : '<img class="pr10" src="'.url('public/uploads/logo/'.$companyInfo->logo).'" style="max-width: 32px;">'.$companyInfo->company_name;
				
				$organogram["meta"]["dataContent"] = $contentStart.
					$trStart."Address".$trMiddle.$companyInfo->address.$trEnd.
					$trStart."Mobile".$trMiddle.$companyInfo->mobile.$trEnd.
					$trStart."Office Phone".$trMiddle.$companyInfo->office_phone.$trEnd.
					$trStart."Fax".$trMiddle.$companyInfo->fax.$trEnd.
					$trStart."Email".$trMiddle.$companyInfo->email.$trEnd.
					$trStart."Website".$trMiddle.$companyInfo->website.$trEnd.$contentEnd;
            }
        } else {
            $organogram["name"] = $name;
			$organogram["meta"]["dataOriginalTitle"] = (empty($userInfo->image)) ? $name : '<img class="pr10" src="'.url('public/uploads/user_profile_images/'.$userInfo->image).'" style="max-width: 32px;">'.$userInfo->name;
        }
		$organogram["step"] = $step;
		
		if(!empty($userInfo)) {
			$organogram["meta"]["dataContent"] = $contentStart.
                $trStart."Employee ID".$trMiddle.$userInfo->emp_id.$trEnd.
                $trStart."Designation".$trMiddle.$userInfo->designation_name.$trEnd.
                $trStart."Mobile".$trMiddle.$userInfo->mobile.$trEnd.
                $trStart."Email".$trMiddle.$userInfo->email.$trEnd.
                $trStart."Job Area".$trMiddle.$userInfo->area_name.$trEnd.
                $trStart."Area Details".$trMiddle.$userInfo->area_details.$trEnd.$contentEnd;
		}
		
        $organogram["meta"]["class"] = "popoverText";

        //Child
        if(count($childUser)>0) {
            $step++;
            foreach($childUser as $childUser) {
                $children[] = self::getEmployeeOrganogram($childUser->user_id, $childUser->name, $childUser, $step);
            }
            $organogram["children"] = $children;
        }
        return $organogram;
    }

    public static function getTeamArray($teamArray=[], $upline_id = 0) {
        $childUser = EmployeeBasicInfo_user::valid()->where('report_to', $upline_id)->get()->pluck('user_id')->all();

        if(!empty($teamArray)) {
            $teamArray[$upline_id] = array_merge($teamArray[$upline_id], $childUser);
            foreach($teamArray as $k => $team) {
                if(in_array($upline_id, $team)) {
                    $teamArray[$k] = array_merge($team, $childUser);
                }
            }
        }

        foreach($childUser as $childUser) {
            $teamArray[$childUser] = [];
            $teamArray = self::getTeamArray($teamArray, $childUser);
        }

        return $teamArray;
    }
   
}

