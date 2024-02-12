<?php

namespace App\Http\Controllers\Recruitment;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\EwCandidatesCV;
use App\Model\EwInterview;
use App\Model\EwInterviewCall;
use App\Model\EwMobilizationMasterTable;
use App\Model\EwProjects;
use App\Model\EwReportConfiguration;
use App\User;
use Auth;
use DB;
use Helper;
use Illuminate\Http\Request;
use Validator;

class ReportController extends Controller
{   

    protected $projectId;
    protected $candidateId;
    protected $projectCountryId;
    protected $passport;


  
    public function __construct(Request $r){
        $this->projectId        = $r->projectId;
        $this->candidateId      = $r->candidateId;
        $this->passport         = $r->passport;
        $this->projectCountryId = @Helper::projects($this->projectId)->project_country_id; 
        
    }

    public function selectionReport(Request $request){
        $data['inputData'] = $request->all();
        return view('recruitment.reports.selectionReport', $data);
    }

    public function selectionReportData(Request $request){
        $data             = $request->all();
        $search           = $request->search;
        $data['access']   = Helper::userPageAccess($request);
        $ascDesc          = Helper::ascDesc($data, ['id']);
        $paginate         = Helper::paginate($data);
        $data['sn']       = $paginate->serial;
        
        $data['projects'] = EwProjects::valid()
            ->where('status', 1)
            ->where(function($query) use ($search)
            {
                $query->where('project_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
             return view('recruitment.reports.selectionReportData', $data);
    }

    public function selectionCandidateList(Request $request){
        $data               = $request->all();
        $search             = $request->search;
        $data['access']     = Helper::userPageAccess($request);
        $ascDesc            = Helper::ascDesc($data, ['']);
        $paginate           = Helper::paginate($data);
        $data['sn']         = $paginate->serial;
        $data['project_id'] = $this->projectId;

        $data['candidateLists'] = EwCandidatesCV::join('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
            //  ->select($exploedFieldQuery)
            ->where('ew_interviews.ew_project_id', $this->projectId)
            ->where('ew_interviews.interview_selected_status',1)
            ->get();

        return view('recruitment.reports.selectionCandidateList', $data);
    }

    public function selectionCandidateListData(Request $request){
        $data             = $request->all();
        $search           = $request->search;
        $data['access']   = Helper::userPageAccess($request);
        $ascDesc          = Helper::ascDesc($data, ['id']);
        $paginate         = Helper::paginate($data);
        $data['sn']       = $paginate->serial;
        
        $data['projects'] = EwProjects::valid()
            ->where('status', 1)
            ->where(function($query) use ($search)
            {
                $query->where('project_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.reports.selectionCandidateListData', $data);

    }

    public function rejectionReport(Request $request){
        $data['inputData'] = $request->all();
        return view('recruitment.reports.rejectionReport', $data);
    }

    public function rejectionReportData(Request $request){
        $data             = $request->all();
        $search           = $request->search;
        $data['access']   = Helper::userPageAccess($request);
        $ascDesc          = Helper::ascDesc($data, ['id']);
        $paginate         = Helper::paginate($data);
        $data['sn']       = $paginate->serial;
        
        $data['projects'] = EwProjects::valid()
            ->where('status', 1)
            ->where(function($query) use ($search)
            {
                $query->where('project_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
             return view('recruitment.reports.rejectionReportData', $data);
    }

    public function rejectionCandidateList(Request $request){
        $data               = $request->all();
        $search             = $request->search;
        $data['access']     = Helper::userPageAccess($request);
        $ascDesc            = Helper::ascDesc($data, ['']);
        $paginate           = Helper::paginate($data);
        $data['sn']         = $paginate->serial;
        $data['project_id'] = $this->projectId;
        
        $data['candidateLists'] = EwCandidatesCV::join('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
            //  ->select($exploedFieldQuery)
            ->where('ew_interviews.ew_project_id', $this->projectId)
            ->where('ew_interviews.interview_selected_status', 2)
            ->get();
            return view('recruitment.reports.rejectionCandidateList', $data);
    }

    public function previewReport(Request $request){
// return $this->projectCountryId;
        $data['projectId']   = $this->projectId;
        if($this->projectCountryId == 180){
            $fieldQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_array;
            $exploedFieldQuery   = explode(",", $fieldQuery);
            
            $labelQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->label_array;
            $labelExplodeQuery   = explode(",", $labelQuery);
            
            $cvLabelQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_label;
            $cvExplodeLabelQuery = explode(",", $cvLabelQuery);

             $cvFieldQuery       = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_field;
            $cvExplodeFieldQuery = explode(",", $cvFieldQuery);

            $fieldQueryObject    = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_query_object_name;

            $fieldQueryObjectName = explode(",", $fieldQueryObject);

            $candidatesIds = EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->where('project_country_id', $this->projectCountryId)
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('id');

           $reports = EwCandidatesCV::join('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
            ->join('ew_mobilization_master_tables', 'ew_interviews.ew_candidatescv_id', '=', 'ew_mobilization_master_tables.ew_candidatescv_id')
            ->join('ew_references','ew_candidatescv.reference_id','=','ew_references.id')
            ->leftjoin('ew_agency','ew_candidatescv.selected_agency','=','ew_agency.id')
            ->groupBy('ew_mobilization_master_tables.ew_candidatescv_id')
            ->where('ew_candidatescv.valid',1)
            ->select($exploedFieldQuery)
            ->where('ew_interviews.ew_project_id', $this->projectId)
            ->whereIn('ew_interviews.ew_candidatescv_id',$candidatesIds)
            ->get(); 
          
        }

        if($this->projectCountryId == 185){ 
            $fieldQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_array;

            $exploedFieldQuery   = explode(",", $fieldQuery);
            
            $labelQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->label_array;
            $labelExplodeQuery   = explode(",", $labelQuery);
            
            $cvLabelQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_label;
            $cvExplodeLabelQuery = explode(",", $cvLabelQuery); 

            $cvFieldQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_field;
            $cvExplodeFieldQuery  = explode(",", $cvFieldQuery);
            
            $fieldQueryObject     = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_query_object_name;
            
            $fieldQueryObjectName = explode(",", $fieldQueryObject);

            $candidatesIds = EwCandidatesCV::valid()->where('ew_project_id', $this->projectId)
            ->where('result', 1)
            ->get()
            ->pluck('id');

     /*       echo "<pre>";
            print_r($exploedFieldQuery); exit();*/

            $reports = EwCandidatesCV::join('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
            ->join('ew_mobilization_master_tables', 'ew_interviews.ew_candidatescv_id', '=', 'ew_mobilization_master_tables.ew_candidatescv_id')
            ->join('ew_references','ew_candidatescv.reference_id','=','ew_references.id')
            ->leftjoin('ew_agency','ew_candidatescv.selected_agency','=','ew_agency.id')
            ->groupBy('ew_mobilization_master_tables.ew_candidatescv_id')
            ->where('ew_candidatescv.valid',1)
            ->select($exploedFieldQuery)
            ->whereIn('ew_interviews.ew_candidatescv_id',$candidatesIds)
            ->get();

        }

        $data['candidatesIds'] = $candidatesIds;
        $data['labels']        = (Object)$labelExplodeQuery;
        $data['objectNames']   = (Object)$fieldQueryObjectName;
        $data['cvFields']      = (Object)$cvExplodeFieldQuery;
        $data['cvLabels']      = (Object)$cvExplodeLabelQuery;
        $data['reports']       = $reports;
        return view('recruitment.reports.previewReport', $data);    
    }

    public function index(Request $request)
    {
        $data['inputData'] = $request->all();       
        return view('recruitment.reports.list', $data);
    }

    public function projectFinalList(Request $request) {
        $data             = $request->all();
        $search           = $request->search;
        $data['access']   = Helper::userPageAccess($request);
        $ascDesc          = Helper::ascDesc($data, ['id']);
        $paginate         = Helper::paginate($data);
        $data['sn']       = $paginate->serial;
        
        $data['projects'] = EwProjects::valid()
            ->where('status', 1)
            ->where(function($query) use ($search)
            {
                $query->where('project_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.reports.listData', $data);
    }

    public function candidateList(Request $request){
        $data['inputData'] = $request->all();
        $data['projectId'] = $this->projectId;

        return view('recruitment.reports.candidateList', $data);
    }

        public function candidateListData(Request $request){
       
    }

    public function candidateReport(Request $request){
        $data['projectId']   = $this->projectId;
        $data['candidateId'] = $this->candidateId;
        $data['inputData']   = $request->all();

        return view('recruitment.reports.candidateReport', $data);   
    }


    public function candidateReportData(Request $request){
        $data['projectId']   = $this->projectId;
        $data                = $request->all();
        $search              = $request->search;
        $data['access']      = Helper::userPageAccess($request);
        $ascDesc             = Helper::ascDesc($data, ['id']);
        $paginate            = Helper::paginate($data);
        $data['sn']          = $paginate->serial;
        if($this->projectCountryId == 180){
            $fieldQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_array;
            $exploedFieldQuery   = explode(",", $fieldQuery);
            
            $labelQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->label_array;
            $labelExplodeQuery   = explode(",", $labelQuery);
            
            $cvLabelQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_label;
            $cvExplodeLabelQuery = explode(",", $cvLabelQuery);

             $cvFieldQuery       = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_field;
            $cvExplodeFieldQuery = explode(",", $cvFieldQuery);

            $fieldQueryObject    = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_query_object_name;

            $fieldQueryObjectName = explode(",", $fieldQueryObject);

           $candidatesIds = EwCandidatesCV::where('ew_project_id', $this->projectId)
           ->orderBy($ascDesc[0], $ascDesc[1])
           ->where('project_country_id', $this->projectCountryId)
           ->get()->pluck('id');


            $reports = EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->select($cvExplodeFieldQuery)
            ->where(function($query) use ($search)
            {
                $query->where('full_name', 'LIKE', '%'.$search.'%');
            })
            ->where('project_country_id', $this->projectCountryId)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
          
        }

        if($this->projectCountryId == 185){ 
            $fieldQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_array;
            $exploedFieldQuery   = explode(",", $fieldQuery);
            
            $labelQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->label_array;
            $labelExplodeQuery   = explode(",", $labelQuery);
            
            $cvLabelQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_label;
            $cvExplodeLabelQuery = explode(",", $cvLabelQuery); 

            $cvFieldQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_field;

            $cvExplodeFieldQuery  = explode(",", $cvFieldQuery);
            
            $fieldQueryObject     = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_query_object_name;
            
            $fieldQueryObjectName = explode(",", $fieldQueryObject);
           
            // $candidatesIds = EwCandidatesCV::where('ew_project_id', $this->projectId)
            // ->where('project_country_id', $this->projectCountryId)->get()->pluck('id');

            $candidatesIds = EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->where('project_country_id', $this->projectCountryId)
           ->get()->pluck('id');


            $reports = EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->select($cvExplodeFieldQuery)
            ->where(function($query) use ($search)
            {
                $query->where('full_name', 'LIKE', '%'.$search.'%');
            })
            ->where('project_country_id', $this->projectCountryId)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
        }
    

        $reports->candidatesIds = $candidatesIds;
        $data['candidatesIds']  = $candidatesIds;
        $data['labels']         = (Object)$labelExplodeQuery;
        
        $data['objectNames']    = (Object)$fieldQueryObjectName;
        
        $data['cvFields']       = (Object)$cvExplodeFieldQuery;
        
        
        $data['cvLabels']       = (Object)$cvExplodeLabelQuery;
        
        // $data['candidates']  = $candidates;
        $data['reports']        = $reports;

        return view('recruitment.reports.candidateReportData', $data);    
    }

    public function viewReport(Request $request){
        $data['projectId']   = $this->projectId;
        if($this->projectCountryId == 180){
            $fieldQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_array;
            $exploedFieldQuery   = explode(",", $fieldQuery);
            
            $labelQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->label_array;
            $labelExplodeQuery   = explode(",", $labelQuery);
            
            $cvLabelQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_label;
            $cvExplodeLabelQuery = explode(",", $cvLabelQuery);

             $cvFieldQuery       = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_field;
            $cvExplodeFieldQuery = explode(",", $cvFieldQuery);

            $fieldQueryObject    = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_query_object_name;
            $fieldQueryObjectName = explode(",", $fieldQueryObject);

             $interview_and_master_table_object_name     = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->interview_and_master_table_object_name;
            $interviewAndMasterTableObjectName = explode(",", $interview_and_master_table_object_name);

            $interview_and_master_table_query     = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->interview_and_master_table_query;
            $interviewAndMasterTableQuery = explode(",", $interview_and_master_table_query);

            $candidatesIds = EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->where('project_country_id', $this->projectCountryId)->get()->pluck('id');

           $reports = EwCandidatesCV::join('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
            ->join('ew_mobilization_master_tables', 'ew_interviews.ew_candidatescv_id', '=', 'ew_mobilization_master_tables.ew_candidatescv_id')
             ->select($exploedFieldQuery)
             ->where('ew_candidatescv.id', $this->candidateId)
            // ->whereIn('ew_interviews.ew_candidatescv_id',$candidatesIds)
            ->get(); 
          
        }

        if($this->projectCountryId == 185){ 
            $fieldQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_array;
            $exploedFieldQuery   = explode(",", $fieldQuery);
            
            $labelQuery          = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->label_array;
            $labelExplodeQuery   = explode(",", $labelQuery);
            
            $cvLabelQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_label;
            $cvExplodeLabelQuery = explode(",", $cvLabelQuery); 

            $cvFieldQuery        = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->cv_field;
            $cvExplodeFieldQuery  = explode(",", $cvFieldQuery);
            
            $fieldQueryObject     = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->field_query_object_name;
            $fieldQueryObjectName = explode(",", $fieldQueryObject); 

            $interview_and_master_table_object_name     = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->interview_and_master_table_object_name;
            $interviewAndMasterTableObjectName = explode(",", $interview_and_master_table_object_name);

            $interview_and_master_table_query     = EwReportConfiguration::where('project_country_id', $this->projectCountryId)
            ->first()
            ->interview_and_master_table_query;
            $interviewAndMasterTableQuery = explode(",", $interview_and_master_table_query);
           
             $candidatesIds = EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->where('project_country_id', $this->projectCountryId)
            ->get()
            ->pluck('id');

            $reports = EwCandidatesCV::join('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
            ->join('ew_mobilization_master_tables', 'ew_interviews.ew_candidatescv_id', '=', 'ew_mobilization_master_tables.ew_candidatescv_id')
             ->select($interviewAndMasterTableQuery)
            ->where('ew_candidatescv.id', $this->candidateId)
            // ->whereIn('ew_interviews.ew_candidatescv_id',$candidatesIds)
            ->get();

        }

        $data['candidatesIds']     = $candidatesIds;
        $data['labels']            = (Object)$labelExplodeQuery;
        $data['objectNames']       = (Object)$fieldQueryObjectName;
        $data['masterObjectNames'] = (Object)$interviewAndMasterTableObjectName;
        $data['cvFields']          = (Object)$cvExplodeFieldQuery;
        $data['cvLabels']          = (Object)$cvExplodeLabelQuery;
        $data['masterQuerys']      = (Object)$interviewAndMasterTableQuery;
        $data['reports']           = $reports;

         return view('recruitment.reports.view-report', $data);
    }
    
     /**
      * 
      *
      * @return Response
      */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $interviewCallId   = EwInterviewCall::valid()->get()->pluck('ew_project_id')->all();
        $data['projects']  = EwProjects::valid()->whereNotIn('id', $interviewCallId)->get();
        return view('recruitment.reports.create', $data);
    }

    public function add()
    {
        //
    }

    /**
     * 
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        EwInterviewCall::create([
            'ew_project_id' => $request->ew_project_id,
            'status'        => 0
        ]);
            
        $output['messege'] = 'Candidate CV has been created';
        $output['msgType'] = 'success';
        return $output;
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
        $data['editInterviewCalls'] =  EwInterviewCall::valid()->where('id', $id)->first();
        $data['countries']          = DB::table('countries')->get();
        $data['projects']           = EwProjects::valid()->get();
        return view('recruitment.reports.update', $data);
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
       
        $data = [
            'ew_project_id' => $request->ew_project_id   
        ];

        EwInterviewCall::valid()->find($id)->update($data);
        $output['messege'] = 'Company has been updated';
        $output['msgType'] = 'success';
       
        return $output;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {   
        $project = EwInterviewCall::valid()->where('id', $id)->first();
        if($project->status == 1 || $project->status == 2 ){
        return 'You can not delete this project';            
        }else{
           EwInterviewCall::valid()->find($id)->delete();          
        }
            
    }

    public static $prop1 = "hello world";
    public function hello(){

    }


}
