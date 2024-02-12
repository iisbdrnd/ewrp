<?php

namespace App\Http\Controllers\Recruitment;
use DB;
use Auth;
use Helper;
use Validator;
use Carbon\Carbon;
use App\Http\Requests;
use App\Model\EwTrades;
use App\Model\EwInterview;
use App\Model\EwReferences;
use Illuminate\Http\Request;
use App\Model\EwCandidatesCV;
use App\Model\EwInterviewCall;
use App\Http\Controllers\Controller;


class CandidateCVController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('recruitment.candidateCV.list', $data);
    }

    public function cv_moved_form(Request $request){
        $data['candidateId'] = $request->candidate_id;
        $data['projects']    = EwInterviewCall::valid()->where('status', 1)->get();
        return view('recruitment.candidateCV.cv-moved-form', $data);
    }
    
    public function getDealer(Request $request){
        $reference = EwReferences::valid()->where('id', $request->dealerId)->first()->dealer;
        if(!empty($reference)){
            $refJson = json_decode($reference, true);
           $get_dealer = '';
            foreach($refJson as $ref){
                $dealerName = Helper::dealer($ref)->name;
               $get_dealer .= $dealerName.', ';
            }
            return response()->json(['dealer' => rtrim($get_dealer,', ')]);
        }
    }

    public function cv_moved_to_interview(Request $request){
       $candidateCVId = $request->candidate_id;
       EwCandidatesCV::valid()
       ->where('id', $candidateCVId)
       ->update([
        'ew_project_id'         => $request->ew_project_id,
        'cv_transferred_status' => 1
       ]);

        EwInterview::create([
        'ew_project_id'      => $request->ew_project_id,
        'ew_candidatescv_id' => $candidateCVId
       ]);

        $output['messege'] = 'Candidate CV has been moved to interview';
        $output['msgType'] = 'success';
        return $output;
    }

    public function interview_token(Request $request){
        $data['candidateId']    = $request->candidate_id;
        $data['interviewCalls'] = EwInterviewCall::valid()->where('status', 1)->get();
        return view('recruitment.candidateCV.interview-token', $data);
    }

    public function interview_token_create(Request $request){
        EwInterview::create([
         'ew_project_id'      => $request->ew_project_id,
         'ew_candidatescv_id' => $request->ew_candidatescv_id   
        ]);
        $output['messege'] = 'Candidate CV has been created';
        $output['msgType'] = 'success';
        return $output; 
    }

    public function candidatesCVListData(Request $request) {
        $data           = $request->all();
        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['id', 'full_name', 'passport_no', 'reference_name', 'dealer']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $data['candidateCVs'] = EwCandidatesCV::join('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
            ->leftJoin('ew_trades', 'ew_candidatescv.selected_trade', '=', 'ew_trades.id')
            ->select('ew_candidatescv.*', 'ew_references.reference_name', 'ew_references.dealer', 'ew_trades.trade_name as selectedTrade')
            ->where(function($query) use ($search)
            {
                $query->where('ew_candidatescv.full_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.cv_number', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.candidate_status', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.candidateCV.listData', $data);
    }

     /**
      * Show the form for creating a new resource.
      *
      * @return Response
      */
    public function create(Request $request)
    {   $data['references'] = EwReferences::valid()->get();
        $data['inputData']  = $request->all();
        $data['countries']  = DB::table('countries')->where('id', 1)->get();
        $data['years'] = DB::table('ew_years')->get();
        $data['trades'] = EwTrades::valid()->get();
        $data['expCountries'] = DB::table('countries')->where('id','!=',1)->get();
        return view('recruitment.candidateCV.create', $data);
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
            $output       = array();
            $input        = $request->all();
            $validator    = [
            // 'passport_no' => 'required|unique:ew_candidatescv',
            // 'national_id' => 'required|unique:ew_candidatescv'
            ];

        $tradeArr = [];
        if($request->trade != null){
          foreach($request->trade as $tradeId){
            $tradeArr[$tradeId] = $tradeId;
          }            
        }

        $validator = Validator::make($input, $validator);
        $cv_number = Carbon::now()->format('y').Carbon::now()->format('m').Carbon::now()->format('d').'-';

       if($validator->passes()){
        $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
        $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));
        
        $table = EwCandidatesCV::create([
        'full_name'                    => $request->full_name,
        'father_name'                  => $request->father_name,
        'date_of_birth'                => $date_of_birth,
        'age'                          => $request->age,
        // 'national_id'                  => $request->national_id,
        'education'                    => $request->education,
        'passport_no'                  => $request->passport_no,
        'passport_expired_date'        => $passport_expired_date,
        'contact_no'                   => $request->contact_no,
        'trade'                        => json_encode($tradeArr),
        'reference_id'                 => $request->reference_id,
        'process'                      => $request->process,
        'home_experience_details'      => json_encode($request->home_experience_details),
        'from_home_exp'                => json_encode($request->from_home_exp),
        'to_home_exp'                  => json_encode($request->to_home_exp),
        'total_home_exp'               => json_encode($request->total_home_exp),
        'oversease_experience_details' => json_encode($request->oversease_experience_details),
        'oversease_country'            => json_encode($request->oversease_country),
        'from_overs_exp'               => json_encode($request->from_overs_exp),
        'to_overs_exp'                 => json_encode($request->to_overs_exp),
        'total_overs_exp'              => json_encode($request->total_overs_exp),
        'total_years_of_experience'    => array_sum($request->total_home_exp) + array_sum($request->total_overs_exp),
        'country_id'                   => 1
        ]);
        
        $update_cv_number = EwCandidatesCV::where('id', $table->id)->update(['cv_number' => $cv_number.Helper::getCVId($table->id)]);

        $output['messege'] = 'CV has been created';
        $output['msgType'] = 'success';
        
    }else{
       
        $output = Helper::vError($validator);
        
    }
       
    echo json_encode($output);  
    }

    public function cvPrintPreview(Request $request){
          $data['candidate_id']  = $request->candidate_id;
          $data['cvPrints']      = EwCandidatesCV::valid()->where('id', $request->candidate_id)->first();
          $tradeIds              = EwInterview::valid()->where('ew_candidatescv_id', $request->candidate_id)->first();
          $tradeSelected         = $tradeIds->selected_trade;
          
          $tradeApplied          = $tradeIds->trade_applied;
          $pdf_url               = url('recruitment/cv-print-preview?candidate_id='.$request->candidate_id);
          
          if($tradeSelected      === null){
          $data['tradeSelected'] = null;  
          }else{  
          $data['tradeSelected'] = $tradeSelected;
          }  
          
          if($tradeApplied       === null){
          $data['tradeApplied']  = null;  
          }else{  
          $data['tradeApplied']  = $tradeApplied;
          } 
        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('recruitment.candidateCV.cvPrintPreview', $data);
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
    public function edit($id){   
        $data['references']       = EwReferences::valid()->get();
        $data['editCandidateCvs'] = $candidates =  EwCandidatesCV::valid()->where('id', $id)->first();
        $data['singgleRefs'] = EwReferences::valid()->where('id', $candidates->reference_id)->first();
        $data['tradeIds'] = json_decode($candidates->trade, true);
        $data['countries']        = DB::table('countries')->get();
        $data['years'] = DB::table('ew_years')->get();
        $data['trades'] = EwTrades::valid()->get();
        $data['expCountries'] = DB::table('countries')->where('id','!=',1)->get();
        return view('recruitment.candidateCV.update', $data);
    }

    /**
     * Update candidate cv.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
         $tradeArr = [];

        if($request->trade != null){
          foreach($request->trade as $tradeId){
            $tradeArr[$tradeId] = $tradeId;
          }            
        }

        $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
        $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));
        $data                              =  [
            'full_name'                    => $request->full_name,
            'father_name'                  => $request->father_name,
            'date_of_birth'                => $date_of_birth,
            'age'                          => $request->age,
            'education'                    => $request->education,
            'passport_no'                  => $request->passport_no,
            // 'national_id'                  => $request->national_id,
            'passport_expired_date'        => $passport_expired_date,
            'contact_no'                   => $request->contact_no,
            'trade'                        => json_encode($tradeArr),
            'reference_id'                 => $request->reference_id,
            'process'                 => $request->process,
            'home_experience_details'      => json_encode($request->home_experience_details),
            'from_home_exp'                => json_encode($request->from_home_exp),
            'to_home_exp'                  => json_encode($request->to_home_exp),
            'total_home_exp'               => json_encode($request->total_home_exp),
            'oversease_experience_details' => json_encode($request->oversease_experience_details),
            'oversease_country'                 => json_encode($request->oversease_country),
            'from_overs_exp'               => json_encode($request->from_overs_exp),
            'to_overs_exp'                 => json_encode($request->to_overs_exp),
            'total_overs_exp'              => json_encode($request->total_overs_exp),
            'total_years_of_experience'    => array_sum($request->total_home_exp) + array_sum($request->total_overs_exp),
            'country_id'                   => 1           
        ];

        EwCandidatesCV::valid()->find($id)->update($data);
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
        // $EwAviationBill = EwCandidatesCV::where('id', $id)->first();
        // if (!empty($EwAviationBill)) {
        //    echo "This Aviation is used. You can not delete this!!!";
        // }
        // else{
        //     EwAviation::valid()->find($id)->delete();
        // }
         EwCandidatesCV::valid()->find($id)->delete();
        
    }
}
