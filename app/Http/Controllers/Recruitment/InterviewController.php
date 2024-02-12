<?php

namespace App\Http\Controllers\Recruitment;

use DB;
use Auth;
use Helper;
use Validator;
use Carbon\Carbon;
use App\Http\Requests;
use App\Model\EwTrades;
use App\Model\EwProjects;
use App\Model\EwInterview;
use App\Model\EwReferences;
use Illuminate\Http\Request;
use App\Model\EwCandidatesCV;
use App\Model\EwPassportForm;
use App\Model\EwInterviewCall;
use App\Model\EwProjectTrades;
use App\Http\Controllers\Controller;
use App\Model\EwMobilizationMasterTable;
use App\Model\EwAgency;
use App\Model\EwProjectAgency;

class InterviewController extends Controller
{
    protected $projectId;
    protected $projectCountryId;
    protected $candidateId;

    public function __construct(Request $request)
    {
      $this->projectId   = $request->projectId;
      $this->projectCountryId   = $request->projectCountryId;
      $this->candidateId = $request->candidateId;
    }

    public function getPassportDetails(Request $request){
        $passport = $request->passport;

        $candidates = EwCandidatesCV::valid()->where('passport_no', $passport)->first();
        
        if(count($candidates) > 0)
        {
            $mobilizeStage = EwMobilizationMasterTable::valid()->where('ew_project_id', $candidates->ew_project_id)->where('ew_candidatescv_id', $candidates->id)->first();

            $message = "This passport number already used!";
                    $mobilizeName = @Helper::mobilization( $mobilizeStage->total_completed)[0]->name;

            if ( $mobilizeName != NULL) {
                $mobilizeName = $mobilizeName; 
            } else {
                $mobilizeName = "No Mobilization";
            }
            $projectName = @Helper::projects( $mobilizeStage->ew_project_id)->project_name;
            $candidatesName = $candidates->full_name;
            
            return response()->json([
                'message'        => $message,
                'projectName'    => $projectName,
                'candidatesName' => $candidatesName,
                'mobilizeName'   => $mobilizeName, 
                ]);
        }
        else
        {
            $mobilizeStage = 0;

            echo $mobilizeStage;
        }
        
    }

    /**
     * 
     *
     * @return Response
     */
    
    public function interviewListData(Request $request) 
    { 
        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['ew_projects.project_name']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;
        $data['interviewCallProjects'] = EwInterviewCall::join('ew_projects','ew_projects.id','=','ew_interviewcalls.ew_project_id')
        ->where('ew_interviewcalls.valid', '=', 1)
        ->where('ew_projects.valid', '=', 1)
        ->where('ew_interviewcalls.status','=', 1)
        ->select('ew_projects.*')
        ->where(function($query) use ($search)
            {
                $query->where('ew_projects.project_name', 'LIKE', '%'.$search.'%');
            })
        ->orderBy($ascDesc[0], $ascDesc[1])
        ->paginate($paginate->perPage);

        return view('recruitment.interview.listData', $data);

    }

    public function interviewEvaluationForm(Request $request)
    {
        $data['candidate_id']  = $request->candidate_id;
        $data['cvPrints']      = EwCandidatesCV::valid()->where('id', $request->candidate_id)->first();
        $tradeIds              = EwInterview::valid()->where('ew_candidatescv_id', $request->candidate_id)->first();
        $tradeSelected         = $tradeIds->selected_trade;
        
        $tradeApplied          = $tradeIds->trade_applied;
        $pdf_url               = url('recruitment/interview-evaluation-form?candidate_id='.$request->candidate_id);
        
        if ($tradeSelected === null) {

        $data['tradeSelected'] = null;  

        }else {  

        $data['tradeSelected'] = $tradeSelected;

        }  
        
        if ($tradeApplied === null) {

        $data['tradeApplied']  = null;  

        }else {  

        $data['tradeApplied']  = $tradeApplied;

        } 

      $data = array_merge($data, ['pdf_url' => $pdf_url]);

     return view('recruitment.interview.interview_evaluation_form', $data);  

    }

