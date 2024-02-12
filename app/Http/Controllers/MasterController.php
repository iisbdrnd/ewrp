<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\SoftwareModules;
use App\Model\SoftwareMenu;
use App\Model\Project;
use App\Model\ProjectInfo;
use App\Model\User_user;
use App\Model\EmployeeBasicInfo_user;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use DB;
use Auth;
use Helper;
use DateTime;
use DateInterval;
use Mail;

class MasterController extends Controller
{
    //Login
    public function getLogin()
    {
        return view('login');
    }

    //Post Login
    public function postLogin(Request $request)
    {
        $data = array(
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'email_verified' => 1,
            'status'   => 'Active',
            'valid'    => 1
        );

        if (Auth::user()->attempt($data)) {
            return redirect()->route('apps');
        } else {
            return redirect()->route('login')->with('error', 'Email or password is not correct.');
        }
    }

    //Post Login API
    public function postLoginExternal(Request $request)
    {
        if(!empty($request->input('auth_code'))) {
            $data = array(
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'auth_code' => $request->input('auth_code'),
                'email_verified' => 1,
                'status'   => 'Active',
                'valid'    => 1
            );

            if (Auth::user()->attempt($data)) {
                User_user::valid()->find(Auth::user()->get()->id)->update(["auth_code"=>""]);
                return redirect()->route('apps');
            } else {
                return redirect()->to($request->input('fail_url'));
            }
        } else {
            return redirect()->to($request->input('fail_url'));
        }
    }

    //Logout
    public function logout()
    {
        Auth::user()->logout();
        return redirect()->route('login');
    }

    //All Apps
    public function apps()
    {
        //Countdown
        /*$curTime = new DateTime('');
        $curTimestamp = $curTime->getTimestamp();

        $endingTime = new DateTime('2018-05-23 17:00:00');
        $endingTimestamp = $endingTime->getTimestamp();

        if($endingTimestamp<=$curTimestamp) {
            return view('timeOver');
        }*/
        //-------

        $user_id = Auth::user()->get()->id;
        $project_id = Auth::user()->get()->project_id;
        $data['modules'] = $modules = SoftwareModules::userAccessModules($user_id);
        $project = Project::where('id', $project_id)->first();
        $data['projectInfo'] = ProjectInfo::where('project_id', $project_id)->first();
        $data['module_number'] = $module_number = count($modules);

        $data['userImage'] = EmployeeBasicInfo_user::select('image')
            ->where('user_id', $user_id)
            ->where('valid', 1)
            ->where('project_id', $project_id)
            ->first();

        if($module_number == 1) {
            return redirect($modules[0]->url_prefix);
        } else {
            return view('apps', $data);
        }
    }

    public static function master($prefix, $data=array())
    {
        $user_id = Auth::user()->get()->id;
        $project_id = Auth::user()->get()->project_id;
		$data['uid'] = 'uid'.$user_id;
        $data['prefix'] = $prefix;
        $data['modules'] = $modules = SoftwareModules::userAccessModules($user_id);
        $project = Project::where('id', $project_id)->first();
        $data['projectInfo'] = ProjectInfo::where('project_id', $project_id)->first();
        $data['module_number'] = $module_number = count($modules);

        $data['userImage'] = EmployeeBasicInfo_user::select('image')
            ->where('user_id', $user_id)
            ->where('valid', 1)
            ->where('project_id', $project_id)
            ->first();

        if(($module_number>0) && (collect($modules->pluck('url_prefix'))->contains($prefix))) {
            $data['module'] = $module = SoftwareModules::where('url_prefix', $prefix)->first();
            $data['software_menus'] = SoftwareMenu::userAccessMenus($user_id, $module->id);

            return view('master', $data);
        } else {
            return redirect()->route('apps');
        }
    }

	/*
		Master:
			1st parameter: url prefix
			2nd parameter: Data
	*/

    //Administrator
    public function administrator()
    {
        $data['title'] = 'Administrator | Innovation Information System';

        return self::master('admin', $data);
    }

    public function administrator_home() {
        return view('admin.home');
    }

    //East West
    public function eastWest()
    {
        $data['title'] = 'East West Human Resource | Innovation Information System';
        return self::master('eastWest', $data);
    }

    public function ew_home() {
        return view('ew.welcome');
    }

    //Recruitment
    public function recruitment()
    {
        $data['title'] = 'Recruitment | Innovation Information System';
        return self::master('recruitment', $data);
    }
    public function recruitment_home() {
        return view('recruitment.home');
    }

    //For forgot password
    public function forgotPassword() {
        return view('forgotPassword');
    }

