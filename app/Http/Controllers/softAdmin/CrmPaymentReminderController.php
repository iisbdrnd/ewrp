<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\AdminDesignation;
use App\Model\EmployeeDesignation_admin;
use App\Model\Project;
use App\Model\CrmPaymentReminder;

class CrmPaymentReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['crmPaymentReminder'] = CrmPaymentReminder::valid()->first();

        
        return view('softAdmin.paymentReminder.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function store(Request $request)
    {
        $output = array();
        $input = $request->all(); 

        $validator = Validator::make($input, [
            'general_before_days'       => 'required',
            'general_subject'           => 'required',
            'general_description'       => 'required',
            'extreme_before_days'       => 'required',
            'extreme_subject'           => 'required',
            'extreme_description'       => 'required'
        ]);

        if ($validator->passes()) {
            $crmPaymentReminder = CrmPaymentReminder::valid()->first();
            if (!empty($crmPaymentReminder)) {
                $crmPaymentReminder->update($input);
                $output['messege'] = 'Payment Reminder has been updated';
                $output['msgType'] = 'success';
            }else{
                CrmPaymentReminder::create($input); 
                $output['messege'] = 'Payment Reminder has been created';
                $output['msgType'] = 'success';
            }
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }
}