    public function workerEvaluationForm(Request $request)
    {
        $data['candidate_id']  = $request->candidate_id;
        $data['cvPrints']      = EwCandidatesCV::valid()->where('id', $request->candidate_id)->first();
        $tradeIds              = EwInterview::valid()->where('ew_candidatescv_id', $request->candidate_id)->first();
        $tradeSelected         = $tradeIds->selected_trade;
        
        $tradeApplied          = $tradeIds->trade_applied;
        $pdf_url               = url('recruitment/interview-evaluation-form?candidate_id='.$request->candidate_id);
        
        if ($tradeSelected === null) {

        $data['tradeSelected'] = null;  

        }else {  

        $data['tradeSelected'] = $tradeSelected;

        }  
        
        if ($tradeApplied === null) {

        $data['tradeApplied']  = null;  

        }else {  

        $data['tradeApplied']  = $tradeApplied;

        } 

      $data = array_merge($data, ['pdf_url' => $pdf_url]);

     return view('recruitment.interview.workerEvaluationForm', $data); 

    }

    public function cvInfoPrint(Request $request)
    {  
        $data['cvInfoPrints'] = EwCandidatesCV::join('ew_interviews', 'ew_interviews.ew_candidatescv_id', '=', 'ew_candidatescv.id')
        ->where('ew_candidatescv.ew_project_id','=', $this->projectId)
        ->get();
         $data['projectId'] = $this->projectId;
        return view('recruitment.interview.cvInfoPrint', $data);
    }

    public function getPassportFormData(Request $request)
    {   
        // return $request->passport_no;
        $data = EwPassportForm::valid()
        ->where('passport_no', $request->passport_no)->first();
        return response()->json($data);
    }

    /**---------------------
     *  ASSIGN CANDIDATE
    -----------------------*/

    public function assign_candidate(Request $request)
    {
        $data['interview_id'] = $request->interview_id;
        return view('recruitment.interview.assign-candidate', $data);
    }
     /*--------------------------
        PROJECT DETAILS SHOWING
    ----------------------------*/

    public function project_details(Request $request)
    {
        $data['interview_id'] = $request->interview_id;
        return view('recruitment.interview.project-details', $data);
    }

     /*-----------------------
        GO TO INTERVIEW ROOM
    -------------------------*/
    public function go_to_interview_room(Request $request )
    {
       $data['project_id'] = $request->project_id;
        return view('recruitment.interview.go-to-interview-room', $data);
    }


