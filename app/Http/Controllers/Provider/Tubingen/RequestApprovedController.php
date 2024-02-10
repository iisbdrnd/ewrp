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

class RequestApprovedController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.ApprovalSystem.requestApproved.list', $data);
    }
    
    public function RequestApprovedListData(Request $request)
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
        return view('provider.ApprovalSystem.requestApproved.listData', $data);
    }

    public function requestAprrovedStatus(Request $request){
        $employeeId = Auth::guard('provider')->user()->id;
        $data['req_chain_id'] = $req_chain_id = $request->req_chain_id;
        $data['service_req_info'] = $service_req_info = ServiceRequestedChain_provider::valid()->find($req_chain_id);
        $data['apprevedReacordInfo'] = $apprevedReacordInfo = ServiceReqApprovedRecord_provider::valid()
            ->where('req_chain_id', $req_chain_id)
            ->first();

        if (!empty($apprevedReacordInfo)) {
           $data['approvedRecordAttachment']  = ServiceReqApprovedAttachment_provider::valid()->where('approve_record_id', $apprevedReacordInfo->id)->get();
        } else{ 
            $data['approvedRecordAttachment'] = [];
        }
        
        // REQUEST PREVIOUS SUMMERY
        $data['approvedRecords'] = $approvedRecords = ServiceReqApprovedRecord_provider::join('service_requested_chain', 'service_requested_chain.id', '=', 'service_request_approved_records.req_chain_id')
            ->join('en_provider_user', 'en_provider_user.id', '=', 'service_request_approved_records.employee_id')
            ->select('service_request_approved_records.*', 'en_provider_user.name as employee_name')
            ->where('service_request_approved_records.service_req_id', $service_req_info->service_req_id)
            ->where('service_requested_chain.approve_status', 1) // 1 = Already Approved
            ->where('service_requested_chain.valid', 1) 
            ->orderBy('service_requested_chain.sl_no', 'asc')
            ->get();

        foreach ($approvedRecords as $key => $record) {
            $record->attachments = ServiceReqApprovedAttachment_provider::valid()->where('approve_record_id', $record->id)->get();
        }

        return view('provider.ApprovalSystem.requestApproved.requestAprrovalTab', $data);

        // if($service_req_info->employee_id == $employeeId && $service_req_info->approve_status != 1){
        //     return view('provider.ApprovalSystem.requestApproved.requestAprrovalTab', $data);
        // }else{
        //     $err['msg'] = 'You are not authorised';
        //     return view('errors.503',$err);
        // }

        

        // if(!empty($service_req_info)){
            // return view('provider.ApprovalSystem.requestApproved.requestAprrovalTab',$data);
        // }else{
        //     $err['msg'] = 'You are not authorised';
        //     return view('provider.errors.503',$err);
        // }
    }
    public function requestAprrovedStatusAction(Request $request)
    {
        $output = array();
        $input  = $request->all();
        $req_chain_id   = intval($request->req_chain_id);
        $service_req_id = $request->service_req_id;
        $employee_id    = $request->employee_id;
        $request_status = $request->request_status;
        $current_date = now();

        $req_chain_Info = ServiceRequestedChain_provider::valid()->find($req_chain_id);

        $reqChainArray = ServiceRequestedChain_provider::valid()
                ->where('service_req_id',$service_req_id)
                ->orderBy('sl_no','asc')
                ->pluck('id')->toArray();

        $totalIndexLength = count($reqChainArray)-1;
        $chainIndexPos = array_search($req_chain_id,$reqChainArray,true);

        $validator = Validator::make($input, [
            "note"  => "required",
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();

            $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
            $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
            $fau_attachment_size = (!empty($request->fau_attachment_size)) ? $request->fau_attachment_size : [];
            
            //request chain id update
            ServiceRequestedChain_provider::valid()->find($req_chain_id)->update([
                'approve_status'    => $request_status,
                'approved_datetime' => date('Y-m-d H:i:s'),
                'active_chain_req' => ($request_status == 1) ? 0 : 1,
            ]);

            //next request chain id update
            if ($chainIndexPos != $totalIndexLength) {
                $nextChainId = $reqChainArray[$chainIndexPos+1];
                
                $nextChainInfo = ServiceRequestedChain_provider::valid()->find($nextChainId);
                if (!empty($nextChainInfo)){
                    $nextChainInfo->update([
                        'active_chain_req' => 1,
                        'arrival_date' => $current_date
                    ]);
                }
            }

            if ($req_chain_Info->approve_status == 0 ) {
                //insert record
                $reqApprovedRecord = ServiceReqApprovedRecord_provider::create([
                        "service_req_id"        => $service_req_id,
                        "req_chain_id"          => $req_chain_id,
                        "employee_id"           => $employee_id,
                        "note"                  => $request->note,
                ]);

                //insert attachment
                foreach ($fau_attachment as $index => $attachm) {
                    ServiceReqApprovedAttachment_provider::create([
                        "approve_record_id"     => $reqApprovedRecord->id,
                        "attachment_name"       => $attachm,
                        "attachment_real_name"  => $fau_attachment_real_name[$index],
                        "attachment_size"       => $fau_attachment_size[$index]
                    ]);
                }
            }else{

                $recordInfo = ServiceReqApprovedRecord_provider::valid()->where('req_chain_id',$req_chain_id)->first();

                if (!empty($recordInfo)) {

                    $recordInfo->update(["note" => $request->note]);

                    $fau_attachment_id = (!empty($request->fau_attachment_id)) ? $request->fau_attachment_id : [];
                    $fau_attachment_db = collect(ServiceReqApprovedAttachment_provider::valid()->where("approve_record_id", $recordInfo->id)->get()->pluck("id")->all());
                    $fau_attachment_diff = $fau_attachment_db->diff($fau_attachment_id);

                    $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
                    $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
                    $fau_attachment_size = (!empty($request->fau_attachment_size)) ? $request->fau_attachment_size : [];

                    foreach($fau_attachment_diff as $fau_attachment_db_id) {
                        ServiceReqApprovedAttachment_provider::find($fau_attachment_db_id)->delete();
                    }

                    if (count($fau_attachment_id) > 0) {
                        foreach ($fau_attachment_id as $index=>$fau_attachment_id) {
                                if($fau_attachment_id==0 || !$fau_attachment_db->contains($fau_attachment_id)) {
                                ServiceReqApprovedAttachment_provider::create([
                                    "approve_record_id"    => $recordInfo->id,
                                    "attachment_name"       => $fau_attachment[$index],
                                    "attachment_real_name"  => $fau_attachment_real_name[$index],
                                    "attachment_size"       => $fau_attachment_size[$index]
                                ]);
                            }
                        }
                    }
                    
                }

            }

            DB::commit();

            $output['messege'] = 'Approved Info saved';
            $output['msgType'] = 'success';

        } 
        else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function aprrovedInfoPreview(Request $request){
        

        return view('provider.ApprovalSystem.requestApproved.aprrovedInfoPreview');
    }

    public function requestReceiveAcceptStatus(Request $request)
    {
        $id = $request->id;
        $current_date = now();
        ServiceRequestedChain_provider::find($id)->update([
            'receive_status'=> 1,
            'receive_date'=> $current_date,

        ]);
        $output['messege'] = 'Received';
        $output['msgType'] = 'success';

        echo json_encode($output);
    }

}
