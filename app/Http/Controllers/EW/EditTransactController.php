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
use App\Model\EwCandidateTransaction;

class EditTransactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('ew.editTransact.index', $data);
    }

    public function editTransactView(Request $request)
    {
        $date = $data['date'] = $request->date;
        $transaction_no = $data['transaction_no'] = $request->transaction_no;

        $date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');

        $data['voucher'] = EwAccountTransaction::valid()
            ->where('transaction_no', $transaction_no)
            ->where('transaction_date', $date)
            ->first();

        return view('ew.editTransact.editForm', $data);
    }

    public function editTransactUpdate(Request $request)
    {
        $id = $request->id;
        $remarks = $request->remarks;

        $validator = Validator::make($request->all(), ['remarks' => 'required']);

        if ($validator->passes()) {
            $transaction = EwAccountTransaction::valid()->find($id);

            if(!empty($transaction)) {
                $accTransactions = EwAccountTransaction::valid()->where('transaction_no', $transaction->transaction_no)->where('transaction_date', $transaction->transaction_date)->get();
                foreach($accTransactions as $accTransaction) {
                    EwAccountTransaction::valid()->find($accTransaction->id)->update(['remarks'=>$remarks]);
                    if($accTransaction->candidate_transaction_id>0) {
                        $candidateTransaction = EwCandidateTransaction::valid()->find($accTransaction->candidate_transaction_id);
                        if(!empty($candidateTransaction)) { $candidateTransaction->update(['remarks'=>$remarks]); }
                    }
                }
                $output['messege'] = 'Update has been completed';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'Voucher has not found';
                $output['msgType'] = 'danger';
            }
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
    }

}