    public function listOfCV(Request $request)
    {
        $data           = $request->all();
        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        // $ascDesc        = Helper::ascDesc($data, ['id', 'cv_number', 'full_name', 'father_name', 'passport_no', 'contact_no', 'reference_name', 'dealer', 'trade_name']);
        $ascDesc        = Helper::ascDesc($data, ['id', 'cv_number', 'full_name', 'father_name', 'passport_no', 'contact_no']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $data['project_id'] = $project_id = $this->projectId;
        $data['projectCountryId'] = $projectCountryId = $this->projectCountryId;

        // return EwCandidatesCV::where('ew_project_id', $this->projectId)->count();
        $data['CVLists']    = EwCandidatesCV::join('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
            ->leftJoin('ew_trades', 'ew_candidatescv.selected_trade', '=', 'ew_trades.id')
            ->select('ew_candidatescv.*', 'ew_references.reference_name', 'ew_trades.trade_name', 'ew_references.dealer')
            ->where('ew_candidatescv.valid', 1)
            ->where('ew_candidatescv.ew_project_id', $project_id)
            ->where(function($query) use ($search)
            {
                $query->where('cv_number', 'LIKE', '%'.$search.'%')
                    ->orWhere('full_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('father_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('passport_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('contact_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('reference_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('trade_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);


        // $data['CVLists']    = EwCandidatesCV::where('valid', 1)
        //     ->where('ew_project_id', $project_id)
        //     ->where(function($query) use ($search)
        //     {
        //         $query->where('cv_number', 'LIKE', '%'.$search.'%')
        //             ->orWhere('full_name', 'LIKE', '%'.$search.'%')
        //             ->orWhere('father_name', 'LIKE', '%'.$search.'%')
        //             ->orWhere('passport_no', 'LIKE', '%'.$search.'%')
        //             ->orWhere('contact_no', 'LIKE', '%'.$search.'%');
        //     })
        //     ->orderBy($ascDesc[0], $ascDesc[1])
        //     ->paginate($paginate->perPage);
        // return EwProjects::where('id', $project_id)->where('valid', 1)->first();

            $data['interviewCallProjectName'] = EwProjects::where('id', $project_id)->where('valid', 1)->first()->project_name;

        return view('recruitment.interview.listOfCV', $data);

    }
 
    public function interviewCreateCVForm(Request $request)
    {
        $projectId                  = $this->projectId;
        $data['project_id']         = $this->projectId;
        $data['projectCountryId']   = $this->projectCountryId;
        $data['inputData']          = $request->all();
        $data['countries']          = DB::table('countries')->get();
        $data['tradeAddAccess']     = Helper::userAccess('trades.create', 'ew');
        $data['trades']             = EwTrades::valid()->get();
        $data['references']         = EwReferences::valid()->get();
        $ew_project_agency          = EwProjectAgency::valid()->where('ew_project_id',$projectId)->lists('agency_id');
        $data['agency']             = EwAgency::valid()->whereIn('id', $ew_project_agency)->get();
        $data['inputData']          = $request->all();
        $data['countries']          = DB::table('countries')->where('id', 1)->get();
        $data['expCountries']       = DB::table('countries')->where('id','!=', 1)->get();
        $data['projectTrades']      = EwProjectTrades::join('ew_trades', function($join) use ($projectId){
            $join->on('ew_project_trades.trade_id', '=', 'ew_trades.id')
            ->where('ew_project_trades.ew_project_id', '=', $projectId);
        })->get();

        $data['years'] = DB::table('ew_years')->get();
                
        return view('recruitment.interview.create', $data); 

    }

    public function interviewCreateCVStore(Request $request)
    {
        $output       = array();
        $input        = $request->all();

        $validator    = [
        'passport_no' => 'required|unique:ew_candidatescv', 
        // 'national_id' => 'required|unique:ew_candidatescv'
        ];

         $validator = Validator::make($input, $validator);
         
        // return array_sum($request->home_experience);
        $cv_number = Carbon::now()->format('y').Carbon::now()->format('m').Carbon::now()->format('d').'-';
        $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
        $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));
        $selection_date        = date("Y-m-d", strtotime($request->selection_date));
        $interview_date        = date("Y-m-d", strtotime($request->interview_date));
        $cvCompleteness        = $request->cv_completeness_status;

        if ($cvCompleteness == 1) {

            $completeness = 1;

        }else {

            $completeness = 0;

        }

        if ($validator->passes()) {

            if ( empty($request->reference_id) || $request->reference_id == "" || $request->reference_id == null  ){
                $reference_id = 0;
                $dealer_id = 0;
            }else{ 
                $reference_id = $request->reference_id;
                $reference = EwReferences::valid()->where('id',$reference_id)->first();
                if(!empty($reference)){
                    $dealer_id = $reference->dealer;
                }else{
                    $dealer_id = 0;
                }
            }

            $candidateCV = EwCandidatesCV::create([
                'ew_project_id'                 => $request->ew_project_id,
                'project_country_id'            => $request->project_country_id,
                'full_name'                     => $request->full_name,
                'father_name'                   => $request->father_name,
                'date_of_birth'                 => $date_of_birth,
                'age'                           => $request->age,
                'education'                     => $request->education,
                'result'                        => $request->result == "" 
                    || $request->result == null ?0 : $request->result,

                'home_experience_details'       => json_encode($request->home_experience_details),
                'from_home_exp'                 => json_encode($request->from_home_exp),
                'to_home_exp'                   => json_encode($request->to_home_exp),
                'total_home_exp'                => json_encode($request->total_home_exp),
                'oversease_experience_details'  => json_encode($request->oversease_experience_details),
                'oversease_country'             => json_encode($request->oversease_country),
                'from_overs_exp'                => json_encode($request->from_overs_exp),
                'to_overs_exp'                  => json_encode($request->to_overs_exp),
                'total_overs_exp'               => json_encode($request->total_overs_exp),
                'total_years_of_experience'     => array_sum($request->total_home_exp) + array_sum($request->total_overs_exp),

                'passport_no'                   => $request->passport_no == "" 
                || $request->passport_no == null 
                ? 0 : $request->passport_no,

                'passport_status'               => $request->passport_status == "" 
                || $request->passport_status == null 
                ? 0 : $request->passport_status,
                'passport_expired_date'         => $passport_expired_date,

                'trade_applied'                 => $request->trade_applied == "" 
                || $request->trade_applied == null 
                ? 0 : $request->trade_applied,

                'selected_trade'                => $request->selected_trade == "" 
                || $request->selected_trade == null 
                ? 0 : $request->selected_trade,

                'selected_agency'                => $request->agency_id == "" 
                || $request->agency_id == null 
                ? 0 : $request->agency_id,

                'contact_no'                    => $request->contact_no == "" 
                || $request->contact_no == null 
                ? 0 : $request->contact_no,

                'reference_id'       => $reference_id,
                'dealer_id'          => $dealer_id,

                'process'                       => $request->process == "" 
                || $request->process == null 
                ? 0 : $request->process,
                'cv_completeness_status'        => 1,
                'country_id'                    => 1,
                'cv_transferred_status'         => 1,
            ]);

            $update_cv_number = EwCandidatesCV::where('id', $candidateCV->id)->update(['cv_number' => $cv_number.Helper::getCVId($candidateCV->id)]);

            EwInterview::create([
                'ew_project_id'                => $request->ew_project_id,
                'interview_date'               => $interview_date,
                'project_country_id'           => $request->project_country_id,
                'ew_candidatescv_id'           => $candidateCV->id,
                'interview_attend'             => 1,
                'passport_status'              => $request->passport_status == "" 
                || $request->passport_status == null 
                ? 0 : $request->passport_status,

                'trade_applied'                => $request->trade_applied == "" 
                || $request->trade_applied == null 
                ? 0 : $request->trade_applied,

                'selected_trade'               => $request->selected_trade == "" 
                || $request->selected_trade == null 
                ? 0 : $request->selected_trade,

                'wqrt_no'                      => $request->wqrt_no == "" 
                || $request->wqrt_no == null 
                ? 0 : $request->wqrt_no,

                'rt_test_result'               => $request->rt_test_result == "" 
                || $request->rt_test_result == null 
                ? 0 : $request->rt_test_result,


                'wqrt_test_report'             => $request->wqrt_test_report == "" 
                || $request->wqrt_test_report == null 
                ? 0 : $request->wqrt_test_report,


                'interview_selected_status'    => 1,

                'salary_ad'                    => $request->salary_ad == "" 
                || $request->salary_ad == null 
                ? 0 : $request->salary_ad,

                'salary'                       => $request->salary == "" 
                || $request->salary == null 
                ? 0 : $request->salary,

                'food'                         => $request->food == "" 
                || $request->food == null 
                ? 0 : $request->food,

                'ot'                           => $request->ot == "" 
                || $request->ot == null 
                ? 0 : $request->ot,

                'selection_date'               => $selection_date,

                'grade'                        => $request->grade == "" 
                || $request->grade == null 
                ? 0 : $request->grade,

                'score'                        => $request->score == "" 
                || $request->score == null 
                ? 0 : $request->score,

                'remarks'                      => $request->remarks == "" 
                || $request->remarks == null 
                ? 0 : $request->remarks,
            ]);

            $output['messege'] = 'Candidate CV has been created';
            $output['msgType'] = 'success';

        } else {
            $output = Helper::vError($validator);
        }

        return $output;
    }

    public function candidateDetails(Request $request)
    {
      $candidateId         = $this->candidateId;
      $projectId           = $this->projectId;
      $data['candidte_id'] = $this->candidateId;
      $data['projectId']   = $this->projectId;
      $data['cvDetails']   = EwCandidatesCV::join('ew_interviews', function($join) use($candidateId, $projectId){
        $join->on('ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
        ->where('ew_candidatescv.id','=', $candidateId)
        ->where('ew_candidatescv.ew_project_id', '=', $projectId);
      })->first();

      return view('recruitment.interview.candidate-details', $data);

    }

     /**
      * 
      *
      * @return Response
      */
    public function create(Request $request)
    {   
       
        
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
        
        $data['references']       = EwReferences::valid()->get();
        $data['editCandidateCvs'] = $details = EwCandidatesCV::join('ew_interviews', 'ew_interviews.ew_candidatescv_id','=', 'ew_candidatescv.id')
        ->where('ew_candidatescv.id', $id)
        ->first();

         $details->reference_id;
        $data['singgleRefs']      = EwReferences::valid()->where('id', $details->reference_id)->first();
        $projectId                = $details->ew_project_id;
        
        $data['projectTrades']    = EwProjectTrades::join('ew_trades', function($join) use ($projectId){
        $join->on('ew_project_trades.trade_id', '    =', 'ew_trades.id')
        ->where('ew_project_trades.ew_project_id', ' =', $projectId);
        })->get();

      $data['expCountries'] = DB::table('countries')->where('id','!=',1)->get();    
      $tradeIds      = EwInterview::valid()->where('ew_candidatescv_id', $id)->first();
      $tradeSelected = $tradeIds->selected_trade;
      $tradeApplied  = $tradeIds->trade_applied;

      $ew_project_agency          = EwProjectAgency::valid()->where('ew_project_id',$details->ew_project_id)->lists('agency_id');
      $data['agency']             = EwAgency::valid()->whereIn('id', $ew_project_agency)->get();

      if ($tradeSelected === null) {

      $data['tradeSelected'] = null;  

      } else {  

      $data['tradeSelected'] = $tradeSelected;

      }  

    if ($tradeApplied === null) {

        $data['tradeApplied'] = null;  

    } else { 

        $data['tradeApplied'] = $tradeApplied;

    } 
    $data['projectCountryId'] = $this->projectCountryId;
    
    $data['countries']   = DB::table('countries')->where('id', 1)->get();
    $data['years'] = DB::table('ew_years')->get();    

    return view('recruitment.interview.update', $data);

    }

    /**
     * 
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {   
       
        $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
        $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));
        $selection_date        = date("Y-m-d", strtotime($request->selection_date));
        $interview_date        = date("Y-m-d", strtotime($request->interview_date));
        $cvCompleteness        = $request->cv_completeness_status;

        if ($cvCompleteness == 1) {

            $completeness = 1;

        } else {

            $completeness = 0;

        }

        if ( empty($request->reference_id) || $request->reference_id == "" || $request->reference_id == null  ){
            $reference_id = 0;
            $dealer_id = 0;
        }else{ 
            $reference_id = $request->reference_id;
            $reference = EwReferences::valid()->where('id',$reference_id)->first();
            if(!empty($reference)){
                $dealer_id = $reference->dealer;
            }else{
                $dealer_id = 0;
            }
        }
        
        $cvBasicData  = [
            'project_country_id'                => $request->project_country_id,
            'full_name'                         => $request->full_name,

            'father_name'                       => $request->father_name,
            'date_of_birth'                     => $date_of_birth,

            'age'                               => $request->age == "" 
            || $request->age == null 
            ? 0 : $request->age,

            'education'                         => $request->education == "" 
            || $request->education == null ?0 : $request->education,

            'result'                            => $request->result == "" 
            || $request->result == null ?0 : $request->result,

            'home_experience_details'           => json_encode($request->home_experience_details),
            'from_home_exp'                     => json_encode($request->from_home_exp),
            'to_home_exp'                       => json_encode($request->to_home_exp),
            'total_home_exp'                    => json_encode($request->total_home_exp),
            'oversease_experience_details'      => json_encode($request->oversease_experience_details),
            'oversease_country'                 => json_encode($request->oversease_country),
            'from_overs_exp'                    => json_encode($request->from_overs_exp),
            'to_overs_exp'                      => json_encode($request->to_overs_exp),
            'total_overs_exp'                   => json_encode($request->total_overs_exp),
            'total_years_of_experience'         => array_sum($request->total_home_exp) + array_sum($request->total_overs_exp),
            'passport_no'                       => $request->passport_no == "" 
            || $request->passport_no == null 
            ? 0 : $request->passport_no,

            'passport_status'                   => $request->passport_status == "" 
            || $request->passport_status == null 
            ? 0 : $request->passport_status,

            'passport_expired_date'             => $passport_expired_date,

            'contact_no'                        => $request->contact_no == "" 
            || $request->contact_no == null 
            ? 0 : $request->contact_no,

            'trade_applied'                     => $request->trade_applied == "" 
            || $request->trade_applied == null 
            ? 0 : $request->trade_applied,

            'selected_trade'                    => $request->selected_trade == "" 
            || $request->selected_trade == null 
            ? 0 : $request->selected_trade,

            'selected_agency'                   => $request->agency_id == "" 
            || $request->agency_id == null 
            ? 0 : $request->agency_id,

            'country_id'                        => $request->country_id == "" 
            || $request->country_id == null 
            ? 0 : $request->country_id,

            'reference_id'                      => $request->reference_id == "" 
            || $request->reference_id == null 
            ? 0 : $request->reference_id,

            'dealer_id'                         => $dealer_id,

            'process'                           => $request->process == "" 
            || $request->process == null 
            ? 0 : $request->process,

            'cv_completeness_status'            => 1,
            'cv_transferred_status'             => 1,
                    
        
        ];

        EwCandidatesCV::valid()->where('id',$id)->update($cvBasicData);

            $cvInterviewData  = [
            'project_country_id'                => $request->project_country_id,
            'interview_date'                    => $interview_date,
            'ew_candidatescv_id'                => $id,
            'interview_attend'                  => 1,

            'passport_status'                   => $request->passport_status == 0 
            || $request->passport_status == null 
            ? 0 : $request->passport_status,

            'trade_applied'                     => $request->trade_applied == 0 
            || $request->trade_applied == null 
            ? 0 : $request->trade_applied,

            'selected_trade'                    => $request->selected_trade == 0 
            || $request->selected_trade == null 
            ? 0 : $request->selected_trade,

            'wqrt_no'                           => $request->wqrt_no == 0 
            || $request->wqrt_no == null 
            ? 0 : $request->wqrt_no,

            'rt_test_result'                    => $request->rt_test_result == 0 
            || $request->rt_test_result == null 
            ? 0 : $request->rt_test_result,

            'wqrt_test_report'                  => $request->wqrt_test_report == 0 
            || $request->wqrt_test_report == null 
            ? 0 : $request->wqrt_test_report,

            'interview_selected_status'         => 1,

            'salary'                            => $request->salary == 0 
            || $request->salary == null 
            ? 0 : $request->salary,

            'salary_ad'                         => $request->salary_ad == 0 
            || $request->salary_ad == null 
            ? 0 : $request->salary_ad,

            'food'                              => $request->food == 0 
            || $request->food == null 
            ? 0 : $request->food,
            'ot'                                => $request->ot == 0 
            || $request->ot == null 
            ? 0 : $request->ot,
            'selection_date'                    => $selection_date,

            'grade'                             => $request->grade == 0 
            || $request->grade == null 
            ? 0 : $request->grade,

            'score'                             => $request->score == 0 
            || $request->score == null 
            ? 0 : $request->score,

            'remarks'                           => $request->remarks
            ];

         EwInterview::valid()->where('ew_candidatescv_id', $id)->update($cvInterviewData);

        $output['messege'] = 'Candidate CV has been updated';
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
        // $EwAviationBill = EwCandidatesCV::where('id', $id)->first();
        // if (!empty($EwAviationBill)) {
        //    echo "This Aviation is used. You can not delete this!!!";
        // }
        // else{
        //     EwAviation::valid()->find($id)->delete();
        // }


         EwCandidatesCV::find($id)->delete();
        
    }


/*----------------------------------
    INTERVIEW CALL STATUS UPDATE
------------------------------------*/
    public function interviewCall(){
        return view('recruitment.interview.interview-call');
    }

    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('recruitment.interview.list', $data);
    }

    public function tradeDetails(Request $request){
        $data['details'] = EwProjectTrades::valid()
            ->where('ew_project_id', $request->projectId)
            ->where('trade_id', $request->tradeId)
            ->first();

        return $data;
    }


}
