<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CrmSubscriptionHistory;

class SubscriptionHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function crmSubscriptionHis(Request $request) {
        $data['inputData'] = $request->all();
        return view('admin.subscriptionHistory.list', $data);
    }

    public function crmSubscriptionHisData(Request $request) {
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

        $ascDesc = Helper::ascDesc($data, ['name', 'reason', 'extended_user', 'current_user', 'extended_duration', 'created_at', 'amount']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $project_id = Auth::user()->get()->project_id;
        $data['crmSubscriptionHistory'] = CrmSubscriptionHistory::join('users', 'crm_subscription_history.user_id', '=', 'users.id')
            ->select('crm_subscription_history.*', 'users.name')
            ->where(function($query) use ($from_date, $to_date) {
                if (!empty($from_date) && !empty($to_date)) {
                    $query->whereBetween('crm_subscription_history.created_at', [$from_date, $to_date]);
                }
            })
            ->where(function($query) use ($search)
            {
                $query->where('users.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('crm_subscription_history.reason', 'LIKE', '%'.$search.'%')
					->orWhere('crm_subscription_history.extended_user', 'LIKE', '%'.$search.'%')
					->orWhere('crm_subscription_history.current_user', 'LIKE', '%'.$search.'%')
					->orWhere('crm_subscription_history.extended_duration', 'LIKE', '%'.$search.'%')
					->orWhere('crm_subscription_history.created_at', 'LIKE', '%'.$search.'%')
					->orWhere('crm_subscription_history.amount', 'LIKE', '%'.$search.'%');
            })
            ->where('crm_subscription_history.project_id', $project_id)
            ->where('crm_subscription_history.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('admin.subscriptionHistory.listData', $data);
    }




}
