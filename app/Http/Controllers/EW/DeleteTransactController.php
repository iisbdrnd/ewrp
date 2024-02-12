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

class DeleteTransactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('ew.deleteTransact.index', $data);
    }

    public function deleteTransactView(Request $request)
    {
        $date = $data['date'] = $request->date;
        $transaction_no = $data['transaction_no'] = $request->transaction_no;

        $date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');

        $data['voucher_names'] = ['Payment Voucher', 'Received Voucher', 'Bank Payment Voucher', 'Bank  Received Voucher', 'Journal Voucher'];
        $data['vouchers'] = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
            ->select('ew_account_transaction.*', 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.account_head')
            ->where('ew_account_transaction.valid', 1)
            ->where('ew_account_transaction.transaction_no', $transaction_no)
            ->where('ew_account_transaction.transaction_date', $date)
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('transaction_no');

        return view('ew.deleteTransact.report', $data);
    }

    public function deleteTransactDestroy($id)
    {
        $date = $data['date'] = $request->date;
        $transaction_no = $data['transaction_no'] = $request->transaction_no;

        $date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');

        $data['voucher_names'] = ['Payment Voucher', 'Received Voucher', 'Bank Payment Voucher', 'Bank  Received Voucher', 'Journal Voucher'];
        $data['vouchers'] = EwAccountTransaction::join('ew_chart_of_accounts', 'ew_account_transaction.account_code', '=', 'ew_chart_of_accounts.account_code')
            ->select('ew_account_transaction.*', 'ew_chart_of_accounts.account_code', 'ew_chart_of_accounts.account_head')
            ->where('ew_account_transaction.valid', 1)
            ->where('ew_account_transaction.transaction_no', $transaction_no)
            ->where('ew_account_transaction.transaction_date', $date)
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('transaction_no');

        return view('ew.deleteTransact.report', $data);
    }

}
