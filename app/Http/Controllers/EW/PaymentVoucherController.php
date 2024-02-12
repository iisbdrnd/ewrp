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
use App\Model\EwCandidates;
use App\Model\EwChartOfAccounts;
use App\Model\EwAccountConfiguration;
use App\Model\EwAccountTransaction;

class PaymentVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['projects'] = EwProjects::valid()->get();

        //get cash upper_control_code
        $cash_account = EwAccountConfiguration::valid()->where('particular', 'cash')->first();
        $upper_control_code = substr($cash_account->account_code, 0, 4);
        $data['cash_accounts'] = EwChartOfAccounts::Valid()
            ->where('upper_control_code', $upper_control_code)
            ->orderBy('account_code')
            ->get();

        return view('ew.paymentVoucher.paymentVoucher', $data);
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
        $transaction_by = $request->transaction_by;
        $account_codes = $request->account_codes;
        $amounts = $request->amounts;

        $validator = Validator::make($input, [
            'ew_project_id'     => 'required',
            'transaction_by'    => 'required'
        ]);

        if ($validator->passes()) {
            $transaction_status = 1; //1 for payment voucher
            $transaction = Helper::getTransactionInstrumentNo($transaction_status);
            $data['transaction_no'] = $transaction['transaction_no'];
            $data['instrument_no'] = $transaction['instrument_no'];
            $data['voucher_type'] = 'PV';
            $data['transaction_date'] = date('Y-m-d');
            $data['transaction_by'] = $transaction_by;
            $data['remarks'] = $request->remarks;
            $data['ew_project_id'] = $request->ew_project_id;
            $data['transaction_status'] = $transaction_status;

            foreach($account_codes as $key=>$account_code) {
                $data['account_code'] = $account_code;
                $data['contra_account_code'] = $transaction_by;
                $data['debit_amount'] = $amounts[$key];
                $data['credit_amount'] = 0;
                EwAccountTransaction::create($data);
            }

            $data['account_code'] = $transaction_by;
            $data['contra_account_code'] = implode(",",$account_codes);
            $data['debit_amount'] = 0;
            $data['credit_amount'] = $request->total_amount;
            EwAccountTransaction::create($data);

            $output['messege'] = 'Payment voucher has been created';
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
