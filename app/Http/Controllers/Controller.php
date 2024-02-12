<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Config;
use Auth;

abstract class Controller extends BaseController {
	
    use DispatchesJobs, ValidatesRequests;
	
	public function __construct() {
		//get user time zone from auth
		$timezone = (Auth::user()->get()) ? Auth::user()->get()->timezone : 'Asia/Dhaka';
        Config::set('app.timezone', $timezone); // Set user timezone
    }
}
