<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use DateTime;
use Collection;
use DateInterval;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwAccountConfiguration;
use App\Model\EwProjects;
use App\Model\EwTrades;
use App\Model\EwCandidates;
use App\Model\EwReferences;
use App\Model\EwAccountTransaction;
use App\Model\EwFlights;

class DashboardController extends Controller {
    //dashboard
    public function index(Request $request) {
        $data['module'] = $module = (!empty($request->module)) ? $request->module:0;
        $data['projects'] = EwProjects::valid()->count();
    	$data['candidates'] = EwCandidates::valid()->count();
        $data['references'] = EwReferences::valid()->count();
        $data['trades'] = EwTrades::valid()->count();

        //Todays Voucher
        if($module==0 || $module==1){
            $acc_receivable_code = EwAccountConfiguration::valid()->where('particular', 'acc_receivable')->first()->account_code;
            $acc_receivable_control = substr($acc_receivable_code,0,4);
            $receivable = EwAccountTransaction::valid()
                ->select(DB::raw('sum(debit_amount) as debit_amount, sum(credit_amount) as credit_amount'))
                ->where(DB::raw('SUBSTR(account_code,1,4)'), $acc_receivable_control)
                ->first();
            $data['receivable_amount'] = $receivable->debit_amount-$receivable->credit_amount;

            $acc_payable_code = EwAccountConfiguration::valid()->where('particular', 'acc_payable')->first()->account_code;
            $acc_payable_control = substr($acc_payable_code,0,4);

            $payable = EwAccountTransaction::valid()
                ->select(DB::raw('sum(debit_amount) as debit_amount, sum(credit_amount) as credit_amount'))
                ->where(DB::raw('SUBSTR(account_code,1,4)'), $acc_payable_control)
                ->first();
            $data['payable_amount'] = $payable->credit_amount-$payable->debit_amount;

            $current_date = date('Y-m-d');
            $data['voucher_names'] = ['Payment', 'Received', 'Bank Payment', 'Bank Received', 'Journal'];
            $data['todays_voucher'] = EwAccountTransaction::valid()
                ->select(DB::raw('sum(debit_amount) as debit_amount, sum(credit_amount) as credit_amount'), 'transaction_status')
                ->where('transaction_date', $current_date)
                ->groupBy('transaction_status')
                ->get()
                ->keyBy('transaction_status')
                ->all();
        }
        //Flights(Current Month)
        if($module==0 || $module==4){
            $data['firstDay'] = $firstDay = date('Y-m-d',strtotime("first day of this month"));
            $lastDay = date('Y-m-d',strtotime("last day of this month"));
            $data['flights'] = EwFlights::valid()
                ->select(DB::raw('count(id) as flight_qty'), 'flight_date')
                ->where('flight_status', 1)
                ->whereBetween('flight_date',[$firstDay, $lastDay])
                ->groupBy('flight_date')
                ->get()
                ->keyBy('flight_date')
                ->all();
        }
        //Ongoing Project
        if($module==0 || $module==3){
            $data['ongoingProject'] = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                ->select('ew_projects.project_name', DB::raw('count(ew_candidates.id) as candidateQty, sum(ew_candidates.flight_status) as flightQty'))
                ->where('ew_projects.valid', 1)
                ->where('ew_candidates.valid', 1)
                ->groupBy('ew_candidates.ew_project_id')
                ->orderBy('project_name', 'asc')
                ->get();
        }
        //Incomes & Expenses
        if($module==0 || $module==2){
            $data['firstDay'] = $firstDay = date('Y-m-d',strtotime("first day of this month"));
            $lastDay = date('Y-m-d',strtotime("last day of this month"));
            $data['incomes'] = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
                ->select('ew_account_transaction.transaction_date', DB::raw('(sum(ew_account_transaction.credit_amount)-sum(ew_account_transaction.debit_amount)) as incBalance'))
                ->whereBetween('ew_account_transaction.transaction_date',[$firstDay, $lastDay])
                ->where('ew_chart_of_accounts.main_code', 3)
                ->where('ew_account_transaction.valid', 1)
                ->groupBy('ew_account_transaction.transaction_date')
                ->get()
                ->keyBy('transaction_date')
                ->all();

            $data['expenses'] = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
                ->select('ew_account_transaction.transaction_date', DB::raw('(sum(ew_account_transaction.debit_amount)-sum(ew_account_transaction.credit_amount)) as expBalance'))
                ->whereBetween('ew_account_transaction.transaction_date',[$firstDay, $lastDay])
                ->where('ew_chart_of_accounts.main_code', 4)
                ->where('ew_account_transaction.valid', 1)
                ->groupBy('ew_account_transaction.transaction_date')
                ->get()
                ->keyBy('transaction_date')
                ->all();
        }        

        return view('ew.dashboard.index', $data);
    }

    public function onGoingProjects(Request $request) {
    	$data['inputData'] = $request->all();

        return view('ew.project.list', $data);
    }

    public function totalCandidates(Request $request) {
        $data['inputData'] = $request->all();

        return view('ew.candidateInfo.list', $data);
    }

    public function totalReferences(Request $request) {
        $data['inputData'] = $request->all();

        return view('ew.reference.list', $data);
    }

    public function totalTrades(Request $request) {
        $data['inputData'] = $request->all();

        return view('ew.trades.list', $data);
    }

}
