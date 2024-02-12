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

class CollectableIncomeController extends Controller
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
        return view('ew.reports.collectableIncome.index', $data);
    }

    public function collectableIncome(Request $request) {
        $param = $request->all();

        $data = self::getCollectableIncome($param);
        $pdf_url = route('ew.collectableIncomePDF', $param);

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.reports.collectableIncome.report', $data);
    }

    public function collectableIncomePDF(Request $request) {
        $param = $request->all();

        $data = self::getCollectableIncome($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.reports.collectableIncome.report', $data);
        $file_name = 'collectable-income-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getCollectableIncome($param) {
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

        $candidate_income = EwAccountConfiguration::valid()->where('particular', 'candidate_income')->first()->account_code;

        if($group_report==1) {
            $data['projects'] = EwProjects::valid()->orderBy('project_name', 'asc')->get();
        }

        $ledgers = EwAccountTransaction::join('ew_collectable_account_heads', 'ew_account_transaction.collectable_account', '=', 'ew_collectable_account_heads.id')
            ->select(DB::raw('sum(ew_account_transaction.credit_amount)-sum(ew_account_transaction.debit_amount) as incAmount'), 'ew_account_transaction.ew_project_id', 'ew_collectable_account_heads.account_head')
            ->where(function($query) use ($project_id, $date_range, $date, $from_date, $to_date) {
                if($project_id!=Null) { $query->where('ew_project_id', $project_id); }
                if($date_range==1) { $query->where('transaction_date', '<=', $date); }
                else if($date_range==2) { $query->whereBetween('transaction_date', [$from_date, $to_date]); }
            })
            ->where('ew_account_transaction.account_code', $candidate_income)
            ->where('ew_account_transaction.valid', 1);
        if($group_report==1) {
            $ledgers = $ledgers->groupBy('ew_account_transaction.ew_project_id');
        }
        $ledgers = $ledgers->groupBy('ew_account_transaction.collectable_account')
            ->orderBy('account_head', 'asc')
            ->get();
        $data['ledgers'] = ($group_report==1) ? $ledgers->groupBy('ew_project_id')->all() : $ledgers;

        return $data;
    }

}
