<?php

namespace App\Http\Controllers\Provider\ApprovalSystem;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Helper;
use Validator;
use App\Model\ServiceInfo_provider;
use App\Model\RequestFeedbackAttachment_provider;
use App\Model\RequestSendAttachment_provider;


class RequestReceiveController extends Controller
{
    public function index(Request $request){

        $data['inputData'] = $request->all();
        return view('provider.ApprovalSystem.requestReceive.list', $data);
    }
    
    public function requestReceivetListData (Request $request)
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
        ->where('request_for_information.req_to_employee_id', $employeeId)
        ->where('request_for_information.valid', 1)
        ->where(function($query) use ($search)
            {
                $query->where('service_request.title', 'LIKE', '%'.$search.'%');
            })
        ->orderBy($ascDesc[0], $ascDesc[1])
        ->paginate($paginate->perPage);

        return view('provider.ApprovalSystem.requestReceive.listData', $data);
    }

    public function infoFeedback(Request $request)
    {
        $data = $request->all();
        $data['infoId'] = $request->infoId;
        return view('provider.ApprovalSystem.requestReceive.feedback',$data);
    }
    public function infoFeedbackAction(Request $request){
        
        $input = $request->all();
        $infoId = $request->infoId;
        $data['service_request_Info'] =$service_request_Info = ServiceInfo_provider::valid()->where('id', $infoId)->first();

        $employeeId = Auth::guard('provider')->user()->id;

        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
        $fau_attachment_size = (!empty($request->fau_attachment_size)) ? $request->fau_attachment_size : [];

        $validator = Validator::make($input, [
            'note' => 'required'
        ]);

        if ($validator->passes()) {
            $request_feedback_Info = ServiceInfo_provider::create([
                "request_chain_id"         => $service_request_Info->request_chain_id,
                "service_request_id"       => $service_request_Info->service_request_id,
                "req_from_employee_id"     => $service_request_Info->req_to_employee_id,
                "req_to_employee_id"       => $service_request_Info->req_from_employee_id,
                "note"                     => $request->note
            ]);

            foreach ($fau_attachment as $index=>$attachm) {
                RequestFeedbackAttachment_provider::create([
                    "service_Info_id"       => $request_feedback_Info->id,
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
        // return redirect()->route('provider.approvalSystem.requestReceive')->with($output);

    }

    public function viewRequestedNote(Request $request){
        $infoId = $request->infoId;
        $data['service_request_Info'] =$service_request_Info = ServiceInfo_provider::join('en_provider_user_info','en_provider_user_info.user_id','=','request_for_information.req_from_employee_id')
            ->join('employee_designation','employee_designation.id','=','en_provider_user_info.designation')
            ->join('department','department.id','=','en_provider_user_info.department_id')
            ->select('request_for_information.*','en_provider_user_info.name as userName','en_provider_user_info.department_id','en_provider_user_info.designation','en_provider_user_info.image','employee_designation.name as designation_name','department.name as department_name')
            ->where('request_for_information.id', $infoId)
            ->where('request_for_information.valid', 1)
            ->first();

        // dd($service_request_Info);

        $data['request_send_attatchments'] = RequestSendAttachment_provider::select('service_request_send_attachments.attachment_name')
            ->where('request_info_id', $service_request_Info->id)
            ->valid()
            ->get();
        
        return view('provider.ApprovalSystem.requestReceive.viewRequestedNote',$data);
    }

    public function infoAcceptStatus(Request $request){
        $id = $request->id;
        ServiceInfo_provider::find($id)->update([
            'seen_status'=> 1
        ]);
        $output['messege'] = 'Accepted';
        $output['msgType'] = 'success';

        echo json_encode($output);
    }
}
