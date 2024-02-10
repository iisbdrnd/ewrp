<?php

namespace App\Http\Controllers\softAdmin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Model\AdminMenu;

class MasterController extends Controller{

	use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'softAdmin/home';

    public function index(){
        $admin_id = Auth::guard('softAdmin')->id();
        $data['prefix'] = 'softAdmin';
        $data['title'] = 'SoftWare Administrator | Innovation Information System';
        $data['admin_menus'] = AdminMenu::adminAccessMenus($admin_id);
        return view('softAdmin.master', $data);
    }

	public function getLogin(){
		return view('softAdmin.login');
	}

	public function postLogin(Request $request){
        $data = array(
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'status'   => 'Active',
            'valid'    => 1
        );
        if ($this->guard()->attempt($data)) {
            return redirect()->route('softAdmin.content');
        } else {
            return redirect()->route('softAdmin.login')->with('error', 'Username or password is not correct.');
        }
    }

    public function logout(){
        Auth::guard('softAdmin')->logout();
        return redirect()->route('softAdmin.login');
    }

	/**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard(){
        return Auth::guard('softAdmin');
    }
}
