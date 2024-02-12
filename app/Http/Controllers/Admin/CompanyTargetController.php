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
use App\Model\CrmCompanyTarget;
use App\Model\Project;
use App\Model\CrmJobArea_user;

class CompanyTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('admin.companyTarget.list', $data);
    }

    public function companyTargetListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $user_id = Auth::user()->get()->id;
        $project_id = Auth::user()->get()->project_id;


        $ascDesc = Helper::ascDesc($data, ['target_amount']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['comTargetAmo'] = CrmCompanyTarget::leftJoin('crm_currency', 'crm_company_target.currency_id', '=', 'crm_currency.id')  
            ->select("crm_company_target.*", "crm_currency.html_code")
            ->where(function($query) use ($search)
            {
                $query->where('crm_company_target.target_amount', 'LIKE', '%'.$search.'%');
            })
            ->where('crm_company_target.project_id', $project_id)
            ->where('crm_company_target.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
            
        return view('admin.companyTarget.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $project_id = Auth::user()->get()->project_id;
        $data['inputData'] = $request->all();
        $data['crmCurrency'] = DB::table('crm_currency')->get();
        $project_default_currency = Project::where('valid', 1)->find($project_id);
        $data['currency'] = DB::table('crm_currency')->find($project_default_currency->default_currency);
        
        return view('admin.companyTarget.create', $data);
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
            'from_date'         => 'required',
            'to_date'           => 'required',
            'target_amount'     => 'required'
        ]);

        if ($validator->passes()) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $fromDate = DateTime::createFromFormat('m/Y', $from_date);
            $fromDate->modify('first day of this month');
            $input['from_date'] = $fromDate->format('Y-m-d');

            $toDate = DateTime::createFromFormat('m/Y', $to_date);
            $input['to_date'] = $toDate->format('Y-m-t');

            $projectCurrency = Project::where('valid', 1)->find($project_id);
            $input['currency_id'] = $projectCurrency->default_currency;

            CrmCompanyTarget::create($input);
            $output['messege'] = 'Area has been created';
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
        $data['companyTargetAmount'] = $companyTargetAmount = CrmCompanyTarget::valid()->find($id);
        $data['currency'] = DB::table('crm_currency')->find($companyTargetAmount->currency_id);
        
        return view('admin.companyTarget.update', $data);
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
            'from_date'         => 'required',
            'to_date'           => 'required',
            'target_amount'     => 'required'
        ]);

        if ($validator->passes()) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $fromDate = DateTime::createFromFormat('m/Y', $from_date);
            $fromDate->modify('first day of this month');
            $input['from_date'] = $fromDate->format('Y-m-d');

            $toDate = DateTime::createFromFormat('m/Y', $to_date);
            $input['to_date'] = $toDate->format('Y-m-t');

            $projectCurrency = Project::where('valid', 1)->find($project_id);
            $input['currency_id'] = $projectCurrency->default_currency;

            CrmCompanyTarget::valid()->find($id)->update($input);
            $output['messege'] = 'Company target amount has been updated';
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
        CrmCompanyTarget::valid()->find($id)->delete();
    }
}
