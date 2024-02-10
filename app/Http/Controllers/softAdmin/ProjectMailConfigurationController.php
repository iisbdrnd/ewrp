<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Mail;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\AdminDesignation;
use App\Model\EmployeeDesignation_admin;
use App\Model\Project;
use App\Model\ProjectMailConfiguration_admin;
use App\Model\CrmPaymentReminder;

class ProjectMailConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['project_id'] = $project_id = $request->input('data');

        $data['mailConfigaration'] = ProjectMailConfiguration_admin::where('valid', 1)->where('project_id', $project_id)->first();
        
        return view('softAdmin.projectMailConfigaration.create', $data);
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
        
        $validator = [
            'mail_from_email'     => 'required',
            'mail_from_name'      => 'required',
            'mail_protocal'       => 'required'
        ];

        if($request->mail_protocal==1) {
            $validator = array_merge($validator, [
                'smtp_hostname'       => 'required',
                'smtp_username'       => 'required|email',
                'smtp_password'       => 'required',
                'smtp_port'           => 'required',
                'smtp_secure'         => 'required'
            ]);
        }
        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {
            $crmMailConfigaration = ProjectMailConfiguration_admin::where('valid', 1)->where('project_id', $request->project_id)->first();
            if (!empty($crmMailConfigaration)) {
                $crmMailConfigaration->update($input);
                $output['messege'] = 'Project mail configuration has been updated';
                $output['msgType'] = 'success';
            }else{
                ProjectMailConfiguration_admin::create($input); 
                $output['messege'] = 'Project mail configuration has been created';
                $output['msgType'] = 'success';
            }
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function configarationTest(Request $request)
    {
        $mailConfig = ProjectMailConfiguration_admin::where('valid', 1)->first();

        if (!empty($mailConfig)) {
			Helper::mailConfig();
            $mail = Mail::send('emails.configureTest', [], function($message) use ($mailConfig)
            {
                $message->subject("Test Mail for Email Configuration");
                $message->to($mailConfig->mail_from_email, $mailConfig->mail_from_name);
            });
            $output['messege'] = $mail;
            $output['msgType'] = 'success';
        } else {
            $output['messege'] = 'Please, update your E-mail configuration';
            $output['msgType'] = 'danger';
        }

        echo json_encode($output);
    }
}
