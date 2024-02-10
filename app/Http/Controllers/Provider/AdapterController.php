<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Model\ProjectInfo;
use Helper;
use Auth;

class AdapterController extends Controller {

	static $nodeUrl = 'http://adminpc:3000';
	//static $nodeUrl = 'https://crm.iisbd.com:3000';
	static $webUrl = 'https://www.sudoksho.com';
	
	//------ START JAVASCRIPT (PROVIDER) ------
	public function javascript() {
		$data = ['defaultPage' => 'dashboard'];
		$data['nodeUrl'] = self::$nodeUrl;
		$data['webUrl'] = self::$webUrl;
		$data['loginRoute'] = "provider.login";
		return view('javascript_view', $data);
	}
	//------ END JAVASCRIPT (PROVIDER) ------

	//------ START ADMIN PANEL (PROVIDER) ------
	public function javascript_admin() {
		$data['prefix'] = 'provider/admin';
		$data['defaultPage'] = Helper::providerUserAccess('dashboard', 'admin', 0) ? 'dashboard' : 'home';
		$data['nodeUrl'] = self::$nodeUrl;
		$data['webUrl'] = self::$webUrl;
		$data['loginRoute'] = "provider.login";
		return view('javascript_view', $data);
	}
	//------ END ADMIN PANEL (PROVIDER) ------ 
	//------ START TRANING MANAGER (SERVICE PROVIDER) ------
	public function javascript_eastWest() {
		$data['prefix'] = 'provider/eastWest';
		$data['defaultPage'] = Helper::providerUserAccess('home', 'eastWest', 0) ? 'home' : 'welcome';
		$data['nodeUrl'] = self::$nodeUrl;
		$data['webUrl'] = self::$webUrl;
		$data['loginRoute'] = "provider.login";
		return view('javascript_view', $data);
	}
	//------ END TRANING MANAGER (SERVICE PROVIDER) ------

}

