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
use App\Model\ServiceInfo_provider;
use App\Model\ServiceRequest_provider;
use App\Model\ServiceRequestedChain_provider;
use App\Model\RequestSendAttachment_provider;


class RequestInfoController extends Controller
{

    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.ApprovalSystem.requestInfo.list', $data);
    }
    
    public function requestInfoSendListData(Request $request)
    {
        $data = $request->all();
        $search = $request->search;
        $employeeId = Auth::guard('provider')->user()->id;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['serviceInfos'] = ServiceInfo_provider::join('service_request', 'service_request.id', '=', 'request_for_information.service_request_id')
        ->select('request_for_information.*','service_request.title')
        ->where('request_for_information.req_from_employee_id', $employeeId)
        ->where('request_for_information.valid', 1)
        ->where(function($query) use ($search)
            {
                $query->where('service_request.title', 'LIKE', '%'.$search.'%');
            })
        ->orderBy($ascDesc[0], $ascDesc[1])
        ->paginate($paginate->perPage);

        return view('provider.ApprovalSystem.requestInfo.listData', $data);
    }

    public function create(Request $request)
    {
        $employeeId = Auth::guard('provider')->user()->id;
        $data['inputData'] = $request->all();

        $data['employees'] = EmployeeUser_provider::valid()->where('id','!=',$employeeId)->get();

        $data['service_request_chains'] = ServiceRequestedChain_provider::join('service_request','service_request.id', 'service_requested_chain.service_req_id')
        ->select('service_requested_chain.id','service_request.title','service_request.id as service_request_id')
        ->where('service_requested_chain.employee_id',$employeeId)
        ->where('service_requested_chain.valid', 1 )
        ->get();
        //dd($data['service_request_chains']);

        return view('provider.ApprovalSystem.requestInfo.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        //dd($input);
        $employeeId = Auth::guard('provider')->user()->id;

        $data['service_request'] =$service_request = ServiceRequestedChain_provider::join('service_request','service_request.id', 'service_requested_chain.service_req_id')
        ->select('service_request.id as service_request_id')
        ->where('service_requested_chain.id',$request->request_chain_id)
        ->where('service_requested_chain.valid', 1 )
        ->first();
        //dd($data['service_request']);

        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
        $fau_attachment_size = (!empty($request->fau_attachment_size)) ? $request->fau_attachment_size : [];

        $validator = Validator::make($input, [
            'request_chain_id'   => 'required',
            'req_to_employee_id' => 'required',
            'note'               => 'required'
        ]);

        if ($validator->passes()) {
            $request_Send_Info = ServiceInfo_provider::create([
                "request_chain_id"         => $request->request_chain_id,
                "service_request_id"       => $service_request->service_request_id,
                "req_from_employee_id"     => $employeeId,
                "req_to_employee_id"       => $request->req_to_employee_id,
                "note"                     => $request->note
            ]);

            foreach ($fau_attachment as $index=>$attachm) {
                RequestSendAttachment_provider::create([
                    "request_info_id"       => $request_Send_Info->id,
                    "attachment_name"       => $attachm,
                    "attachment_real_name"  => $fau_attachment_real_name[$index],
                    "attachment_size"       => $fau_attachment_size[$index]
                ]);
            }

            $output['messege'] = 'Messege has been created';
            $output['msgType'] = 'success';

            } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }


    public function edit($id)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        $employeeId = Auth::guard('provider')->user()->id;
        $data['service_request_send'] = ServiceInfo_provider::valid()->where('id', $id)->first();
        $data['employees'] = EmployeeUser_provider::valid()->where('id','!=',$employeeId)->get();
        $data['service_requests'] = ServiceRequestedChain_provider::join('service_request','service_request.id', 'service_requested_chain.service_req_id')
        ->select('service_requested_chain.id','service_request.title','service_request.id as service_request_id')
        ->where('service_requested_chain.valid', 1 )
        ->where('service_requested_chain.employee_id',$employeeId)
        ->get();
        $data['requestAttFiles'] = RequestSendAttachment_provider::valid()->where('request_info_id', $id)->get();
        return view('provider.ApprovalSystem.requestInfo.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $employeeId = Auth::guard('provider')->user()->id;

        $validator = Validator::make($input, [
            'note'                  => 'required'
        ]);

        if ($validator->passes()) {
            ServiceInfo_provider::valid()->find($id)->update([
                "note" => $request->note
            ]);

            $fau_attachment_id = (!empty($request->fau_attachment_id)) ? $request->fau_attachment_id : [];
            $fau_attachment_db = collect(RequestSendAttachment_provider::valid()->where("request_info_id", $id)->get()->pluck("id")->all());
            $fau_attachment_diff = $fau_attachment_db->diff($fau_attachment_id);

            $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
            $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
            $fau_attachment_size = (!empty($request->fau_attachment_size)) ? $request->fau_attachment_size : [];

            foreach($fau_attachment_diff as $fau_attachment_db_id) {
                RequestSendAttachment_provider::find($fau_attachment_db_id)->delete();
            }

            foreach ($fau_attachment_id as $index=>$fau_attachment_id) {
                if($fau_attachment_id==0 || !$fau_attachment_db->contains($fau_attachment_id)) {
                    RequestSendAttachment_provider::create([
                        "request_info_id"       => $id,
                        "attachment_name"       => $fau_attachment[$index],
                        "attachment_real_name"  => $fau_attachment_real_name[$index],
                        "attachment_size"       => $fau_attachment_size[$index]
                    ]);
                }
            }

            $output['messege'] = 'Messege has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        ServiceInfo_provider::valid()->find($id)->delete();
    }
    public function viewSendedNote(Request $request)
    {
        $data['request_send_info'] = $request_send_info = ServiceInfo_provider::select('id','note')->valid()->where('id', $request->id)->first();
        $data['request_send_attatchments'] = RequestSendAttachment_provider::select('attachment_name')
            ->valid()
            ->where('request_info_id', $request_send_info->id)
            ->get();

        return view('provider.ApprovalSystem.requestInfo.viewSendedNote', $data);
    }

}
