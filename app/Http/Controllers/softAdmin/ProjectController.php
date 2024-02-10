<?php

namespace App\Http\Controllers\softAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Provider\Admin\EmployeeController;
use App\Http\Controllers\Provider\AdapterController;
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
use App\Model\EnProviderUser_admin;
use App\Model\EnProviderDesignation_admin;
use App\Model\CrmStatus;
use App\Model\CrmLeadCategory;
use App\Model\SoftwarePackage;
use App\Model\SoftwarePackageHistory;
use App\Model\SoftwareModules;
use App\Model\SoftwareMenu;
use App\Model\SoftwareInternalLink;
use App\Model\ProjectAccess;
use App\Model\CrmJobArea_admin;
use App\Model\CrmPackage;
use App\Model\CrmPackageHistory;
use App\Model\EnProviderUserInfo_admin;
use App\Model\CrmUserPaymentHistory;
use App\Model\CrmSubscriptionHistory;
use App\Model\User_config_admin;
use App\Model\Web_UserInfo;

use App\Http\Requests;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
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
     * @return \Illuminate\Http\Response
     */
    public function create(){   
        $lastProjectInfo = Project::orderBy('id', 'desc')->first();
        $mydate = $lastProjectInfo->created_at->format('Y-m-d H:i:s');
        $data['autoProjectId'] = self::getProjectId($mydate);
        $data['countries'] = DB::table('en_country')->orderBy('country', 'asc')->get();
        $data['currency'] = DB::table('en_currency')->get();
        $data['timezones'] = DB::table('timezones')->where('valid', 1)->get();
        // return $data;

        return view('softAdmin.project.createProject', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $output = array();

        $validator = Validator::make($request->all(), [
            "project_id"            => "required",
            "name"                  => "required",
            "company_name"          => "required",
            "country"               => "required",
            "default_currency"      => "required",
            "user_name"             => "required",
            "designation"           => "required",
            "user_email"            => "required|email",
            "timezone"              => "required",
            "mobile"                => "required|numeric",
            "email"                 => "required|email"
            
            
        ]);

        if($validator->passes()) {
            $autoProjectId = self::getProjectId(Project::orderBy('id', 'desc')->pluck('created_at'));

            if(Project::where('project_id', $request->input('project_id'))->where('valid', 1)->first()) {
                $output['messege'] = 'Project Id already exists';
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
                    "website"             => $request->website
                ));
                EnProviderUser_admin::create([
                    "project_id"          => $projectId,
                    "name"                => $request->user_name,
                    "email"               => $request->user_email,
                    "auth_key"            => str_replace('.', '', uniqid('', true)),
                    "secret_key"          => md5(time()),
                    "timezone_id"         => $timezone->id,
                    "timezone"            => $timezone->value,
                    "status"              => "Active",
                    "verification_code"   => $verification_code
                ]);

                $user_id = EnProviderUser_admin::orderBy('id', 'desc')->first()->id;

                EnProviderDesignation_admin::create([
                    "name"                => $request->designation,
                    "project_id"          => $projectId
                ]);
                $designation_id = EnProviderDesignation_admin::orderBy('id', 'desc')->first()->id;

                EnProviderUserInfo_admin::create([
                    "emp_id"              => EmployeeController::getEmpId($projectId),
                    "user_id"             => $user_id,
                    "name"                => $request->user_name,
                    "designation"         => $designation_id,
                    "project_id"          => $projectId
                ]);
                
                
                //Send mail
                // Helper::mailConfig();
                // $email_data['data'] = [
                //     'name'              =>  $request->user_name,
                //     'email'             =>  $request->user_email
                // ];
                // $email_data['link'] = url('provider/pro_email_verification?token=' . $verification_code);
                // Mail::send('emails.email_verification', $email_data, function($message) use ($request)
                // {
                //     $message->subject('Verify Sudoksho Account');
                //     $message->to($request->user_email, $request->user_name);
                // });
                
                $output['messege'] = 'Project has been created';
                $output['msgType'] = 'success';
            }
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);

        DB::commit();
    }

    public function projectIdView() {
        echo json_encode(['projectId' => self::getProjectId(Project::orderBy('id', 'desc')->pluck('created_at'))]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $data['project'] = $project = Project::where('valid', 1)->find($id);
        $data['projectInfo'] = $projectInfo = ProjectInfo::where('valid', 1)->where('project_id', $project->id)->first();
        $data['countries'] = DB::table('en_country')->orderBy('country', 'asc')->get();
        $data['currency'] = DB::table('en_currency')->get();
        
        return view('softAdmin.project.updateProject', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        DB::beginTransaction();
        $project_id = ProjectInfo::where('project_id', $id)->where('valid', 1)->first()->id;
        Project::valid()->find($id)->delete();
        ProjectInfo::valid()->find($project_id)->delete();
        DB::commit();
    }
    

    public static function getProjectId($date) {
        $projectId = Project::orderBy('id', 'desc')->first();
        $currentDate = date("Ymd");
        $currentDate2 = date("Y-m-d");

        $lastProjectInfo = Project::orderBy('id', 'desc')->first();
        $mydate = $lastProjectInfo->created_at->format('Y-m-d H:i:s');

        function getCreatedAtAttribute($date){
            if($date != '') {
                return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
            } else {
                return '';
            }
        }

        if ($currentDate2 == getCreatedAtAttribute($mydate)) {
            $autoProjectId = $projectId->project_id + 1;
        }else{
            $autoProjectId = $currentDate.'1';
        }
        return $autoProjectId;
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
        $data['report_to'] = EnProviderUser_admin::where('project_id', $project_id)
            ->where('valid', 1)
            ->orderBy('name', 'asc')
            ->get();
        return view('softAdmin.project.projectUserById', $data);
    }

    public function projectDesignationById(Request $request)
    {
        $project_id = $request->input('project_id');
        $data['empDesignation'] = EnProviderDesignation_admin::where('project_id', $project_id)
            ->where('valid', 1)
            ->orderBy('grade', 'asc')
            ->get();
        return view('softAdmin.project.projectDesignationById', $data);
    }

    

    public function projectRenew(Request $request)
    {
        $project_id = $request->input('data');
        $data['project'] = Project::where('valid', 1)->find($project_id);
        $data['users'] = EnProviderUser_admin::valid()->where('project_id', $project_id)->get();
        $data['crmPackage'] = CrmPackage::valid()->where('id', '>', 1)->orderBy('package_name', 'asc')->get()->keyBy('id')->all();
        $data['currency'] = DB::table('en_currency')->get();

        return view('softAdmin.project.projectRenew', $data);
    }

    public function projectRenewAc(Request $request)
    {
        DB::beginTransaction();
        $output = array();
        $validator = Validator::make($request->all(), [
            "en_expire_date"       => "required",
            "user_id"               => "required"
        ]);

        if ($validator->passes()) {
            $projectId = $request->project_id;
            $en_expire_date = $request->en_expire_date;
            $crmExpireDate = DateTime::createFromFormat('d/m/Y', $en_expire_date);
                
                $project = array(
                    "en_expire_date"               => $crmExpireDate->format('Y-m-d'),
                    "general_payment_notify_status" => 0,
                    "extreme_payment_notify_status" => 0
                );
                Project::valid()->where('id', $projectId)->update($project);

                $en_user_payment_history = array(
                    "user_id"             => $request->user_id,
                    "amount"              => $request->amount,
                    "currency"            => $request->currency,
                    "payment_method"      => $request->payment_method,
                    "project_id"          => $projectId 
                );
                CrmUserPaymentHistory::create($en_user_payment_history);

                $en_subscription_history = array(
                    "user_id"             => $request->user_id,
                    "amount"              => $request->amount,
                    "currency"            => $request->currency,
                    "current_expire_date" => $crmExpireDate->format('Y-m-d'),
                    "reason"              => 2,
                    "project_id"          => $projectId
                );
                CrmSubscriptionHistory::create($en_subscription_history);

                $output['messege'] = 'Project has been renewed';
                $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
        DB::commit();
    }
}
