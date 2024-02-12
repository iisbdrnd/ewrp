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
use App\Model\EwCandidates;
use App\Model\EwChartOfAccounts;
use App\Model\EwAccountConfiguration;
use App\Model\EwAccountTransaction;

class PrintVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('ew.printVoucher.index', $data);
    }

    public function printVoucherReport(Request $request)
    {
        $voucher_mode = $param['voucher_mode'] = $request->voucher_mode;
        $transaction_no = $param['transaction_no'] = $request->transaction_no;
        $date = $param['date'] = $request->date;
        $param['voucher_type'] = $request->voucher_type;
        $from_date = $param['from_date'] = $request->from_date;
        $to_date = $param['to_date'] = $request->to_date;

        $pdf_url = route('ew.printVoucherReportPDF', $param);
        $data = self::getVoucherReport($param);
        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('ew.printVoucher.report', $data);
    }

    public function printVoucherReportPDF(Request $request)
    {
        $param['voucher_mode'] = $request->voucher_mode;
        $param['transaction_no'] = $request->transaction_no;
        $param['date'] = $request->date;
        $param['voucher_type'] = $request->voucher_type;
        $param['from_date'] = $request->from_date;
        $param['to_date'] = $request->to_date;
        $data = self::getVoucherReport($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.printVoucher.report', $data);
        $file_name = 'voucher-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
    }

    public static function getVoucherReport($param) {
        $voucher_mode = $data['voucher_mode'] = $param['voucher_mode'];
        $transaction_no = $data['transaction_no'] = $param['transaction_no'];
        $date = $data['date'] = $param['date'];
        $voucher_type = $param['voucher_type'];
        $from_date = $data['from_date'] = $param['from_date'];
        $to_date = $data['to_date'] = $param['to_date'];

        $data['voucher_names'] = ['Payment Voucher', 'Received Voucher', 'Bank Payment Voucher', 'Bank  Received Voucher', 'Journal Voucher'];

        if($voucher_mode==1) {
            $date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');

            $vouchers = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
            ->select('ew_account_transaction.*', 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.account_head')
            ->where('ew_account_transaction.valid', 1)
            ->where('ew_account_transaction.transaction_no', $transaction_no)
            ->where('ew_account_transaction.transaction_date', $date)
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('transaction_no');
        } else {
            $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
            $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');

            $vouchers = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
            ->select('ew_account_transaction.*', 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.account_head')
            ->whereBetween('ew_account_transaction.transaction_date', [$from_date, $to_date])
            ->where(function($query) use ($voucher_type){
                if(!empty($voucher_type)) {
                    $query->where('ew_account_transaction.voucher_type', strtoupper($voucher_type));
                }
            })
            ->where('ew_account_transaction.valid', 1)
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('transaction_no');
        }

        $data['vouchers'] = $vouchers;

        return $data;
    }

}
