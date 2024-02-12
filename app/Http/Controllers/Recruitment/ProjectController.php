<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\EwConfiguration;
use App\Model\EwMobilization;
use App\Model\EwProjectTrades;
use App\Model\EwProjects;
use App\Model\EwTrades;
use App\Model\EwAgency;
use App\Model\EwProjectAgency;
use Auth;
use DB;
use Helper;
use DateTime;
use Illuminate\Http\Request;
use Validator;

class ProjectController extends Controller
{
    /**
     * Display project list
     *
     * @return Response
     */
   
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('recruitment.project.list', $data);
    }

    public function assignProjectUser(Request $request){
        $data['users'] = DB::table('users')->where('valid', 1)->get();
        $assignUsers = EwProjects::where('id', $request->projectId)->first()->assign_user;
        $data['coordinators'] = EwProjects::where('id', $request->projectId)->first()->coordinator;
        if(!empty($assignUsers)){ 
            $data['assignUsers'] = json_decode($assignUsers, true);
        }
        return view('recruitment.project.assignProjectUser', $data);
    }

    public function assignProjectUserStore(Request $request){
        // return $request->coordinator;
        $arr = [];
        foreach($request->assign_user as $user){
            $arr[$user] = $user;
        }
         
        EwProjects::where('id', $request->projectId)->update([
            'assign_user' => json_encode($arr),
            'coordinator' => $request->coordinator
            ]);
        $output['messege'] = 'User has been Assigned';
        $output['msgType'] = 'success'; 
        return $output;
    }

    public function projectListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['project_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $projects = EwProjects::valid()
            ->where(function($query) use ($search)
            {
                $query->where('project_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('configuration', 'LIKE', '%'.$search.'%')
                    ->orWhere('status', 'LIKE', '%'.$search.'%')
                    ->orWhere('project_country_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

/*        foreach($projects as $project) {
            $project->trades = EwProjectTrades::join('ew_trades', 'ew_project_trades.trade_id', '=', 'ew_trades.id')
                ->select('ew_project_trades.*', 'ew_trades.trade_name')
                ->where('ew_project_trades.ew_project_id', $project->id)
                ->where('ew_project_trades.valid', 1)
                ->get()
                ->implode('trade_name', ', ');
        }*/
        foreach ($projects as $ewProject) {
            $ewProject->agency = EwProjectAgency::leftJoin('ew_agency','ew_project_agency.agency_id', '=', 'ew_agency.id')
                               ->where('ew_project_agency.ew_project_id',$ewProject->id)
                               ->where('ew_project_agency.valid',1)
                               ->select('ew_agency.agency_name','ew_project_agency.quantity')
                               ->get();

            $ewProject->trades = EwProjectTrades::join('ew_trades', 'ew_project_trades.trade_id', '=', 'ew_trades.id')
                ->select('ew_project_trades.trade_qty', 'ew_trades.trade_name')
                ->where('ew_project_trades.ew_project_id', $ewProject->id)
                ->where('ew_project_trades.valid', 1)
                ->get();
        }

        $data['ewProjects'] = $projects;
        // dd( $data['ewProjects']);
        // $data['configurationIds'] = EwConfiguration::all();

        // $data['ew_project_id'] = EwConfiguration::where('ew_project_id', $project_id)->first();
        return view('recruitment.project.listData', $data);
    }

     public function projectStatusForm(Request $request){
        $data['projectId'] = $request->projectId;
        $data['projectStatus'] = EwProjects::where('id', $request->projectId)->first();
        return view('recruitment.project.project-status-form', $data);
    }

    public function updateProjectStatus(Request $request)
    {
        EwProjects::where('id', $request->projectId)
        ->update(['status' => $request->status]);

        $output['messege'] = 'Project status has been updated';
        $output['msgType'] = 'success'; 
        return $output;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['tradeAddAccess'] = Helper::userAccess('trades.create', 'ew');
        $data['trades'] = EwTrades::valid()->get();
        $data['agency'] = EwAgency::valid()->get();
        $data['countries'] = DB::table('countries')->where('show_status', 1)->get();

        return view('recruitment.project.create', $data);
    }

    public function add()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // return $request->all();

        DB::beginTransaction();

        $output             = array();
        $input              =  $request->all();
        $project_name       = $request->project_name;
        $input_trades       = $request->trade_id;
        $input_quantity     = $request->trade_qty;
        $input_trade_salary = $request->trade_salary;
        $input_trade_others = $request->others;

        //agency
        $input_agency       = $request->agency_id;
        $input_akama_no     = $request->akama_no;
        $input_akama_date   = $request->akama_rec_date;
        $input_akama_qty    = $request->akama_qty;

        $project_country_id = $request->country_name;
        $required_quantity  = $request->required_quantity;
        $start_date         = (!empty($request->start_date)? DateTime::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'):'');
        
        $validator = Validator::make($input, [
            'project_name'       => 'required'
        ]);

        if ($validator->passes()) {
            $project = EwProjects::create([
                'project_name'       => $project_name,
                'project_country_id' => $project_country_id,
                'required_quantity'  => $required_quantity,
                'start_date'         => $start_date
            ]);

            $project_id = $project->id;
            $filter_trader_id = array_filter($input_trades);
            foreach($filter_trader_id as $key => $trade_id) 
            {
                EwProjectTrades::create([
                    'ew_project_id'      => $project_id, 
                    'trade_id'           => $trade_id, 
                    'trade_qty'          => $input_quantity[$key], 
                    'trade_salary'       => $input_trade_salary[$key],
                    'trade_others'       => $input_trade_others[$key]
                ]);
            }

            $filter_agency_id = array_filter($input_agency);
            if(count($filter_agency_id) > 0)
            {
                foreach($filter_agency_id as $key => $agency_id) 
                {
                    EwProjectAgency::create([
                        'ew_project_id'      => $project_id, 
                        'agency_id'          => $agency_id, 
                        'akama_no'           => $input_akama_no[$key], 
                        'akama_rec_date'     => (!empty($input_akama_date[$key])? DateTime::createFromFormat('d-m-Y', $input_akama_date[$key])->format('Y-m-d'):''),
                        'quantity'           => $input_akama_qty[$key]
                    ]);
                }
            }

            $output['messege'] = 'Project has been created';
            $output['msgType'] = 'success'; 
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);

        DB::commit();
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
        $data['project'] = EwProjects::valid()->find($id);
        $data['tradeAddAccess'] = Helper::userAccess('trades.create', 'ew');
        $data['selected_trades'] = EwProjectTrades::valid()->select('trade_id','trade_qty','trade_salary', 'trade_others')
            ->where('ew_project_id', $id)->get();
        $data['selected_agency'] = EwProjectAgency::valid()->select('agency_id','akama_no','akama_rec_date','quantity')
                                           ->where('ew_project_id', $id)->get();

        $data['trades'] = EwTrades::valid()->get();
        $data['agency_info'] = EwAgency::valid()->get();

        return view('recruitment.project.update', $data);
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

        $output             = array();
        $input              = $request->all();
        $project_name       = $request->project_name;
        $input_trades_all   = $request->trade_id;
        $input_trade_qty    = $request->trade_qty;
        $input_trades       = array_filter($input_trades_all);
        $input_trade_salary = $request->trade_salary;
        $input_trade_others = $request->others;

        /*agency*/
        $input_agency       = $request->agency_id;
        $input_akama_no     = $request->akama_no;
        $input_akama_date   = $request->akama_rec_date;
        $input_akama_qty    = $request->akama_qty;

        $project_country_id = $request->country_name;
        $required_quantity  = $request->required_quantity;
        $start_date         = (!empty($request->start_date)? DateTime::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'):'');
        
        $validator = Validator::make($input, [
            'project_name' => 'required'
        ]);

        if ($validator->passes()) {
            EwProjects::find($id)->update([
                'project_name'       => $project_name, 
                'project_country_id' => $project_country_id,
                'required_quantity'  => $required_quantity,
                'start_date'         => $start_date
                ]);


            $selected_trades = EwProjectTrades::valid()->where('ew_project_id', $id)->get();

            if(!empty($selected_trades)) {
                //remove trade_id
                foreach($selected_trades as $project_selected_trade) {
                    EwProjectTrades::valid()->find($project_selected_trade->id)->delete();
                }
            }

            $filter_trade_id = array_filter($input_trades);

            if(!empty($input_trades)) {
                foreach($filter_trade_id as $key => $trade_id) {
                   EwProjectTrades::create([
                        'ew_project_id'  => $id,
                        'trade_id'       => $trade_id,
                        'trade_qty'      => $input_trade_qty[$key],
                        'trade_salary'   => $input_trade_salary[$key],
                        'trade_others'   => $input_trade_others[$key]
                    ]);
                }
            }

            $selected_agency = EwProjectAgency::valid()->where('ew_project_id', $id)->get();

            if(!empty($selected_agency)) {
                //remove trade_id
                foreach($selected_agency as $project_selected_agency) {
                    EwProjectAgency::valid()->find($project_selected_agency->id)->delete();
                }
            }

            $filter_agency_id = array_filter($input_agency);

            if(!empty($input_agency)) {
                foreach($filter_agency_id as $key => $agency_id) 
                {
                    EwProjectAgency::create([
                        'ew_project_id'      => $id, 
                        'agency_id'          => $agency_id, 
                        'akama_no'           => $input_akama_no[$key], 
                        'akama_rec_date'     => (!empty($input_akama_date[$key])? DateTime::createFromFormat('d-m-Y', $input_akama_date[$key])->format('Y-m-d'):''),
                        'quantity'           => $input_akama_qty[$key]
                    ]);
                }
            }

            $output['messege'] = 'Project has been updated';
            $output['msgType'] = 'success'; 
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
        EwProjects::valid()->find($id)->delete();
    }

    /*---------------------------------------------
        CONFIGURATION BLADE METHOD
        @author Shere Ali
        Date: 24-10-2018
        @ProjectName: Project Configuration blade
    -----------------------------------------------*/
    public function configure($project_id){

        $configures             = EwConfiguration::where('ew_project_id', $project_id)->first();
        if(empty($configures)){
        $data['mobilizations']  = EwMobilization::valid()->orderBy('custom_sl')->get(); 
        }else{
        $data['configurations'] =  $conId = json_decode($configures->mobilization_id, true); 
        $data['mobilizations']  = EwMobilization::valid()->whereNotIn('id', $conId)
                                                ->orderBy('custom_sl')
                                                ->get(); 
        }
        $data['project_id']     = $project_id;

        return view('recruitment.project.configure', $data);
    }

    public function storeConfiguration(Request $request){
        // return $request->all();
        $user_id             = Auth::user()->get()->id;
        $project_id          = $request->project_id;
        $moblizationIds      = $request->mobilization_id;
        $mobilization_id     = json_encode($moblizationIds);
        $checkConfigureTable = EwConfiguration::where('ew_project_id', $project_id)->first();

        if(empty($checkConfigureTable)){
         EwConfiguration::create([
            'ew_project_id'   => $project_id, 
            'mobilization_id' => $mobilization_id 
        ]);
        EwProjects::where('id', $project_id)->update(['configuration' => 1]);

        }else{
           EwConfiguration::where('ew_project_id',$project_id)->update([
            'mobilization_id' => $mobilization_id,
            'updated_at'      => date("Y-m-d H:i:s"),
            'updated_by'      =>  $user_id 
       ]);
           EwProjects::where('id', $project_id)->update(['configuration' => 1]);
    }


        // $configurations = EwConfiguration::valid()->where('ew_project_id', $project_id)->get(); 
        // $configures = $configurations->pluck('mobilization_id'); 

        // if (empty($configures)) {
        //     EwConfiguration::create([
        //         'mobilization_id'=>$mobilizationId,
        //             'ew_project_id'=>$project_id
        //     ]);
        //     EwProjects::where('id', $project_id)->update(['configuration' => 1]);
        // }else{
        //     foreach ($configures as $configure) {
        //         DB::table('ew_configurations')->where('ew_project_id', $project_id)->delete();

        //     }
        //    foreach ($moblizationIds as $mobilizationId) {
        //         EwConfiguration::create([
        //             'mobilization_id'=>$mobilizationId,
        //             'ew_project_id'=>$project_id,
        //             'updated_at'=> date("Y-m-d H:i:s"),
        //             'updated_by'=>  $user_id
        //         ]);
        //     }

        //     EwProjects::where('id', $project_id)->update(['configuration' => 1]); 
        // }
        
            

            
            $output['messege'] = 'Configuration has been updated';
            $output['msgType'] = 'success';

            return $output;
    }

   

    public function geCountry(){
        $countries = DB::table('countries')->where('show_status', 1)->get();
    }

    public function traderFilter(Request $request)
    { 
        $trade_id = array_filter($request->trade_get_id);
        if(empty($trade_id))
        {
            $t_id = array();
        }
        else
        {
           $t_id = $trade_id;
        }

        $data['tradeSearch'] = EwTrades::valid()
            ->whereNotIn('id', $t_id)
            ->get();

        return view('recruitment.project.tradeFilter', $data);
    }

    public function agencyFilter(Request $request)
    { 
        $agency_id = array_filter($request->agency_get_id);

        if(empty($agency_id))
        {
            $agency_get_id = array();
        }
        else
        {
           $agency_get_id = $agency_id;
        }

        $data['agencySearch'] = EwAgency::valid()
            ->whereNotIn('id', $agency_get_id)
            ->get();

        return view('recruitment.project.agencyFilter', $data);
    }
}
