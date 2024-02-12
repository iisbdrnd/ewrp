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

class PrivacyPoliceController extends Controller {

    
    public function privacy_police(Request $request) {
    	$data['inputData'] = $request->all();
    	
    	
        return view('privacy_police', $data);
    }

    
}
