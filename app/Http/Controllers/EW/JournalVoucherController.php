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

class JournalVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = $request->all();

        $data['accountLevelOfThree']  = EwChartOfAccounts::Valid()->where('account_level', 3)->get()->keyBy('general_control_code');
        $data['accountLevelOfFour'] = EwChartOfAccounts::Valid()->where('account_level', 4)->orderBy('account_code')->get()->groupBy('upper_control_code');

        $data['projects'] = EwProjects::valid()->get();

        return view('ew.journalVoucher.journalVoucher', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        
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
        $debit_account_codes = $request->debit_account_codes;
        $debit_amounts = $request->debit_amounts;
        $credit_account_codes = $request->credit_account_codes;
        $credit_amounts = $request->credit_amounts;
        $debit_total_amount = $request->debit_total_amount;
        $credit_total_amount = $request->credit_total_amount;

        $validator = Validator::make($input, [
            'ew_project_id'     => 'required'
        ]);

        if ($validator->passes()) {
            if($debit_total_amount>0 && $credit_total_amount>0 && $debit_total_amount==$credit_total_amount) {
                $transaction_status = 5; //5 for journal voucher
                $transaction = Helper::getTransactionInstrumentNo($transaction_status);
                $data['transaction_no'] = $transaction['transaction_no'];
                $data['instrument_no'] = $transaction['instrument_no'];
                $data['voucher_type'] = 'JV';
                $data['transaction_date'] = date('Y-m-d');
                $data['remarks'] = $request->remarks;
                $data['ew_project_id'] = $request->ew_project_id;
                $data['transaction_status'] = $transaction_status;

                foreach($debit_account_codes as $key=>$account_code) {
                    $data['account_code'] = $account_code;
                    $data['contra_account_code'] = implode(",",$credit_account_codes);
                    $data['debit_amount'] = $debit_amounts[$key];
                    $data['credit_amount'] = 0;
                    EwAccountTransaction::create($data);
                }
                foreach($credit_account_codes as $key=>$account_code) {
                    $data['account_code'] = $account_code;
                    $data['contra_account_code'] = implode(",",$debit_account_codes);
                    $data['debit_amount'] = 0;
                    $data['credit_amount'] = $credit_amounts[$key];
                    EwAccountTransaction::create($data);
                }

                $output['messege'] = 'Journal voucher has been created';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'Debit and Credit amount do not match.';
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
