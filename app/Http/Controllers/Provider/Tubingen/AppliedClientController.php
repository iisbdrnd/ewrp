<?php

namespace App\Http\Controllers\Provider\Tubingen;

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
use App\Model\TubClient_provider; 
use App\Model\ServiceReqApprovedRecord_provider; 
use App\Model\ServiceReqApprovedAttachment_provider; 
use App\Model\ProjectInfo_provider; 

class AppliedClientController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.eastWest.requestedClient.list', $data);
    }
    
    public function appliedClientListData(Request $request)
    {
        $data = $request->all();
        $search = $request->search;
        $employeeId = Auth::guard('provider')->user()->id;
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['clientRequests'] = TubClient_provider::select('tub_client.*')
            ->valid()
            ->where(function($query) use ($search)
                {
                    $query->where('name', 'LIKE', '%'.$search.'%');
                })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.requestedClient.listData', $data);
    }

    public function requestedClientApproveStatus(Request $request){
        $employeeId = Auth::guard('provider')->user()->id;
        $data['client_id'] = $client_id = $request->client_id;
        $data['client'] = $client = TubClient_provider::valid()->find($client_id);

        return view('provider.eastWest.requestedClient.requestApprovalModal', $data);
    }

    public function requestApprovedStatusAction(Request $request)
    {
        $output = array();
        $input  = $request->all();
        $client_id      = $request->client_id;
        $approve_status = $request->approve_status;
        $project_id  = Auth::guard('provider')->user()->project_id;
        $projectInfo = ProjectInfo_provider::valid()->where('project_id', $project_id)->first();

        $validator = Validator::make($input, [
            "approve_status"  => "required",
        ]);
        if ($validator->passes()) {
            TubClient_provider::valid()->find($client_id)->update([
                'approve_status'    => $approve_status
            ]);

            //send mail
            // if($approve_status==1){
            //     $user_info = @TubClient_provider::where('valid', 1)->where('id', $client_id)->first();
            //     Helper::mailConfig();
            //         $email_data['company_name'] = @Helper::getCompanyInfo(Auth::guard('provider')->user()->project_id)->company_name;

            //         $email_data['poject_logo'] = @Helper::getCompanyInfo(Auth::guard('provider')->user()->project_id)->logo;

            //         $email_data['data'] = [
            //             'name'              =>  $user_info->name,
            //             'email'             =>  $user_info->email,
            //             'mobile'            =>  $projectInfo->mobile,
            //             'logo'              =>  $projectInfo->logo,
            //             'company_name'      =>  $projectInfo->company_name,
            //             'website'           =>  $projectInfo->website
            //         ];
            //     $email_data['link'] = url('provider.pro_email_verification?token=' . $verification_code);
            //     Mail::send('emails.email_verification', $email_data, function($message) use ($user_info)
            //     {
            //         $message->subject('Email Verification');
            //         $message->to($user_info->email, $user_info->name);
            //     });
            // }

            $output['messege'] = 'Approved Status Updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }
    
}
