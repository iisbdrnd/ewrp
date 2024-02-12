<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwProjects;
use App\Model\EwCandidates;
use App\Model\EwChartOfAccounts;
use App\Model\EwAccountConfiguration;
use App\Model\EwAccountTransaction;
use App\Model\ProjectInfo_user;
use App\Model\Project_user;

class CompanyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $project_id = Auth::user()->get()->project_id;
        $data['companyInfo'] = $companyInfo = DB::table('project_info')->where('valid', 1)->where('project_id', $project_id)->first();
        $data['company_country'] = DB::table('countries')->find($companyInfo->country);
        $data['countries'] = DB::table('countries')->get();

        return view('ew.companyProfile.profile', $data);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        $project_id = Auth::user()->get()->project_id;

        $validator = Validator::make($input, [
            'company_name'          => 'required',
            'country'               => 'required',
            'mobile'                => 'required',
            'email'                 => 'required'
        ]);

        if ($validator->passes()) {
            $data = [
                'company_name'      => $request->company_name,
                'logo'              => $request->logo,
                'mobile'            => $request->mobile,
                'address'           => ($request->address)?$request->address:'',
                'office_phone'      => ($request->office_phone)?$request->office_phone:'',
                'street'            => ($request->street)?$request->street:'',
                'fax'               => ($request->fax)?$request->fax:'',
                'city'              => ($request->city)?$request->city:'',
                'email'             => $request->email,
                'state'             => ($request->state)?$request->state:'',
                'website'           => ($request->website)?$request->website:'',
                'post_code'         => ($request->post_code)?$request->post_code:'',
                'country'           => $request->country
            ];
            ProjectInfo_user::where('valid', 1)->where('project_id', $project_id)->update($data);

            DB::table('project')->where('valid', 1)->where('id', $project_id)->update([
                'name'              => $request->company_name
            ]);

            $output['messege'] = 'Company profile has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
        DB::commit();
    }
}
