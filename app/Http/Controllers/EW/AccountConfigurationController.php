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
use App\Model\EwReferences;
use App\Model\EwCandidateTransaction;
use App\Model\EwAccountTransaction;
use App\Model\EwCandidates;
use App\Model\EwChartOfAccounts;
use App\Model\EwProjects;
use App\Model\EwAccountConfiguration;

class AccountConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['accountConfigurationCodes'] = EwAccountConfiguration::valid()->get()->chunk(2);

        return view('ew.accountConfiguration.create', $data);
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
        unset($input['_token']);
        $validatorRules = [];
        foreach ($input as $field => $value) {
            $validatorRules[$field] = 'required';
        }

        $validator = Validator::make($input, $validatorRules);

        if ($validator->passes()) {
            $accountCodeOk = true;
            foreach ($input as $field => $accountCode) {
                $configureInfo = EwChartOfAccounts::valid()->where('account_code', $accountCode)->first();

                if (empty($configureInfo)) {
                    $accountCodeOk = false;
                    $errorField = $field;
                    break;
                }
            }
            if ($accountCodeOk) {
                foreach ($input as $field => $accountCode) {
                    EwAccountConfiguration::valid()->where('particular', $field)->update([
                        'account_code'  => $accountCode
                    ]);                    
                }
                $output['messege'] = 'Account configuration has been updated';
                $output['msgType'] = 'success';
            }else{
                $errorFieldName = EwAccountConfiguration::valid()->where('particular', $errorField)->first()->particular_name;
                $output['messege'] = $errorFieldName.' Account code is not exist!';
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
