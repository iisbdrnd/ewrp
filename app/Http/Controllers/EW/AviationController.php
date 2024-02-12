<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwAviation;
use App\Model\EwAviationBill;
use App\Model\EwChartOfAccounts;

class AviationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('ew.aviation.list', $data);
    }

    public function aviationInfoListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['company_name', 'account_name','account_code', 'contact_person', 'contact_no', 'address']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['aviation'] = EwAviation::valid()
            ->where(function($query) use ($search)
            {
                $query->where('account_code', 'LIKE', '%'.$search.'%')
                    ->orWhere('company_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('account_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('contact_person', 'LIKE', '%'.$search.'%')
                    ->orWhere('contact_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('address', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('ew.aviation.listData', $data);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        // $data['ewAccount'] = EwChartOfAccounts::valid()->get();
        $data = array_merge($data, AccountController::accPayableComboData());
        return view('ew.aviation.create', $data);
    }

    public function add()
    {
        //
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
        $account_code=$request->account_code;
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'company_name'     => 'required',
            'account_name'     => 'required',
            'account_code'     => 'required',
            'address'          => 'required',
            'contact_person'   => 'required',
            'contact_no'       => 'required'

        ]);

        if ($validator->passes()) {
            // EwAviation::create($input);
            EwAviation::create([
                'company_name'                 => $request->company_name,
                'account_name'                 => $request->account_name,
                'account_code'                 => $request->account_code,
                'address'                      => $request->address,
                'contact_person'               => $request->contact_person,
                'contact_no'                   => $request->contact_no,

            ]);
            $output['messege'] = 'Aviation has been created';
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
        $data['aviationDetails'] = EwAviation::valid()->where('id', $id)->first();
        $data = array_merge($data, AccountController::accPayableComboData());
        return view('ew.aviation.update', $data);
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
            'company_name'            => 'required',
            'account_name'            => 'required',
            'account_code'            => 'required',
            'address'                 => 'required',
            'contact_person'          => 'required',
            'contact_no'              => 'required'            
        ]);
        if ($validator->passes()) {
        $data = [
            'company_name'            => $request->company_name,
            'account_name'            => $request->account_name,
            'account_code'            => $request->account_code,
            'address'                 => $request->address,
            'contact_person'          => $request->contact_person,
            'contact_no'              => $request->contact_no,            

        ];
        EwAviation::valid()->find($id)->update($data);

        $output['messege'] = 'Company has been updated';
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
        $EwAviationBill = EwAviationBill::where('aviation_id', $id)->first();
        if (!empty($EwAviationBill)) {
           echo "This Aviation is used. You can not delete this!!!";
        }
        else{
            EwAviation::valid()->find($id)->delete();
        }
        
    }

    public static function cashBankComboData() {
        //Get cash upper_control_code
        $cash_account = EwAccountConfiguration::valid()->where('particular', 'cash')->first()->account_code;
        $cash_account_control = substr($cash_account,0,4);
        //Get bank upper_control_code
        $bank_account = EwAccountConfiguration::valid()->where('particular', 'bank')->first()->account_code;
        $bank_account_control = substr($bank_account,0,4);

        $data['accountLevelOfThree']  = EwChartOfAccounts::Valid()
            ->where(function($query) use($cash_account, $bank_account)
            {
                $query->where('account_code', '=', $cash_account)
                      ->orWhere('account_code', '=', $bank_account);
            })
            ->get()
            ->keyBy('general_control_code');

        $data['accountLevelOfFour'] = EwChartOfAccounts::Valid()->where('account_level', 4)
            ->where(function($query) use($cash_account_control, $bank_account_control)
            {
                $query->where('upper_control_code', '=', $cash_account_control)
                      ->orWhere('upper_control_code', '=', $bank_account_control);
            })
            ->orderBy('account_code')->get()->groupBy('upper_control_code');

        $data['bankList'] = EwChartOfAccounts::Valid()->where('account_level', 4)->where('upper_control_code', $bank_account_control)->orderBy('account_code')->get()->pluck('account_code')->all();

        return $data;
    }

    public static function projectAccountCode(Request $request) {
        $project_id = $request->ew_project_id;

        $process_cost_code = '';
        $upper_control_code = '';
        if($project_id>0) {
            $process_cost_code = EwAccountConfiguration::valid()->where('particular', 'process_cost')->first()->account_code;
            $upper_control_code = substr($process_cost_code, 0, 4);
        }

        $data['payment_accounts']  = EwChartOfAccounts::Valid()
            ->where('account_level', 3)
            ->where(function($query) use ($project_id, $process_cost_code)
            {
                if($project_id>0) {
                    $query->where('account_code', $process_cost_code);
                } else {
                    $query->where('main_code', 1)->orWhere('main_code', 2)->orWhere('main_code', 4);
                }
            })
            ->get()
            ->keyBy('general_control_code');
        $data['payment_accounts_level_four'] = EwChartOfAccounts::Valid()
            ->where('account_level', 4)
            ->where(function($query) use ($project_id, $upper_control_code)
            {
                if($project_id>0) {
                    $query->where('upper_control_code', $upper_control_code);
                } else {
                    $query->where('main_code', 1)->orWhere('main_code', 2)->orWhere('main_code', 4);
                }
            })
            ->orderBy('account_code')
            ->get()
            ->groupBy('upper_control_code');

        return view('ew.accounts.projectAccountCode', $data);
    }



    public function accountCode(Request $request)
    {
        $accountCode = EwChartOfAccounts::valid()->where('account_head', $request->accountId)->first();
        $output['account_code'] = $accountCode->account_code;
        
        echo json_encode($output);
    }
}
