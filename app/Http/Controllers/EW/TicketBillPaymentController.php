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
use App\Model\EwAmountReceive;
use App\Model\EwCandidates;
use App\Model\EwChartOfAccounts;
use App\Model\EwProjects;
use App\Model\EwCandidateTransaction;
use App\Model\EwAccountTransaction;
use App\Model\EwAccountConfiguration;
use App\Model\EwAviation;
use App\Model\EwAviationBill;
use App\Model\EwAviationBillTypes;
use App\Model\EwAviationBillPayment;
use App\Model\EwTicketBillLedgers;
use App\Model\EwTicketBillMaster;
use App\Model\EwTicketBillDetails;

class TicketBillPaymentController extends Controller
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

        //get cash upper_control_code
        $data = array_merge($data, AccountController::cashBankComboData());
        return view('ew.ticketBillPayment.create', $data);
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

        $ew_project_id = $request->ew_project_id;        
        $aviation_id = $request->aviation_id;
        $transaction_by = $request->transaction_by;
        $cheque_no = $request->cheque_no;
        $target_amount = $request->amount;
        $remarks = $request->remarks;

        $ticket_ledger = EwTicketBillLedgers::valid()
                    ->select(DB::raw('sum(bill_amount)-sum(payment_amount) as total_payable_amount'))
                    ->where('aviation_id', $aviation_id)
                    ->first();

        $balance = $ticket_ledger->total_payable_amount;

        $validator = [
            'ew_project_id'                 => 'required',
            'aviation_id'                   => 'required',
            'transaction_by'                => 'required',
            'amount'                        => 'required'
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
            if($balance>0 && $balance>=$target_amount) {
                
                $voucher_type = 'JV';
                $transaction_status = 5; //5 for journal voucher
                
                $transaction = Helper::getTransactionInstrumentNo($transaction_status);
                $aviation_acc_code = EwAviation::valid()->find($aviation_id)->account_code;

                $data2['transaction_no'] = $transaction['transaction_no'];
                $data2['instrument_no'] = $transaction['instrument_no'];
                $data2['voucher_type'] = $voucher_type;
                $data2['account_code'] = $aviation_acc_code;
                $data2['contra_account_code'] = $transaction_by;
                $data2['transaction_by'] = $transaction_by;
                $data2['transaction_date'] = date('Y-m-d');
                $data2['transaction_status'] = $transaction_status;
                $data2['ew_project_id'] = $ew_project_id;
                $data2['debit_amount'] = $target_amount;
                $data2['credit_amount'] = 0;
                $aviation_name = EwAviation::valid()->find($aviation_id)->company_name;
                $data2['remarks'] = $remarks;

                if($bankPayment) {
                    $data2["cheque_no"] = $request->cheque_no;
                    $data2["cheque_date"] = $cheque_date;
                }
                //Create Debit Transaction
                EwAccountTransaction::create($data2);

                
                //Create Credit Transaction
                $data2['account_code'] = $transaction_by;
                $data2['contra_account_code'] = $aviation_acc_code;
                $data2['debit_amount'] = 0;
                $data2['credit_amount'] = $target_amount;
                EwAccountTransaction::create($data2);

                $acc_transaction_transaction_no = EwAccountTransaction::valid()->orderBy('id', 'desc')->first()->transaction_no;

                $ticket_bill_ledger['aviation_id'] = $aviation_id;
                // $ticket_bill_ledger['ew_ticket_bill_id'] = $ew_ticket_bill_id;
                $ticket_bill_ledger['transaction_no'] = $acc_transaction_transaction_no;
                $ticket_bill_ledger['transaction_date'] = date('Y-m-d');
                $ticket_bill_ledger['bill_amount'] = 0;
                $ticket_bill_ledger['payment_amount'] = $target_amount;
                $ticket_bill_ledger['remarks'] = $remarks;
                EwTicketBillLedgers::create($ticket_bill_ledger);

                $output['messege'] = 'Aviation bill payment has been created';
                $output['msgType'] = 'success';

            } else {
                    $output['messege'] = 'Received amount can not greater than Receiable Amount.';
                    $output['msgType'] = 'danger';
            }
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
        DB::commit();
    }
    //PROJECT WISE AVIATION
    public function projectAviations(Request $request)
    {
        $data['inputData'] = $request->all();
        $ew_project_id = $request->ew_project_id;
        $projectAviationsId = EwTicketBillDetails::join('ew_ticket_bill_master', 'ew_ticket_bill_master.id', '=', 'ew_ticket_bill_details.ew_ticket_bill_id')
                          ->select('ew_ticket_bill_master.aviation_id')  
                          ->where('ew_ticket_bill_details.ew_project_id', $ew_project_id)
                          ->get()
                          ->pluck('aviation_id')
                          ->all();
        $projectAviationsId = array_unique($projectAviationsId);
        $projectAviations = EwAviation::valid()->whereIn('id', $projectAviationsId)->get();
        
        $html = "<option value=''> Select Aviation Name </option>";
        foreach($projectAviations as $aviation) {
            $ticket_ledger = EwTicketBillLedgers::valid()
                    ->select(DB::raw('sum(bill_amount) as total_bill_amount, sum(payment_amount) as total_payment_amount, sum(bill_amount)-sum(payment_amount) as total_payable_amount'))
                    ->where('aviation_id', $aviation->id)
                    ->first();

            if($ticket_ledger->total_payable_amount>0) {
              $html .= "<option value=\"$aviation->id\"> [$aviation->account_code]  $aviation->company_name </option>";  
            }
            
        }
        return $html;
    }
    //Aviation sort details
    public function aviationSortDetails(Request $request)
    {
        $data['inputData'] = $request->all();
        $aviationId = $request->aviationId;

        $ticket_ledger = EwTicketBillLedgers::valid()
                    ->select(DB::raw('sum(bill_amount) as total_bill_amount, sum(payment_amount) as total_payment_amount, sum(bill_amount)-sum(payment_amount) as total_payable_amount'))
                    ->where('aviation_id', $aviationId)
                    ->first();

        $output['payment_amount'] = number_format($ticket_ledger->total_bill_amount, 2);
        $output['received'] = number_format($ticket_ledger->total_payment_amount, 2);
        $output['balance'] = number_format($ticket_ledger->total_payable_amount, 2);

        echo json_encode($output);
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

    public function projectCandidates(Request $request)
    {
        $candidate_id = EwCandidates::valid()->where('candidate_id', $request->ew_project_id)->first();
        $output['candidate_name'] = $candidate_id->candidate_name;
        
        echo json_encode($output);
    }
}
