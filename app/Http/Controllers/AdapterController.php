<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Model\ProjectInfo;
use Helper;
use Auth;

class AdapterController extends Controller{
	static $nodeUrl = 'http://adminpc:3000';
	//static $nodeUrl = 'https://crm.leadvas.com:3000';
	static $webUrl = 'https://localhost:8000/';
	
	public function javascript() {
		$data = ['defaultPage' => 'dashboard'];
		$data['nodeUrl'] = self::$nodeUrl;
		$data['webUrl'] = self::$webUrl;
		return view('javascript_view', $data);
	}

	public function javascript_softAdmin() {
		$data['prefix'] = 'softAdmin';
		$data['defaultPage'] = 'dashboard';
		$data['loginRoute'] = 'softAdmin.login';
		$data['nodeUrl'] = self::$nodeUrl;
		$data['webUrl'] = self::$webUrl;
		return view('javascript_view', $data);
	}

	public function javascript_administrator() {
		$data['prefix'] = 'admin';
		$data['defaultPage'] = Helper::userAccess('dashboard', 'admin', 0) ? 'dashboard' : 'home';
		$data['nodeUrl'] = self::$nodeUrl;
		$data['webUrl'] = self::$webUrl;
		return view('javascript_view', $data);
	}

	public function javascript_crm() {
		$data['prefix'] = 'crm';
		$data['defaultPage'] = Helper::userAccess('home', 'crm', 0) ? 'home' : 'welcome';
		$data['nodeUrl'] = self::$nodeUrl;
		$data['webUrl'] = self::$webUrl;
		return view('javascript_view', $data);
	}
}
