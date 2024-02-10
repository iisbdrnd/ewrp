<?php

namespace App\Http\Controllers\Provider\ApprovalSystem;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Helper;
use Validator;
use App\Model\Chain_provider;
use App\Model\EmployeeUser_provider;
use App\Model\ServiceCategory_provider;
use App\Model\ServiceRequestedChain_provider; 
use App\Model\ServiceReqApprovedRecord_provider; 
use App\Model\ServiceReqApprovedAttachment_provider; 

class ReadyForJobController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.ApprovalSystem.readyForJob.list', $data);
    }
    
    public function ReadyForJobListData(Request $request)
    {
        $data = $request->all();
        $search = $request->search;
        $employeeId = Auth::guard('provider')->user()->id;
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['serviceAprrovedInfos'] = ServiceRequestedChain_provider::join('en_provider_user', 'en_provider_user.id', '=', 'service_requested_chain.employee_id')
            ->join('service_request','service_request.id', 'service_requested_chain.service_req_id')
            ->select('service_requested_chain.id','service_requested_chain.approve_status','service_requested_chain.receive_status','en_provider_user.name as employee_name','service_request.title','service_request.details','service_request.approximate_date','service_request.approximate_amount','service_request.created_at as request_date')
            ->where('service_requested_chain.employee_id',$employeeId)
            ->where('service_requested_chain.valid', 1 )
            ->where(function($query) use ($search)
                {
                    $query->where('en_provider_user.name', 'LIKE', '%'.$search.'%');
                })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.ApprovalSystem.readyForJob.listData', $data);
    }

}
