<?php

namespace App\Http\Controllers\Provider\ApprovalSystem;
use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use DateTime;
use Collection;
use DateInterval;

use App\Http\Requests;
use App\Model\CrmActivitiesTask;
use App\Model\CrmCampaign;
use App\Http\Controllers\Controller;
use App\Model\EnUsers_provider;

class HomeController extends Controller {
    //dashboard
    public function index(Request $request) {
        $data['dateNow'] = $currentDate = new DateTime();
        return view('provider.ApprovalSystem.home.testHome', $data);
    }
    //Dashboard related Function Start

    
    
}
