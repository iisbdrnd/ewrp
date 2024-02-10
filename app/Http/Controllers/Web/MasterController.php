<?php namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use Mail;
use Image;
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
use App\Model\TubClient_webAuth;
use App\Model\TubClient_web;

class MasterController extends Controller {  
    public function getLogin(Request $request)
    {
        $data['prevUrl'] = $request->prevUrl;
        if(isset($request->prevUrl)){
            return view('web.login', $data);
        }else{
            return view('web.login');
        }
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
        if (Auth::guard('web')->attempt($data)) {
            if(isset($request->prevUrl)){
                return redirect()->route('product', [$request->prevUrl]);
            }else{
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('login')->with('message', 'Email or password is not correct.');
        }
    }
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('home');
    }

    public function profile($id)
    {
        $data['client'] = TubClient_web::find($id);
        return view('web.profile', $data);
    }

    public function profileUpdate(Request $request){
        DB::beginTransaction();
        $input = $request->all();

        $validator = Validator::make($input, [
            'name'           => 'required',
            'mobile'         => 'required',
            'company'        => 'required',
            // "department_id"  => "required",
            // "designation_id" => "required",
        ]);
        if ($validator->passes()) {
            $authId = Auth::guard('web')->id();
            $generalUserInfo = TubClient_web::find($authId)->update([
                'name'                  => $request->name,
                'mobile'                => $request->mobile,
                'company'               => $request->company,
            ]);
            
            $output['messege'] = 'Profile Update Successful';
            $output['msgType'] = 'success';
            $output['status'] = 1;
        } else {
            $output['status'] = 0;
            $output = Helper::vError($validator);
        }
        DB::commit();
        return response($output);
    }

    public function cropImagePage($id)
    {
        $data['client'] = TubClient_web::valid()->where('id', $id)->first();
        return view('web.cropImagePage', $data);
    }

    public function saveImage()
    {
        $client_id = Auth::guard('web')->id();

        $data = $_POST['image'];
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);
        $imageName = time().'.png';
        file_put_contents('public/uploads/client/'.$imageName, $data);
        //create instance
        $img = Image::make('public/uploads/client/'.$imageName);
        //resize image
        $img->resize(80, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save('public/uploads/client/thumb/'.$imageName); //save the same file as thumb
        $client = TubClient_web::find($client_id);
        if(!empty($client->image)) {
            @unlink('./public/uploads/client/' . $client->image);
            @unlink('./public/uploads/client/thumb/' . $client->image);
        }
        //UPDATE NAME TO TRAINEE USERS TABLE
        $client->update(array('image'=>$imageName));
        //UPDATE NAME TO TRAINEE USERS INFO TABLE
        // $userInfo = EnTraineeUserInfo::where('valid', 1)->where('user_id', $user_id)->first();
        // $input['image'] = $imageName;
        // if($userInfo) {
        //     $userInfo->update($input);
        // } else {
        //     $input['user_id'] = $user_id;
        //     EnTraineeUserInfo::create($input);
        // }

        return response($imageName);
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

    //Tubingen
    public function tubingen()
    {
        $data['title'] = 'Tubingen | Innovation Information System';
        $data['attr'] = array("callforward"=>"dashButtonCleaner");

        return self::master('provider/tubingen', $data);
    }

    public function tubingen_home() {
        return view('provider.tubingen.welcome');
    }
       
    //For Provider Email Verification
    public function emailVerification(Request $request)
    {
        $token = $request->token;
        $providerUser = TubClient_web::where('verification_code', $token)->valid()->first();
        if(!empty($providerUser)) {
            if($providerUser->email_verified==0) {
                //Set Pass
                return view('web.email_verification', ['token'=>$token]);
            } else {
                //Allready Verified
                return view('provider.account_verified');
            }
        } else {
            //Unathorize Tocken
            return view('provider.unauthorized_token');
        }
    }
    
    public function emailVerificationAction(Request $request)
    {
        $token = $request->token;
        $password = password_hash($request->password, PASSWORD_DEFAULT);
        
        $providerUser = TubClient_web::where('verification_code', $token)->where('valid', 1)->first();
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
                
                return redirect()->route('login')->with(['message' => 'Login Your Account']);
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
    public function forgetPassword(Request $request)
    {
        if (isset($request->message)) {
            $data['message'] = $request->message;
        }else{
            $data['message'] = 'No message';
        }
        return view('web.forgetPassword', $data);
    }

    //FOR FORGOT PASSWORD ACTION
    public function forgetPasswordAction(Request $request)
    {
        $output = array();
        $userInfo = TubClient_web::where('valid', 1)->where('email_verified', 1)->where('email', $request->email)->first();
        if (!empty($userInfo)) {
            $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
            $original_string = implode("", $original_string);
            $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);

            $userInfo->update(["verification_code"=>$verification_code]);
            
            //SEND MAIL
            Helper::mailConfig();
            $email_data['link'] = url('forgot-email-verification?token=' . $verification_code);
            $email_data['userInfo'] = $userInfo;
            Mail::send('emails.forgot_password', $email_data, function($message) use ($request, $userInfo)
            {
                $message->subject('Tubingen Client Password Reset');
                $message->to($request->email, $userInfo->name);
            });
            return redirect()->route('login')->with(['message' => 'Check your email to change password.']);
        } else {
            return redirect()->route('forgetPassword')->with(['message' => 'Please enter registered email.', 'msgType'=>0]);
        }  
    }

    //FOR FORGOT PASSWORD PROVIDER EMAIL VERIFICATION
    public function forgotEmailVerification(Request $request)
    {
        $token = $request->token;
        // dd($token);
        $providerUser = TubClient_web::where('verification_code', $token)->where('valid', 1)->first();
        if(!empty($providerUser)) {
            if($providerUser->email_verified==1) {
                //PASSWORD UPDATE
                return view('web.forgotEmailVerification', ['token'=>$token]);
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
    public function forgotEmailVerificationAction(Request $request)
    {
        $token = $request->token;
        $password = password_hash($request->password, PASSWORD_DEFAULT);
        
        $client = TubClient_web::where('verification_code', $token)->where('valid', 1)->first();

        if(!empty($client)) {
            if($client->email_verified==1) {
                $data = array(
                    'email' => $client->email,
                    'status'   => 'Active',
                    'valid'    => 1
                );

                //Set Pass
                $client->update(['password'=>$password, 'email_verified'=>1, 'verification_code'=>'']);
                $data["password"] = $request->password;

                // if (Auth::guard('web')->attempt($data)) {
                //     $data['isLogin'] = false;
                // } else {
                //     $data['isLogin'] = true;
                // }
                
                // $data['title'] = 'Congratulations!';
                // $data['message'] = 'Your account has been verified!';
                // return redirect()->route('login')->with($data);
                return redirect()->route('login')->with(['message' => 'Password changed successfully', 'msgType'=>0]);
            } else {
                //Allready Verified
                return view('provider.account_verified');
            }
        } else {
            //Unathorize Tocken
            return view('web.unauthorized_token');
        }
    }
    //For Confirmation
    public function confirmation() {
        if(!empty(session('title'))) {
            $data['title']   = session('title');
            $data['message'] = session('message');
            $data['isLogin'] = session('isLogin');

            return view('web.confirmation', $data);
        } else {
            return redirect()->route('home');
        }
        
    }
}