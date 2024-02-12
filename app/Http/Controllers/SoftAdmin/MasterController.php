<?php namespace App\Http\Controllers\SoftAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AdminMenu;
use DB;
use Auth;

class MasterController extends Controller {
    public function index()
    {
        $admin_id = Auth::admin()->get()->id;
        $data['prefix'] = 'softAdmin';
        $data['title'] = 'SoftWare Administrator | Innovation Information System';
        $data['admin_menus'] = AdminMenu::adminAccessMenus($admin_id);

        return view('softAdmin.master', $data);
    }
    
    public function getLogin()
    {
        return view('softAdmin.login');
    }

    public function postLogin(Request $request)
    {
        $data = array(
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'status'   => 'Active',
            'valid'    => 1
        );

        if (Auth::admin()->attempt($data)) {
            return redirect()->route('softAdmin.content');
        } else {
            return redirect()->route('softAdmin.login')->with('error', 'Username or password is not correct.');
        }
    }

    public function logout()
    {
        Auth::admin()->logout();
        return redirect()->route('softAdmin.login');
    }

}