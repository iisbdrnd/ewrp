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

class TrialBalanceController extends Controller
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
        return view('ew.reports.trialBalance.index', $data);
    }

    public function trialBalance(Request $request) {
        $project_id = $param['project_id'] = $request->project;
        $account_level = $param['account_level'] = $request->account_level;
        $date_range = $param['date_range'] = $request->date_range;
        $from_date = $param['from_date'] = $request->from_date;
        $to_date = $param['to_date'] = $request->to_date;

        $data = self::getTrialBalance($param);
        $pdf_url = route('ew.trialBalancePDF').'?project='.$project_id.'&account_level='.$account_level.'&date_range='.$date_range.'&from_date='.$from_date.'&to_date='.$to_date;

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.reports.trialBalance.report', $data);
    }

    public function trialBalancePDF(Request $request) {
        $param['project_id'] = $request->project;
        $param['account_level'] = $request->account_level;
        $param['date_range'] = $request->date_range;
        $param['from_date'] = $request->from_date;
        $param['to_date'] = $request->to_date;

        $data = self::getTrialBalance($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.reports.trialBalance.report', $data);
        $file_name = 'trial-balance-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getTrialBalance($param) {
        $project_id = $data['project_id'] = $param['project_id'];
        $account_level = $data['account_level'] = $param['account_level'];
        $date_range = $data['date_range'] = $param['date_range'];
        $from_date = $data['from_date'] = $param['from_date'];
        $to_date = $data['to_date'] = $param['to_date'];

        $trial_balances = EwAccountTransaction::join('ew_chart_of_accounts', function($join) {
                $join->on('ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
                    ->on('ew_chart_of_accounts.valid', '=', DB::raw(1));
            })
            ->select(DB::raw('sum(ew_account_transaction.debit_amount) as debit_amount, sum(ew_account_transaction.credit_amount) as credit_amount'), 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.main_code', 'ew_chart_of_accounts.classified_code', 'ew_chart_of_accounts.control_code', 'ew_chart_of_accounts.account_head')
            ->where(function($query) use ($project_id, $date_range, $from_date, $to_date) {
                if($project_id!=Null) {
                    $query->where('ew_account_transaction.ew_project_id', $project_id);
                }
                if($date_range) {
                    $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
                    $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
                    $query->whereBetween('ew_account_transaction.transaction_date', [$from_date, $to_date]);
                }
            })
            ->where('ew_account_transaction.valid', 1)
            ->orderBy('account_code', 'asc');

        if($account_level==1) {
            $trial_balances->groupBy('ew_chart_of_accounts.main_code');
        } else if($account_level==2) {
            $trial_balances->groupBy('ew_chart_of_accounts.main_code')
                ->groupBy('ew_chart_of_accounts.classified_code');
        } else if($account_level==3) {
            $trial_balances->groupBy('ew_chart_of_accounts.main_code')
                ->groupBy('ew_chart_of_accounts.classified_code')
                ->groupBy('ew_chart_of_accounts.control_code');
        } else {
            $trial_balances->groupBy('ew_account_transaction.account_code');
        }
        if($account_level<4) {
            $data['accHead'] = EwChartOfAccounts::valid()->select('account_code', 'account_head')->where('account_level', $account_level)->get()->keyBy('account_code');
        }

        $data['trial_balances'] = $trial_balances->get();
        return $data;
    }

}
