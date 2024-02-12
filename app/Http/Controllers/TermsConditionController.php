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

class TermsConditionController extends Controller {

    
    public function terms_condition(Request $request) {
    	$data['inputData'] = $request->all();
    	
    	
        return view('terms&condition', $data);
    }

    
}
