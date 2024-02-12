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

class AccountsReceivableController extends Controller
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
        return view('ew.reports.accountsReceivable.index', $data);
    }

    public function accountsReceivable(Request $request) {
        $project_id = $param['project_id'] = $request->project;
        $date_range = $param['date_range'] = $request->date_range;
        $date = $param['date'] = $request->date;

        $data = self::getAccountsReceivable($param);
        $pdf_url = route('ew.accountsReceivablePDF', $param);

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.reports.accountsReceivable.report', $data);
    }

    public function accountsReceivablePDF(Request $request) {
        $param['project_id'] = $request->project;
        $param['account_code'] = $request->account;
        $param['date_range'] = $request->date_range;
        $param['date'] = $request->date;

        $data = self::getAccountsReceivable($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.reports.accountsReceivable.report', $data);
        $file_name = 'accounts-receivable-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getAccountsReceivable($param) {
        $project_id = $data['project_id'] = $param['project_id'];
        $date_range = $data['date_range'] = $param['date_range'];
        $date = $data['date'] = $param['date'];
        if($date_range) { $date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d'); }

        $acc_receivable_code = EwAccountConfiguration::valid()->where('particular', 'acc_receivable')->first()->account_code;
        $acc_receivable_control = substr($acc_receivable_code,0,4);
        $data['chart_of_accounts'] = EwChartOfAccounts::Valid()->where('account_level', 4)->where('upper_control_code', $acc_receivable_control)->get()->keyBy('account_code');

        $data['ledgers'] = EwAccountTransaction::valid()
            ->select('*', DB::raw('sum(debit_amount) as debit_amount, sum(credit_amount) as credit_amount'))
            ->where(function($query) use ($project_id, $date_range, $date) {
                if($project_id!=Null) { $query->where('ew_project_id', $project_id); }
                if($date_range) { $query->where('transaction_date', '<=', $date); }
            })
            ->where(DB::raw('SUBSTR(account_code,1,4)'), $acc_receivable_control)
            ->groupBy('account_code')
            ->orderBy('account_code')
            ->get();
        return $data;
    }

}
