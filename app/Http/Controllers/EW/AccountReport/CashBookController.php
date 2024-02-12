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

class CashBookController extends Controller
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

        return view('ew.reports.cashBook.index', $data);
    }

    public function cashBook(Request $request) {
        $project_id = $param['project_id'] = $request->project;
        $account_code = $param['account_code'] = $request->account;
        $date_range = $param['date_range'] = $request->date_range;
        $from_date = $param['from_date'] = $request->from_date;
        $to_date = $param['to_date'] = $request->to_date;

        $data = self::getCashBook($param);
        $pdf_url = route('ew.cashBookPDF').'?project='.$project_id.'&account='.$account_code.'&date_range='.$date_range.'&from_date='.$from_date.'&to_date='.$to_date;

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.reports.cashBook.report', $data);
    }

    public function cashBookPDF(Request $request) {
        $param['project_id'] = $request->project;
        $param['account_code'] = $request->account;
        $param['date_range'] = $request->date_range;
        $param['from_date'] = $request->from_date;
        $param['to_date'] = $request->to_date;

        $data = self::getCashBook($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.reports.cashBook.report', $data);
        $file_name = 'cash-book-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getCashBook($param) {
        $project_id = $data['project_id'] = $param['project_id'];
        $account_code = $param['account_code'];
        $date_range = $param['date_range'];
        $from_date = $data['from_date'] = $param['from_date'];
        $to_date = $data['to_date'] = $param['to_date'];

        if($date_range) {
            $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
            $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
            $data['opening_balance'] = EwAccountTransaction::valid()
                        ->select(DB::raw('sum(debit_amount) as total_debit, sum(credit_amount) as total_credit'))
                        ->where(function($query) use ($project_id, $from_date) {
                            if($project_id!=Null) {
                                $query->where('ew_project_id', $project_id);
                            }
                            $query->where('transaction_date', '<', $from_date);
                        })
                        ->where('account_code', $account_code)
                        ->first();
            $data['opening_date'] = date('Y-m-d', strtotime('-1 day', strtotime($from_date)));
        }

        $data['account'] = EwChartOfAccounts::valid()->where('account_code', $account_code)->first();
        //$data['chart_of_accounts'] = EwChartOfAccounts::Valid()->where('account_level', 4)->get()->keyBy('account_code');

        $data['ledgers'] = EwAccountTransaction::valid()
                        ->where(function($query) use ($project_id, $date_range, $from_date, $to_date) {
                            if($project_id!=Null) { $query->where('ew_project_id', $project_id); }
                            if($date_range) {
                                $query->whereBetween('transaction_date', [$from_date, $to_date]);
                            }
                        })
                        ->where('account_code', $account_code)
                        ->get();
        return $data;
    }

}
