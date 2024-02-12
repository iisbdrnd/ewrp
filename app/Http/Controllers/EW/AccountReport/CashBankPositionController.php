<?php

namespace App\Http\Controllers\EW\AccountReport;

use PDF;

use DateTime;
use App\Http\Requests;
use App\Model\EwProjects;
use Illuminate\Http\Request;
use App\Model\EwChartOfAccounts;
use Illuminate\Support\Facades\DB;
use App\Model\EwAccountTransaction;
use App\Http\Controllers\Controller;
use App\Model\EwCandidateTransaction;
use App\Model\EwCollectableAccountHeads;
use App\Model\EwProjectCollectableSelection;

class CashBankPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['projects'] = EwProjects::valid()->get();
        return view('ew.reports.cashBankPosition.cash-bank-position', $data);
    }

    public function cashBankPositionReport(Request $request)
    {
        $project_id    = $param['project_id']    = $request->project;
        $account_level = $param['account_level'] = $request->account_level;
        $date_range    = $param['date_range']    = $request->date_range;
        $from_date     = $param['from_date']     = $request->from_date;
        $to_date       = $param['to_date']       = $request->to_date;

        if ($date_range) {
        $fromDate = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');

        $previous_date = $param['previous_date'] = date('Y-m-d', strtotime($fromDate .' -1 day'));
        } else {
            $previous_date = $param['previous_date'] = 0;
        }
        

        $data = self::cashBankPosition($param);

        $pdf_url = route('ew.cash-bank-position-report-pdf').'?project='.$project_id.'&date_range='.$date_range. '&from_date='.$from_date. '&to_date='.$to_date. '&previous_date='.$previous_date;
        $data = array_merge($data, ['pdf_url' => $pdf_url]);

        return view('ew.reports.cashBankPosition.cash-bank-position-report', $data);
    }

    public function cashBankPosition($param)
    {
        $project_id    = $data['project_id']    = $param['project_id'];
        $account_level = $data['account_level'] = $param['account_level'];
        $date_range    = $data['date_range']    = $param['date_range'];
        $from_date     = $data['from_date']     = $param['from_date'];
        $to_date       = $data['to_date']       = $param['to_date'];
        $previous_date = $data['previous_date'] = $param['previous_date'];

        
        $data['opening_balances'] = EwAccountTransaction::valid()->where(function($query) use ($project_id, $date_range, $previous_date) {
                if($project_id!=Null) {
                    $query->where('ew_project_id', $project_id);
                }
                if($date_range) {
                    $query->where('transaction_date', $previous_date);
                }
            })
            ->select(
                DB::raw('SUM(debit_amount) - SUM(credit_amount) as opening_balance')  
            )
            ->orderBy('account_code', 'asc')->get();

        $cash_bank_position = EwAccountTransaction::valid()->where(function($query) use ($project_id, $date_range, $from_date, $to_date) {
                if($project_id!=Null) {
                    $query->where('ew_project_id', $project_id);
                }
                if($date_range) {
                    $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
                    $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
                    $query->whereBetween('transaction_date', [$from_date, $to_date]);
                }
            })
            ->select(
                DB::raw('SUM(debit_amount) as receive'),
                DB::raw('SUM(credit_amount) as payment'),
                'ew_project_id'
                
            );

        $data['cash_bank_position'] = $cash_bank_position->get();
        
        if ($project_id == 0) {
            $data['account_heads'] = $account_heads = EwProjectCollectableSelection::valid()->groupBy('collectable_account_id')->get();

        } else {

            $data['account_heads'] = $account_heads = EwProjectCollectableSelection::valid()->where('ew_project_id', $project_id)->groupBy('collectable_account_id')->get();
        }

        $dt = [];
        foreach ($account_heads as $key => $account_head) {
        $dt[] = $account_head;
            $cash_in_flow = EwCandidateTransaction::join('ew_chart_of_accounts', 'ew_chart_of_accounts.id', '=', 'ew_candidate_transaction.collectable_account')
            ->join('ew_account_transaction' ,'ew_account_transaction.id', '=', 'ew_candidate_transaction.account_transaction_no')
            ->where('ew_candidate_transaction.collectable_account', $account_head->collectable_account_id)
            ->where(function($query) use($project_id, $date_range, $from_date, $to_date, $previous_date){
                if($project_id!= Null || $project_id != 0) {
                    $query->where('ew_account_transaction.ew_project_id', $project_id);
                }
                if($date_range) {
                    $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
                    $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
                    $query->whereBetween('ew_candidate_transaction.transaction_date', [$from_date, $to_date]);
                }
            })
            ->select(DB::raw('SUM(ew_candidate_transaction.received_amount) as received'))
            ->get();
            $account_head->cash_in_flow = $cash_in_flow;
        }

        // dd($dt);

        $data['cash_out_account_heads'] = $cash_out_account_heads = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_chart_of_accounts.account_code', '=', 'ew_account_transaction.account_code')
         ->where('ew_chart_of_accounts.main_code', 4)
         ->where(function($query) use($project_id){
             if ($project_id != null || $project_id != 0){
                 $query->where('ew_account_transaction.ew_project_id', $project_id);
             }
         })
        ->select('ew_account_transaction.*', 'ew_chart_of_accounts.account_head', 'ew_chart_of_accounts.main_code')->groupBy('ew_account_transaction.account_code')->get();

        $dt1 = [];
        foreach ($cash_out_account_heads as $key => $cash_out_account_head) {
            $dt1[] = $cash_out_account_head;
        $cash_out_account_head->cash_out_flows = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_chart_of_accounts.account_code', '=', 'ew_account_transaction.account_code')
        ->where('ew_chart_of_accounts.main_code', 4)
        ->where('ew_account_transaction.account_code', $cash_out_account_head->account_code)
        ->where(function($query) use($project_id, $date_range, $from_date, $to_date, $previous_date){
               if($project_id != Null || $project_id != 0) {
                    $query->where('ew_account_transaction.ew_project_id', $project_id);
                }
                if($date_range) {
                    $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
                    $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
                    $query->whereBetween('ew_account_transaction.transaction_date', [$from_date, $to_date]);
                }
            })
            ->select(
                'ew_chart_of_accounts.*' , DB::raw('SUM(ew_account_transaction.debit_amount) as payment'),
                'ew_account_transaction.debit_amount' 
                )
           ->get(); 
        }

        $data['bank_account_heads'] = $bank_account_heads = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_chart_of_accounts.account_code', '=', 'ew_account_transaction.account_code')
         ->where('ew_chart_of_accounts.main_code', 2)
         ->where('ew_chart_of_accounts.general_control_code', 2202)
         ->where(function($query) use($project_id){
             if ($project_id != NULL || $project_id != 0){
                 $query->where('ew_account_transaction.ew_project_id', $project_id);
             }
         })
        ->select('ew_account_transaction.*', 'ew_chart_of_accounts.account_head', 'ew_chart_of_accounts.main_code')->groupBy('ew_account_transaction.account_code')->get();
        
        foreach ($bank_account_heads as $bank_account_head) {
          $data['opening'] = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_chart_of_accounts.account_code', '=', 'ew_account_transaction.account_code')
        ->where('ew_chart_of_accounts.main_code', 2)
        ->where('ew_chart_of_accounts.general_control_code', 2202)
        ->where('ew_account_transaction.account_code', $bank_account_head->account_code)
          ->where(function($query) use ($project_id, $date_range, $previous_date) {
                if($project_id!=Null) {
                    $query->where('ew_account_transaction.ew_project_id', $project_id);
                }
                if($date_range) {
                    $query->where('ew_account_transaction.transaction_date', $previous_date);
                }
            })
            ->select(
                DB::raw('SUM(ew_account_transaction.debit_amount) - SUM(ew_account_transaction.credit_amount) as opening')  
            )
            ->get();

        $bank_account_head->bank_position = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_chart_of_accounts.account_code', '=', 'ew_account_transaction.account_code')
        ->where('ew_chart_of_accounts.main_code', 2)
        ->where('ew_chart_of_accounts.general_control_code', 2202)
        ->where('ew_account_transaction.account_code', $bank_account_head->account_code)
        ->where(function($query) use($project_id, $date_range, $from_date, $to_date, $previous_date){
               if($project_id != Null || $project_id != 0) {
                    $query->where('ew_account_transaction.ew_project_id', $project_id);
                }
                if($date_range) {
                    $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
                    $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
                    $query->whereBetween('ew_account_transaction.transaction_date', [$from_date, $to_date]);
                }
            })
            ->select(
                DB::raw('SUM(ew_account_transaction.debit_amount) as debit'),
                DB::raw('SUM(ew_account_transaction.credit_amount) as credit'),
                'ew_account_transaction.debit_amount'
                )
           ->get();
           
        }
        // dd($dt1);
        return $data;
    }


    public function cashBankPositionReportPDF(Request $request)
    {
        $param['project_id']    = $request->project;
        $param['account_level'] = $request->account_level;
        $param['date_range']    = $request->date_range;
        $param['from_date']     = $request->from_date;
        $param['to_date']       = $request->to_date;
        $param['previous_date'] = $request->previous_date;

        $data      = self::cashBankPosition($param);
        $data      = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf       = PDF::loadView('ew.reports.cashBankPosition.cash-bank-position-report', $data);
        $file_name = 'cash-bank-position-report-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
