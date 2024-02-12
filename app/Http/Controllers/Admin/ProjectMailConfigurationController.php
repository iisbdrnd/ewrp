<?php

namespace App\Http\Controllers\Admin;

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
use App\Model\ProjectMailConfiguration_user;
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
        $data['crmMailConfigaration'] = ProjectMailConfiguration_user::valid()->first();
        
        return view('admin.crmMailConfigaration.create', $data);
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
        $project_id = Auth::user()->get()->project_id;
        
        $validator = [
            'mail_from_email'     => 'required',
            'mail_from_name'      => 'required',
            'mail_protocal'       => 'required'
        ];

        $validator = Validator::make($input, [
            'mail_from_email'       => 'required',
            'mail_from_name'        => 'required',
            'smtp_hostname'         => 'required',
            'smtp_username'         => 'required',
            'smtp_password'         => 'required',
            'smtp_port'             => 'required',
            'smtp_secure'           => 'required'
        ]);

        if ($validator->passes()) {
            $smtpInfo = array(
                "mail_from_email"   => $request->mail_from_email,
                "mail_from_name"    => $request->mail_from_name,
                "mail_protocal"     => 1,
                "smtp_password"     => $request->smtp_password,
                "smtp_hostname"     => $request->smtp_hostname,
                "smtp_username"     => $request->smtp_username,
                "smtp_port"         => $request->smtp_port,
                "smtp_secure"       => $request->smtp_secure,
                "project_id"        => $project_id
                
            );

            $crmMailConfigaration = ProjectMailConfiguration_user::valid()->first();
            if (!empty($crmMailConfigaration)) {
                $crmMailConfigaration->update($smtpInfo);
                $output['messege'] = 'Project mail configuration has been updated';
                $output['msgType'] = 'success';
            }else{
                ProjectMailConfiguration_user::create($smtpInfo); 
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
        $mailConfig = ProjectMailConfiguration_user::valid()->first();

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
