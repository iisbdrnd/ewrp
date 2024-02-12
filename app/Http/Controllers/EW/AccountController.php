<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwAccountConfiguration;
use App\Model\EwChartOfAccounts;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('ew.accounts.list', $data);
    }

    public function accountListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['account_code', 'account_head']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['accounts'] = EwChartOfAccounts::valid()
            ->where(function($query) use ($search)
            {
                $query->where('account_code', 'LIKE', '%'.$search.'%')
                    ->orWhere('account_head', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('ew.accounts.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $accounts = EwChartOfAccounts::valid()->select('account_code', 'account_head', 'account_level')->get()->keyBy('account_code')->all();
        $data['accounts'] = str_replace("\\", "\\\\", json_encode($accounts, JSON_HEX_APOS | JSON_HEX_QUOT));

        return view('ew.accounts.create', $data);
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'account_code' => 'required',
            'account_head' => 'required'
        ]);

        if ($validator->passes()) {
            $account_code = $request->account_code;
            $account_code_exist = EwChartOfAccounts::valid()->where('account_code', $account_code)->first();
            if(empty($account_code_exist)) {
                $account_code_array = str_split($account_code);
                if(count($account_code_array)==10) {
                    if($account_code_array[0]==0) {
                        $account_code_format=false;
                    } else {
                        $account_code_format=true;
                        $account_level=1;
                        $level1_code=$account_code_array[0].'000000000';
                        $level2_code=$account_code_array[0].$account_code_array[1].'00000000';
                        $level3_code=$account_code_array[0].$account_code_array[1].$account_code_array[2].$account_code_array[3].'000000';
                        foreach($account_code_array as $key=>$account_code_ar) {
                            $account_code_ar = ord($account_code_ar);
                            if($account_code_ar<48 || ($account_code_ar>57 && $account_code_ar<65) || ($account_code_ar>90 && $account_code_ar<97) || $account_code_ar>122) {
                                $account_code_format=false; break;
                            }
                            if($key==1 && $account_code_ar!=48) {
                                $account_level=2;
                            } else if($key>1 && $key<=3 && $account_code_ar!=48) {
                                $account_level=3;
                            } else if($key>3 && $key<=9 && $account_code_ar!=48) {
                                $account_level=4;
                            }
                        }
                    }

                    if($account_code_format) {
                        if($account_level>1) {
                            $account_level1_exist = EwChartOfAccounts::valid()->where('account_code', $level1_code)->first();
                            if(empty($account_level1_exist)) {
                                $output['messege'] = 'Main Classification is not exist';
                                $output['msgType'] = 'danger';
                            } else {
                                $account_level2_exist = EwChartOfAccounts::valid()->where('account_code', $level2_code)->first();
                                if($account_level>2 && empty($account_level2_exist)) {
                                    $output['messege'] = 'General Classification is not exist';
                                    $output['msgType'] = 'danger';
                                } else {
                                    $account_level3_exist = EwChartOfAccounts::valid()->where('account_code', $level3_code)->first();
                                    if($account_level>3 && empty($account_level3_exist)) {
                                        $output['messege'] = 'Control Classification is not exist';
                                        $output['msgType'] = 'danger';
                                    } else {
                                        if($account_level==2) { $upper_control_code=$account_level1_exist->general_control_code; }
                                        if($account_level==3) { $upper_control_code=$account_level2_exist->general_control_code; }
                                        if($account_level==4) { $upper_control_code=$account_level3_exist->general_control_code; }
                                        EwChartOfAccounts::create([
                                            "account_code"          => $account_code,
                                            "main_code"             => $account_code_array[0],
                                            "classified_code"       => $account_code_array[1],
                                            "control_code"          => $account_code_array[2].$account_code_array[3],
                                            "upper_control_code"    => $upper_control_code,
                                            "general_control_code"  => $account_code_array[0].$account_code_array[1].$account_code_array[2].$account_code_array[3],
                                            "subsidiary_code"       => $account_code_array[4].$account_code_array[5].$account_code_array[6].$account_code_array[7].$account_code_array[8].$account_code_array[9],
                                            "account_head"          => $request->account_head,
                                            "account_level"         => $account_level
                                        ]);
                                        $output['messege'] = 'Account has been created';
                                        $output['msgType'] = 'success';

                                        $account = ['account_code'=>$account_code, 'account_head'=>$request->account_head, 'account_level'=>(string)$account_level];
                                        $output['account'] = str_replace("\\", "\\\\", json_encode($account, JSON_HEX_APOS | JSON_HEX_QUOT));
                                    }
                                }
                            }
                        } else {
                            $output['messege'] = 'Main Classification can not be add or change';
                            $output['msgType'] = 'danger';
                        }
                    } else {
                        $output['messege'] = 'Account Code Format is wrong';
                        $output['msgType'] = 'danger';
                    }
                } else {
                    $output['messege'] = 'Account Code must be 10 digit';
                    $output['msgType'] = 'danger';
                }
            } else {
                $output['messege'] = 'Account Code is exist';
                $output['msgType'] = 'danger';
            }
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
        $data['accountDetails'] = EwChartOfAccounts::valid()->where('id', $id)->first();

        return view('ew.accounts.update', $data);
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
            'account_head'      => 'required'
        ]);

        if ($validator->passes()) {
            EwChartOfAccounts::valid()->find($id)->update([
                'account_head'  => $request->account_head
            ]);

            $output['messege'] = 'Account code has been updated';
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
        EwChartOfAccounts::valid()->find($id)->delete();
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

    public static function accPayableComboData() {
        //Get A/P upper_control_code
        $account_payable = EwAccountConfiguration::valid()->where('particular', 'acc_payable')->first()->account_code;
        $account_payable_control = substr($account_payable,0,4);

        $data['accountLevelOfThree']  = EwChartOfAccounts::Valid()
            ->where(function($query) use($account_payable)
            {
                $query->where('account_code', '=', $account_payable);
            })
            ->get()
            ->keyBy('general_control_code');

        $data['accountLevelOfFour'] = EwChartOfAccounts::Valid()->where('account_level', 4)
            ->where(function($query) use($account_payable_control)
            {
                $query->where('upper_control_code', '=', $account_payable_control);
            })
            ->orderBy('account_code')->get()->groupBy('upper_control_code');
        $data['acc_payable_id'] = EwChartOfAccounts::Valid()->where('account_level', 4)
            ->where(function($query) use($account_payable_control)
            {
                $query->where('upper_control_code', '=', $account_payable_control);
            })
            ->get();
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
}
