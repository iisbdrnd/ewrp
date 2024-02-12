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

class AmountLessController extends Controller
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

        return view('ew.amountLess.create', $data);
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
            'amount'                => 'required'
        ];
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

                            //Candidate Transaction
                            EwCandidateTransaction::create([
                                'transaction_no'        => Helper::getCandidateTransactionNo(),
                                'candidate_id'          => $request->candidate_id,
                                'less_amount'           => $amount,
                                'collectable_account'   => $request->account_head,
                                'transaction_date'      => $transaction_date,
                                'remarks'               => $request->remarks,
                                'transaction_status'    => 5
                            ]);

                            $output['messege'] = 'Account less has been completed';
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
