<?php namespace App\Http\Controllers\Provider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use Mail;
use DateInterval;
use Carbon\Carbon;
use App\Model\SoftwareModules_provider;
use App\Model\SoftwareMenu_provider;
use App\Model\Project_provider;
use App\Model\ProjectInfo_provider;
use App\Model\EnProviderUserInfo_provider;
use App\Model\EnProviderUser_provider;
use App\Model\ServiceRequestedChain_provider;
use App\Model\ServiceInfo_provider;

class MasterController extends Controller {  

    public function getLogin()
    {
        return view('provider.login');
    }
    public function postLogin(Request $request)
    {
        $data = array(
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'email_verified' => 1,
            'status'   => 'Active',
            'valid'    => 1
        );
        if (Auth::guard('provider')->attempt($data)) {
            return redirect()->route('provider.apps');
        } else {
            return redirect()->route('provider.login')->with('error', 'Email or password is not correct.');
        }
    }
    public function logout()
    {
        Auth::guard('provider')->logout();
        return redirect()->route('provider.login');
    }

    public function apps(){
        $user_id = Auth::guard('provider')->id();
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['modules'] = $modules = SoftwareModules_provider::providerUserAccessModules($user_id);
        $project = Project_provider::where('id', $project_id)->first();
        $data['projectInfo'] = ProjectInfo_provider::where('project_id', $project_id)->first();
        $data['module_number'] = $module_number = count($modules);

        $data['userImage'] = EnProviderUserInfo_provider::select('image')
            ->where('user_id', $user_id)
            ->where('valid', 1)
            ->where('project_id', $project_id)
            ->first();

        if($module_number == 1) {
            return redirect($modules[0]->url_prefix);
        } else {
            return view('provider.apps', $data);
        }
    }

    public static function master($prefix, $data=array()){
        $user_id = Auth::guard('provider')->id();
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['prefix'] = $prefix;
        $data['modules'] = $modules = SoftwareModules_provider::providerUserAccessModules($user_id);
        $project = Project_provider::where('id', $project_id)->first();
        $data['projectInfo'] = ProjectInfo_provider::where('project_id', $project_id)->first();
        $data['module_number'] = $module_number = count($modules);

        $data['req_receive_notification'] = ServiceRequestedChain_provider::valid()
            ->where('employee_id', $user_id)
            ->where('active_chain_req', 1) //1 = Running Request
            ->where('approve_status', 0)
            ->count();
        $data['request_info_receive_notifi'] = ServiceInfo_provider::valid()
            ->where('req_to_employee_id', $user_id)
            ->where('seen_status', 0)
            ->count();

        $data['userImage'] = EnProviderUserInfo_provider::select('image')
            ->where('user_id', $user_id)
            ->where('valid', 1)
            ->where('project_id', $project_id)
            ->first();


        if(($module_number>0) && (collect($modules->pluck('url_prefix'))->contains($prefix))) {
            $data['module'] = $module = SoftwareModules_provider::where('url_prefix', $prefix)->first();
            $data['software_menus'] = SoftwareMenu_provider::providerUserAccessMenus($user_id, $module->id);
            return view('provider.master', $data);
        } else {
            return redirect()->route('provider.apps');
        }
    }

    /*
        Master:
            1st parameter: url prefix
            2nd parameter: Data
    */

    //Admin
    public function admin()
    {
        $data['title'] = 'Admin | Innovation Information System';
        $data['attr'] = array("callforward"=>"dashButtonCleaner");

        return self::master('provider/admin', $data);
    }

    public function admin_home() {
        return view('provider.admin.home');
    }

    //East West
    public function eastWest()
    {
        $data['title'] = 'East West | Innovation Information System';
        $data['attr'] = array("callforward"=>"dashButtonCleaner");

        return self::master('provider/eastWest', $data);
    }

    public function eastWest_home() {
        return view('provider.eastWest.welcome');
    }
    
    //For Provider Email Verification
    public function email_verification(Request $request)
    {
        $token = $request->token;
        $providerUser = EnProviderUser_provider::where('verification_code', $token)->where('valid', 1)->first();
        if(!empty($providerUser)) {
            if($providerUser->email_verified==0) {
                //Set Pass
                return view('provider.email_verification', ['token'=>$token]);
            } else {
                //Allready Verified
                return view('provider.account_verified');
            }
        } else {
            //Unathorize Tocken
            return view('provider.unauthorized_token');
        }
    }
    
