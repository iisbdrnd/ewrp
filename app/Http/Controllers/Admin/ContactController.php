<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Mail;
use Helper;
use DateTime;
use Collection;
use Validator;
use DateInterval;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\User_user;

class ContactController extends Controller {

    public function index(Request $request) {
        $data['inputData']  = $request->all();
        $data['user_id'] = $user_id = Auth::user()->get()->id;

        $data['userInfo'] = User_user::valid()->where('id', $user_id)->find($user_id);


        return view('crm.contact.index', $data);
    }



    public function storeContact(Request $request) {
        
        $output = array();
        $data['user_id'] = $user_id = Auth::user()->get()->id;
        $input = array(
                    "name"    			=> $request->name,
                    "email"           	=> $request->email,
                    "message"        	=> $request->message
                );
        $validator = Validator::make($input, [
                    "name"        		=> "required",
                    "email"    			=> "required",
                    "message"           => "required"
                ]);

        if ($validator->passes()) {
            
            $user = array(
                    "name"              => $request->name,
                    "email"             => $request->email,
                    "message"           => $request->message
                );

			// Helper::mailConfig();
            // Mail::send('crm.test.emailTemplate_2', ['user' => $user], function ($m) use ($user) {
            //     $m->from('no_reply@demo.leadvas.com', 'Your Application');

            //     $m->to($user->email, $user->name)->subject('Your Reminder!');
            // }); 
               
            DB::table('crm_user_contact')->insert($input);

            $output['messege'] = 'Email has been send';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }
}
