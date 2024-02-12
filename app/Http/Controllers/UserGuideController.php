<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use DateTime;
use DateInterval;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserGuideController extends Controller {

    
    public function index() {
    	
        return view('userGuide');
    }

    
}
