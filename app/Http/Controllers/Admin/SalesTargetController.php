<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use DateTime;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CrmSalesTarget;
use App\Model\CrmJobArea_user;
use App\Model\EmployeeBasicInfo_user;
use App\Model\CrmCompanyTarget;
use App\Model\Project;
use App\Model\User_user;

class SalesTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('admin.salesTarget.list', $data);
    }

    public function salesTargetListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $user_id = Auth::user()->get()->id;
        $project_id = Auth::user()->get()->project_id;


        $ascDesc = Helper::ascDesc($data, ['name', 'target_amount']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['crmSalesTargetAmount'] = CrmSalesTarget::leftJoin('crm_currency', 'crm_sales_target.currency_id', '=', 'crm_currency.id')  
            ->leftJoin('users', 'crm_sales_target.user_id', '=', 'users.id')
            ->join('crm_company_target', 'crm_sales_target.company_target_id', '=', 'crm_company_target.id')
            ->select("crm_sales_target.*", "crm_company_target.from_date", "crm_company_target.to_date", "crm_company_target.target_amount as company_target_amount", "crm_currency.html_code", 'users.name as employee_name')
            ->where(function($query) use ($search)
            {
                $query->where('crm_sales_target.target_amount', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.name', 'LIKE', '%'.$search.'%');
            })
            ->where('crm_sales_target.project_id', $project_id)
            ->where('crm_sales_target.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
        
        return view('admin.salesTarget.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $project_id = Auth::user()->get()->project_id;

        $data['employee'] = EmployeeBasicInfo_user::valid()->get();
        $data['crmCompanyTargets'] = CrmCompanyTarget::valid()->get();
        $project_default_currency = Project::where('valid', 1)->find($project_id);
        $data['currency'] = DB::table('crm_currency')->find($project_default_currency->default_currency);
        
        return view('admin.salesTarget.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::user()->get()->project_id;
        $validator = Validator::make($input, [
            'user_id'               => 'required',
            'target_amount'         => 'required'
        ]);

        if ($validator->passes()) {

            $projectCurrency = Project::where('valid', 1)->find($project_id);
            $input['currency_id'] = $projectCurrency->default_currency;
            CrmSalesTarget::create($input);
            $output['messege'] = 'Sales target has been created';
            $output['msgType'] = 'success';
            } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $project_id = Auth::user()->get()->project_id;
        $data['salesTargetAmount'] = $salesTargetAmount = CrmSalesTarget::leftJoin('users', 'crm_sales_target.user_id', '=', 'users.id')
                        ->select('crm_sales_target.*', 'users.name as employee_name')
                        ->where('crm_sales_target.project_id', $project_id)
                        ->where('crm_sales_target.valid', 1)
                        ->find($id);
        $data['currency'] = DB::table('crm_currency')->find($salesTargetAmount->currency_id);
        $data['crmCompanyTargets'] = CrmCompanyTarget::valid()->get();


        return view('admin.salesTarget.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::user()->get()->project_id;

        $validator = Validator::make($input, [
            'user_id'           => 'required',
            'target_amount'     => 'required'
        ]);

        if ($validator->passes()) {

            $projectCurrency = Project::where('valid', 1)->find($project_id);
            $input['currency_id'] = $projectCurrency->default_currency;
            CrmSalesTarget::valid()->find($id)->update($input);
            $output['messege'] = 'Sales target amount has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        CrmSalesTarget::valid()->find($id)->delete();
    }
}
