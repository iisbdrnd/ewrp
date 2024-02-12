<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwProjects;
use App\Model\EwTrades;
use App\Model\EwReferences;
use App\Model\EwCandidates;
use App\Model\EwProjectCollectableSelection;
use App\Model\EwCandidateTransaction;
use App\Model\EwProjectTrades;

class CandidateInfoController extends Controller
{
    protected $projectId;
    protected $candidateId;

    public function __construct(Request $request){
        $this->projectId   = $request->projectId;
        $this->candidateId = $request->candidateId;
    }

    public function candidateInfo(Request $request){
        $data['projectId'] = $this->projectId;
        $data['inputData'] = $request->all();
        return view('ew.candidateInfo.list', $data);
    }

    public function candidateInfoListData(Request $request) {
        // return $this->projectId;
         $data['collectable_selection_status'] = $collectable_selection_status = (!empty($request->collectable_selection_status)? $request->collectable_selection_status: 0);

        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['candidate_name', 'project_name', 'trade_name', 'reference_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;
        if($request->collectable_selection_status){
            $status = $request->collectable_selection_status;
        }else{
            $status = 0;
        }



        $data['candidates'] = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
            ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
            ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
            ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_trades.trade_name', 'ew_references.reference_name')
            ->where('ew_candidates.ew_project_id', $this->projectId)
            // ->where('ew_candidates.collectable_status', 0)
            ->where(function($query) use ($collectable_selection_status)
                {
                    if (!empty($collectable_selection_status)) {
                        switch ($collectable_selection_status) {
                            case 1:
                                $query->where('ew_candidates.collectable_status', 1);
                                break;
                            case 2:
                                $query->where('ew_candidates.collectable_status', 0);
                                $query->orWhere('ew_candidates.collectable_status', null);
                                break;
                            // default:

                            //     $query->where('collectable_status', 0)
                            //           ->orWhere('collectable_status', 1);
                            //     break;
                        }
                    }else{
                        $query->where('ew_candidates.collectable_status', 0);  
                    }
                })
            ->where(function($query) use ($search)
            {
                $query->where('ew_candidates.candidate_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_candidates.candidate_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_projects.project_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search.'%');
            })
            ->where('ew_candidates.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('ew.candidateInfo.listData', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('ew.candidateInfo.candidateProjectInfo', $data);
    }

    public function candidateProjectInfoData(Request $request){
         $data           = $request->all();
        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['project_name', 'updated_at']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;
        $projects       = EwProjects::valid()->where(function($query) use ($search)
            {
                $query->where('project_name', 'LIKE', '%'.$search.'%')
                      ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        foreach($projects as $project) {
            $project->trades = EwProjectTrades::join('ew_trades', 'ew_project_trades.trade_id', '=', 'ew_trades.id')
            ->select('ew_project_trades.*', 'ew_trades.trade_name')
            ->where('ew_project_trades.ew_project_id', $project->id)
            ->where('ew_project_trades.valid', 1)
            ->get()
            ->implode('trade_name', ', ');
        }

        $data['ewProjects'] = $projects;
        return view('ew.candidateInfo.candidateProjectInfoData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['ewProjects'] = EwProjects::valid()->get();
        $data['ewTrades'] = EwTrades::valid()->get();
        $data['ewReferences'] = EwReferences::valid()->get();

        return view('ew.candidateInfo.create', $data);
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
        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        $collectableHeadIds = $request->collectable_head_id;
        $collectableHeadAmounts = empty($request->collectable_head_amount)?[]:$request->collectable_head_amount;

        $validator = Validator::make($input, [
            'ew_project_id'             => 'required',
            'trade_id'                  => 'required',
            'reference_id'              => 'required',
            'collectable_amount'        => 'required',
            'candidate_name'            => 'required',
            'father_name'               => 'required',
            'passport_number'           => 'required',
            'national_id'               => 'required',
            'remarks'                   => 'required'
        ]);

        if ($validator->passes()) {
            $collectableAmount = $request->collectable_amount;
            $totalCollectableAmount = array_sum($collectableHeadAmounts); //All Collectable Selection Head's Amount

            if ($collectableAmount>0) {
                if ($collectableAmount==$totalCollectableAmount) {
                    $getCandidateId = Helper::getCandidateId();
                    //Candidate Information
                    EwCandidates::create([
                        "candidate_id"          => $getCandidateId,
                        "ew_project_id"         => $request->ew_project_id,
                        "trade_id"              => $request->trade_id,
                        "reference_id"          => $request->reference_id,
                        "collectable_amount"    => $collectableAmount,
                        "candidate_name"        => $request->candidate_name,
                        "father_name"           => $request->father_name,
                        "passport_number"       => $request->passport_number,
                        "national_id"           => $request->national_id,
                        "remarks"               => $request->remarks,
                        "flight_status"         => 0,
                        "collectable_status"    => 1,
                    ]);

                    //Candidate Transaction
                    $candidate_id = EwCandidates::valid()->where('ew_project_id', $request->ew_project_id)->orderBy('id', 'desc')->first()->id;
                    $transaction_no = Helper::getCandidateTransactionNo();
                    $data = [
                        'transaction_no'        => $transaction_no,
                        'candidate_id'          => $candidate_id,
                        'transaction_date'      => date('Y-m-d'),
                        'remarks'               => $request->remarks,
                        'transaction_status'    => 1
                    ];
                    foreach($collectableHeadIds as $key=>$collectableHeadId) {
                        $data['receivable_amount'] = $collectableHeadAmounts[$key];
                        $data['collectable_account'] = $collectableHeadId;
                        EwCandidateTransaction::create($data);
                    }
                    $output['messege'] = 'Candidate information has been created';
                    $output['msgType'] = 'success';
                }else{
                    $output['messege'] = 'Collectable amount must be same to head amount!';
                    $output['msgType'] = 'danger';
                }
            }else{
                $output['messege'] = 'Collectable amount must be more than 0!';
                $output['msgType'] = 'danger';
            }

        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
        DB::commit();
    }

    public function createCandidate(Request $request)
    {
        // return $request->all();
        $data['inputData']    = $request->all();
        $data['ewProjects']   = EwProjects  ::valid()->get();
        $data['ewTrades']     = EwTrades    ::valid()->get();
        $data['ewReferences'] = EwReferences::valid()->get();

        return view('ew.candidateInfo.create-candidate', $data);
    }

    public function createCandidateStore(Request $request)
    {

        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        $collectableHeadIds = $request->collectable_head_id;
        $collectableHeadAmounts = empty($request->collectable_head_amount)?[]:$request->collectable_head_amount;

        $validator = Validator::make($input, [
            'ew_project_id'             => 'required',
            'trade_id'                  => 'required',
            'reference_id'              => 'required',
            'collectable_amount'        => 'required',
            'candidate_name'            => 'required',
            'father_name'               => 'required',
            'passport_number'           => 'required',
            'national_id'               => 'required',
            'remarks'                   => 'required'
        ]);

        if ($validator->passes()) {
            $collectableAmount = $request->collectable_amount;
            $totalCollectableAmount = array_sum($collectableHeadAmounts); //All Collectable Selection Head's Amount

            if ($collectableAmount>0) {
                if ($collectableAmount==$totalCollectableAmount) {
                    $getCandidateId = Helper::getCandidateId();
                    //Candidate Information
                    EwCandidates::create([
                        "candidate_id"          => $getCandidateId,
                        "ew_project_id"         => $request->ew_project_id,
                        "trade_id"              => $request->trade_id,
                        "reference_id"          => $request->reference_id,
                        "collectable_amount"    => $collectableAmount,
                        "candidate_name"        => $request->candidate_name,
                        "father_name"           => $request->father_name,
                        "passport_number"       => $request->passport_number,
                        "national_id"           => $request->national_id,
                        "remarks"               => $request->remarks,
                        "flight_status"         => 0,
                        "collectable_status"    => 1,
                    ]);

                    //Candidate Transaction
                    $candidate_id = EwCandidates::valid()->where('ew_project_id', $request->ew_project_id)->orderBy('id', 'desc')->first()->id;
                    $transaction_no = Helper::getCandidateTransactionNo();
                    $data = [
                        'transaction_no'        => $transaction_no,
                        'candidate_id'          => $candidate_id,
                        'transaction_date'      => date('Y-m-d'),
                        'remarks'               => $request->remarks,
                        'transaction_status'    => 1
                    ];
                    foreach($collectableHeadIds as $key=>$collectableHeadId) {
                        $data['receivable_amount'] = $collectableHeadAmounts[$key];
                        $data['collectable_account'] = $collectableHeadId;
                        EwCandidateTransaction::create($data);
                    }
                    $output['messege'] = 'Candidate information has been created';
                    $output['msgType'] = 'success';
                }else{
                    $output['messege'] = 'Collectable amount must be same to head amount!';
                    $output['msgType'] = 'danger';
                }
            }else{
                $output['messege'] = 'Collectable amount must be more than 0!';
                $output['msgType'] = 'danger';
            }

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
        $data['id'] = $id;
        $data['ewCandidates'] = $candidate = EwCandidates::valid()->find($id);
        $data['projectTrades'] = EwProjectTrades::join('ew_trades', 'ew_project_trades.trade_id', '=', 'ew_trades.id')
            ->select('ew_trades.*')
            ->where('ew_project_trades.valid', 1)
            ->where('ew_trades.valid', 1)
            ->where('ew_project_trades.ew_project_id', $candidate->ew_project_id)
            ->get();

        $data['ewProjects'] = EwProjects::valid()->get();
        $data['ewJobs'] = EwTrades::valid()->get();
        $data['ewReferences'] = EwReferences::valid()->get();

        $projectCollectableSelections = EwCandidateTransaction::join('ew_collectable_account_heads', 'ew_candidate_transaction.collectable_account', '=', 'ew_collectable_account_heads.id')
            ->select('ew_collectable_account_heads.*', DB::raw('(sum(ew_candidate_transaction.receivable_amount)-sum(ew_candidate_transaction.less_amount)) as receivable_amount'))
            ->where('ew_candidate_transaction.candidate_id', $id)
            ->where('ew_candidate_transaction.account_transaction_no', 0)
            ->where(function($query){
                $query->where('ew_candidate_transaction.transaction_status', 1)
                    ->orWhere('ew_candidate_transaction.transaction_status', 5);
            })
            ->where('ew_candidate_transaction.valid', 1)
            ->groupBy('ew_candidate_transaction.collectable_account')
            ->get();

        // echo "<pre>";
        // print_r($projectCollectableSelections);

        $data['total_receivable_amount'] = $projectCollectableSelections->sum('receivable_amount');
        $data['projectCollectableSelections'] = $projectCollectableSelections->chunk(2);

        return view('ew.candidateInfo.update', $data);
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
        // return $request->collectable_head_id;

        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        $collectableHeadIds = $request->collectable_head_id;
        $collectableHeadAmounts = empty($request->collectable_head_amount)?[]:$request->collectable_head_amount;

        $validator = Validator::make($input, [
            'ew_project_id'             => 'required',
            'trade_id'                  => 'required',
            'reference_id'              => 'required',
            'collectable_amount'        => 'required',
            'candidate_name'            => 'required',
            'father_name'               => 'required',
            'passport_number'           => 'required',
            'national_id'               => 'required'
        ]);

        if ($validator->passes()) {
            $collectableAmount = $request->collectable_amount;
            $totalCollectableAmount = array_sum($collectableHeadAmounts); //All Collectable Selection Head's Amount

            if ($collectableAmount>0) {
                if ($collectableAmount==$totalCollectableAmount) {
                    $getCandidateId = Helper::getCandidateId();
                    //Candidate Information
                    EwCandidates::where('id', $id)->update([
                        "trade_id"              => $request->trade_id,
                        "reference_id"          => $request->reference_id,
                        "collectable_amount"    => $collectableAmount,
                        "candidate_name"        => $request->candidate_name,
                        "father_name"           => $request->father_name,
                        "passport_number"       => $request->passport_number,
                        "national_id"           => $request->national_id,
                        "remarks"               => $request->remarks,
                        "flight_status"         => 0,
                        "collectable_status"    => 1,
                    ]);

                    //Candidate Transaction
                   
                    $transaction_no = Helper::getCandidateTransactionNo();
                    $data = [
                        'transaction_no'        => $transaction_no,
                        'candidate_id'          => $id,
                        'transaction_date'      => date('Y-m-d'),
                        'remarks'               => $request->remarks,
                        'transaction_status'    => 1
                    ];

                    $candidateTransaction = EwCandidateTransaction::valid()->where('candidate_id', $id)->exists();
                    // dd($candidateTransaction);
                   
                            foreach($collectableHeadIds as $key=>$collectableHeadId) {

                                $data['receivable_amount'] = $collectableHeadAmounts[$key];
                                $data['collectable_account'] = $collectableHeadId;
                                    if( $candidateTransaction === true){
                                    EwCandidateTransaction::where('candidate_id', $id)
                                        ->where('account_transaction_no', 0)
                                        ->where('collectable_account', $collectableHeadId)
                                        ->update($data);
                                    }else{
                                        EwCandidateTransaction::create($data);
                                    } 
                            }
                    
                    $output['messege'] = 'Candidate information has been updated';
                    $output['msgType'] = 'success';
                }else{
                    $output['messege'] = 'Collectable amount must be same to head amount!';
                    $output['msgType'] = 'danger';
                }
            }else{
                $output['messege'] = 'Collectable amount must be more than 0!';
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
    // public function destroy($id)
    // {
    //     EwCandidates::valid()->find($id)->delete();
    // }



    //Candidate sort details
    public function candidateSortDetails(Request $request)
    {
        $candidate_id = $request->candidateId;
        $less = $request->less;
        $output['candidate_details'] = $candidate_details = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
            ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
            ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
            ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_trades.trade_name', 'ew_references.reference_name','ew_references.dealer')
            ->where('ew_candidates.valid', 1)
            ->where('ew_candidates.id', $candidate_id)
            ->first();

        if(isset($candidate_details->dealer))
        {
            $output['candidate_details']->dealerName = @Helper::getDealerName($candidate_details->dealer);
        }
        else
        {
            $output['candidate_details']->dealerName = "";
        }

        
        if($output['candidate_details']) {
            $output['candidate_details']->candidate_flight_date = DateTime::createFromFormat('Y-m-d', $output['candidate_details']->candidate_flight_date)->format('d/m/Y');
        }
        $transaction = Helper::getCandidateBalance($candidate_id);

        $receivable_amount = EwCandidateTransaction::valid()
                ->select('candidate_id', DB::raw('sum(receivable_amount) as receivable_amount'))
                ->where('candidate_id', $candidate_id)
                ->where('account_transaction_no', 0)
                ->first()->receivable_amount;

        $output['total_receivable'] = number_format($receivable_amount, 2);

        $output['total_received'] = number_format(($transaction->received_amount - $transaction->paid_amount), 2);
        $output['total_less'] = number_format($transaction->less_amount, 2);
        $output['total_receivable_with_less'] = number_format($transaction->receivable_amount - $transaction->less_amount, 2);
        $output['balance'] = number_format($transaction->balance, 2);

        echo json_encode($output);
    }

    //Candidate Account Head Summary
    public function candidateAccountHeadSummary(Request $request)
    {
        $candidate_id = $request->candidateId;
        $account_head = $request->account_head;

        $transaction = Helper::getCandidateBalance($candidate_id, $account_head);
        $output['total_receivable'] = number_format($transaction->receivable_amount, 2);
        $output['total_received'] = number_format(($transaction->received_amount-$transaction->paid_amount), 2);
        $output['total_less'] = number_format($transaction->less_amount, 2);
        $output['total_receivable_with_less'] = number_format($transaction->receivable_amount - $transaction->less_amount, 2);
        $output['balance'] = number_format($transaction->balance, 2);

        echo json_encode($output);
    }


    //PROJECT WISE CANDIDATES
    public function projectCandidates(Request $request)
    {
        $data['inputData'] = $request->all();
        $ew_project_id = $request->ew_project_id;
        $data['projectCandidates'] = EwCandidates::valid()->where('ew_project_id', $ew_project_id)->get();

        return view('ew.candidateInfo.projectCandidates', $data);
    }
    

    //PROJECT WISE CANDIDATES MULTIPLE SELECT
    public function projectCandidatesMultipleSelect(Request $request)
    {
        $data['inputData'] = $request->all();
        $ew_project_id = $request->ew_project_id;

        $data['projectCandidates'] = EwCandidates::valid()->where('ew_project_id', $ew_project_id)->get();

        return view('ew.candidateInfo.projectCandidatesMultipleSelect', $data);
    }
}
