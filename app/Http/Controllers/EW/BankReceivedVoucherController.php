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

class BankReceivedVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $data['received_accounts']  = EwChartOfAccounts::Valid()
            ->where('account_level', 3)
            ->where(function($query)
            {
                $query->where('main_code', '=', 1)
                      ->orWhere('main_code', '=', 3);
            })
            ->get()
            ->keyBy('general_control_code');
        $data['received_accounts_level_four'] = EwChartOfAccounts::Valid()
            ->where('account_level', 4)
            ->where(function($query)
            {
                $query->where('main_code', '=', 1)
                      ->orWhere('main_code', '=', 3);
            })
            ->orderBy('account_code')
            ->get()
            ->groupBy('upper_control_code');

        $data['projects'] = EwProjects::valid()->get();

        //get bank upper_control_code
        $bank_account = EwAccountConfiguration::valid()->where('particular', 'bank')->first();
        $upper_control_code = substr($bank_account->account_code, 0, 4);
        $data['bank_accounts'] = EwChartOfAccounts::Valid()
            ->where('upper_control_code', $upper_control_code)
            ->orderBy('account_code')
            ->get();

        return view('ew.bankReceivedVoucher.bankReceivedVoucher', $data);
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
            'transaction_by'    => 'required',
            'cheque_no'         => 'required',
            'cheque_date'       => 'required'
        ]);

        if ($validator->passes()) {
            $transaction_status = 4; //4 for bank received voucher
            $transaction = Helper::getTransactionInstrumentNo($transaction_status);
            $data['transaction_no'] = $transaction['transaction_no'];
            $data['instrument_no'] = $transaction['instrument_no'];
            $data['voucher_type'] = 'BRV';
            $data['transaction_date'] = date('Y-m-d');
            $data['transaction_by'] = $transaction_by;
            $data['cheque_no'] = $request->cheque_no;
            $data['cheque_date'] = DateTime::createFromFormat('d/m/Y', $request->cheque_date)->format('Y-m-d');
            $data['remarks'] = $request->remarks;
            $data['ew_project_id'] = $request->ew_project_id;
            $data['transaction_status'] = $transaction_status;

            $data['account_code'] = $transaction_by;
            $data['contra_account_code'] = implode(",",$account_codes);
            $data['debit_amount'] = $request->total_amount;
            $data['credit_amount'] = 0;
            EwAccountTransaction::create($data);

            foreach($account_codes as $key=>$account_code) {
                $data['account_code'] = $account_code;
                $data['contra_account_code'] = $transaction_by;
                $data['debit_amount'] = 0;
                $data['credit_amount'] = $amounts[$key];
                EwAccountTransaction::create($data);
            }

            $output['messege'] = 'Bank received voucher has been created';
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
