<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use PDF;

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
use App\Model\EwTicketBillMaster;
use App\Model\EwTicketBillLedgers;
use App\Model\EwTicketBillDetails;
use App\Model\EwAviationBillTypes;

class TicketingBillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('ew.ticketingBill.list', $data);
    }

    public function ticketBillListData(Request $request) 
    {
        $data = $request->all();
        $search = $request->search;
        $data['bill_send_type'] = $bill_send_type = (!empty($request->bill_send_type)? $request->bill_send_type: 3);

        $data['bill_transfer_type'] = $bill_transfer_type = (!empty($request->bill_transfer_type)? $request->bill_transfer_type: 3);

        $data['bill_paid_type'] = $bill_paid_type = (!empty($request->bill_paid_type)? $request->bill_paid_type: 3);

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['aviation_id', 'bill_no', 'target_amount']);
        $paginate = Helper::paginate($data,10);
        $data['sn'] = $paginate->serial;

        $data['aviation'] = EwTicketBillMaster::join("ew_aviations", "ew_ticket_bill_master.aviation_id", "=", "ew_aviations.id")
            ->select("ew_ticket_bill_master.*", "ew_aviations.company_name", "ew_aviations.account_code")
            ->where(function($query) use ($bill_send_type)
            {
                if (!empty($bill_send_type)) {
                    switch ($bill_send_type) {
                        case 1:
                            $query->where('ew_ticket_bill_master.bill_send_status', 1);
                            break;
                        case 2:
                            $query->where('ew_ticket_bill_master.bill_send_status', 0);
                            break;
                        default:
                            $query->where('ew_ticket_bill_master.bill_send_status', 0)
                                  ->orWhere('ew_ticket_bill_master.bill_send_status', 1);
                            break;
                    }
                }
            })
             ->where(function($query) use ($bill_transfer_type)
            {
                if (!empty($bill_transfer_type)) {
                    switch ($bill_transfer_type) {
                        case 1:
                            $query->where('ew_ticket_bill_master.bill_send_status', 1)
                                ->where('ew_ticket_bill_master.bill_transfer_status', 1);
                            break;
                        case 2:
                            $query->where('ew_ticket_bill_master.bill_transfer_status', 0);
                            break;
                        default:
                            $query->where('ew_ticket_bill_master.bill_transfer_status', 0)
                                  ->orWhere('ew_ticket_bill_master.bill_transfer_status', 1);
                            break;
                    }
                }
            })
             ->where(function($query) use ($bill_paid_type)
            {
                if (!empty($bill_paid_type)) {
                    switch ($bill_paid_type) {
                        case 1:
                            $query->where('ew_ticket_bill_master.paid_status', 1);
                            break;
                        case 2:
                            $query->where('ew_ticket_bill_master.paid_status', 0);
                            break;
                        default:
                            $query->where('ew_ticket_bill_master.paid_status', 0)
                                  ->orWhere('ew_ticket_bill_master.paid_status', 1);
                            break;
                    }
                }
            })
            ->where(function($query) use ($search)
            {
                $query->Where('account_code', 'LIKE', '%'.$search.'%')
                    ->orWhere('company_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('bill_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('target_amount', 'LIKE', '%'.$search.'%');
            })
            ->where('ew_ticket_bill_master.valid','=', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('ew.ticketingBill.listData', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['ewAviations'] = EwAviation::valid()->get();
        $data['ewProjects'] = EwProjects::valid()->get();
        // $data = array_merge($data, AccountController::cashBankComboData());
        $data['aviationTypes'] = DB::table('ew_aviation_bill_types')->get();
        
        return view('ew.ticketingBill.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $output = array();
        $input = $request->all();

        $aviation_id = $request->aviation_id;
        $target_amount = $request->target_amount;

        //ARRAY FILEDS
        $ew_project_id = $request->ew_project_id;
        $candidate_id = $request->candidate_id;
        $bill_type = $request->bill_type;
        $nos = $request->nos;
        $amounts = $request->amounts;
        $remarks = $request->remarks;
        $total_amount = $request->total_amount;
        $main_remarks = $request->main_remarks;

        $validator = [
            'aviation_id'               => 'required',
            'target_amount'             => 'required',
            'nos'                       => 'required'
        ];
        $validator = Validator::make($input, $validator);
        
        if ($validator->passes()) {
            if($target_amount>0 && $total_amount>0 && $target_amount==$total_amount) {


                $ticket_bill_master['aviation_id'] = $aviation_id;
                $ticket_bill_master['target_amount'] = $target_amount;
                $ticket_bill_master['remarks'] = $main_remarks;
                EwTicketBillMaster::create($ticket_bill_master);

                $ew_ticket_bill_id = EwTicketBillMaster::valid()->orderBy('id','desc')->first()->id;

                foreach($ew_project_id as $key=>$ew_project_id) {
                    $ticket_bill_details['ew_project_id'] = $ew_project_id;
                    $ticket_bill_details['candidate_id'] = $candidate_id[$key];
                    $ticket_bill_details['ew_ticket_bill_id'] = $ew_ticket_bill_id;
                    $ticket_bill_details['nos'] = $nos[$key];
                    $ticket_bill_details['bill_type'] = $bill_type[$key];
                    $ticket_bill_details['bill_amount'] = $amounts[$key];
                    $ticket_bill_details['remarks'] = $remarks[$key];
                    EwTicketBillDetails::create($ticket_bill_details);
                }
                $ticket_bill_ledger['aviation_id'] = $aviation_id;
                $ticket_bill_ledger['ew_ticket_bill_id'] = $ew_ticket_bill_id;
                $ticket_bill_ledger['bill_amount'] = $target_amount;
                $ticket_bill_ledger['payment_amount'] = 0;
                $ticket_bill_ledger['remarks'] = $main_remarks;
                EwTicketBillLedgers::create($ticket_bill_ledger);

                $output['messege'] = 'Ticket bill voucher has been created';
                $output['msgType'] = 'success';

            } else {
                    $output['messege'] = 'Target amount and Bill amount do not match.';
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
    public function ticketBillSend(Request $request)
    {   
        // DB::beginTransaction();
        $output = array();
        $id = $request->bill_id;
        $input = $request->all();
        // $bill_date = DateTime::createFromFormat('d/m/Y', $request->bill_date)->format('Y-m-d');
        $validator = Validator::make($input, [
            'send_status'            => 'required'      
        ]);
        if ($validator->passes()) {
        
            $data['bill_send_status'] = $request->send_status;
            EwTicketBillMaster::valid()->find($id)->update($data);

            //candidate table's update with ticket_bill_send_status
            // $candidate_status['ticket_bill_send_status'] = 1;
            //just for getting candidate_id
            // $candidate_id = EwTicketBillDetails::valid()->where('ew_ticket_bill_id', $id)->first()->candidate_id;

            // EwCandidates::valid()->find($candidate_id)->update($candidate_status);

        $output['messege'] = 'Bill send successfull.';
        $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
        // DB::commit();
    }

    public function ticketBillPaidStatus(Request $request)
    {   
        $output = array();
        $id = $request->bill_id;
        $input = $request->all();
        $validator = Validator::make($input, [
            'paid_status'            => 'required'      
        ]);
        if ($validator->passes()) {
        
            $data['paid_status'] = $request->paid_status;
            EwTicketBillMaster::valid()->find($id)->update($data);

        $output['messege'] = 'Bill Paid successfull.';
        $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
    }

    public function ticketBillTransferModal(Request $request)
    {
        $id = $request->data;
        $data['ticketingBill'] = EwTicketBillMaster::valid()->find($id);
        // print_r($data);
        return view('ew.ticketingBill.billTransfer', $data);
    }

    public function ticketBillTransfer(Request $request)
    {   
        DB::beginTransaction();
        $output = array();
        $id = $request->bill_id;
        $input = $request->all();
        $bill_date = DateTime::createFromFormat('d/m/Y', $request->bill_date)->format('Y-m-d');
        $bill_no = $request->bill_no;

        $validator = [
            'bill_no'            => 'required',
            'bill_date'          => 'required'  
        ];
        $validator = Validator::make($input, $validator);
        
        if ($validator->passes()) {
            if($bill_no>0) {

                $ticket_bill_master['bill_no'] = $bill_no;
                $ticket_bill_master['bill_date'] = $bill_date;
                $ticket_bill_master['bill_transfer_status']   = 1;
                EwTicketBillMaster::valid()->find($id)->update($ticket_bill_master);

                //retrive this update's row
                // $ew_ticket_bill_master = EwTicketBillMaster::valid()->where('id',$id)->first();
                $bill_master = EwTicketBillMaster::valid()->where('id',$id)->first();

                $bill_master_aviation_id = $bill_master->aviation_id;
                $bill_master_remarks = $bill_master->remarks;
                $bill_master_target_amount = $bill_master->target_amount;

                //Transaction table insert
                $voucher_type = 'JV';
                $transaction_status = 5; //5 for journal voucher
                
                $transaction = Helper::getTransactionInstrumentNo($transaction_status);
                $ticket_acc_code = EwAccountConfiguration::valid()->where('particular','account_code')->first()->account_code;

                $aviation_acc_code = EwAviation::valid()->find($bill_master_aviation_id)->account_code;

                $acc_transaction['transaction_no'] = $transaction['transaction_no'];
                $acc_transaction['instrument_no'] = $transaction['instrument_no'];
                $acc_transaction['voucher_type'] = $voucher_type;
                $acc_transaction['account_code'] = $ticket_acc_code;
                $acc_transaction['contra_account_code'] = $aviation_acc_code;
                $acc_transaction['transaction_date'] = date('Y-m-d');
                $acc_transaction['transaction_status'] = $transaction_status;
                $acc_transaction['debit_amount'] = $bill_master_target_amount;
                $acc_transaction['credit_amount'] = 0;
                $acc_transaction['remarks'] = $bill_master_remarks;
                //Create Debit Transaction
                EwAccountTransaction::create($acc_transaction);

                //Create Credit Transaction
                $acc_transaction['account_code'] = $aviation_acc_code;
                $acc_transaction['contra_account_code'] = $ticket_acc_code;
                $acc_transaction['debit_amount'] = 0;
                $acc_transaction['credit_amount'] = $bill_master_target_amount;
                EwAccountTransaction::create($acc_transaction);

                $acc_transaction_transaction_no = EwAccountTransaction::valid()->orderBy('id', 'desc')->first()->transaction_no;
                
                $bill_ledger['transaction_no'] = $acc_transaction_transaction_no;
                $bill_ledger['transaction_date'] = date('Y-m-d');
                EwTicketBillLedgers::valid()->where('ew_ticket_bill_id', $id)->update($bill_ledger);

                $output['messege'] = 'Ticket bill voucher has been created';
                $output['msgType'] = 'success';

            } else {
                    $output['messege'] = 'Target amount and Bill amount do not match.';
                    $output['msgType'] = 'danger';
            }
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
        DB::commit();
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

    //PROJECT WISE CANDIDATES
    public function projectWiseCandidates(Request $request)
    {
        $data['inputData'] = $request->all();
        $ew_project_id = $request->ew_project_id;
        
        $candidate_array = EwCandidates::join('ew_ticket_bill_details', 'ew_ticket_bill_details.candidate_id', '=', 'ew_candidates.id')
                ->select('ew_candidates.id')
                ->where('ew_candidates.ew_project_id', $ew_project_id)
                ->where('ew_candidates.flight_status', 1)
                ->where('ew_candidates.valid', 1)
                ->get()
                ->pluck('id')
                ->all();

        $candidate_array = array_unique($candidate_array);


        $projectCandidates = EwCandidates::valid()
                            ->whereNotIn('id', $candidate_array)
                            ->where('ew_project_id', $ew_project_id)
                            ->where('flight_status', 1)
                            ->get();

        $html = "<option value=''> Select Candidate Name </option>";
        foreach($projectCandidates as $Candidate) {
            $html .= "<option value=\"$Candidate->id\"> $Candidate->candidate_id - $Candidate->candidate_name </option>";
        }
        return $html;
    }

    //Report view pdf
    public function ticketBillReport(Request $request) {
        $bill_foreign_id = $param['bill_foreign_id'] = $request->bill_foreign_id;

        $data = self::getAccountsPayable($param);
        $pdf_url = route('ew.ticketBillPDF', $param);

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.ticketingBill.aviationBillReport', $data);
    }

    public function ticketBillPDF(Request $request) {
        $param['bill_foreign_id'] = $request->bill_foreign_id;
        // $param['account_code'] = $request->account;

        $data = self::getAccountsPayable($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.ticketingBill.aviationBillReport', $data);
        $file_name = 'accounts-payable-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getAccountsPayable($param) {
        $bill_foreign_id = $data['bill_foreign_id'] = $param['bill_foreign_id'];
        $data['ledgers'] = EwTicketBillDetails::join("ew_ticket_bill_master", "ew_ticket_bill_details.ew_ticket_bill_id", "=", "ew_ticket_bill_master.id")
            ->join("ew_aviations", "ew_ticket_bill_master.aviation_id", "=", "ew_aviations.id")
            ->join("ew_projects", "ew_ticket_bill_details.ew_project_id", "=", "ew_projects.id")
            ->join("ew_aviation_bill_types", "ew_ticket_bill_details.bill_type", "=", "ew_aviation_bill_types.id")
            ->join("ew_candidates", "ew_ticket_bill_details.candidate_id", "=", "ew_candidates.id")
            ->select("ew_ticket_bill_details.*", "ew_aviations.company_name", "ew_aviations.account_code", "ew_projects.project_name", "ew_ticket_bill_master.bill_transfer_status", "ew_ticket_bill_master.bill_date", "ew_ticket_bill_master.bill_no", "ew_ticket_bill_master.bill_send_status", "ew_aviation_bill_types.type_name", "ew_candidates.candidate_name")
            ->where('ew_ticket_bill_master.id', '=', $bill_foreign_id)
            ->get();

        $data['aviation_name'] = EwTicketBillMaster::join('ew_aviations', 'ew_aviations.id','=', 'ew_ticket_bill_master.aviation_id')
                ->select('ew_ticket_bill_master.aviation_id', 'ew_aviations.company_name')
                ->where('ew_ticket_bill_master.id', $bill_foreign_id)
                ->first()->company_name;

        return $data;
    }
}