    public function email_verification_action(Request $request)
    {
        $token = $request->token;
        $password = password_hash($request->password, PASSWORD_DEFAULT);
        
        $providerUser = EnProviderUser_provider::where('verification_code', $token)->where('valid', 1)->first();
        if(!empty($providerUser)) {
            if($providerUser->email_verified==0) {
                $data = array(
                    'email' => $providerUser->email,
                    'status'   => 'Active',
                    'valid'    => 1
                );

                //Set Pass
                $providerUser->update(['password'=>$password, 'email_verified'=>1, 'verification_code'=>'']);
                $data["password"] = $request->password;

                if (Auth::guard('provider')->attempt($data)) {
                    $data['isLogin'] = false;
                } else {
                    $data['isLogin'] = true;
                }
                
                $data['title'] = 'Congratulations!';
                $data['message'] = 'Your account has been verified!';
                return redirect()->route('provider.confirmation')->with($data);
            } else {
                //Allready Verified
                return view('provider.account_verified');
            }
        } else {
            //Unathorize Tocken
            return view('provider.unauthorized_token');
        }
    }

    //For Confirmation
    public function confirmation() {
        if(!empty(session('title'))) {
            $data['title'] = session('title');
            $data['message'] = session('message');
            $data['isLogin'] = session('isLogin');
            return view('provider.confirmation', $data);
        } else {
            return redirect()->route('provider.apps');
        }
        
    }

    
    //For Confirmation
    public function unauthorized_token()
    {
        return view('provider.unauthorized_token');
    }

    //FOR CONFIRMATION
    public function account_verified()
    {
        return view('provider.account_verified');
    }

    //FOR FORGOT PASSWORD VIEWPAGE
    public function forgotPassword()
    {

        return view('provider.forgotPassword');
    }

    //FOR FORGOT PASSWORD ACTION
    public function forgotPasswordAc(Request $request)
    {
        $output = array();
        $userInfo = EnProviderUser_provider::where('valid', 1)->where('email_verified', 1)->where('email', $request->email)->first();

        if (!empty($userInfo)) {
            $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
            $original_string = implode("", $original_string);
            $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);

            $userInfo->update(["verification_code"=>$verification_code]);
            
            //SEND MAIL
            Helper::mailConfig();
            $email_data['link'] = url('provider/forgot_email_verification?token=' . $verification_code);
            $email_data['userInfo'] = $userInfo;
            Mail::send('emails.forgot_password', $email_data, function($message) use ($request, $userInfo)
            {
                $message->subject('Eduvess Provider Password Reset');
                $message->to($request->email, $userInfo->name);
            });

            return redirect()->route('provider.login')->with('messege', 'Check your email to change password.')->with('msgType', 'success');
        } else {
            return redirect()->route('provider.forgotPassword')->with('messege', 'Please enter registered email.');
        }  
    }

    //FOR FORGOT PASSWORD PROVIDER EMAIL VERIFICATION
    public function forgot_email_verification(Request $request)
    {
        $token = $request->token;
        $providerUser = EnProviderUser_provider::where('verification_code', $token)->where('valid', 1)->first();
        if(!empty($providerUser)) {
            if($providerUser->email_verified==1) {
                //PASSWORD UPDATE
                return view('provider.forgot_email_verification', ['token'=>$token]);
            } else {
                //UNVERIFIED ACCOUNT
                return view('provider.email_verification', ['token'=>$token]);
            }
        } else {
            //Unathorize Tocken
            return view('provider.unauthorized_token');
        }
    }

    //FOR FORGOT PASSWORD PROVIDER EMAIL VERIFICATION ACTION
    public function forgot_email_verification_action(Request $request)
    {
        $token = $request->token;
        $password = password_hash($request->password, PASSWORD_DEFAULT);
        
        $providerUser = EnProviderUser_provider::where('verification_code', $token)->where('valid', 1)->first();
        if(!empty($providerUser)) {
            if($providerUser->email_verified==1) {
                $data = array(
                    'email' => $providerUser->email,
                    'status'   => 'Active',
                    'valid'    => 1
                );

                //Set Pass
                $providerUser->update(['password'=>$password, 'email_verified'=>1, 'verification_code'=>'']);
                $data["password"] = $request->password;

                if (Auth::guard('provider')->attempt($data)) {
                    $data['isLogin'] = false;
                } else {
                    $data['isLogin'] = true;
                }
                
                $data['title'] = 'Congratulations!';
                $data['message'] = 'Your account has been verified!';
                return redirect()->route('provider.confirmation')->with($data);
            } else {
                //Allready Verified
                return view('provider.account_verified');
            }
        } else {
            //Unathorize Tocken
            return view('provider.unauthorized_token');
        }
    }
}