	//For forgot password
    public function forgotPasswordAction(Request $request) {

        $email = $request->input('email');
		$user = User_user::where('email', $email)->where('email_verified', 1)->where('valid', 1)->first();
		if(!empty($user)) {
			//for generate verification_code
			$original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
			$original_string = implode("", $original_string);
			$token = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);
			//update user_info data in web database
			$data['password_req_token'] = $token;
			$data['password_req_date'] = date('Y-m-d');
			User_user::find($user->id)->update($data);

			// email data
			$email_data['name'] = $user->name;
			$email_data['link'] = url('password-req-verification?token=' . $token);
			//send email
			Helper::mailConfig();
			Mail::send('emails.password_req_verification', $email_data, function($message) use ($user)
			{
				//$message->from('no-reply@delbd.com', 'Digital Engravers Ltd.');
				$message->subject('Password reset request');
				$message->to($user->email, $user->name);
			});

			$redirect_data['title'] = 'Success!';
			$redirect_data['message'] = 'Password reset token has been sent to your email.';
			$redirect_data['isLogin'] = true;
			return redirect()->route('confirmation')->with($redirect_data);

		} else {
			return redirect()->route('forgotPassword')->with('message', '<div class="alert alert-warning"><i class="fa fa-warning alert-icon"></i> Please enter registered email.</div>');
		}
    }


	public function passwordReqVerification(Request $request) {
        $token = $request->input('token');
		$user_info = User_user::where('password_req_token', $token)->where('email_verified', 1)->where('valid', 1)->first();
		if(!empty($user_info)) {
			//for verify time chk
            $curr_date = date('Y-m-d');
            $expiry_date = new DateTime($user_info->password_req_date);
            $interval = new DateInterval('P5D');
            $expiry_date->add($interval);
            $expiry_date = $expiry_date->format('Y-m-d');
            if($curr_date < $expiry_date) {
				return redirect()->route('resetPassword')->with('pass_reset_token', $token);
            } else {
                //flash message reditect
				return redirect()->route('forgotPassword')->with('message', '<div class="alert alert-warning"><i class="fa fa-warning alert-icon"></i> Password reset request time expired!</div>');
            }
		} else {
			//flash message reditect
			return redirect()->route('forgotPassword')->with('message', '<div class="alert alert-warning"><i class="fa fa-warning alert-icon"></i> Something went wrong please try again!</div>');
		}
    }

	public function resetPassword() {
		$pass_reset_token = session('pass_reset_token');
		if(!empty($pass_reset_token)) {
			$data['token'] = $pass_reset_token;
			return view('resetPassword', $data);
		} else {
			return redirect()->route('forgotPassword')->with('message', '<div class="alert alert-warning"><i class="fa fa-warning alert-icon"></i> Something went wrong please try again!</div>');
		}
	}

	public function resetPasswordAction(Request $request) {
		// get user data form session
		$token = $request->input('token');
		$user_info = User_user::where('password_req_token', $token)->where('email_verified', 1)->where('valid', 1)->first();
		if(!empty($user_info)) {
			$password = bcrypt($request->input('password'));
			User_user::find($user_info->id)->update(array('password' => $password, 'password_req_token' => Null, 'password_req_date' => Null));

			$data['title'] = 'Congratulations!';
			$data['message'] = 'Your password has been reset';
			$data['isLogin'] = true;
			return redirect()->route('confirmation')->with($data);

		} else {
			//flash message reditect
			return redirect()->route('forgotPassword')->with('message', '<div class="alert alert-warning"><i class="fa fa-warning alert-icon"></i> Something went wrong please try again!</div>');
		}
    }


    //For Email Verification
    public function email_verification(Request $request)
    {
		$token = $request->token;
		$user = User_user::where('verification_code', $token)->where('valid', 1)->first();
		if(!empty($user)) {
			if($user->email_verified==0) {
				//Set Pass
				return view('email_verification', ['token'=>$token]);
			} else {
				//Allready Verified
                return view('account_verified');
			}
		} else {
			//Unathorize Tocken
            return view('unauthorized_token');
		}
    }

	public function email_verification_action(Request $request)
    {
		$token = $request->token;
		$password = password_hash($request->password, PASSWORD_DEFAULT);

		$user = User_user::where('verification_code', $token)->where('valid', 1)->first();
		if(!empty($user)) {
			if($user->email_verified==0) {
                $data = array(
                    'email' => $user->email,
                    'status'   => 'Active',
                    'valid'    => 1
                );

                //Set Pass
                $user->update(['password'=>$password, 'email_verified'=>1]);
                $data["password"] = $request->password;

                if (Auth::user()->attempt($data)) {
                    $data['isLogin'] = false;
                } else {
                    $data['isLogin'] = true;
                }

				$data['title'] = 'Congratulations!';
				$data['message'] = 'Your account has been verified!';
				return redirect()->route('confirmation')->with($data);
			} else {
				//Allready Verified
                return view('account_verified');
			}
		} else {
			//Unathorize Tocken
            return view('unauthorized_token');
		}
    }

    //For Confirmation
    public function confirmation() {
		if(!empty(session('title'))) {
			$data['title'] = session('title');
			$data['message'] = session('message');
			$data['isLogin'] = session('isLogin');
			return view('confirmation', $data);
		} else {
			return redirect()->route('apps');
		}

    }


    //For Confirmation
    public function unauthorized_token()
    {
        return view('unauthorized_token');
    }

    //For Confirmation
    public function account_verified()
    {
        return view('account_verified');
    }

    //For forgot password Update
    public function passwordUpdate()
    {
        return view('passwordUpdate');
    }


}
