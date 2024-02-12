<?php

namespace App\Http\Controllers\SoftAdmin;

use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Http\Request;
use DB;
use Auth;
use Input;
use Carbon;
use Helper;
use DateTime;
use Validator;
use Mail;
use App\Model\Project;
use App\Model\ProjectInfo;
use App\Model\User_admin;
use App\Model\EmployeeDesignation_admin;
use App\Model\SoftwareModules;
use App\Model\SoftwareMenu;
use App\Model\SoftwareInternalLink;
use App\Model\ProjectAccess;
use App\Model\JobArea_admin;
use App\Model\EmployeeBasicInfo_admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Project Registration';

        return view('softAdmin.project.index', $data);
    }

    public function projectList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['project_id', 'name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['projects'] = Project::where('valid', 1)
            ->where(function($query) use ($search)
            {
                $query->where('project_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.project.projectList', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {   
        $data['autoProjectId'] = self::getProjectId(Project::orderBy('id', 'desc')->pluck('created_at'));
        $data['countries'] = DB::table('countries')->orderBy('country', 'asc')->get();
        $data['currency'] = DB::table('currency')->get();
        $data['timezones'] = DB::table('timezones')->where('valid', 1)->get();

        return view('softAdmin.project.createProject', $data);
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
            "project_id"            => "required",
            "name"                  => "required",
            "default_currency"      => "required",
            "company_name"          => "required",
            "country"               => "required",
            "mobile"                => "required|numeric",
            "email"                 => "required|email",
            "user_name"             => "required",
            "designation"           => "required",
            "user_email"            => "required"
        ]);

        if($validator->passes()) {
            $autoProjectId = self::getProjectId(Project::orderBy('id', 'desc')->pluck('created_at'));

            if(Project::where('project_id', $request->input('project_id'))->where('valid', 1)->first()) {
                $output['messege'] = 'Project Id already exists';
                $output['msgType'] = 'danger';
            } else {
                $emailCheck = User_admin::where('email', $request->user_email)->where('valid', 1)->first();
				if(!empty($emailCheck)){
					$output['messege'] = 'Email already exist.';
                    $output['msgType'] = 'danger';
                } else {
					$date = new DateTime();
					$created_at = $date->format('Y-m-d H:i:s');
					$project = array(
						"project_id"          => $autoProjectId,
						"name"                => $request->name,
						"default_currency"    => $request->default_currency,
						"compile_status"      => 1,
						"valid"               => 1
					);
					Project::create($project);
					$projectId = Project::valid()->where('project_id', $request->project_id)->orderBy('id', 'desc')->first()->id;
					$timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();
					$original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
                    $original_string = implode("", $original_string);
                    $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);
					
					ProjectInfo::create(array(
						"project_id"          => $projectId,
						"company_name"        => $request->company_name,
						"logo"                => $request->logo,
						"address"             => $request->address,
						"street"              => $request->street,
						"city"                => $request->city,
						"state"               => $request->state,
						"post_code"           => $request->post_code,
						"country"             => $request->country,
						"mobile"              => $request->mobile,
						"office_phone"        => $request->office_phone,
						"fax"                 => $request->fax,
						"email"               => $request->email,
						"website"             => $request->website,
						"valid"               => 1
					));
                    User_admin::create([
                        "project_id"          => $projectId,
                        "name"                => $request->user_name,
                        "email"               => $request->user_email,
                        "auth_key"            => str_replace('.', '', uniqid('', true)),
                        "secret_key"          => md5(time()),
                        "timezone_id"         => $timezone->id,
                        "timezone"            => $timezone->value,
                        "verification_code"   => $verification_code,
                        "status"              => "Active"
                    ]);

                    $user_id = User_admin::orderBy('id', 'desc')->first()->id;

                    EmployeeDesignation_admin::create([
                        "name"                => $request->designation,
                        "project_id"          => $projectId
                    ]);
                    $designation_id = EmployeeDesignation_admin::orderBy('id', 'desc')->first()->id;

                    EmployeeBasicInfo_admin::create([
						"emp_id"              => EmployeeController::getEmpId($projectId),
                        "user_id"             => $user_id,
                        "name"                => $request->user_name,
                        "designation"         => $designation_id,
                        "project_id"          => $projectId
                    ]);

                    //send mail
					Helper::mailConfig();
                    $email_data['link'] = url('email_verification?token=' . $verification_code);
                    Mail::send('emails.email_verification', $email_data, function($message) use ($request)
                    {
                        //$message->from('no-reply@delbd.com', 'Digital Engravers Ltd.');
                        $message->subject('Verify East West Human Resource Ltd. Account');
                        $message->to($request->user_email, $request->user_name);
                    });
					
					$output['messege'] = 'Project has been created';
					$output['msgType'] = 'success';  
                }      
			}
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);

        DB::commit();
    }

    public static function getProjectId($date) {
        $projectId = Project::orderBy('id', 'desc')->first();
        $currentDate = date("Ymd");
        $currentDate2 = date("Y-m-d");

		function getCreatedAtAttribute($date)
		{
			if(!empty($date)) {
				return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
			} else {
				return '';
			}
		}
		
		if ($currentDate2== getCreatedAtAttribute(Project::orderBy('id', 'desc')->pluck('created_at'))) {
			$autoProjectId = $projectId->project_id+1;
		}else{
			$autoProjectId = $currentDate.'1';
		}
		return $autoProjectId;
    }

    public function projectIdView() {
        echo json_encode(['projectId' => self::getProjectId(Project::orderBy('id', 'desc')->pluck('created_at'))]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['project'] = $project = Project::where('valid', 1)->find($id);
        $data['projectInfo'] = $projectInfo = ProjectInfo::where('valid', 1)->where('project_id', $project->id)->first();
        $data['countries'] = DB::table('countries')->orderBy('country', 'asc')->get();
        $data['currency'] = DB::table('currency')->get();
        
        return view('softAdmin.project.updateProject', $data);
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
            "default_currency"      => "required",
            "company_name"          => "required",
            "country"               => "required",
            "mobile"                => "required|numeric",
            "email"                 => "required|email"
        ]);

        if ($validator->passes()) {

            if(Project::where('project_id', $request->input('project_id'))->where('valid', 1)->where('id', '!=', $id)->first()) {
                $output['messege'] = 'Project Id already exists';
                $output['msgType'] = 'danger';
            } else {
                $project = array(
                    "project_id"        => $request->project_id,
                    "name"              => $request->name,
                    "default_currency"  => $request->default_currency
                );

                Project::valid()->where('id', $id)->update($project);
                $timezone = DB::table('timezones')->where('id', $request->timezone)->where('valid', 1)->first();

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
                    "email"             => $request->email,
                    "website"           => $request->website
                );
                ProjectInfo::valid()->where('project_id', $id)->update($project_info);

                $output['messege'] = 'Project has been Updated';
                $output['msgType'] = 'success';             
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
        $project_id = ProjectInfo::where('project_id', $id)->where('valid', 1)->first()->id;
        Project::valid()->find($id)->delete();
        ProjectInfo::valid()->find($project_id)->delete();
        DB::commit();
    }

    public function projectNameById(Request $request)
    {
        $project_id = $request->input('project_id');
        echo json_encode([
            "emp_id" => EmployeeController::getEmpId($project_id),
            "project_name" => Project::find($project_id)->name
        ]);
    }

    public function projectUserById(Request $request)
    {
        $project_id = $request->input('project_id');
        $data['report_to'] = User_admin::where('project_id', $project_id)
            ->where('valid', 1)
            ->orderBy('name', 'asc')
            ->get();
        return view('softAdmin.project.projectUserById', $data);
    }

    public function projectDesignationById(Request $request)
    {
        $project_id = $request->input('project_id');
        $data['empDesignation'] = EmployeeDesignation_admin::where('project_id', $project_id)
            ->where('valid', 1)
            ->orderBy('grade', 'asc')
            ->get();
        return view('softAdmin.project.projectDesignationById', $data);
    }

    public function projectAreaById(Request $request)
    {
        $project_id = $request->input('project_id');
        $data['adminJobAreaList'] = JobArea_admin::where('project_id', $project_id)
            ->where('valid', 1)
            ->orderBy('area_name', 'asc')
            ->get()->keyBy('id')->all();
            
        return view('softAdmin.project.projectJobAreaById', $data);
    }

    
}
