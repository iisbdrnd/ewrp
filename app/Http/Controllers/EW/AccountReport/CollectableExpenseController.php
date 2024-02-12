<?php

namespace App\Http\Controllers\EW\AccountReport;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use PDF;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwProjects;
use App\Model\EwChartOfAccounts;
use App\Model\EwAccountConfiguration;
use App\Model\EwCandidateTransaction;
use App\Model\EwAccountTransaction;

class CollectableExpenseController extends Controller
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
        return view('ew.reports.collectableExpense.index', $data);
    }

    public function collectableExpense(Request $request) {
        $param = $request->all();

        $data = self::getCollectableExpense($param);
        $pdf_url = route('ew.collectableExpensePDF', $param);

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.reports.collectableExpense.report', $data);
    }

    public function collectableExpensePDF(Request $request) {
        $param = $request->all();

        $data = self::getCollectableExpense($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.reports.collectableExpense.report', $data);
        $file_name = 'collectable-expense-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getCollectableExpense($param) {
        $data = $param;
        $project_id = $param['project'];
        $group_report = $param['group_report'];
        $date_range = $param['date_range'];
        $date=''; $from_date=''; $to_date='';
        if($date_range==1) {
            $date = DateTime::createFromFormat('d/m/Y', $param['date'])->format('Y-m-d');
        } else if($date_range==2) {
            $from_date = DateTime::createFromFormat('d/m/Y', $param['from_date'])->format('Y-m-d');
            $to_date = DateTime::createFromFormat('d/m/Y', $param['to_date'])->format('Y-m-d');
        }

        $process_cost = EwAccountConfiguration::valid()->where('particular', 'process_cost')->first()->account_code;
        $process_cost_control = substr($process_cost,0,4);

        if($group_report==1) {
            $data['projects'] = EwProjects::valid()->orderBy('project_name', 'asc')->get();
        }

        $ledgers = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
            ->select(DB::raw('sum(ew_account_transaction.debit_amount)-sum(ew_account_transaction.credit_amount) as expAmount'), 'ew_account_transaction.ew_project_id', 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.account_head')
            ->where(function($query) use ($project_id, $date_range, $date, $from_date, $to_date) {
                if($project_id!=Null) { $query->where('ew_account_transaction.ew_project_id', $project_id); }
                if($date_range==1) { $query->where('ew_account_transaction.transaction_date', '<=', $date); }
                else if($date_range==2) { $query->whereBetween('ew_account_transaction.transaction_date', [$from_date, $to_date]); }
            })
            ->where(DB::raw('SUBSTR(ew_account_transaction.account_code,1,4)'), $process_cost_control)
            ->where('ew_account_transaction.valid', 1);
        if($group_report==1) {
            $ledgers = $ledgers->groupBy('ew_account_transaction.ew_project_id');
        }
        $ledgers = $ledgers->groupBy('ew_account_transaction.account_code')
            ->orderBy('account_code', 'asc')
            ->get();
        $data['ledgers'] = ($group_report==1) ? $ledgers->groupBy('ew_project_id')->all() : $ledgers;

        return $data;
    }

}
