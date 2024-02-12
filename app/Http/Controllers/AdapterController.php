<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\ProjectInfo;
use Helper;
use Auth;

class AdapterController extends Controller {
	
	static $nodeUrl = 'http://admin-pc:3000';
	// static $nodeUrl = 'http://mis.delbd.com:3000';

	public function javascript() {
		$data = ['defaultPage' => 'dashboard'];
		$data['nodeUrl'] = self::$nodeUrl;
		return view('javascript_view', $data);
	}

	public function javascript_softAdmin() {
		$data['prefix'] = 'softAdmin';
		$data['defaultPage'] = 'dashboard';
		$data['loginRoute'] = 'softAdmin.login';
		$data['nodeUrl'] = self::$nodeUrl;
		return view('javascript_view', $data);
	}

	public function javascript_administrator() {
		$data['prefix'] = 'admin';
		$data['defaultPage'] = Helper::userAccess('dashboard', 'admin', 0) ? 'dashboard' : 'home';
		$data['nodeUrl'] = self::$nodeUrl;
		return view('javascript_view', $data);
	}

	public function javascript_ew() {
		$data['prefix'] = 'eastWest';
		$data['defaultPage'] = Helper::userAccess('home', 'eastWest', 0) ? 'home' : 'welcome';
		$data['nodeUrl'] = self::$nodeUrl;
		return view('javascript_view', $data);
	}

	public function javascript_recruitment() {
		$data['prefix'] = 'recruitment';
		$data['defaultPage'] = 'home';
		$data['nodeUrl'] = self::$nodeUrl;
		return view('javascript_view', $data);
	}
}

