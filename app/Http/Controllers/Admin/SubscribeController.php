<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use DateTime;
use Collection;
use DateInterval;

use App\Http\Requests;
use App\Model\PaymentTemp;
use App\Model\Project;
use App\Model\EmployeeBasicInfo_user;
use App\Model\CrmActivitiesTask;
use App\Model\CrmCampaign;
use App\Http\Controllers\Controller;

class SubscribeController extends Controller {
	
    public function packagePlan() {
		$project_id = Auth::user()->get()->project_id;
		$project = Project::valid()->find($project_id);
		
		$current_date = date_create(date('Y-m-d'));
		$expire_date = date_create($project->crm_expire_date);
		$diff = date_diff($current_date, $expire_date);
		$diff = intval($diff->format("%R%a"));
		if($diff > 0) {
			$daily_price_per_user = 50;
			$data['remaining_days'] = $diff;
			$data['extra_charge_per_user'] = ($daily_price_per_user*$diff);
		} else {
			$data['remaining_days'] = 0;
			$data['extra_charge_per_user'] = 0;
		}
		$data['project'] = $project;
        return view('admin.subscribe.index', $data);
    }
    

    public function paymentConfirm(Request $request) {
		$date = new DateTime();
		$user_id = Auth::user()->get()->id;
		$project_id = Auth::user()->get()->project_id;
		$project = Project::valid()->find($project_id);
		
		$user  = $request->user;
		$duration  = $request->duration;
		$package_price = ((($user > 1) ? $user*1500 : 2500)*$duration);
		
		if($project->crm_user < $user) {
			$extend_user = ($user - $project->crm_user);
			$current_date = date_create(date('Y-m-d'));
			$expire_date = date_create($project->crm_expire_date);
			$diff = date_diff($current_date, $expire_date);
			$diff = intval($diff->format("%R%a"));
			if($diff > 0) {
				$extra_charge_per_user = (50*$diff);
			} else {
				$extra_charge_per_user = 0;
			}
			$extra_charge = ($extra_charge_per_user*$extend_user);
			$package_price = ($package_price+$extra_charge);
		}
		
		$payment_temp_data = PaymentTemp::where('user_id', $user_id)->where('work_status', 0)->where('reason', 2)->first();
		
		$payment_data['registration_id'] = $user_id;
		$payment_data['user_id'] = $user_id;
		$payment_data['local_amount'] = $package_price;
		$payment_data['user_qty'] = $user;
		$payment_data['duration'] = $duration;
		$payment_data['transacted_on'] = $date->format('Y-m-d H:i:s');
		if(!empty($payment_temp_data)) {
			$tran_id = $payment_temp_data->tran_id;
			PaymentTemp::where('tran_id', $tran_id)->update($payment_data); //update data into payment temporary table
		} else {
			$tran_id = PaymentTemp::orderBy('id', 'desc')->first();
			if (!empty($tran_id)) {
				$tran_id = $tran_id->tran_id + 1;
			} else {
				$tran_id = 100001;
			}
			$payment_data['tran_id'] = $tran_id;
			$payment_data['reason'] = 2; //2 for renew
			PaymentTemp::create($payment_data); //save data into payment temporary table
		}
		$data['tran_id'] = $tran_id;
        $data['inputData']  = $request->all();
		$data['user_data'] = EmployeeBasicInfo_user::valid()->where('user_id', $user_id)->first();
		$data['user_email'] = Auth::user()->get()->email;
        return view('admin.subscribe.paymentConfirm', $data);
    }


    



}
