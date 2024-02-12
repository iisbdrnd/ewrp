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
use App\Model\EwProjects;
use App\Model\EwChartOfAccounts;
use App\Model\EwAccountConfiguration;
use App\Model\EwCandidateTransaction;
use App\Model\EwAccountTransaction;

class TransactionPostingController extends Controller
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
        return view('ew.transactionPosting.index', $data);
    }

    public function transactionPosting(Request $request) {
        $project_id = $param['project_id'] = $request->project;
        $date_range = $param['date_range'] = $request->date_range;
        $from_date = $param['from_date'] = $request->from_date;
        $to_date = $param['to_date'] = $request->to_date;

        $data = self::getTransactionPosting($param);
        $pdf_url = route('ew.transactionPostingPDF').'?project='.$project_id.'&date_range='.$date_range.'&from_date='.$from_date.'&to_date='.$to_date;

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.transactionPosting.report', $data);
    }

    public function transactionPostingPDF(Request $request) {
        $param['project_id'] = $request->project;
        $param['date_range'] = $request->date_range;
        $param['from_date'] = $request->from_date;
        $param['to_date'] = $request->to_date;

        $data = self::getTransactionPosting($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.transactionPosting.report', $data);
        $file_name = 'transaction-posting-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getTransactionPosting($param) {
        $project_id = $data['project_id'] = $param['project_id'];
        $date_range = $data['date_range'] = $param['date_range'];
        $from_date = $data['from_date'] = $param['from_date'];
        $to_date = $data['to_date'] = $param['to_date'];

        $data['transactions'] = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
            ->select('ew_account_transaction.*', 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.account_head')
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
            ->orderBy('id', 'asc')
            ->get();

        return $data;
    }

}
