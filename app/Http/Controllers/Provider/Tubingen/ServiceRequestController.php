<?php

namespace App\Http\Controllers\Provider\ApprovalSystem;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\AdminDesignation;
use App\Model\ServiceCategory_provider;
use App\Model\ServiceRequest_provider;
use App\Model\EmployeeUser_provider;
use App\Model\Chain_provider;
use App\Model\ServiceRequestAttachment_provider;
use App\Model\ServiceRequestedChain_provider;
use App\Model\ServiceReqApprovedAttachment_provider;
use App\Model\ServiceReqApprovedRecord_provider;
use App\Model\ServiceReqestPoke_provider;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.ApprovalSystem.serviceRequest.list', $data);
    }

    public function serviceRequestListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $project_id = Auth::guard('provider')->user()->project_id;

        $data['serviceRequests'] = ServiceRequest_provider::join('service_category', 'service_request.service_category_id' , '=', 'service_category.id')
            ->where(function($query) use ($search)
            {
                $query->where('title', 'LIKE', '%'.$search.'%');
                    // ->orWhere('description', 'LIKE', '%'.$search.'%');
            })
            ->select('service_request.*','service_category.name as category_name', 'service_category.id as category_id')
            ->where('service_request.project_id', $project_id)
            ->where('service_request.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
            
        return view('provider.ApprovalSystem.serviceRequest.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['serviceCategories'] = ServiceCategory_provider::valid()->select('service_category.id', 'service_category.name')->get();
        return view('provider.ApprovalSystem.serviceRequest.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $service_category_id = $request->service_category_id;
        $isChainExist = Chain_provider::valid()->where('cat_id', $service_category_id)->first();
        $validator = [
            'service_category_id' => 'required',
            'title'               => 'required',
            'details'             => 'required'
        ];
        // MENUAL APPROVAL PROCESS
        if (!$isChainExist) {
            $validator['employee_id'] = 'required';
        }
        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {
            $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
            $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
            $fau_attachment_size = (!empty($request->fau_attachment_size)) ? $request->fau_attachment_size : [];

            DB::beginTransaction();
            // $service_request
            if (!$isChainExist) {
                // MENUAL APPROVAL PROCESS
                $service_request = ServiceRequest_provider::create([
                    'service_category_id' => $request->service_category_id,
                    'title'               => $request->title,
                    'details'             => $request->details,
                    'is_approx_date'      => $request->is_approx_date,
                    'approximate_date'    => $request->approximate_date,
                    'is_approx_amount'    => $request->is_approx_amount,
                    'approximate_amount'  => $request->approximate_amount
                ]);

                ServiceRequestedChain_provider::create([
                    'service_req_id'  => $service_request->id,
                    'employee_id'     => $request->employee_id,
                    'sl_no'           => 0,
                    'active_chain_req'=> 1,
                ]);

            } else {
                // AUTO APPROVAL PROCESS    
                $service_request = ServiceRequest_provider::create([
                    'service_category_id' => $request->service_category_id,
                    'request_type'        => 1, //1 = Auto
                    'title'               => $request->title,
                    'details'             => $request->details,
                    'is_approx_date'      => $request->is_approx_date,
                    'approximate_date'    => $request->approximate_date,
                    'is_approx_amount'    => $request->is_approx_amount,
                    'approximate_amount'  => $request->approximate_amount
                ]);
                // REQUESTED CHAIN SCEQUENCE
                $employee_ids = json_decode($isChainExist->chain, true);
                foreach ($employee_ids as $key => $employee_id) {
                    ServiceRequestedChain_provider::create([
                        'service_req_id'    => $service_request->id,
                        'employee_id'       => $employee_id,
                        'active_chain_req'  => $key == 0 ? 1 : 0,
                        'sl_no'             => ++$key,
                    ]);
                }
            }

            foreach ($fau_attachment as $index=>$attachm) {
                ServiceRequestAttachment_provider::create([
                    "service_request_id"    => $service_request->id,
                    "attachment_name"       => $attachm,
                    "attachment_real_name"  => $fau_attachment_real_name[$index],
                    "attachment_size"       => $fau_attachment_size[$index]
                ]);
            }
            DB::commit();
            
            $output['messege'] = 'Service Request has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        // return response($output);
        echo json_encode($output);
        
        
    }

    public function show($id)
    {
        return $id;
    }

    public function edit($id)
    {
        $project_id = Auth::guard('provider')->user()->project_id;

        $data['serviceRequest'] = ServiceRequest_provider::join('service_category', 'service_request.service_category_id' , '=', 'service_category.id')
            ->select('service_request.*','service_category.name as category_name')
            ->where('service_request.project_id', $project_id)
            ->where('service_request.id', $id)
            ->where('service_request.valid', 1)
            ->first();
        $data['serviceCategories'] = ServiceCategory_provider::valid()->select('service_category.id', 'service_category.name')->get();
        $data['employees'] = EmployeeUser_provider::valid()->get();
        $data['requestAttFiles'] = ServiceRequestAttachment_provider::valid()->where('service_request_id', $id)->get();
        return view('provider.ApprovalSystem.serviceRequest.update', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        $service_category_id = $request->service_category_id;
        $isChainExist = Chain_provider::valid()->where('cat_id', $service_category_id)->first();
        $current_date = now();
        
        $validator = [
            'title'               => 'required',
            'details'             => 'required'
        ];
        // MENUAL APPROVAL PROCESS
        if (!$isChainExist) {
            $validator['employee_id'] = 'required';
        }

        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {
            
            ServiceRequest_provider::find($id)->update([
                'service_category_id' => $request->service_category_id,
                'request_type'        => (!$isChainExist) ? 0 : 1, //0 = Menual, 1= auto
                'title'               => $request->title,
                'details'             => $request->details,
                'is_approx_date'      => $request->is_approx_date,
                'approximate_date'    => $request->approximate_date,
                'is_approx_amount'    => $request->is_approx_amount,
                'approximate_amount'  => $request->approximate_amount
            ]);

            if (!$isChainExist) {
                $requested_chain_exist = ServiceRequestedChain_provider::valid()->where('service_req_id', $id)->get();
                if (count($requested_chain_exist) > 0) {
                    foreach($requested_chain_exist as $requested_chain) {
                        ServiceRequestedChain_provider::find($requested_chain->id)->delete();
                    }
                }
                // MENUAL APPROVAL PROCESS
                ServiceRequestedChain_provider::valid()->where('service_req_id',$id)->update([
                    'employee_id'     => $request->employee_id,
                ]);

            } else {

                $employee_ids = json_decode($isChainExist->chain, true);
                $chain_employee_ids = ServiceRequestedChain_provider::valid()
                    ->where('service_req_id', $id)
                    ->orderBy('sl_no')
                    ->pluck('employee_id')
                    ->toArray();

                if($employee_ids != $chain_employee_ids){
                    
                    $exits_req_chains = ServiceRequestedChain_provider::valid()->where('service_req_id',$id)->get();

                    foreach ($exits_req_chains as $key => $chain) {
                        ServiceRequestedChain_provider::valid()->find($chain->id)->delete();
                    }

                    foreach ($employee_ids as $key => $employee_id) {
                        ServiceRequestedChain_provider::create([
                            'service_req_id'   => $id,
                            'employee_id'      => $employee_id,
                            'active_chain_req' => $key == 0 ? 1 : 0,
                            'arrival_date'     => $key == 0 ? $current_date : null,
                            'sl_no'            => ++$key,
                        ]);
                    }
                    
                }


            }
      

            $fau_attachment_id = (!empty($request->fau_attachment_id)) ? $request->fau_attachment_id : [];
            $fau_attachment_db = collect(ServiceRequestAttachment_provider::valid()->where("service_request_id", $id)->get()->pluck("id")->all());
            $fau_attachment_diff = $fau_attachment_db->diff($fau_attachment_id);

            $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
            $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
            $fau_attachment_size = (!empty($request->fau_attachment_size)) ? $request->fau_attachment_size : [];

            foreach($fau_attachment_diff as $fau_attachment_db_id) {
                ServiceRequestAttachment_provider::find($fau_attachment_db_id)->delete();
            }

            foreach ($fau_attachment_id as $index=>$fau_attachment_id) {
                if($fau_attachment_id==0 || !$fau_attachment_db->contains($fau_attachment_id)) {
                    ServiceRequestAttachment_provider::create([
                        "service_request_id"    => $id,
                        "attachment_name"       => $fau_attachment[$index],
                        "attachment_real_name"  => $fau_attachment_real_name[$index],
                        "attachment_size"       => $fau_attachment_size[$index]
                    ]);
                }
            }

            $output['messege'] = 'Service Category has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        DB::commit();

        echo json_encode($output);
    }

    public function destroy($id)
    {
        $hasApprove = ServiceRequestedChain_provider::valid()->where('service_req_id',$id)->where('approve_status',1)->first();
        if(empty($hasApprove)){
            ServiceRequest_provider::valid()->find($id)->delete();
        }else{
            return "it's already approved by someone";
        }
        
    }

    public function serviceCategoryChain(Request $request)
    {
        $service_category_id = $request->service_category_id;
        $isChainExist = Chain_provider::valid()->where('type', 1)->where('cat_id', $service_category_id)->first();
        if (!$isChainExist) {
            $data['employees'] = EmployeeUser_provider::valid()->get();
        } else {
            $data['employees'] = [];
        }
        
        return view('provider.ApprovalSystem.serviceRequest.employeeSelectOption', $data);
    }
    
    public function requestProgress(Request $request)
    {
        $data['today_date']=$today_date= date('Y-m-d H:i:s');
        $today_strtotime = strtotime($today_date);

        $data['serviceRequestedChains'] = $serviceRequestedChains = ServiceRequestedChain_provider::join('en_provider_user_info', 'en_provider_user_info.user_id', '=', 'service_requested_chain.employee_id')
            ->join('employee_designation', 'employee_designation.id', 'en_provider_user_info.designation')
            ->select('service_requested_chain.*', 'en_provider_user_info.name as employee_name', 'employee_designation.name as designation_name', 'en_provider_user_info.image')
            ->where('service_requested_chain.service_req_id', $request->id)
            ->where('service_requested_chain.valid', 1)
            ->orderBy('service_requested_chain.sl_no', 'asc')
            ->get();
        
        foreach ($serviceRequestedChains as $serviceRequestedChain) {
            $data['approve_all_records'] = $approve_all_records = ServiceReqApprovedRecord_provider::join('en_provider_user_info', 'en_provider_user_info.user_id', '=', 'service_request_approved_records.employee_id')
                ->select('service_request_approved_records.*', 'en_provider_user_info.name as employee_name', 'en_provider_user_info.image')
                ->where('service_request_approved_records.service_req_id', $serviceRequestedChain->service_req_id)
                // ->where('service_request_approved_records.req_chain_id', $serviceRequestedChain->id)
                ->get();
            // ServiceReqApprovedAttachment_provider
        }

        foreach ($approve_all_records as $key => $record) {
            $record->attachments = ServiceReqApprovedAttachment_provider::valid()->where('approve_record_id', $record->id)->get();
        }

        // echo "<pre>";
        // print_r($data['approve_all_records']);
        // exit();
        // if ($serviceRequest->request_type == 0) {
            // ServiceRequestedChain_provider::where('')->get();
        // }
        return view('provider.ApprovalSystem.serviceRequest.requestProgress', $data);
    }

    public function requestProgressDetails(Request $request)
    {
        $data['approve_records'] = $approve_records = ServiceReqApprovedRecord_provider::join('en_provider_user', 'en_provider_user.id', '=', 'service_request_approved_records.employee_id')
            ->select('service_request_approved_records.*', 'en_provider_user.name as employee_name')
            ->where('service_request_approved_records.service_req_id', $request->service_req_id)
            ->where('service_request_approved_records.req_chain_id', $request->id)
            ->get();
        
        foreach ($approve_records as $key => $record) {
            $record->attachments = ServiceReqApprovedAttachment_provider::valid()->where('approve_record_id', $record->id)->get();
        }

        // echo "<pre>";
        // print_r($data);
        // exit();
            
        return view('provider.ApprovalSystem.serviceRequest.requestProgressDetails', $data);
    }

    public function serviceRequestPoke(Request $request)
    {
        $requested_chain = ServiceRequestedChain_provider::select('id', 'employee_id')
            ->valid()
            ->where('service_req_id', $request->requested_chain_id)
            ->where('active_chain_req', 1)
            ->first();
        $select_poke = ServiceReqestPoke_provider::select('id', 'employee_id', 'poke_quantity')
            ->valid()
            ->where('service_requested_chain_id', $requested_chain->id)
            ->first();
        if (!empty($select_poke)) {
            if ($select_poke->poke_quantity < 5) { //user can do maximum 5 time poke
                ServiceReqestPoke_provider::find($select_poke->id)->update([
                    'poke_quantity' => $select_poke->poke_quantity + 1
                ]);
            }else{
                $output['messege'] = 'You already poked maximum time.';
                $output['msgType'] = 'success';

                return response($output);
            }
        }else{
            ServiceReqestPoke_provider::create([
                'service_requested_chain_id' => $requested_chain->id,
                'employee_id'                => $requested_chain->employee_id,
                'poke_quantity'              => 1
            ]);
        }
        
        $output['messege'] = 'Poked Successfully';
        $output['msgType'] = 'success';
        
        return response($output);
    }
}
