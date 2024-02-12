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
use App\Model\EwReferences;
use App\Model\EwCandidateTransaction;
use App\Model\EwAccountTransaction;
use App\Model\EwCandidates;
use App\Model\EwChartOfAccounts;
use App\Model\EwProjects;
use App\Model\EwAccountConfiguration;

class AmountReceivedController extends Controller
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
        return view('ew.amountReceived.create', $data);
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
        $validator = [
            'project_id'            => 'required',
            'candidate_id'          => 'required',
            'account_head'          => 'required',
            'amount'                => 'required',
            'transaction_by'        => 'required'
        ];

        //Get bank upper_control_code
        $bank_account = EwAccountConfiguration::valid()->where('particular', 'bank')->first()->account_code;
        $bank_account_control = substr($bank_account,0,4);

        $transaction_by = $request->transaction_by;
        $bankList = EwChartOfAccounts::Valid()->where('account_level', 4)->where('upper_control_code', $bank_account_control)->orderBy('account_code')->get()->pluck('account_code')->all();
        $bankPayment = in_array($transaction_by, $bankList);
        if($bankPayment) {
            $validator["cheque_no"] = 'required';
            $validator["cheque_date"] = 'required';
            $cheque_date = DateTime::createFromFormat('d/m/Y', $request->cheque_date)->format('Y-m-d');
        }
        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {
            $amount = $request->amount;
            if($amount>0) {
                $candidate = EwCandidates::Valid()->find($request->candidate_id);
                if(!empty($candidate)) {
                    if($candidate->flight_status==0) {
                        $transaction_balance = Helper::getCandidateBalance($request->candidate_id, $request->account_head);
                        if($transaction_balance->balance>=$amount) {
                            $transaction_date = date('Y-m-d');
                            $transaction_status = ($bankPayment)?4:2;
                            $accTransaction = Helper::getTransactionInstrumentNo($transaction_status);

                            //Candidate Transaction
                            $candidate_input = [
                                'transaction_no'        => Helper::getCandidateTransactionNo(),
                                'account_transaction_no'=> $accTransaction['transaction_no'],
                                'candidate_id'          => $request->candidate_id,
                                'received_amount'       => $amount,
                                'collectable_account'   => $request->account_head,
                                'transaction_by'        => $transaction_by,
                                'transaction_date'      => $transaction_date,
                                'remarks'               => $request->remarks,
                                'transaction_status'    => 2
                            ];
                            if($bankPayment) {
                                $candidate_input["cheque_no"] = $request->cheque_no;
                                $candidate_input["cheque_date"] = $cheque_date;
                            }
                            EwCandidateTransaction::create($candidate_input);
                            $candidate_transaction_id = EwCandidateTransaction::valid()->orderBy('id', 'desc')->first()->id;

                            //Account Transaction
                            $candidate_liability = EwAccountConfiguration::valid()->where('particular', 'candidate_liability')->first()->account_code;
                            $account_input = [
                                'transaction_no'        => $accTransaction['transaction_no'],
                                'instrument_no'         => $accTransaction['instrument_no'],
                                'voucher_type'          => ($bankPayment)?'BRV':'RV',
                                'collectable_account'   => $request->account_head,
                                'transaction_by'        => $transaction_by,
                                'transaction_date'      => $transaction_date,
                                'remarks'               => $request->remarks,
                                'ew_project_id'         => $candidate->ew_project_id,
                                'candidate_id'          => $request->candidate_id,
                                'candidate_transaction_id' => $candidate_transaction_id,
                                'transaction_status'    => $transaction_status
                            ];
                            if($bankPayment) {
                                $account_input["cheque_no"] = $request->cheque_no;
                                $account_input["cheque_date"] = $cheque_date;
                            }
                            //Debit
                            $account_input["account_code"] = $transaction_by;
                            $account_input["contra_account_code"] = $candidate_liability;
                            $account_input["debit_amount"] = $amount;
                            $account_input["credit_amount"] = 0;
                            EwAccountTransaction::create($account_input);
                            //Credit
                            $account_input["account_code"] = $candidate_liability;
                            $account_input["contra_account_code"] = $transaction_by;
                            $account_input["debit_amount"] = 0;
                            $account_input["credit_amount"] = $amount;
                            EwAccountTransaction::create($account_input);

                            $output['messege'] = 'Account receive has been completed';
                            $output['msgType'] = 'success';
                        } else {
                            $output['messege'] = 'Amount can not be greater than balance';
                            $output['msgType'] = 'danger';
                        }
                    } else {
                        $output['messege'] = 'Candidate flight has already done';
                        $output['msgType'] = 'danger';
                    }
                } else {
                    $output['messege'] = 'Candidate is not exist';
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

    }
}
