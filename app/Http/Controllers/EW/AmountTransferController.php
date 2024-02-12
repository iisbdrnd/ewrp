<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwReferences;
use App\Model\EwAmountReceive;
use App\Model\EwCandidates;
use App\Model\EwChartOfAccounts;
use App\Model\EwProjects;
use App\Model\EwCandidateTransaction;
use App\Model\EwAccountTransaction;
use App\Model\EwAccountConfiguration;

class AmountTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['ewProjects'] = EwProjects::valid()->get();
        $data = array_merge($data, AccountController::cashBankComboData());
        
        return view('ew.amountTransfer.create', $data);
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
        $validator = Validator::make($input, [
            'from_project_id'       => 'required',
            'from_candidate_id'     => 'required',
            'from_account_head'     => 'required',
            'to_project_id'         => 'required',
            'to_candidate_id'       => 'required',
            'to_account_head'       => 'required',
            'amount'                => 'required',
        ]);

        if ($validator->passes()) {
            $amount = $request->amount;
            if($amount>0) {
                $from_candidate = EwCandidates::Valid()->find($request->from_candidate_id);
                $to_candidate = EwCandidates::Valid()->find($request->to_candidate_id);
                if(!empty($from_candidate)) {
                    if(!empty($to_candidate)) {
                        if($from_candidate->flight_status==0) {
                            if($to_candidate->flight_status==0) {
                                $from_candidate_balance = Helper::getCandidateBalance($request->from_candidate_id, $request->from_account_head);
                                $to_candidate_balance = Helper::getCandidateBalance($request->to_candidate_id, $request->to_account_head);
                                if(($from_candidate_balance->received_amount-$from_candidate_balance->paid_amount)>=$amount) {
                                    if($to_candidate_balance->balance>=$amount) {
                                        $transaction_date = date('Y-m-d');
                                        $transaction_status = 5;
                                        $accTransaction = Helper::getTransactionInstrumentNo($transaction_status);

                                        //Candidate Transaction
                                        $candidate_input = [
                                            'transaction_no'        => Helper::getCandidateTransactionNo(),
                                            'account_transaction_no'=> $accTransaction['transaction_no'],
                                            'transaction_date'      => $transaction_date,
                                            'remarks'               => $request->remarks,
                                            'transaction_status'    => 4
                                        ];

                                        //From Candidate
                                        $candidate_input["candidate_id"] = $request->from_candidate_id;
                                        $candidate_input["paid_amount"] = $amount;
                                        $candidate_input["received_amount"] = 0;
                                        $candidate_input["collectable_account"] = $request->from_account_head;
                                        $candidate_input["transfer_from"] = 0;
                                        $candidate_input["transfer_to"] = $request->to_candidate_id;
                                        EwCandidateTransaction::create($candidate_input);
                                        $from_candidate_transaction_id = EwCandidateTransaction::valid()->orderBy('id', 'desc')->first()->id;

                                        //To Candidate
                                        $candidate_input["candidate_id"] = $request->to_candidate_id;
                                        $candidate_input["paid_amount"] = 0;
                                        $candidate_input["received_amount"] = $amount;
                                        $candidate_input["collectable_account"] = $request->to_account_head;
                                        $candidate_input["transfer_from"] = $request->from_candidate_id;
                                        $candidate_input["transfer_to"] = 0;
                                        EwCandidateTransaction::create($candidate_input);
                                        $to_candidate_transaction_id = EwCandidateTransaction::valid()->orderBy('id', 'desc')->first()->id;

                                        //Account Transaction
                                        $candidate_liability = EwAccountConfiguration::valid()->where('particular', 'candidate_liability')->first()->account_code;
                                        $account_input = [
                                            'transaction_no'        => $accTransaction['transaction_no'],
                                            'instrument_no'         => $accTransaction['instrument_no'],
                                            'voucher_type'          => 'JV',
                                            'account_code'          => $candidate_liability,
                                            'contra_account_code'   => $candidate_liability,
                                            'transaction_date'      => $transaction_date,
                                            'remarks'               => $request->remarks,
                                            'transaction_status'    => $transaction_status
                                        ];
                                        //Debit
                                        $account_input["debit_amount"] = $amount;
                                        $account_input["credit_amount"] = 0;
                                        $account_input["collectable_account"] = $request->from_account_head;
                                        $account_input["ew_project_id"] = $from_candidate->ew_project_id;
                                        $account_input["candidate_id"] = $request->from_candidate_id;
                                        $account_input["candidate_transaction_id"] = $from_candidate_transaction_id;
                                        EwAccountTransaction::create($account_input);
                                        //Credit
                                        $account_input["debit_amount"] = 0;
                                        $account_input["credit_amount"] = $amount;
                                        $account_input["collectable_account"] = $request->to_account_head;
                                        $account_input["ew_project_id"] = $to_candidate->ew_project_id;
                                        $account_input["candidate_id"] = $request->to_candidate_id;
                                        $account_input["candidate_transaction_id"] = $to_candidate_transaction_id;
                                        EwAccountTransaction::create($account_input);

                                        $output['messege'] = 'Account transfer has been completed';
                                        $output['msgType'] = 'success';
                                    } else {
                                        $output['messege'] = "Amount can not be greater than ".$to_candidate->candidate_name."'s balance";
                                        $output['msgType'] = 'danger';
                                    }
                                } else {
                                    $output['messege'] = "Amount can not be greater than ".$from_candidate->candidate_name."'s received amount";
                                    $output['msgType'] = 'danger';
                                }
                            } else {
                                $output['messege'] = $to_candidate->candidate_name."'s flight has already done";
                                $output['msgType'] = 'danger';
                            }
                        } else {
                            $output['messege'] = $from_candidate->candidate_name."'s flight has already done";
                            $output['msgType'] = 'danger';
                        }
                    } else {
                        $output['messege'] = 'To Candidate is not exist';
                        $output['msgType'] = 'danger';
                    }
                } else {
                    $output['messege'] = 'From Candidate is not exist';
                    $output['msgType'] = 'danger';
                }
            } else {
                $output['messege'] = 'Amount must be greater than zero';
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
