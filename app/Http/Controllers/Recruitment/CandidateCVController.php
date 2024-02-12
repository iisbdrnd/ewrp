<?php

namespace App\Http\Controllers\Recruitment;
use DB;
use Auth;
use Helper;
use PDF;
use Excel;
use Validator;
use Carbon\Carbon;
use App\Http\Requests;
use App\Model\EwTrades;
use App\Model\EwInterview;
use App\Model\EwReferences;
use Illuminate\Http\Request;
use App\Model\EwCandidatesCV;
use App\Model\EwInterviewCall;
use App\Model\EwProjects;
use App\Model\EwAgency;
use App\Model\EwCandidates;
use App\Model\EwProjectAgency;
use App\Http\Controllers\Controller;
use TijsVerkoyen\CssToInlineStyles\Css\Rule\Rule;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


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
        $data['projects']    = EwInterviewCall::valid()->where('status', 1)->orderBy('ew_project_id', 'asc')->get();
        return view('recruitment.candidateCV.cv-moved-form', $data);
    }
    
    public function getDealer(Request $request){
        $reference = EwReferences::valid()->where('id', $request->dealerId)->first()->dealer;
        $dealer = Helper::dealer($reference)->name;
        return response()->json(['dealer' => rtrim($dealer)]);

        // if(!empty($reference)){
        //     $refJson = json_decode($reference, true);
        //    $get_dealer = '';
        //     foreach($refJson as $ref){
        //         $dealerName = Helper::dealer($ref)->name;
        //        $get_dealer .= $dealerName.', ';
        //     }
        //     return response()->json(['dealer' => rtrim($get_dealer,', ')]);
        // }
    }

    public function agency_details(Request $request){  
        $agency = EwAgency::valid()->where('id', $request->agencyId)->first()->recruiting_licence_no;
        $total_qty =  EwProjectAgency::valid()
                                    ->where('ew_project_id',$request->projectId)
                                    ->where('agency_id',$request->agencyId)
                                    ->first()->quantity;

        $used_qty = EwCandidatesCV::valid()  
                                  ->where('ew_project_id',$request->projectId)                          
                                  ->where('selected_agency',$request->agencyId)  
                                  ->count();

        $availble_qty =  $total_qty - $used_qty;                                                 
        return response()->json(['licence' => $agency,'qty' => $availble_qty]);
    }

    public function cv_moved_to_interview(Request $request){

       $candidateCVId = $request->candidate_id;
       EwCandidatesCV::valid()
       ->where('id', $candidateCVId)
       ->update([
            'ew_project_id'         => $request->ew_project_id,
            'cv_transferred_status' => 1
       ]);

       $cvCandidateId = EwCandidatesCV::valid()->where('id', $candidateCVId)->first()->candidate_id;
       $candidatesRow = EwCandidates::valid()->where('candidate_id', $cvCandidateId)->first();

       if(!empty($candidatesRow)){
            $candidatesRow->update([
                'ew_project_id' => $request->ew_project_id
            ]);
       }

        $check = EwInterview::valid()->where('ew_candidatescv_id', $candidateCVId)->exists();

        if ($check == true) {
            EwInterview::valid()->where('ew_candidatescv_id', $candidateCVId)->update([
                'ew_project_id'      => $request->ew_project_id,
            ]); 

        } else {
            EwInterview::create([
                'ew_project_id'      => $request->ew_project_id,
                'ew_candidatescv_id' => $candidateCVId
            ]);
        }
       

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
        $search = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['id', 'full_name', 'passport_no', 'reference_name', 'dealer']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;
        $data['trades'] = EwTrades::valid()->get();
        $data['references'] = EwReferences::valid()->get();
        $data['dealers'] = DB::table('users')
            ->where('valid', 1)
            ->where('id', '!=', '4')
            ->orderBy('name')
            ->get();

        $data['projects'] = EwProjects::valid()->orderBy('project_name', 'ASC')->get();
            
        $data['selectedTradeId'] = $selectedTradeId = (!empty($request->search_trade)? $request->search_trade:'');
        $data['selectedDealerId'] = $selectedDealerId = (!empty($request->search_dealer)? $request->search_dealer:'');
        $data['selecteProjectId'] = $selecteProjectId = (!empty($request->search_project)? $request->search_project:'');

        if($selecteProjectId == "c"){

            $data['candidateCVs'] = $cv =  EwCandidatesCV::join('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
            ->join('ew_mobilization_master_tables', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
            ->select('ew_candidatescv.*', 'ew_references.reference_name', 'ew_references.dealer','ew_mobilization_master_tables.flight_completed')
            ->where(function($query) use ($search)
            {
                $query->where('ew_candidatescv.full_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.cv_number', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_references.dealer', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.candidate_status', 'LIKE', '%'.$search.'%');
            })
            ->where(function($query) use ($selectedTradeId, $selectedDealerId){
                if (!empty($selectedTradeId)) {
                   $query->where('ew_candidatescv.search_trade', 'like', '%"' . $selectedTradeId . '"%');
                }
                if (!empty($selectedDealerId)) {
                    $query->where('ew_candidatescv.dealer_id', $selectedDealerId);
                }
            })
            ->where('ew_candidatescv.approved_status', 1)
            ->where('ew_candidatescv.valid', 1)
            ->where('ew_mobilization_master_tables.flight_completed', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        }else{

            $data['candidateCVs'] = $cv =  EwCandidatesCV::join('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
            ->select('ew_candidatescv.*', 'ew_references.reference_name', 'ew_references.dealer')
            ->where(function($query) use ($search)
            {
                $query->where('ew_candidatescv.full_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.cv_number', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_references.dealer', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.candidate_status', 'LIKE', '%'.$search.'%');
            })
            ->where(function($query) use ($selectedTradeId, $selectedDealerId, $selecteProjectId){
                if (!empty($selectedTradeId)) {
                    // $query->where('ew_candidatescv.selected_trade', $selectedTradeId)
                    // ->orWhere('ew_candidatescv.trade', 'like', '%"' . $selectedTradeId . '"%');
                    // $query->where('ew_candidatescv.search_trade', 'like', '%"' . $selectedTradeId . '"%');
                    $query->where('ew_candidatescv.search_trade', 'like', "%\"{$selectedTradeId}\"%");
                }
                if (!empty($selectedDealerId)) {
                    $query->where('ew_candidatescv.dealer_id', $selectedDealerId);
                }

                if($selecteProjectId == "a"){
                    $query->whereNotNull('ew_candidatescv.ew_project_id');
                }else if($selecteProjectId == "b"){
                    $query->whereNull('ew_candidatescv.ew_project_id');
                }else{
                    if($selecteProjectId > 0){
                        $query->whereNotNull('ew_candidatescv.ew_project_id');
                        $query->where('ew_candidatescv.ew_project_id', $selecteProjectId);
                    }
                }
            })
            ->where('ew_candidatescv.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
        }

        $data['totalCV'] = EwCandidatesCV::valid()->count();
        $data['pdf_url'] = route('recruit.candidateCvPDF').'?search='.$search.'&selectedTradeId='.$selectedTradeId.'&selectedDealerId='.$selectedDealerId.'&selecteProjectId='.$selecteProjectId;
        $data['excel_url'] = route('recruit.candidateCvExcel').'?search='.$search.'&selectedTradeId='.$selectedTradeId.'&selectedDealerId='.$selectedDealerId.'&selecteProjectId='.$selecteProjectId;

        return view('recruitment.candidateCV.listData', $data);
    }

    public function candidateCvPDF(Request $request) {
        $param['search']            = $request->search;
        $param['selectedTradeId']   = $request->selectedTradeId;
        $param['selectedDealerId']  = $request->selectedDealerId;
        $param['selecteProjectId']  = $request->selecteProjectId;
        $data = self::getCandidateCvPDF($param);

        $pdf = PDF::loadView('recruitment.candidateCV.report', $data);
        $file_name = 'candiateCV-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);

        // $pdf = PDF::loadView('recruitment.candidateCV.report',compact('data'));
        // return $pdf->download('invoice.pdf');
    }

    public static function getCandidateCvPDF($param) {
        $data['search'] = $search = $param['search'];
        $data['selectedTradeId'] = $selectedTradeId = $param['selectedTradeId'];
        $selectedDealerId = $data['selectedDealerId'] = $param['selectedDealerId'];
        $selecteProjectId = $data['selecteProjectId'] = $param['selecteProjectId'];
        $ascDesc = Helper::ascDesc($data, ['id', 'full_name', 'passport_no', 'reference_name', 'dealer']);

        $data['candidateCVs'] = $cv =  EwCandidatesCV::join('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
            // ->join('ew_mobilization_master_tables', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
            ->leftJoin('ew_mobilization_master_tables', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
            ->leftJoin('ew_projects','ew_projects.id', '=','ew_candidatescv.ew_project_id' )
            ->leftJoin('ew_trades', 'ew_candidatescv.selected_trade', '=', 'ew_trades.id')
            ->select('ew_candidatescv.*', 'ew_references.reference_name', 'ew_references.dealer', 'ew_trades.trade_name as selectedTrade','ew_projects.project_name','ew_mobilization_master_tables.flight_completed')
            ->where(function($query) use ($search)
            {
                $query->where('ew_candidatescv.full_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.cv_number', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_projects.project_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_references.dealer', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidatescv.candidate_status', 'LIKE', '%'.$search.'%');
            })
            ->where(function($query) use ($selectedTradeId, $selectedDealerId, $selecteProjectId){
                if (!empty($selectedTradeId)) {
                    // $query->where('ew_candidatescv.selected_trade', $selectedTradeId);
                    $query->where('ew_candidatescv.search_trade', 'like', "%\"{$selectedTradeId}\"%");
                }
                if (!empty($selectedDealerId)) {
                    $query->where('ew_references.dealer', $selectedDealerId);
                }
                // switch ($selecteProjectId) {
                //     case 1:
                //             $query->whereNotNull('ew_candidatescv.ew_project_id');
                //             $query->where('ew_candidatescv.ew_project_id', '!=', 0);
                //         break;
                //     case 2:
                //             $query->whereNull('ew_candidatescv.ew_project_id')
                //                     ->orWhere('ew_candidatescv.ew_project_id', 0);
                //         break;
                //     case 3:
                //             $query->where('ew_candidatescv.approved_status', 1);
                //             $query->where('ew_mobilization_master_tables.flight_completed', 1);
                //         break;
                // }
                switch ($selecteProjectId) {
                    case "a":
                            $query->whereNotNull('ew_candidatescv.ew_project_id');
                            $query->where('ew_candidatescv.ew_project_id', '!=', 0);
                        break;
                    case "b":
                            $query->whereNull('ew_candidatescv.ew_project_id')
                                    ->orWhere('ew_candidatescv.ew_project_id', 0);
                        break;
                    case "c":
                            $query->where('ew_candidatescv.approved_status', 1);
                            $query->where('ew_mobilization_master_tables.flight_completed', 1);
                        break;
                    default:
                        if($selecteProjectId > 0){
                            $query->whereNotNull('ew_candidatescv.ew_project_id');
                            $query->where('ew_candidatescv.ew_project_id', '!=', 0);
                            $query->where('ew_candidatescv.ew_project_id', $selecteProjectId);
                        }
                        break;
                }
                
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->get();
        // dd(count($cv));
        return $data;
        
    }

    public function candidateCvExcel(Request $request){
        $param['search']            = $request->search;
        $param['selectedTradeId']   = $request->selectedTradeId;
        $param['selectedDealerId'] = $selectedDealerId  = $request->selectedDealerId;
        $param['selecteProjectId']  = $request->selecteProjectId;
        $candidateCvData = self::getCandidateCvPDF($param);

        $excel_data_attribute_array = array(
            array(
                'SL', 
                'CV No', 
                'Name', 
                'PP No', 
                'Project', 
                'Trade', 
                'Reference', 
                'Dealer', 
                'Contact No', 
                'Home Exp', 
                'Ovr Exp', 
                'CV Status',
                'Result'
            )
        );

        $excel_dynamic_data_array = array();
        $index=0;

        foreach ($candidateCvData['candidateCVs'] as $key => $candidateCv) {

            if(!empty($candidateCv->ew_project_id)){
                $ProjectName = Helper::projects($candidateCv->ew_project_id)->project_name;
            }else{
                $ProjectName = 'CV Bank';
            }

            $trades = json_decode($candidateCv->trade, true);
            $i=1;
            $totalTrades = count((array)$trades);    
            $allTrades = "";
            foreach ($trades as $trade){
                $allTrades .= Helper::singleTrade($trade)->trade_name;
                if ($i<$totalTrades) {
                    $allTrades.= ',';
                } 
                $i++;
            }

            $dealers = json_decode($candidateCv->dealer, true);
            $i=1;
            $totaldealers = count((array)$dealers);    
            // $alldealers = "";
            // foreach ($dealers as $dealer){
            //     $alldealers .= Helper::dealer($dealer)->name;
            //     if ($i<$totaldealers) {
            //         $alldealers.= ',';
            //     } 
            //     $i++;
            // }

            $homeExp = (object)json_decode($candidateCv->total_home_exp, true);
            $totalHomeExp = 0;
            foreach($homeExp as $hExp){
                $totalHomeExp+= $hExp;
            }

            $OvrExp = (object)json_decode($candidateCv->total_overs_exp, true);
            $totalOvrExp = 0;
            foreach($OvrExp as $OExp){
                $totalOvrExp+= $OExp;
            }

            $cvStatus = '';
            $result = '';
            $deployDate = Helper::deployDate($candidateCv->ew_project_id,$candidateCv->id);
            if( @Helper::flightCompleted($candidateCv->ew_project_id,$candidateCv->id) == 1 && $candidateCv->approved_status == 1){
                $cvStatus.="Deployed";
                $cvStatus.=":";
                $cvStatus.=$deployDate;
                //$cvStatus.=",";
            }
            if($candidateCv->result == 1){
                $result.= "Pass";
            }else if($candidateCv->result == 2){
                $result.= "Fail";
            }else if($candidateCv->result == 3){
                $result.= "Waiting";
            }else if($candidateCv->result == 4){
                $result.= "Hold";
            }else if($candidateCv->result == 5){
                $result.= "Decline";
            }
            // if($candidateCv->result == 1){
            //     $cvStatus.= "Pass";
            // }else if($candidateCv->result == 2){
            //     $cvStatus.= "Fail";
            // }else if($candidateCv->result == 3){
            //     $cvStatus.= "Waiting";
            // }else if($candidateCv->result == 4){
            //     $cvStatus.= "Hold";
            // }else if($candidateCv->result == 5){
            //     $cvStatus.= "Decline";
            // }
 
            $excel_dynamic_data_array[$index][] = $key + 1;
            $excel_dynamic_data_array[$index][] = $candidateCv->cv_number;
            $excel_dynamic_data_array[$index][] = $candidateCv->full_name;
            $excel_dynamic_data_array[$index][] = $candidateCv->passport_no;
            $excel_dynamic_data_array[$index][] = $ProjectName;
            $excel_dynamic_data_array[$index][] = (!empty($candidateCv->selectedTrade)) ? $candidateCv->selectedTrade : $allTrades;
            $excel_dynamic_data_array[$index][] = Helper::reference($candidateCv->reference_id)->reference_name;
            $excel_dynamic_data_array[$index][] = Helper::dealer($selectedDealerId)->name;
            $excel_dynamic_data_array[$index][] = $candidateCv->contact_no;
            $excel_dynamic_data_array[$index][] = $totalHomeExp;
            $excel_dynamic_data_array[$index][] = $totalOvrExp;
            $excel_dynamic_data_array[$index][] = $cvStatus;
            $excel_dynamic_data_array[$index][] = $result;
            $index++;
        }
        // echo "<pre>";
        // print_r($excel_dynamic_data_array); exit();
  
        $final_array = array_merge($excel_data_attribute_array, $excel_dynamic_data_array);
        // echo "<pre>";
        // print_r($final_array); exit();

        Excel::create('Candidate Cv Report', function($excel) use ($final_array) {

            $excel->getDefaultStyle()
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $excel->getDefaultStyle()
                ->getAlignment()
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $excel->sheet('Candidate Cv Report', function($sheet) use ($final_array) {
                $sheet->fromArray($final_array);
                $sheet->row(2, function($row) {
                    $row->setFontSize('13');
                    $row->setFontColor('#333333');
                    $row->setBackground('#E6E6E6');
                    $row->setFontWeight('bold');
                });
                // $sheet->cell('A1', function($row) { 
                //     $row->setFontColor('#333333');
                // });
            });

        })->export('xlsx');
    }

     /**
      * Show the form for creating a new resource.
      *
      * @return Response
      */
    public function create(Request $request)
    {   

        $cv_number = Carbon::now()->format('y').Carbon::now()->format('m').Carbon::now()->format('d').'-';
        $lastCVId = EwCandidatesCV::orderBy('id', 'DESC')->first()->id;

        $data['genCvNumber'] = $cv_number.Helper::getCVId($lastCVId+1);

        $data['references'] = EwReferences::valid()->get();
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
        'passport_no' => 'required|unique:ew_candidatescv', 
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

        if ($validator->passes()){

            $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
            $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));

            if(!empty($request->reference_id)){
                $reference_id = $request->reference_id;
                $dealer_id = EwReferences::where('id',$reference_id)->first()->dealer;
            }else{
                $reference_id = 0;
                $dealer_id = 0;
            }
            
            $table = EwCandidatesCV::create([
            'full_name'                    => $request->full_name,
            'father_name'                  => $request->father_name,
            'date_of_birth'                => $date_of_birth,
            'age'                          => $request->age,
            'village'                      => $request->village,
            'po'                           => $request->po,
            'upazila'                      => $request->upazila,
            'district'                     => $request->district,
            'national_id'                  => $request->national_id,
            'education'                    => $request->education,
            'passport_no'                  => $request->passport_no,
            'passport_expired_date'        => $passport_expired_date,
            'contact_no'                   => $request->contact_no,
            'trade'                        => json_encode($tradeArr),
            'reference_id'                 => $reference_id,
            'dealer_id'                    => $dealer_id,
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
            
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);  
    }

    public function passportChecker(Request $request){
        $passport = $request->passport_no;
        $data['candidateName'] = $getProject =  EwCandidatesCV::valid()->where('passport_no', $passport)->first();
        $data['projectName'] = @Helper::projects($getProject->ew_project_id)->project_name;
        
        return response()->json($data);
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

        if(!empty($request->reference_id)){
            $reference_id = $request->reference_id;
            $dealer_id = EwReferences::where('id',$reference_id)->first()->dealer;
        }else{
            $reference_id = 0;
            $dealer_id = 0;
        }

        $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
        $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));
        $data                              =  [
            'full_name'                    => $request->full_name,
            'father_name'                  => $request->father_name,
            'date_of_birth'                => $date_of_birth,
            'age'                          => $request->age,
            'village'                      => $request->village,
            'po'                           => $request->po,
            'upazila'                      => $request->upazila,
            'district'                     => $request->district,
            'education'                    => $request->education,
            'passport_no'                  => $request->passport_no,
            'national_id'                  => $request->national_id,
            'passport_expired_date'        => $passport_expired_date,
            'contact_no'                   => $request->contact_no,
            'trade'                        => json_encode($tradeArr),
            'reference_id'                 => $reference_id,
            'dealer_id'                    => $dealer_id,
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
