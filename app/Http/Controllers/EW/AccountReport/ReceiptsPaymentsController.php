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

class ReceiptsPaymentsController extends Controller
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
            ->where('general_control_code', $upper_control_code)
            ->orderBy('account_code')
            ->get();
        //get bank upper_control_code
        $bank_account = EwAccountConfiguration::valid()->where('particular', 'bank')->first();
        $upper_control_code = substr($bank_account->account_code, 0, 4);
        $data['bank_accounts'] = EwChartOfAccounts::Valid()
            ->where('general_control_code', $upper_control_code)
            ->orderBy('account_code')
            ->get();

        return view('ew.reports.receiptsPayments.index', $data);
    }

    public function receiptsPayments(Request $request) {
        $param = $request->all();
        $data = self::getReceiptsPayments($param);
        $pdf_url = route('ew.receiptsPaymentsPDF', $param);

        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.reports.receiptsPayments.report', $data);
    }

    public function receiptsPaymentsPDF(Request $request) {
        $param = $request->all();
        $data = self::getReceiptsPayments($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.reports.receiptsPayments.report', $data);
        $file_name = 'receipts-payments-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getReceiptsPayments($param) {
        $data = $param;
        $project_id = $param['project'];
        $account_type = $param['account_type'];
        $cash = $param['cash'];
        $bank = $param['bank'];
        $from_date =  DateTime::createFromFormat('d/m/Y', $param['from_date'])->format('Y-m-d');
        $to_date = DateTime::createFromFormat('d/m/Y', $param['to_date'])->format('Y-m-d');

        $account=''; $cash_account_control=''; $bank_account_control='';
        if($account_type==1) {
            $account = $data['account'] = EwChartOfAccounts::valid()->where('account_code', $cash)->first();
        } else if($account_type==2) {
            $account = $data['account'] = EwChartOfAccounts::valid()->where('account_code', $bank)->first();
        } else {
            //Get cash upper_control_code
            $cash_account = EwAccountConfiguration::valid()->where('particular', 'cash')->first()->account_code;
            $cash_account_control = substr($cash_account,0,4);
            //Get bank upper_control_code
            $bank_account = EwAccountConfiguration::valid()->where('particular', 'bank')->first()->account_code;
            $bank_account_control = substr($bank_account,0,4);
        }

        $data['opening_balance'] = self::getReceiptsPaymentsData($project_id, $cash, $bank, $cash_account_control, $bank_account_control, $account, $account_type, 'contra_account_code', $from_date);
        $data['ledgers'] = self::getReceiptsPaymentsData($project_id, $cash, $bank, $cash_account_control, $bank_account_control, $account, $account_type, 'account_code', $from_date, $to_date);
        $data['closing_balance'] = self::getReceiptsPaymentsData($project_id, $cash, $bank, $cash_account_control, $bank_account_control, $account, $account_type, 'contra_account_code', $to_date);

        return $data;
    }

    public static function getReceiptsPaymentsData($project_id, $cash, $bank, $cash_account_control, $bank_account_control, $account, $account_type, $groupBy, $date1, $date2=false) {
        $data = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.'.$groupBy, '=', 'ew_chart_of_accounts.account_code')
            ->select(DB::raw('sum(ew_account_transaction.debit_amount) as total_debit, sum(ew_account_transaction.credit_amount) as total_credit'), 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.account_head')
            ->where(function($query) use ($project_id, $cash, $bank, $cash_account_control, $bank_account_control, $account, $account_type, $date1, $date2) {
                if($project_id!=Null) {
                    $query->where('ew_account_transaction.ew_project_id', $project_id);
                }
                if($date2) {
                    $query->whereBetween('ew_account_transaction.transaction_date', [$date1, $date2]);
                } else {
                    $query->where('ew_account_transaction.transaction_date', '<', $date1);
                }
                //Acc Type
                if($account_type==1) {
                    if($account->account_level==3) {
                        $cash_account_control = substr($cash,0,4);
                        $query->where(DB::raw('SUBSTR(ew_account_transaction.contra_account_code,1,4)'), $cash_account_control);
                    } else {
                        $query->where('ew_account_transaction.contra_account_code', $cash);
                    }
                } else if($account_type==2) {
                    if($account->account_level==3) {
                        $bank_account_control = substr($bank,0,4);
                        $query->where(DB::raw('SUBSTR(ew_account_transaction.contra_account_code,1,4)'), $bank_account_control);
                    } else {
                        $query->where('ew_account_transaction.contra_account_code', $bank);
                    }
                } else {
                    $query->where(function($query2) use ($cash_account_control, $bank_account_control) {
                        $query2->where(DB::raw('SUBSTR(ew_account_transaction.contra_account_code,1,4)'), $cash_account_control)
                            ->orWhere(DB::raw('SUBSTR(ew_account_transaction.contra_account_code,1,4)'), $bank_account_control);
                    });
                }
            })
            ->where('ew_account_transaction.valid', 1)
            ->groupBy('ew_account_transaction.'.$groupBy)
            ->orderBy('account_code')
            ->get();
        return $data;
    }

}
