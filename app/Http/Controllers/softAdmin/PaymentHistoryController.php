<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Project;
use App\Model\CrmJobArea_user;
use App\Model\CrmUserPaymentHistory;

class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function paymentHistory(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('softAdmin.paymentHistory.list', $data);
    }

    public function paymentHistoryData(Request $request) {
        $data = $request->all();
        $search = $request->search;
		
		$from_date = $request->from_date;
        $to_date = $request->to_date;
        if(!empty($from_date) && !empty($to_date)) {
            $from_date = DateTime::createFromFormat('d/m/Y', $from_date);
            $from_date = $from_date->format('Y-m-d');

            $to_date = DateTime::createFromFormat('d/m/Y', $to_date);
            $to_date = $to_date->format('Y-m-d');
        }

        $data['projectIdData'] = Project::valid()->orderBy('project_id', 'asc')->get();
        $data['projectId'] = $projectId = $request->project;
        $ascDesc = Helper::ascDesc($data, ['name', 'reason', 'payment_method', 'created_at', 'amount']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['crmUserPaymentHistory'] = CrmUserPaymentHistory::join('users', 'user_payment_history.user_id', '=', 'users.id')
            ->leftJoin('project', 'user_payment_history.project_id', '=', 'project.id')
            ->select('user_payment_history.*', 'users.name', 'project.name as project_name', 'project.project_id as projectId')
			->where(function($query) use ($from_date, $to_date) {
                if (!empty($from_date) && !empty($to_date)) {
                    $query->whereBetween('user_payment_history.created_at', [$from_date, $to_date]);
                }
            })
            ->where(function($query) use ($search)
            {
                $query->where('users.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('user_payment_history.amount', 'LIKE', '%'.$search.'%')
                    ->orWhere('user_payment_history.payment_method', 'LIKE', '%'.$search.'%')
                    ->orWhere('user_payment_history.reason', 'LIKE', '%'.$search.'%')
                    ->orWhere('user_payment_history.created_at', 'LIKE', '%'.$search.'%');
            })
            ->where(function($query) use ($projectId)
            {
                if(!empty($projectId)) {
                    $query->where('user_payment_history.project_id', $projectId);
                }
            })
            ->where('user_payment_history.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);


        return view('softAdmin.paymentHistory.listData', $data);
    }

    public function userPaymentHistorySearch(Request $request) {
        $data = $request->all();

        return view('softAdmin.paymentHistory.historySearch', $data);
    }

    public function leadSearch($from_date, $to_date) {
        $project_id = Auth::user()->get()->project_id;
        $date_lead_search = CrmUserPaymentHistory::join('users', 'user_payment_history.user_id', '=', 'users.id')
            ->select('user_payment_history.*', 'users.name')
            ->where('user_payment_history.project_id', $project_id)
            ->where('user_payment_history.valid', 1)
            ->where(function($query) use ($from_date, $to_date)
            {
                $query->whereBetween('user_payment_history.created_at', [$from_date, $to_date]);
            });
    }




}
